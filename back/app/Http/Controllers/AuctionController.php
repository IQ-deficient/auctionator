<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Http\Requests\StoreAuctionRequest;
use App\Http\Requests\UpdateAuctionRequest;
use App\Models\Item;
use Carbon\Carbon;
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

    public function getFiltered(Request $request)
    {
        // todo: we will never be returning all auctions to customers, rather only the ones corresponding to certain category
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
        // todo: figure out how we will handle roles or rather which user_role can access these actions
        // todo: also functions that define which auctions can be seen by which users depending on 'status'
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
//            'condition' => 'required|string|exists:conditions, name',
            'warehouse_id' => 'required|integer|exists:warehouses,id',
        ]);

        // Firstly make the item that should be formed into the auction
        $item = Item::create([
            'title' => $request->title_item,
            'description' => $request->description,
            'category' => $request->category,
//            'condition' => $request->condition,
            'warehouse_id' => $request->warehouse_id,
        ]);

        // TODO: insert condition for this item in category_condition table
        // todo: update: ja sam jebeno mentalno retardiran, mada to je bilo vise nego ocigledno

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
            'user_id' => Auth::id(),            // Auctioneer that is responsible for creation of this auction
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

    public function update(Request $request, Auction $auction)
    {
        // Only the auction without a bid can be changed for certain parameters
        abort_if($auction->bid_id != null, 412, 'Only auctions with no bid can be altered.');
        // Deactivated auctions are no longer eligible for change
        abort_if($auction->is_active == null, 422, 'This auction was deactivated.');

        $request->validate([
            'title' => 'required|string',
            'seller' => 'required|string',
            'buyout' => 'required|numeric|gt:0',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after:start_datetime',
            'title_item' => 'required|min:6|max:64',
            'description' => 'required|string',
            'category' => 'required|string|exists:categories,name',
//            'condition' => 'required|string|exists:conditions, name',
            'warehouse_id' => 'required|integer|exists:warehouses,id',
        ]);

        // Update the item that this auction was created for
        DB::table('items')
            ->where('id', $auction->item_id)
            ->update([
                'title' => $request->title_item,
                'description' => $request->description,
                'category' => $request->category,
//            'condition' => $request->condition,
                'warehouse_id' => $request->warehouse_id,
                'updated_at' => Carbon::now(),
            ]);

        // Update the auction itself
        $auction->update([
            'title' => $request->title,
            'seller' => $request->seller,
//            'item_id' => $item->id,
            'buyout' => $request->buyout,
            'start_datetime' => $request->start_datetime,
            'end_datetime' => $request->end_datetime,
            'updated_at' => Carbon::now(),          // This is only updated if any other input is different from current ones
        ]);

//        return $auction;
        return Auction::where('id', $auction->id)->first();
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

        // returning Model, so it picks up all formatted data
//        return DB::table('auctions')->where('id', $auction->id)->first();
        return Auction::where('id', $auction->id)->first();
    }
}
