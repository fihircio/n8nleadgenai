<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->withPersonalTeam()->create();

        $this->call(AdminUserSeeder::class);
        $this->call(SubscriptionPlanSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(WarehouseSeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(InventorySeeder::class);
        $this->call(StockTransactionSeeder::class);
    }
}
