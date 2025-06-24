<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Warehouse;

class WarehouseSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            Warehouse::create([
                'name' => 'Warehouse ' . $i,
                'code' => 'WH' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'address' => '123 Warehouse St, City ' . $i,
                'city' => 'City ' . $i,
                'state' => 'State ' . $i,
                'country' => 'Country ' . $i,
                'postal_code' => '1000' . $i,
                'phone' => '555-000' . $i,
                'email' => 'warehouse' . $i . '@example.com',
                'is_default' => $i === 1,
                'active' => true,
            ]);
        }
    }
} 