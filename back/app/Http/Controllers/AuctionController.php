<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Http\Requests\StoreAuctionRequest;
use App\Http\Requests\UpdateAuctionRequest;
use App\Models\Bid;
use App\Models\Category;
use App\Models\History;
use App\Models\Item;
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
use Illuminate\Support\Facades\Validator;

class AuctionController extends Controller
{

    /**
     * Display a listing of the resource.
     * @return Auction[]|Collection
     */
    public function index()
    {
        return Auction::all();
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
            'category' => 'required|string|exists:categories,name', // It is critical that we only get auctions for certain category
//            'title' => 'nullable|string|between:3,128',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Fetch all active Auctions whose item is of the category the Client selected
        $auctions = Auction::query()
            ->where('is_active', true)
            ->whereIn(
                'item_id',
                DB::table('items')
                    ->where('category', $request->category)     // get item IDs for select category
                    ->pluck('id')
            )
            ->where('status', '!=', 'NA')
            ->where('start_datetime', '<=', Carbon::now())      // only auctions that have started (because of queuing)
            ->where('end_datetime', '>=', Carbon::now())      // and NOT ended
            ->get();

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
     * @return Builder|JsonResponse|Model|object|null
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|between:3,128',
            'seller' => 'required|string|between:3,32',
            'buyout' => 'required|numeric|gt:0',    // x.xx > 0
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
            'buyout' => $request->buyout,
            'status' => 'Created',          // Fresh auction
            'start_datetime' => $request->start_datetime,
            'end_datetime' => $request->end_datetime,
            'user_id' => Auth::id(),            // Auctioneer that is responsible for creation of this auction
        ]);

        return Auction::query()->where('id', $auction->id)->first();
    }

    /**
     * Display the specified resource.
     * @param \App\Models\Auction $auction
     * @return Auction
     */
    public function show(Auction $auction)
    {
        return $auction;
    }

    /**
     * Show the form for editing the specified resource.
     * @param \App\Models\Auction $auction
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
        // Deactivated auctions are no longer eligible for change
        abort_if($auction->is_active == null, 422, 'This auction was deactivated.');
        // Auctions with statuses Sold/Expired/Ongoing can not be updated
        $no_update_statuses = ['Sold', 'Expired'];
        abort_if(in_array($auction->status, $no_update_statuses), 410, 'This auction has ended and should therefore not be changed.');
        // Only the auction without a bid can be changed for certain parameters
        abort_if($auction->bid_id != null, 422, 'Only auctions with no bid can be altered.');

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|between:3,128',
            'seller' => 'required|string|between:3,32',
            'buyout' => 'required|numeric|gt:0',
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
            'buyout' => $request->buyout,
            'start_datetime' => $request->start_datetime,
            'end_datetime' => $request->end_datetime,
            'updated_at' => Carbon::now(),          // This is only updated if any other input is different from current ones
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
        // Once deactivated, auction can not be reactivated and a new instance must be freshly made
        // Status does not change because this already guarantees that auction is no longer in play
        // Item is lost or any other colossal issue, and therefore we do not care if there is a bid
        $auction->update([
            'is_active' => false,
            'user_id' => Auth::id()         // Auctioneer that deleted the auction
        ]);

        // Last and only active bid for this auction will be deactivated
        Bid::deactivateBid($auction->bid_id);

        // todo: mail the last bidder (Something unavoidable led to this auction being terminated. itd itd)

        // returning Model, so it picks up all formatted data
        return Auction::query()->where('id', $auction->id)->first();
    }

    /**
     * Some critical errors lead to Auctions being soft deleted, or rather being made Not Available (for a short time).
     * This resets the auction to default settings meaning the duration and bid is reset.
     * @return Auction|Model
     */
    public function softDestroy(Auction $auction)
    {
        // inaktivnoj aukciji ne moze da se mijenja nista
        // ako je expired ili sold ne moze da se mijenja (ove stvari isto vaze i za dosta drugih pa mozda policy?)
        // ako je end_datetime > Carbon::now() pa logicno da se zavrsila i vise nema ovoga (isto za vise stvari ce da vazi ova provjera)

        // Get the User that last placed the Bid on this Auction

        // If we are reverting the Auction to regular state, make it fresh by changing status and duration to reflect that
        if ($auction->status == 'NA') {

            // todo reset the timer
            return response($auction->start_datetime, $auction->end_datetime);


            $auction->update([
                'status' => 'Created',
                'start_datetime' => null,
                'end_datetime' => null,
                'user_id' => Auth::id()
            ]);

            // Also mail the person that last owned the Auction (bid) that it is once again available
            // TODO Sorry for the inconvenience. jada jada ovo ono. You can visit our platform and place your bid once again.
        } else {
            // And when we are making it Not Available, change status and nullify the last bid for that Auction
            Bid::deactivateBid($auction->bid_id);
            $auction->update([
                'status' => 'NA',
                'bid_id' => null,
                'user_id' => Auth::id()         // Auctioneer that altered the auction
            ]);

            // todo: obavezno mailujemo osobu koja je imala bid da se nesto desava ali da ce biti obavijesteni kad se stvari poprave
        }

        return $auction;
    }

}
