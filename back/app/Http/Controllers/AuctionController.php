<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Http\Requests\StoreAuctionRequest;
use App\Http\Requests\UpdateAuctionRequest;
use http\Env\Request;
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

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreAuctionRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAuctionRequest $request)
    {
        //
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
        //
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
