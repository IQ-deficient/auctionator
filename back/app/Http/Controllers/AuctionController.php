<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Http\Requests\StoreAuctionRequest;
use App\Http\Requests\UpdateAuctionRequest;
use App\Models\History;
use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
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
        return Auction::query()->where('is_active', true)->get();
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
     * @return mixed
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
            ->where('status', '!=', 'N/A')
            ->where('start_datetime', '<=', Carbon::now())      // only auctions that have started (because of queuing)
            ->where('end_datetime', '>=', Carbon::now())      // and NOT ended
            ->get();

        // For selected category also get all conditions that exist for parent (master) category of that subcategory
        $conditions = DB::table('category_conditions')
            ->where(
                'category',
                DB::table('categories')
                    ->where(
                        'id',
                        DB::table('categories')
                            ->where('is_active', true)
                            ->where('name', $request->category)
                            ->value('master_category_id')
                    )
                    ->value('name')
            )
            ->pluck('condition');

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
     * @return mixed
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
     * @return mixed
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

        return Auction::query()->where('id', $auction->id)->first();
    }

    /**
     * Alter activity status for the specified resource in storage.
     * @return mixed
     */
    public function destroy(Auction $auction)
    {
        // Once deactivated, auction can not be reactivated and a new instance must be freshly made
        // Status does not change because this already guarantees that auction is no longer in play
        // Item is missing or any other colossal issue, and therefore we do not care if there is a bid
        $auction->update([
            'is_active' => false,
            'user_id' => Auth::id()         // Auctioneer that deleted the auction
        ]);

        // Last and only active bid for this auction will be deactivated
        DB::table('bids')
            ->where('id', $auction->bid_id)
            ->where('is_active', true)
            ->update([
                'is_active' => false
            ]);

        // returning Model, so it picks up all formatted data
        return Auction::query()->where('id', $auction->id)->first();
    }

    // N/A i nazad na Ongoing/Created u odnosu na bid
}
