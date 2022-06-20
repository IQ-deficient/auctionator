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
//                'id' => 1,
                'title' => 'Though the origin is unknown, the beauty of this piece simply shimmers',
                'seller' => 'Demigod Simons',
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
                'title' => 'Provides ample warmth for the upcoming Ice Age',
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
                'title' => 'Not for the faint of heart, nor for the shallow wallets',
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
                'title' => 'The humorous nature of auctions is partially kept alive',
                'seller' => 'Mental Asylum Patient 23',
                'item_id' => 4,
                'bid_id' => 3,
                'buyout' => 1.50,
                'status' => 'Sold',
                'start_datetime' => Carbon::yesterday()->format('Y-m-d H:i:s'),
                'end_datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'user_id' => 6,
                'is_active' => true,
                'created_at' => Carbon::yesterday()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'title' => 'It is said that ancient politicians got drunk using this goblet',
                'seller' => 'Anonymous',
                'item_id' => 5,
                'bid_id' => null,
                'buyout' => 12500,
                'status' => 'Created',
                'start_datetime' => Carbon::yesterday()->format('Y-m-d H:i:s'),
                'end_datetime' => Carbon::tomorrow()->format('Y-m-d H:i:s'),
                'user_id' => 6,
                'is_active' => true,
                'created_at' => Carbon::yesterday()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'title' => 'This auction is prime example for testing the schedule:work artisan command',
                'seller' => 'Laravel Scheduler',
                'item_id' => 6,
                'bid_id' => 2,
                'buyout' => 1.05,
                'status' => 'Ongoing',
                'start_datetime' => Carbon::yesterday()->format('Y-m-d H:i:s'),
                'end_datetime' => Carbon::yesterday()->format('Y-m-d H:i:s'),
                'user_id' => 7,
                'is_active' => true,
                'created_at' => Carbon::yesterday()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::yesterday()->format('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Dummy Expired Status Auction for testing purposes',
                'seller' => 'NPC \'La Kringe\'',
                'item_id' => 7,
                'bid_id' => null,
                'buyout' => 9.99,
                'status' => 'Expired',
                'start_datetime' => Carbon::yesterday()->format('Y-m-d H:i:s'),
                'end_datetime' => Carbon::yesterday()->format('Y-m-d H:i:s'),
                'user_id' => 6,
                'is_active' => true,
                'created_at' => Carbon::yesterday()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::yesterday()->format('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Dummy NA Status Auction for testing purposes',
                'seller' => 'Invisible Man',
                'item_id' => 8,
                'bid_id' => null,
                'buyout' => 2.99,
                'status' => 'NA',
                'start_datetime' => Carbon::yesterday()->format('Y-m-d H:i:s'),
                'end_datetime' => Carbon::tomorrow()->format('Y-m-d H:i:s'),
                'user_id' => 6,
                'is_active' => true,
                'created_at' => Carbon::yesterday()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::yesterday()->format('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Dummy Inactive Auction for testing purposes',
                'seller' => 'Das Animus Defecticus SCP',
                'item_id' => 9,
                'bid_id' => null,
                'buyout' => 4.99,
                'status' => 'Created',
                'start_datetime' => Carbon::yesterday()->format('Y-m-d H:i:s'),
                'end_datetime' => Carbon::tomorrow()->format('Y-m-d H:i:s'),
                'user_id' => 6,
                'is_active' => false,
                'created_at' => Carbon::yesterday()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::yesterday()->format('Y-m-d H:i:s'),
            ],
        ];

        DB::table('auctions')->insert($seeds);
    }
}
