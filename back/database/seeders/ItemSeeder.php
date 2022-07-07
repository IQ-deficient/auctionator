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
                'created_at' => Carbon::yesterday()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::yesterday()->format('Y-m-d H:i:s'),
            ],
            [
                'id' => 2,
                'title' => 'Red leather coat',
                'description' => 'A thick, robust looking red leather coat, seemingly worn out from use.',
                'category' => "Men's Accessories",
                'condition' => 'Worn',
                'warehouse_id' => 1,
                'created_at' => Carbon::yesterday()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::yesterday()->format('Y-m-d H:i:s'),
            ],
            [
                'id' => 3,
                'title' => 'Vintage Chanel handbag',
                'description' => 'A black lacquered Chanel handbag, assumed to be from the 1960s collection.',
                'category' => "Women's Accessories",
                'condition' => 'Worn',
                'warehouse_id' => 2,
                'created_at' => Carbon::yesterday()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::yesterday()->format('Y-m-d H:i:s'),
            ],
            [
                'id' => 4,
                'title' => 'Trading card',
                'description' => 'A highly valued and sonically sealed collectible card from early 2000s.',
                'category' => "Collectibles",
                'condition' => 'Mint',
                'warehouse_id' => 1,
                'created_at' => Carbon::yesterday()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::yesterday()->format('Y-m-d H:i:s'),
            ],
            [
                'id' => 5,
                'title' => 'Golden goblet',
                'description' => 'A goblet, made of solid gold which can hold up to 2 liters of liquid.',
                'category' => "Misc.",
                'condition' => '--',
                'warehouse_id' => 2,
                'created_at' => Carbon::yesterday()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::yesterday()->format('Y-m-d H:i:s'),
            ],
            [
                'id' => 6,
                'title' => 'Old grimoire',
                'description' => 'A textbook that includes instructions on how to summon or invoke supernatural entities.',
                'category' => "Books & Magazines",
                'condition' => '--',
                'warehouse_id' => 2,
                'created_at' => Carbon::yesterday()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::yesterday()->format('Y-m-d H:i:s'),
            ],
            [
                'id' => 7,
                'title' => 'Matryoshka doll',
                'description' => 'Wooden dolls of decreasing size placed one inside another depicting women in Russian national costumes with headscarves.',
                'category' => "Misc.",
                'condition' => '--',
                'warehouse_id' => 2,
                'created_at' => Carbon::yesterday()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::yesterday()->format('Y-m-d H:i:s'),
            ],
            [
                'id' => 8,
                'title' => 'Rapier',
                'description' => 'A light thrusting sword often used for dueling and sport fighting.',
                'category' => "Misc.",
                'condition' => '--',
                'warehouse_id' => 2,
                'created_at' => Carbon::yesterday()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::yesterday()->format('Y-m-d H:i:s'),
            ],
            [
                'id' => 9,
                'title' => 'Old gaming console',
                'description' => 'An old gaming console packaged with a controller and several games.',
                'category' => "Video Games & Consoles",
                'condition' => 'Used',
                'warehouse_id' => 2,
                'created_at' => Carbon::yesterday()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::yesterday()->format('Y-m-d H:i:s'),
            ],
        ];

        DB::table('items')->insert($seeds);
    }
}
