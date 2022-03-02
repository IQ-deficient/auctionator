<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Http\Requests\StoreAuctionRequest;
use App\Http\Requests\UpdateAuctionRequest;
use App\Models\Item;
use Carbon\Carbon;
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
//        abort_if(Auth::user(), '422','jbg');
        // get all active auctions
        return Auction::all();
//        return DB::table('auctions')->get();
//        return DB::select("
//            select a.*, i.title as item_title, i.description, i.category, i.`condition`, i.warehouse_id, b.value, b.username
//            from auctions a, items i, bids b
//            where a.item_id = i.id && a.bid_id = b.id
//        ");
    }

    /**
     * Display a listing of the resource only for active entities.
     * @return Response
     */
    public function getActive()
    {
//        return Auction::all()->where('is_active', true);
        return Auction::where('is_active', true)->get();
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
     * Return Auctions based on some parameters and filter keywords
     * @param Request $request
     * @return mixed
     */
    public function getFiltered(Request $request)
    {
        // BIG TODO: MANAGE HOW WE WILL HANDLE AUCTIONS THAT ARE FINISHED ONCE THE TIMER RUNS OUT
        // basically every time we return auctions, check if any are expired and manage them??? i guess
        // while returning auctions for clients check if they have run out of time and dont show those
        // also dont show ones that have not yet been initiated (queued)

        // todo: we will never be returning all auctions to customers, rather only the ones corresponding to certain category
        // using query builder instead of eloquent for this is probably a must for longevity (and other stuff)

        // Find IDs for searched auctions
        $auction_ids = DB::table('auctions')
            ->where('is_active', true)      // only active auctions
//            ->where('status', )
            ->pluck('id');

        // Return Eloquent Model Auctions by found IDs because they include other data used on interface by default
        $auctions = Auction::whereIn('id', $auction_ids)->get();

        return $auctions;
        // todo: this is about as functional as my brain on daily basis
    }

    /**
     * Show the form for creating a new resource.
     *
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
        // todo: figure out how we will handle roles or rather which user_role can access these actions
        // todo: also functions that define which auctions can be seen by which users depending on 'status'
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|between:3,12',
            'seller' => 'required|string|between:3,32',
            'buyout' => 'required|numeric|gt:0',    // x.xx > 0
            'start_datetime' => 'required|date',
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

        // TODO: insert condition for this item in category_condition table
        // todo: update: ja sam jebeno mentalno retardiran, mada to je bilo vise nego ocigledno

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

        return Auction::where('id', $auction->id)->first();
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Auction $auction
     * @return Response
     */
    public function show(Auction $auction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
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
        // Only the auction without a bid can be changed for certain parameters
        abort_if($auction->bid_id != null, 422, 'Only auctions with no bid can be altered.');
        // Deactivated auctions are no longer eligible for change
        abort_if($auction->is_active == null, 422, 'This auction was deactivated.');

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|between:3,128',
            'seller' => 'required|string|between:3,32',
            'buyout' => 'required|numeric|gt:0',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after:start_datetime',
            'title_item' => 'required|between:3,64',
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

//        return $auction;
        return Auction::where('id', $auction->id)->first();
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
            ->where('is_active', true)
            ->where('id', $auction->bid_id)
            ->update([
                'is_active' => false
            ]);

        // returning Model, so it picks up all formatted data
//        return DB::table('auctions')->where('id', $auction->id)->first();
        return Auction::where('id', $auction->id)->first();
    }
}
