<?php

namespace App\Jobs;

use App\Mail\MailNotification;
use App\Models\Bid;
use App\Models\History;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AuctionEnd implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $statuses = ['Created', 'Ongoing'];

        $auctions = DB::table('auctions')
            ->where('is_active', true)
            ->whereIn('status', $statuses)
            ->where('end_datetime', '<=', Carbon::now())
            ->get();

        foreach ($auctions as $auction) {

            if ($auction->bid_id == null) {
                DB::table('auctions')
                    ->where('id', $auction->id)
                    ->update([
                        'status' => 'Expired',
                        'updated_at' => Carbon::now()
                    ]);
            } else {
                Bid::deactivateBid($auction->bid_id);

                DB::table('auctions')
                    ->where('id', $auction->id)
                    ->update([
                        'status' => 'Sold',
                        'updated_at' => Carbon::now()
                    ]);

                $bid = DB::table('bids')
                    ->where('id', $auction->bid_id)
                    ->first();
                History::create([
                    'auction_id' => $auction->id,
                    'username' => $bid->username,
                    'final_price' => $bid->value
                ]);

                Mail::to(User::query()->where('username', $bid->username)->first())
                    ->queue(new MailNotification(
                        'Congratulations. You now own the following Auction: "' . $auction->title . '". Please visit the History tab on our platform for additional information. Thanks.',
                        'You have won an Auction with ID:' . $auction->id
                    ));
            }
        }
    }
}
