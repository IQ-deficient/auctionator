<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Bid;
use App\Http\Requests\UpdateBidRequest;
use App\Models\Image;
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

        $user_bids = Bid::orderBy('created_at', 'desc')
            ->where('is_active', true)
            ->where('username', $username)
            ->get();

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
        abort_if(!in_array('Client', $roles), 403, 'Only Clients are allowed to place bids.');

        $validator = Validator::make($request->all(), [
            'auction_id' => 'required|integer|exists:auctions,id',
            'value' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $auction = Auction::where([
            ['is_active', true],
            ['id', $request->auction_id]
        ])->first();

        abort_if(!$auction, 404, 'This auction no longer exists.');

        $no_bid_statuses = ['Expired', 'Sold', 'NA'];
        abort_if(in_array($auction->status, $no_bid_statuses) || Carbon::now() >= $auction->end_datetime,
            410, 'This auction is no longer eligible for bids.');

        abort_if($request->value >= $auction->buyout, 400, "The bid value can't be greater than the buyout value.");

        if ($auction->bid_id) {
            $current_bid_user = DB::table('bids')
                ->where('id', $auction->bid_id)
                ->where('is_active', true)
                ->value('username');
            abort_if($username == $current_bid_user, 400, "You already own the highest bid for this auction.");

            abort_if($request->value <= $auction->bid->value, 400, "New bid value must be greater than current one.");

            $compare_old_value = $auction->bid->value * 1.03;
            abort_if($compare_old_value >= $request->value, 400, "New bid value must be at least 3% more than current one.");

            Bid::deactivateBid($auction->bid_id);
        }

        $bid = Bid::create([
            'value' => $request->value,
            'username' => Auth::user()->username,
        ]);

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
