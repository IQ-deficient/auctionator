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
                'title' => 'Bakarni Dildo S Klasse',
                'description' => 'Ovaj dildo ce vam promijeniti zivot. Necete samo morati da primite injekciju protiv tetanusa, iritacija na vasoj odabranoj povrsini nadrazavanja ce ostaviti neizbrisivu senzaciju kakvu do sada nikada niste osjetili zato sto vise necete moci da osjetite nista.',
                'category' => 'Antiques',
                'warehouse_id' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'id' => 2,
                'title' => 'Perika Sabana Saulica',
                'description' => 'Perika legendarnog virtuoza i carobnjaka narodne muzike. Legenda kaze da on nije nosio periku. Naime, same vokalne moci ovog astralnog barda su bile dovoljne da isusuju i obnavljaju folikule dlake na njegovoj glavi. Mocno jebote.',
                'category' => "Men's Accessories",
                'warehouse_id' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'id' => 3,
                'title' => 'Zdravko Krivokapic antitijela bath water',
                'description' => 'For all you thirsty gamer boys. UwU',
                'category' => "Miscellaneous",
                'warehouse_id' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'id' => 4,
                'title' => 'Nemanja Kukulicic',
                'description' => 'Udomi me pls i ja zasluzujem krov nad glavom i topli krevet.',
                'category' => "Women's Accessories",
                'warehouse_id' => 2,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
        ];

        DB::table('items')->insert($seeds);
    }
}
