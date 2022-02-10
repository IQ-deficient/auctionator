<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
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
                'username' => 'milos.admin',
                'password' => Hash::make('milos.admin'),
                'first_name' => 'temp_fn',
                'last_name' => 'temp_ln',
                'email' => 'temp.email1@gmail.com',
                'phone_number' => '067000001',
                'is_active' => true,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'name' => 'PH_name'
            ],
            [
                'id' => 2,
                'username' => 'vladimir.admin',
                'password' => Hash::make('vladimir.admin'),
                'first_name' => 'temp_fn',
                'last_name' => 'temp_ln',
                'email' => 'temp.email2@gmail.com',
                'phone_number' => '067000002',
                'is_active' => true,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'name' => 'PH_name'
            ],
            [
                'id' => 3,
                'username' => 'mirko',
                'password' => Hash::make('mirko'),
                'first_name' => 'temp_fn',
                'last_name' => 'temp_ln',
                'email' => 'temp.email3@gmail.com',
                'phone_number' => '067000003',
                'is_active' => true,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'name' => 'PH_name'
            ],
            [
                'id' => 4,
                'username' => 'kristijan.manager',
                'password' => Hash::make('kristijan.manager'),
                'first_name' => 'temp_fn',
                'last_name' => 'temp_ln',
                'email' => 'temp.email4@gmail.com',
                'phone_number' => '067000004',
                'is_active' => true,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'name' => 'PH_name'
            ],
            [
                'id' => 5,
                'username' => 'serdjo.manager',
                'password' => Hash::make('serdjo.manager'),
                'first_name' => 'temp_fn',
                'last_name' => 'temp_ln',
                'email' => 'temp.email5@gmail.com',
                'phone_number' => '067000005',
                'is_active' => true,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'name' => 'PH_name'
            ],
            [
                'id' => 6,
                'username' => 'nemanja.auctioneer',
                'password' => Hash::make('nemanja.auctioneer'),
                'first_name' => 'temp_fn',
                'last_name' => 'temp_ln',
                'email' => 'temp.email6@gmail.com',
                'phone_number' => '067000006',
                'is_active' => true,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'name' => 'PH_name'
            ],
            [
                'id' => 7,
                'username' => 'borko.auctioneer',
                'password' => Hash::make('borko.auctioneer'),
                'first_name' => 'temp_fn',
                'last_name' => 'temp_ln',
                'email' => 'temp.email7@gmail.com',
                'phone_number' => '067000007',
                'is_active' => true,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'name' => 'PH_name'
            ],
            [
                'id' => 8,
                'username' => 'luka.123',
                'password' => Hash::make('luka.123'),
                'first_name' => 'temp_fn',
                'last_name' => 'temp_ln',
                'email' => 'temp.email8@gmail.com',
                'phone_number' => '067000008',
                'is_active' => true,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'name' => 'PH_name'
            ],
            [
                'id' => 9,
                'username' => 'lazar.123',
                'password' => Hash::make('lazar.123'),
                'first_name' => 'temp_fn',
                'last_name' => 'temp_ln',
                'email' => 'temp.email9@gmail.com',
                'phone_number' => '067000009',
                'is_active' => true,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'name' => 'PH_name'
            ],
            [
                'id' => 10,
                'username' => 'nikola.123',
                'password' => Hash::make('nikola.123'),
                'first_name' => 'temp_fn',
                'last_name' => 'temp_ln',
                'email' => 'temp.email10@gmail.com',
                'phone_number' => '067000010',
                'is_active' => true,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'name' => 'PH_name'
            ],
        ];

        DB::table('users')->insert($seeds);
    }
}
