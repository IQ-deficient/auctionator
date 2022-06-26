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
                'title' => 'Developer Nemanja Kukulicic',
                'description' => 'A literal human being, being sold on an auction. Using the term human being is generous to say the least, and developer is a term being used too broadly.',
                'category' => "Misc.",
                'condition' => '--',
                'warehouse_id' => 1,
                'created_at' => Carbon::yesterday()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::yesterday()->format('Y-m-d H:i:s'),
            ],
            [
                'id' => 5,
                'title' => 'Delicate Golden Goblet',
                'description' => 'This goblet is made of solid gold, the perfect gift for a wealthy king. The goblet has intricate designs engraved in the gold, depicting a lion and a lamb. The goblet can hold up to 2 liters of liquid.',
                'category' => "Misc.",
                'condition' => '--',
                'warehouse_id' => 2,
                'created_at' => Carbon::yesterday()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::yesterday()->format('Y-m-d H:i:s'),
            ],
            [
                'id' => 6,
                'title' => 'Magical Book of Spells',
                'description' => 'A book that can change the reader\'s life. It will take you on a journey of learning and understanding. It will change your life and make you a better person.',
                'category' => "Misc.",
                'condition' => '--',
                'warehouse_id' => 2,
                'created_at' => Carbon::yesterday()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::yesterday()->format('Y-m-d H:i:s'),
            ],
            [
                'id' => 7,
                'title' => 'Potentially cursed Dummy Doll',
                'description' => 'The product is a soft, plush toy designed to look like a human baby. It\'s perfect for infants and toddlers who need comfort.',
                'category' => "Misc.",
                'condition' => '--',
                'warehouse_id' => 2,
                'created_at' => Carbon::yesterday()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::yesterday()->format('Y-m-d H:i:s'),
            ],
            [
                'id' => 8,
                'title' => 'Giant thin wool cotton shroud',
                'description' => 'With the perfect combination of soft material and dark colors, this piece will create an unforgettable look. It can be worn as a shawl or a cloak and is perfect for those who like to show off their individual style.',
                'category' => "Misc.",
                'condition' => '--',
                'warehouse_id' => 2,
                'created_at' => Carbon::yesterday()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::yesterday()->format('Y-m-d H:i:s'),
            ],
            [
                'id' => 9,
                'title' => 'Offline Party Animatronic',
                'description' => 'If you are looking for a new decoration for your haunted house, this is it! With a long, spooky white beard and an eerie, vacant stare, this deactivated animatronic will be sure to give your guests the shivers.',
                'category' => "Misc.",
                'condition' => '--',
                'warehouse_id' => 2,
                'created_at' => Carbon::yesterday()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::yesterday()->format('Y-m-d H:i:s'),
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
//        // figure the length number to be reactive
//        $chunks = array_chunk($seeds, 10);
//
//        foreach ($chunks as $chunk){
//            DB::table('items')->insert($chunk);
//        }

        DB::table('items')->insert($seeds);

    }
}
