<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // ITEM
        $this->call(ConditionSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(CategoryConditionSeeder::class);
        $this->call(WarehouseSeeder::class);
        $this->call(ItemSeeder::class);
        $this->call(ImageSeeder::class);

        // USER
        $this->call(RoleSeeder::class);
        $this->call(GenderSeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(UserSeeder::class);
        $this->call(UserRoleSeeder::class);

        // AUCTION
        $this->call(StatusSeeder::class);
        $this->call(BidSeeder::class);
        $this->call(AuctionSeeder::class);
        $this->call(HistorySeeder::class);
    }
}
