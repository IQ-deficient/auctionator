<?php

namespace App\Http\Controllers;

use App\Mail\MailNotification;
use App\Models\Auction;
use App\Http\Requests\StoreAuctionRequest;
use App\Http\Requests\UpdateAuctionRequest;
use App\Models\Bid;
use App\Models\Category;
use App\Models\History;
use App\Models\Image;
use App\Models\Item;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AuctionController extends Controller
{

    /**
     * Display a listing of the resource.
     * @return array[]|Application|ResponseFactory|Response
     */
    public function index()
    {
        $roles = User::getUserRoles(Auth::user()->username);
        $auctions = Auction::query()
            ->where('is_active', true)
            ->get();
        $created = $ongoing = $sold = $expired = $na = [];      // init status return variables

        // Go through each Auction and return them separated in variables by status
        foreach ($auctions as $auction) {
            switch ($auction->status) {
                case "Created":
                    $created[] = $auction;
                    break;
                case "Ongoing":
                    $ongoing[] = $auction;
                    break;
                case "Sold":
                    $sold[] = $auction;
                    break;
                case "Expired":
                    $expired[] = $auction;
                    break;
                case "NA":
                    $na[] = $auction;
                    break;
            }
            if ($auction) {
                $images = Image::query()
                    ->where('item_id', $auction->item_id)
                    ->get();
                $auction->images = $images;
            }
        }
        // We will be returning inactive auction separately
        $inactive = Auction::query()
            ->where('is_active', false)
            ->get();

        // Only if Administrator or Manager is currently logged in return desired data
        if (in_array('Administrator', $roles) || in_array('Auctioneer', $roles))
            return ['created' => $created, 'ongoing' => $ongoing, 'sold' => $sold, 'expired' => $expired, 'na' => $na, 'inactive' => $inactive];

        return response('You do not have permissions for requested data!', 400);
    }

    /**
     * Display a listing of the resource only for active entities.
     * @return Collection|Builder[]
     */
    public function getActive()
    {
        return Auction::query()
            ->where('is_active', true)
            ->get();
    }

    /**
     * Client will know which auction he owns by ID given to them
     * Auctioneer can search auctions this way for ease of access
     * @param Request $request
     * @return mixed
     */
    public function getById(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Find Auction based on ID
        return Auction::where([
            ['is_active', true],
            ['id', $request->id]
        ])->first();
    }

    /**
     * Return Auctions based on some parameters and filter keywords for Clients
     * we will never be returning ALL auctions to customers, rather only the ones corresponding to certain category
     * @param Request $request
     * @return Application|ResponseFactory|JsonResponse|Response
     */
    public function getFiltered(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category' => 'required|string|exists:categories,name', // It is critical that we only get Auctions for certain Category
//            'title' => 'nullable|string|between:3,128',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Fetch all active Auctions whose item is of the Category the Client selected
        $auctions = Auction::query()
            ->where('is_active', true)
            ->whereIn(
                'item_id',
                DB::table('items')
                    ->where('category', $request->category)     // get Item IDs for select Category
                    ->pluck('id')
            )
            ->whereIn('status', ['Created', 'Ongoing'])
            ->where('start_datetime', '<=', Carbon::now())      // only Auctions that have started (because of queuing)
            ->where('end_datetime', '>=', Carbon::now())      // and NOT ended
            ->get();

        // For query auctions, fetch all Image paths for Item and bind them to each Auction
        foreach ($auctions as $auction) {
            $images = Image::query()
                ->where('item_id', $auction->item->id)
                ->get();
            $auction->images = $images;
        }

        // Also...
        $conditions = Category::getConditionsByCategory($request->category);

        return response(['auctions' => $auctions, 'conditions' => $conditions]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return array|JsonResponse
     */
    public function store(Request $request)
    {
        $roles = User::getUserRoles(Auth::user()->username);
        abort_if(!(in_array('Auctioneer', $roles) || in_array('Administrator', $roles)),
            400,
            'Only Auctioneers and Admins are allowed to create Auctions.');

        $gte_buyout = env('MIN_BUYOUT');

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|between:3,128',
            'seller' => 'required|string|between:3,32',
            'min_bid_value' => 'required|numeric|gte:0|lt:buyout',  // X >= 0€ && X < buyout
            'buyout' => 'required|numeric|gte:' . $gte_buyout,    // X >= 2€
            'start_datetime' => 'required|date|after:today',
            'end_datetime' => 'required|date|after:start_datetime',     // end date_time must be greater than start dt
            'title_item' => 'required|between:3,64',
            'description' => 'required|string|between:3,500',
            'category' => 'required|string|max:64|exists:categories,name',
            'condition' => 'required|string|max:32|exists:conditions,name', // condition is based on category
            'warehouse_id' => 'required|integer|exists:warehouses,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Just in case Condition and Category from request do not work together
        $proper_conditions = Category::getConditionsByCategory($request->category)->toArray();
        abort_if(!in_array($request->condition, $proper_conditions), 400, 'Condition used is not applicable to selected Category.');

        // Firstly make the item that should be formed into the auction
        $item = Item::create([
            'title' => $request->title_item,
            'description' => $request->description,
            'category' => $request->category,
            'condition' => $request->condition,
            'warehouse_id' => $request->warehouse_id,
        ]);

        // Lastly, make the auction with all required data together with item and return it
        $auction = Auction::create([
            'title' => $request->title,
            'seller' => $request->seller,
            'item_id' => $item->id,             // Here goes the ID of the item that was made before the auction for it
            'bid_id' => null,           // There is no bid by default
            'min_bid_value' => $request->min_bid_value,
            'buyout' => $request->buyout,
            'status' => 'Created',          // Fresh auction
            'start_datetime' => $request->start_datetime,
            'end_datetime' => $request->end_datetime,
            'user_id' => Auth::id(),            // Auctioneer that is responsible for creation of this auction
        ]);

        return [
            'auction' => Auction::query()->where('id', $auction->id)->first(),
            'item_id' => $item->id
        ];

    }

    /**
     * Display the specified resource.
     * @param Auction $auction
     * @return Auction
     */
    public function show(Auction $auction)
    {
        return $auction;
    }

    /**
     * Show the form for editing the specified resource.
     * @param Auction $auction
     * @return Response
     */
    public function edit(Auction $auction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param Auction $auction
     * @return Auction|JsonResponse
     */
    public function update(Request $request, Auction $auction)
    {
        $roles = User::getUserRoles(Auth::user()->username);
        abort_if(!(in_array('Auctioneer', $roles) || in_array('Administrator', $roles)),
            400,
            'Only Auctioneers and Admins are allowed to update Auctions.');

        // Deactivated auctions are no longer eligible for change
        abort_if($auction->is_active == null, 422, 'This auction was deactivated.');

        // Auctions with statuses Sold/Expired/Ongoing can not be updated, also check for end_datetime just in case
        $no_update_statuses = ['Sold', 'Expired'];
        abort_if(in_array($auction->status, $no_update_statuses) || Carbon::now() >= $auction->end_datetime,
            410, 'This auction has ended and can therefore not be changed.');

        // Only the auction without a bid can be changed for certain parameters
        abort_if($auction->bid_id != null, 422, 'Only auctions with no bid can be altered.');

        $gte_buyout = env('MIN_BUYOUT');

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|between:3,128',
            'seller' => 'required|string|between:3,32',
            'min_bid_value' => 'required|numeric|gte:0|lt:buyout',
            'buyout' => 'required|numeric|gte:' . $gte_buyout,
            'title_item' => 'required|string|between:3,64',
            'description' => 'required|string|between:3,500',
            'category' => 'required|string|max:64|exists:categories,name',
            'condition' => 'required|string|max:32|exists:conditions,name',
            'warehouse_id' => 'required|integer|exists:warehouses,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Update the item that this auction was created for
        DB::table('items')
            ->where('id', $auction->item_id)
            ->update([
                'title' => $request->title_item,
                'description' => $request->description,
                'category' => $request->category,
                'condition' => $request->condition,
                'warehouse_id' => $request->warehouse_id,
                'updated_at' => Carbon::now(),
            ]);

        // Update the auction itself
        $auction->update([
            'title' => $request->title,
            'seller' => $request->seller,
            'min_bid_value' => $request->min_bid_value,
            'buyout' => $request->buyout,
//            'start_datetime' => $request->start_datetime,
//            'end_datetime' => $request->end_datetime,
            'user_id' => Auth::id(),            // Auctioneer that applies changes (in case its someone else than the creator)
        ]);

        return $auction;
    }

    /**
     * Alter activity status for the specified resource in storage.
     * @return Builder|Model|object|null
     */
    public function destroy(Auction $auction)
    {
        $roles = User::getUserRoles(Auth::user()->username);
        abort_if(!(in_array('Auctioneer', $roles) || in_array('Administrator', $roles)),
            400,
            'Only Auctioneers and Admins are allowed to permanently delete Auctions.');

        // Once deactivated, auction can not be reactivated and a new instance must be freshly made
        // Status does not change because this already guarantees that auction is no longer in play
        // Item is lost or any other colossal issue, and therefore we do not care if there is a bid
        $auction->update([
            'is_active' => false,
            'user_id' => Auth::id()         // Auctioneer that deleted the auction
        ]);

        if ($auction->bid) {
            // Notifying the person that last bid on this Auction before we invalidate that Bid, if there is any
            Mail::to(User::query()->where('username', $auction->bid->username)->first())
                ->queue(new MailNotification(
                    'The Auction: "' . $auction->title . '", with an ID:' . $auction->id . ', is now terminated! Please contact our staff for additional information.',
                    'Something has gone wrong!'
                ));
            // Last and only active bid for this auction will be deactivated
            Bid::deactivateBid($auction->bid_id);
        }

        // returning Model, so it picks up all formatted data
        return Auction::query()->where('id', $auction->id)->first();
    }

    /**
     * Some critical errors lead to Auctions being soft deleted, or rather being made Not Available (for a short time).
     * This resets the Auction to default settings meaning the duration is reset and Bid defaulted.
     * @return Auction|Model
     */
    public function softDestroyAndRestore(Auction $auction)
    {
        $roles = User::getUserRoles(Auth::user()->username);
        abort_if(!(in_array('Auctioneer', $roles) || in_array('Administrator', $roles)),
            400,
            'Only Auctioneers and Admins are allowed to update Auctions.');

        // Non-active auctions can no longer be altered
        abort_if(!$auction->is_active, 422, 'This auction was deactivated.');

        // Auctions with statuses Sold/Expired/Ongoing can not be updated, also check for end_datetime just in case
        $no_update_statuses = ['Sold', 'Expired'];
        abort_if(in_array($auction->status, $no_update_statuses) || Carbon::now() >= $auction->end_datetime,
            410, 'This auction has ended and can therefore not be changed.');


        if ($auction->bid) {
            // Get the User that last placed the Bid on this Auction
            $last_bidder = User::query()
                ->where('username', $auction->bid->username)
                ->first();
        }

        // If we are reverting the Auction to regular state, make it fresh by changing status and duration to reflect that
        if ($auction->status == 'NA') {

            // Get the difference between start and end datetimes of this Auction before we update it
            $diff = Carbon::createFromDate($auction->start_datetime)
                ->diffInSeconds(Carbon::createFromDate($auction->end_datetime));

            $auction->update([
                'status' => 'Created',
                'bid_id' => null,
                'start_datetime' => Carbon::now(),
                'end_datetime' => Carbon::now()->addSeconds($diff),
                'user_id' => Auth::id()
            ]);

            if ($auction->bid) {
                // Also mail the person that last owned the Auction (bid) that it is once again available
                Mail::to($last_bidder)
                    ->queue(new MailNotification(
                        'Great news! The Auction: "' . $auction->title . '" is up and running. You may place your bid once again.',
                        'Good to go - Auction with ID:' . $auction->id
                    ));
            }

        } else {
            // And when we are making it Not Available, change status and nullify the last bid for that Auction
            Bid::deactivateBid($auction->bid_id);

            $auction->update([
                'status' => 'NA',
                'user_id' => Auth::id()         // Auctioneer that altered the auction
            ]);

            if ($auction->bid) {
                Mail::to($last_bidder)
                    ->queue(new MailNotification(
                        'We wanted to let you know that the Auction: "' . $auction->title . '" you have had a bid on is currently Not Available. We are terribly sorry for the inconvenience. You will be notified once we figure this out.',
                        'There is an issue with an auction with ID:' . $auction->id
                    ));
            }

        }

        return $auction;
    }

    public function getItemImage($name)
    {
        return response()->file(public_path('storage/item_images/' . $name));
    }


}
