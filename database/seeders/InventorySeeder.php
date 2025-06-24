<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Warehouse;

class InventorySeeder extends Seeder
{
    public function run(): void
    {
        $productIds = Product::pluck('id')->toArray();
        $warehouseIds = Warehouse::pluck('id')->toArray();
        for ($i = 1; $i <= 25; $i++) {
            Inventory::create([
                'product_id' => $productIds[array_rand($productIds)],
                'warehouse_id' => $warehouseIds[array_rand($warehouseIds)],
                'quantity' => rand(1, 100),
                'reserved_quantity' => rand(0, 10),
                'batch_number' => 'BATCH' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'serial_number' => 'SERIAL' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'expiry_date' => now()->addDays(rand(30, 365)),
                'shelf_location' => 'Aisle ' . rand(1, 10),
                'status' => 'available',
                'metadata' => [],
            ]);
        }
    }
} 