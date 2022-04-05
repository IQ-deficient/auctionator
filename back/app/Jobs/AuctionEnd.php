<?php

namespace App\Jobs;

use App\Models\History;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

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
        // todo: send E-mail to person that won the auction

        $statuses = ['Created', 'Ongoing'];

        // Fetch all Auction instances that are active, with statuses that represent active auctions and that have ended
        $auctions = DB::table('auctions')
            ->where('is_active', true)
            ->whereIn('status', $statuses)
            ->where('end_datetime', '<=', Carbon::now())
            ->get();

        foreach ($auctions as $auction) {

            if ($auction->bid_id == null) {
                // For auctions that have had no bids, just change the status appropriately, effectively making them inactive
                DB::table('auctions')
                    ->where('id', $auction->id)
                    ->update([
                        'status' => 'Expired',
                        'updated_at' => Carbon::now()
                    ]);
            } else {
                //Invalidate the last and only bid for auction being bought out
                DB::table('bids')
                    ->where('id', $auction->bid_id)
                    ->where('is_active', true)
                    ->update([
                        'is_active' => false,
                        'updated_at' => Carbon::now()
                    ]);
                // For auctions with bids, mark them as Sold
                DB::table('auctions')
                    ->where('id', $auction->id)
                    ->update([
                        'status' => 'Sold',
                        'updated_at' => Carbon::now()
                    ]);
                // Create new History instance for this auction using last available Bid data
                $bid = DB::table('bids')
                    ->where('id', $auction->bid_id)
                    ->first();
                History::create([
                    'auction_id' => $auction->id,
                    'username' => $bid->username,       // User that had the last (greatest) Bid wins this Auction
                    'final_price' => $bid->value       // Last Bid value
                ]);
            }
        }
    }
}