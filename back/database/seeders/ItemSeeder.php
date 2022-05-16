<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ItemSeeder extends Seeder
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
                'title' => 'Victorian style chair',
                'description' => 'A very old, possibly a few hundred years old sitting chair with gilded legs.',
                'category' => 'Antiques',
                'condition' => 'Fair',
                'warehouse_id' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'id' => 2,
                'title' => 'Red leather coat',
                'description' => 'A thick, robust looking red leather coat, seemingly worn out from use.',
                'category' => "Men's Accessories",
                'condition' => 'Worn',
                'warehouse_id' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'id' => 3,
                'title' => 'Full stack developer.',
                'description' => 'A literal human being, being sold on an auction. Using the term human being is generous to say the least, and developer is a term being used too broadly.',
                'category' => "Misc.",
                'condition' => '--',
                'warehouse_id' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'id' => 4,
                'title' => 'Vintage Chanel handbag',
                'description' => 'A black lacquered Chanel handbag, assumed to be from the 1960s collection.',
                'category' => "Women's Accessories",
                'condition' => 'Worn',
                'warehouse_id' => 2,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'id' => 5,
                'title' => 'Predmet javnog testiranja',
                'description' => 'Hajde da ne mozgamo previse.',
                'category' => "Misc.",
                'condition' => '--',
                'warehouse_id' => 2,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
        ];

//        for ($i = 1; $i <= 10000; $i++) {
//            $item = [
//                'id' => (1000 + $i),
//                'title' => 'Test Item ' . $i,
//                'description' => 'Test Item Desc ' . $i,
//                'category' => "Cell Phones, Smart Watches & Accessories",
//                'condition' => 'New',
//                'warehouse_id' => 1,
//                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
//                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
//            ];
//            array_push($seeds, $item);
//        }
//
//        // todo: figure the length number to be reactive
//        $chunks = array_chunk($seeds, 10);
//
//        foreach ($chunks as $chunk){
//            DB::table('items')->insert($chunk);
//        }

        DB::table('items')->insert($seeds);

    }
}
