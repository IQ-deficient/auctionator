<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seeds = [
            ['id' => 1, 'name' => 'Bazaar of Coins', 'address' => 'Serdara Jola Piletića, Momišići', 'is_active' => true, 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 2, 'name' => 'Gather Your Assets', 'address' => 'Ulica Gojka Berkuljana St 4', 'is_active' => true, 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 3, 'name' => 'The Reclaimed Goods', 'address' => '12 Piperska', 'is_active' => true, 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 4, 'name' => 'Bidders\' Choice', 'address' => '13-5 VI Crnogorske Udarne Brigade', 'is_active' => true, 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),],
        ];

        DB::table('warehouses')->insert($seeds);
    }
}
