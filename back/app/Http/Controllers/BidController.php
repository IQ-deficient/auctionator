<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Bid;
use App\Http\Requests\StoreBidRequest;
use App\Http\Requests\UpdateBidRequest;
use App\Models\Image;
use App\Models\Item;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BidController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Bid[]|Collection
     */
    public function index()
    {
        return Bid::all();
    }

    /**
     * Display a listing of the resource that references authenticated User only.
     * @return array
     */
    public function getBidsForUser()
    {
        $username = Auth::user()->username;
        $roles = User::getUserRoles($username);
        abort_if(!in_array('Client', $roles), 403, 'Only Clients can preview the history of their purchases.');

        // Find all the bids this User has made
        $user_bids = Bid::orderBy('created_at', 'desc')
            ->where('is_active', true)
            ->where('username', $username)
            ->get();

        // For each Bid we've found, add an Auction that has this bid as bid_id and add whole object to return array if this auction is functional
        foreach ($user_bids as $bid) {
            $auction = Auction::query()
                ->where('is_active', true)
                ->where('bid_id', $bid->id)
                ->whereIn('status', ['Created', 'Ongoing'])
                ->first();
            $bid->auction = $auction;
            if ($auction) {
                $images = Image::query()
                    ->where('item_id', $auction->item_id)
                    ->get();
                $bid->images = $images;
            }
        }

        return $user_bids;
    }

    public function getActive()
    {
        return Bid::query()->where('is_active', true)->get();
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
     * Create a new Bid for the given Auction and deactivate previous one if present.
     * @param Request $request
     * @return Builder|JsonResponse|Model|object
     */
    public function store(Request $request)
    {
        $username = Auth::user()->username;
        $roles = User::getUserRoles($username);
        // Check if the currently authenticated user is registered as Client
        abort_if(!in_array('Client', $roles), 403, 'Only Clients are allowed to place bids.');

        $validator = Validator::make($request->all(), [
            'auction_id' => 'required|integer|exists:auctions,id',
            'value' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Get Auction Model Object that is active
        $auction = Auction::where([
            ['is_active', true],
            ['id', $request->auction_id]
        ])->first();

        // Just in case someone tries to bid on an inactive auction (when previous query did not find it)
        abort_if(!$auction, 404, 'This auction no longer exists.');

        // In case there is an attempt to bid on the unbindable auction, cancel further actions
        // Since we only work with is_active==true entities here, there is no reason to check for that
        $no_bid_statuses = ['Expired', 'Sold', 'NA'];
        abort_if(in_array($auction->status, $no_bid_statuses) || Carbon::now() >= $auction->end_datetime,
            410, 'This auction is no longer eligible for bids.');

        // Client should not be able to bid with value higher than one of the auction buyout as that really just makes buyout irrelevant
        abort_if($request->value >= $auction->buyout, 400, "The bid value can't be greater than the buyout value.");

        // Client must offer a value greater than min_bid_value for that auction
        abort_if($request->value < $auction->min_bid_value, 400, "The bid value can't be less than the minimum defined bid value.");

        // When there is already existent bid_id for auction in question apply the following actions
        if ($auction->bid_id) {
            // Check if the user trying to bid already owns the leading bid value
            $current_bid_user = DB::table('bids')
                ->where('id', $auction->bid_id)
                ->where('is_active', true)
                ->value('username');
            abort_if($username == $current_bid_user, 400, "You already own the highest bid for this auction.");

            // Check if the input bid value is lower or equal to current bid value for this auction
            abort_if($request->value <= $auction->bid->value, 400, "New bid value must be greater than current one.");

            // Make sure new bid value is at least certain amount more than the previous Bid value
            $compare_old_value = $auction->bid->value * 1.02;           // Currently, by agreement, we are using 2%
            abort_if($compare_old_value >= $request->value, 400, "New bid value must be at least 2% more than current one.");

            // When we are sure that we have a valid new value, we should invalidate the previous (current) bid
            Bid::deactivateBid($auction->bid_id);
        }

        // Make a new bid instance with input values and authenticated user (client)
        $bid = Bid::create([
            'value' => $request->value,
            'username' => Auth::user()->username,
        ]);

        // Alter the auction we are bidding on with freshly created Bid and status
        $auction->update([
            'bid_id' => $bid->id,
            'status' => 'Ongoing',
            'updated_at' => Carbon::now()
        ]);

        return Bid::query()->where('id', $bid->id)->first();
    }

    /**
     * Display the specified resource.
     * @param \App\Models\Bid $bid
     * @return Response
     */
    public function show(Bid $bid)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Bid $bid
     * @return Response
     */
    public function edit(Bid $bid)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateBidRequest $request
     * @param \App\Models\Bid $bid
     * @return Response
     */
    public function update(UpdateBidRequest $request, Bid $bid)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Bid $bid
     * @return Response
     */
    public function destroy(Bid $bid)
    {
        //
    }
}
