<?php

namespace App\Http\Controllers;

use App\Mail\MailNotification;
use App\Models\Auction;
use App\Models\Bid;
use App\Models\History;
use App\Http\Requests\UpdateHistoryRequest;
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
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class HistoryController extends Controller
{

    /**
     * Display a listing of the resource.
     * @return Collection|History[]
     */
    public function index()
    {
        return History::all();
    }

    /**
     * Display a listing of the resource that references authenticated User only.
     * @return Builder[]|Collection
     */
    public function getHistoriesForUser()
    {
        $username = Auth::user()->username;
        $roles = User::getUserRoles($username);
        abort_if(!in_array('Client', $roles), 403, 'Only Clients can preview the history of their purchases.');

        $histories = History::query()
            ->where('username', $username)
            ->get();

        foreach ($histories as $history) {
            $images = Image::query()
                ->where('item_id', $history->auction_id)
                ->get();
            $history->images = $images;
        }

        return $histories;
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
     * Auction Buyout - Store a History instance.
     * @param Request $request
     * @return Builder|Model|JsonResponse|object
     */
    public function store(Request $request)
    {
        $roles = User::getUserRoles(Auth::user()->username);
        abort_if(!in_array('Client', $roles), 403, 'Only Clients are allowed to buyout.');

        $validator = Validator::make($request->all(), [
            'auction_id' => 'required|integer|exists:auctions,id'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $auction = Auction::query()
            ->where('id', $request->auction_id)
            ->where('is_active', true)
            ->first();

        abort_if(!$auction, 404, 'This auction no longer exists.');

        $no_bid_statuses = ['Expired', 'Sold', 'NA'];
        abort_if(in_array($auction->status, $no_bid_statuses) || Carbon::now() >= $auction->end_datetime,
            410, 'This auction is no longer eligible for buyout.');

        Bid::deactivateBid($auction->bid_id);

        $auction->update([
            'status' => 'Sold'
        ]);

        $history = History::create([
            'auction_id' => $auction->id,
            'username' => Auth::user()->username,
            'final_price' => $auction->buyout
        ]);

        Mail::to(User::query()->where('email', Auth::user()->email)->first())
            ->queue(new MailNotification(
                'Congratulations. You now own the following Auction: "' . $auction->title . '". Please visit the History tab on our platform for additional information. Thanks.',
                'You have won an Auction with an ID:' . $auction->id
            ));

        return History::query()->where('id', $history->id)->first();
    }

    /**
     * Display the specified resource.
     * @param History $history
     * @return History
     */
    public function show(History $history)
    {
        return $history;
    }

    /**
     * Show the form for editing the specified resource.
     * @param History $history
     * @return Response
     */
    public function edit(History $history)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * @param \App\Http\Requests\UpdateHistoryRequest $request
     * @param History $history
     * @return Response
     */
    public function update(UpdateHistoryRequest $request, History $history)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param History $history
     * @return Response
     */
    public function destroy(History $history)
    {
        //
    }
}
