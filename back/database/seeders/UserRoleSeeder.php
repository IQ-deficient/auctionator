<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seeds = [
            ['username' => 'vladimir.admin', 'role' => 'Administrator', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['username' => 'lazar.admin', 'role' => 'Administrator', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['username' => 'staka.admin', 'role' => 'Administrator', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['username' => 'mirko', 'role' => 'Manager', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['username' => 'mirko', 'role' => 'Auctioneer', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),],
//            ['username' => 'mirko', 'role' => 'Client', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),],
//            ['username' => 'mirko', 'role' => 'Administrator', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['username' => 'maynard.manager', 'role' => 'Manager', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['username' => 'petar.manager', 'role' => 'Manager', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['username' => 'zoran.auctioneer', 'role' => 'Auctioneer', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['username' => 'serdjo.auctioneer', 'role' => 'Auctioneer', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['username' => 'luka.123', 'role' => 'Client', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['username' => 'marcus.123', 'role' => 'Client', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['username' => 'ridzaruto.123', 'role' => 'Client', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['username' => 'evangeline.123', 'role' => 'Client', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['username' => 'katya.123', 'role' => 'Client', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),],
        ];

        DB::table('user_roles')->insert($seeds);
    }
}
