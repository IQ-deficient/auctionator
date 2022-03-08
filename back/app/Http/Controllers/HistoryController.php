<?php

namespace App\Http\Controllers;

use App\Models\History;
use App\Http\Requests\StoreHistoryRequest;
use App\Http\Requests\UpdateHistoryRequest;

class HistoryController extends Controller
{

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return History::all();
    }

    /**
     * Display a listing of the resource that references authenticated User only.
     * @return \Illuminate\Http\Response
     */
    public function getHistoriesForUser()
    {
        // comment of the method explains it
    }


    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * @param \App\Http\Requests\StoreHistoryRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreHistoryRequest $request)
    {
        // todo: BUYOUT OR EXPIRE(latter might be middleware)

        // for buyout: (only clients can buyout naturally)
        // change auction status to sold,
        // deactivate last bid if there is any for that auction,
        // use code from BidController,
        // store an instance of History with that auction and logged-in user and final_price taken from auction buyout value,
        // return that history obj

    }

    /**
     * Display the specified resource.
     * @param \App\Models\History $history
     * @return History
     */
    public function show(History $history)
    {
        return $history;
    }

    /**
     * Show the form for editing the specified resource.
     * @param \App\Models\History $history
     * @return \Illuminate\Http\Response
     */
    public function edit(History $history)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * @param \App\Http\Requests\UpdateHistoryRequest $request
     * @param \App\Models\History $history
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateHistoryRequest $request, History $history)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param \App\Models\History $history
     * @return \Illuminate\Http\Response
     */
    public function destroy(History $history)
    {
        // We might have to delete auctions for some situations, bad practice tho
    }
}
