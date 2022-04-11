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
                'title' => 'Bakarni elektricni uredjaj S Klasse',
                'description' => 'Ovaj strujni aparatus ce vam promijeniti zivot. Necete samo morati da primite injekciju protiv tetanusa, iritacija na vasoj odabranoj povrsini nadrazavanja ce ostaviti neizbrisivu senzaciju kakvu do sada nikada niste osjetili zato sto vise necete moci da osjetite nista.',
                'category' => 'Antiques',
                'condition' => 'Fair',
                'warehouse_id' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Perika Sabana Saulica',
                'description' => 'Perika legendarnog virtuoza i carobnjaka narodne muzike. Legenda kaze da on nije nosio periku. Naime, same vokalne moci ovog astralnog barda su bile dovoljne da isusuju i obnavljaju folikule dlake na njegovoj glavi. Mocno ostroga mi.',
                'category' => "Men's Accessories",
                'condition' => 'Worn',
                'warehouse_id' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Zdravko Krivokapic antitijela bath water',
                'description' => 'For all you thirsty gamer boys. UwU',
                'category' => "Misc.",
                'condition' => '--',
                'warehouse_id' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Nemanja Kukulicic',
                'description' => 'Udomi me pls i ja zasluzujem krov nad glavom i topli krevet.',
                'category' => "Women's Accessories",
                'condition' => 'Worn',
                'warehouse_id' => 2,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Predmet javnog testiranja',
                'description' => 'Hajde da ne mozgamo previse.',
                'category' => "Misc.",
                'condition' => '--',
                'warehouse_id' => 2,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
        ];

        DB::table('items')->insert($seeds);
    }
}
