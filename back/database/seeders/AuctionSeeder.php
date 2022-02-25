<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AuctionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seeds = [
            [
                'id' => 1,
                'title' => 'Jako kul brutalica ludilica mutilica od aukcije',
                'seller' => 'Vladimir Gazivoda',
                'item_id' => 1,
                'bid_id' => null,
                'buyout' => 300.00,
                'status' => 'Created',
                'start_datetime' => Carbon::yesterday()->format('Y-m-d H:i:s'),
                'end_datetime' => Carbon::tomorrow()->format('Y-m-d H:i:s'),
                'user_id' => 6,
                'is_active' => true,
                'created_at' => Carbon::yesterday()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'id' => 2,
                'title' => 'Slava mu i milos, spokoj mu dusi',
                'seller' => 'Saban Saulic',
                'item_id' => 2,
                'bid_id' => null,
                'buyout' => 9999.99,
                'status' => 'Created',
                'start_datetime' => Carbon::yesterday()->format('Y-m-d H:i:s'),
                'end_datetime' => Carbon::tomorrow()->format('Y-m-d H:i:s'),
                'user_id' => 6,
                'is_active' => true,
                'created_at' => Carbon::yesterday()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'id' => 3,
                'title' => 'Pi politika',
                'seller' => 'Dritan Abazovic',
                'item_id' => 3,
                'bid_id' => 1,
                'buyout' => 750000.75,
                'status' => 'Ongoing',
                'start_datetime' => Carbon::yesterday()->format('Y-m-d H:i:s'),
                'end_datetime' => Carbon::tomorrow()->format('Y-m-d H:i:s'),
                'user_id' => 7,
                'is_active' => true,
                'created_at' => Carbon::yesterday()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'id' => 4,
                'title' => 'Aj vise krsta mi svetoga i ostroga da ako se proda ovo magare vise',
                'seller' => 'Zoran Kukulicic',
                'item_id' => 4,
                'bid_id' => 2,
                'buyout' => 1.50,
                'status' => 'Sold',
                'start_datetime' => Carbon::yesterday()->format('Y-m-d H:i:s'),
                'end_datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'user_id' => 3,
                'is_active' => true,
                'created_at' => Carbon::yesterday()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
        ];

        DB::table('auctions')->insert($seeds);
    }
}
