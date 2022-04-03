<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\History;
use App\Http\Requests\StoreHistoryRequest;
use App\Http\Requests\UpdateHistoryRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
     * Store a newly created resource in storage. Auction Buyout
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        // When the client loads Auctions that are eligible for purchase he can still try to Buyout the auctions that might expire during his exploration and thus when he clicks on auction that has ended we can make sure it is stored as History and removed from browsed

        $validator = Validator::make($request->all(), [
            'auction_id' => 'required|integer|exists:auctions,id'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $roles = User::getUserRoles();

        // Check if the currently authenticated user is registered as Client
        abort_if(!in_array('Client', $roles), 403, 'Only Clients are allowed to buyout.');

        // Get Auction Model Object that is active
        $auction = Auction::query()
            ->where('id', $request->auction_id)
            ->where('is_active', true)
            ->first();

        // TODO: Validation to check if the auction expired (just in case) make this check as model function

        // Check if auction can be bought out, we already know the auction is active from previous model query
        $no_bid_statuses = ['Expired', 'Sold', 'N/A'];
        abort_if(in_array($auction->status, $no_bid_statuses), 410, 'This auction is no longer eligible for buyout.');

        // Invalidate the last and only bid for auction being bought out
        DB::table('bids')
            ->where('id', $auction->bid_id)
            ->where('is_active', true)
            ->update([
                'is_active' => false,
                'updated_at' => Carbon::now()
            ]);

        // Change the auction status to Sold (We are not removing the bid_id for this auction)
        $auction->update([
            'status' => 'Sold',
            'updated_at' => Carbon::now(),
        ]);

        // Create new History instance for this auction
        $history = History::create([
            'auction_id' => $auction->id,
            'username' => Auth::user()->username,
            'final_price' => $auction->buyout
        ]);

        return History::where('id', $history->id)->first();
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
