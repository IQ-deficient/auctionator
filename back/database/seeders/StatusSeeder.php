<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seeds = [
            // These statuses represent certain phases of the auction lifecycle
            // Freshly made
            ['id' => 1, 'status' => 'Created', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            // There is an active bid for this auction
            ['id' => 2, 'status' => 'Ongoing', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            // There were no buyers for the duration of this auction
            ['id' => 3, 'status' => 'Expired', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            // Auction either finished with a certain bid value or there was a buyout
            ['id' => 4, 'status' => 'Sold', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            // Catastrophe or other issues (just in case) [Not Available]
            ['id' => 5, 'status' => 'NA', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),],
        ];

        DB::table('statuses')->insert($seeds);
    }
}
