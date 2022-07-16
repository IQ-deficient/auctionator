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
                'title' => 'Arnold\'s Dumbel',
                'description' => 'Arnolds\' dumbel is a specially designed dumbbell that can be used by both children and adults. It is made from high-quality materials and is built to last. it is a great tool for weightlifting, strength training, and rehabilitation.',
                'category' => 'Antiques',
                'condition' => 'Fair',
                'warehouse_id' => 1,
                'created_at' => Carbon::yesterday()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::yesterday()->format('Y-m-d H:i:s'),
            ],
            [
                'id' => 2,
                'title' => 'Golden Cap',
                'description' => 'In the world of rowing, there are no limits to the equipment that can be used to enhance performance. One of the newest pieces of equipment is the Golden Cap. It\'s a helmet that is worn on the head of the rower. The helmet is made of carbon fiber and the headband is made of fabric. The inside of the helmet is padded and lined with a comfortable, breathable fabric. The Golden Cap is designed to reduce drag and make the rower more aerodynamic.',
                'category' => "Men's Accessories",
                'condition' => 'Worn',
                'warehouse_id' => 1,
                'created_at' => Carbon::yesterday()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::yesterday()->format('Y-m-d H:i:s'),
            ],
            [
                'id' => 3,
                'title' => 'Spongebob Prop',
                'description' => 'The Spongebob Prop is a replica of the original Spongebob Squarepants. It is a 6-inch tall vinyl figure of the popular cartoon character. It is a prop and is not a toy.',
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
                'title' => 'Diamond Cigar Box, Filled',
                'description' => 'This cigar set is a perfect accessory for your best suit or tuxedo, but also is great for when you’re wearing your favorite pair of jeans. The sleek box makes it easy to pack and it’s a perfect gift for a friend. Each cigar has intricate designs engraved with diamond dust, depicting a lion and a lamb.',
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
            [
                'id' => 10,
                'title' => 'Star Platinum',
                'description' => 'Star Platinum is a power tool that can be used to perform any number of household tasks. It is the perfect tool for any household, especially those with a large number of members or those with children. Star Platinum can be used to cut, saw, and drill wood and metal. It can also be used to cut up food, grate cheese, and even peel vegetables.',
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
