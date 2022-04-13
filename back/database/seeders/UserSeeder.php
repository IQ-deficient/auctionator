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
                'first_name' => 'Milos',
                'last_name' => 'Krstic',
                'email' => 'milos.admin@gmail.com',
                'phone_number' => '067000001',
                'gender' => null,
                'country' => 'Montenegro',
                'is_active' => true,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'id' => 2,
                'username' => 'vladimir.admin',
                'password' => Hash::make('vladimir.admin'),
                'first_name' => 'Vladimir',
                'last_name' => 'Gazivoda',
                'email' => 'vladimir.admin@gmail.com',
                'phone_number' => '067000002',
                'gender' => null,
                'country' => 'Montenegro',
                'is_active' => true,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'id' => 3,
                'username' => 'mirko',
                'password' => Hash::make('mirko_test'),     // minimum 6 characters for login?
                'first_name' => 'Mirko',
                'last_name' => 'Degenerik',
                'email' => 'mirko@gmail.com',
                'phone_number' => '067000003',
                'gender' => 'Male',
                'country' => 'Bosnia and Herzegovina',
                'is_active' => true,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'id' => 4,
                'username' => 'kristijan.manager',
                'password' => Hash::make('kristijan.manager'),
                'first_name' => 'Kristijan',
                'last_name' => 'Grgusic',
                'email' => 'kristijan.manager@gmail.com',
                'phone_number' => '067000004',
                'gender' => null,
                'country' => 'Serbia',
                'is_active' => true,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'id' => 5,
                'username' => 'serdjo.manager',
                'password' => Hash::make('serdjo.manager'),
                'first_name' => 'Serdjo',
                'last_name' => 'Crevar',
                'email' => 'serdjo.manager@gmail.com',
                'phone_number' => '067000005',
                'gender' => null,
                'country' => null,
                'is_active' => true,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'id' => 6,
                'username' => 'nemanja.auctioneer',
                'password' => Hash::make('nemanja.auctioneer'),
                'first_name' => 'Nemanja',
                'last_name' => 'Kukulicic',
                'email' => 'nemanja.auctioneer@gmail.com',
                'phone_number' => '067000006',
                'gender' => null,
                'country' => null,
                'is_active' => true,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'id' => 7,
                'username' => 'borko.auctioneer',
                'password' => Hash::make('borko.auctioneer'),
                'first_name' => 'Borko',
                'last_name' => 'Smolovic',
                'email' => 'borko.auctioneer@gmail.com',
                'phone_number' => '067000007',
                'gender' => null,
                'country' => null,
                'is_active' => true,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'id' => 8,
                'username' => 'luka.123',
                'password' => Hash::make('luka.123'),
                'first_name' => 'Luka',
                'last_name' => 'Popovic',
                'email' => 'luka.123@gmail.com',
                'phone_number' => '067000008',
                'gender' => null,
                'country' => null,
                'is_active' => true,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'id' => 9,
                'username' => 'lazar.123',
                'password' => Hash::make('lazar.123'),
                'first_name' => 'Lazar',
                'last_name' => 'Lukic',
                'email' => 'lazar.123@gmail.com',
                'phone_number' => '067000009',
                'gender' => null,
                'country' => null,
                'is_active' => true,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'id' => 10,
                'username' => 'nikola.123',
                'password' => Hash::make('nikola.123'),
                'first_name' => 'Nikola',
                'last_name' => 'Neki',
                'email' => 'nikola.123@gmail.com',
                'phone_number' => '067000010',
                'gender' => null,
                'country' => null,
                'is_active' => true,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'id' => 11,
                'username' => 'vladimir.123',
                'password' => Hash::make('vladimir.123'),
                'first_name' => 'Vladimir',
                'last_name' => 'Gazivoda',
                'email' => 'vladimircg98@gmail.com',
                'phone_number' => '067336363',
                'gender' => 'Male',
                'country' => 'Montenegro',
                'is_active' => true,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
        ];

        DB::table('users')->insert($seeds);
    }
}
