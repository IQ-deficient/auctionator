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
                'title' => 'Though the origin is unknown, it is believed the legendary Arnold Schwarzenegger laid his hands on this piece in the past',
                'seller' => 'Demigod Simons',
                'item_id' => 1,
                'bid_id' => null,
                'buyout' => 1999.00,
                'status' => 'Created',
                'start_datetime' => Carbon::yesterday()->format('Y-m-d H:i:s'),
                'end_datetime' => Carbon::tomorrow()->format('Y-m-d H:i:s'),
                'user_id' => 7,
                'is_active' => true,
                'created_at' => Carbon::yesterday()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'title' => 'The rarest of the rare, this glimmering fiber cap provides ample warmth and rowing performance',
                'seller' => 'Saban Saulic',
                'item_id' => 2,
                'bid_id' => null,
                'buyout' => 300000.50,
                'status' => 'Created',
                'start_datetime' => Carbon::yesterday()->format('Y-m-d H:i:s'),
                'end_datetime' => Carbon::tomorrow()->format('Y-m-d H:i:s'),
                'user_id' => 7,
                'is_active' => true,
                'created_at' => Carbon::yesterday()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'title' => 'This prop was originally designed and made by the first people who worked on Spongebob series',
                'seller' => 'Dritan Abazovic',
                'item_id' => 3,
                'bid_id' => 1,
                'buyout' => 15000.00,
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
                'seller' => 'Zoran Kukulicic',
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
                'title' => 'While indulging in smoking, there is a chance to cleanse yourself from sins past and present',
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
                'title' => 'Laravel Scheduler Auction - schedule:work',
                'seller' => 'Laravel Scheduler',
                'item_id' => 6,
                'bid_id' => 2,
                'buyout' => 1.05,
                'status' => 'Ongoing',
                'start_datetime' => Carbon::yesterday()->format('Y-m-d H:i:s'),
                'end_datetime' => Carbon::yesterday()->format('Y-m-d H:i:s'),
                'user_id' => 6,
                'is_active' => true,
                'created_at' => Carbon::yesterday()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::yesterday()->format('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Expired Status Auction',
                'seller' => 'NPC \'La Kringe\'',
                'item_id' => 7,
                'bid_id' => null,
                'buyout' => 9.99,
                'status' => 'Expired',
                'start_datetime' => Carbon::yesterday()->format('Y-m-d H:i:s'),
                'end_datetime' => Carbon::yesterday()->format('Y-m-d H:i:s'),
                'user_id' => 7,
                'is_active' => true,
                'created_at' => Carbon::yesterday()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::yesterday()->format('Y-m-d H:i:s'),
            ],
            [
                'title' => 'NA Status Auction',
                'seller' => 'Mental Asylum Patient 23',
                'item_id' => 8,
                'bid_id' => null,
                'buyout' => 22.99,
                'status' => 'NA',
                'start_datetime' => Carbon::yesterday()->format('Y-m-d H:i:s'),
                'end_datetime' => Carbon::tomorrow()->format('Y-m-d H:i:s'),
                'user_id' => 7,
                'is_active' => true,
                'created_at' => Carbon::yesterday()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::yesterday()->format('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Inactive Auction',
                'seller' => 'Das Animus Defecticus SCP',
                'item_id' => 9,
                'bid_id' => null,
                'buyout' => 5.00,
                'status' => 'Created',
                'start_datetime' => Carbon::yesterday()->format('Y-m-d H:i:s'),
                'end_datetime' => Carbon::tomorrow()->format('Y-m-d H:i:s'),
                'user_id' => 7,
                'is_active' => false,
                'created_at' => Carbon::yesterday()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::yesterday()->format('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Do not mistake it for a Stand name from a popular anime series JOJO.',
                'seller' => 'Gigi Sunbeam',
                'item_id' => 10,
                'bid_id' => null,
                'buyout' => 12000000,
                'status' => 'Created',
                'start_datetime' => Carbon::yesterday()->format('Y-m-d H:i:s'),
                'end_datetime' => Carbon::tomorrow()->format('Y-m-d H:i:s'),
                'user_id' => 7,
                'is_active' => true,
                'created_at' => Carbon::yesterday()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
        ];

        DB::table('auctions')->insert($seeds);
    }
}
