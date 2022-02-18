<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Http\Requests\StoreAuctionRequest;
use App\Http\Requests\UpdateAuctionRequest;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuctionController extends Controller
{
    public function index()
    {
//        abort_if(Auth::user(), '422','jbg');
        // get all active auctions
        return Auction::all();
    }

    public function getActive()
    {
//        return Auction::all()->where('is_active', true);
        return Auction::where('is_active', true)->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'seller' => 'required|string',
            'buyout' => 'required|numeric|gt:0',    // x.xx > 0
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after:start_datetime',     // end date_time must be greater than start dt

            'title_item' => 'required|min:6|max:64',
            'description' => 'required|string',
            'category' => 'required|string|exists:categories,name',
            'warehouse_id' => 'required|integer|exists:warehouses,id',

//            'condition' => 'required|string|exists:conditions, name'
        ]);

        // Firstly make the item that should be formed into the auction
        $item = Item::create([
            'title' => $request->title_item,
            'description' => $request->description,
            'category' => $request->category,
            'warehouse_id' => $request->warehouse_id,
        ]);

        // TODO: insert condition for this item in category_condition table

        // Lastly, make the auction with all required data and return it
        $auction = Auction::create([
            'title' => $request->title,
            'seller' => $request->seller,
            'item_id' => $item->id,             // Here goes the ID of the item that was made before the auction for it
            'bid_id' => null,           // There is no bid by default
            'buyout' => $request->buyout,
            'status' => 'Created',          // Fresh auction
            'start_datetime' => $request->start_datetime,
            'end_datetime' => $request->end_datetime,
            'user_id' => Auth::id(),
        ]);

        return Auction::where('id', $auction->id)->first();
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Auction $auction
     * @return \Illuminate\Http\Response
     */
    public function show(Auction $auction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Auction $auction
     * @return \Illuminate\Http\Response
     */
    public function edit(Auction $auction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateAuctionRequest $request
     * @param \App\Models\Auction $auction
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAuctionRequest $request, Auction $auction)
    {
        // only auctions without a bid can be updated for certain parameters (category, name, ..)
    }

    public function destroy(Auction $auction)
    {
        // Once deactivated, auction can not be reactivated and a new instance must be freshly made
        // Status does not change because this already guarantees that auction is no longer in play
        $auction->update([
            'is_active' => false
        ]);

        // Last and only active bid for this auction will be deactivated
        DB::table('bids')
            ->where('is_active', true)
            ->where('id', $auction->bid_id)
            ->update([
                'is_active' => false
            ]);

        // returning Model so it picks up all formatted data
//        return DB::table('auctions')->where('id', $auction->id)->first();
        return Auction::where('id', $auction->id)->first();
    }
}
