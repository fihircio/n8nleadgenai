<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StockTransaction;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\User;
use Illuminate\Support\Str;

class StockTransactionSeeder extends Seeder
{
    public function run(): void
    {
        $productIds = Product::pluck('id')->toArray();
        $warehouseIds = Warehouse::pluck('id')->toArray();
        $userIds = User::pluck('id')->toArray();
        $types = ['inbound', 'outbound', 'transfer', 'adjustment'];
        for ($i = 1; $i <= 25; $i++) {
            $type = $types[array_rand($types)];
            $sourceWarehouse = null;
            $destinationWarehouse = null;
            if ($type === 'transfer') {
                $sourceWarehouse = $warehouseIds[array_rand($warehouseIds)];
                do {
                    $destinationWarehouse = $warehouseIds[array_rand($warehouseIds)];
                } while ($destinationWarehouse === $sourceWarehouse);
            }
            StockTransaction::create([
                'reference_number' => 'TXN' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'product_id' => $productIds[array_rand($productIds)],
                'warehouse_id' => $warehouseIds[array_rand($warehouseIds)],
                'type' => $type,
                'quantity' => rand(1, 50),
                'batch_number' => 'BATCH' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'serial_number' => 'SERIAL' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'expiry_date' => now()->addDays(rand(30, 365)),
                'unit_cost' => rand(100, 1000) / 100,
                'reason' => $type === 'adjustment' ? 'Stock adjustment' : null,
                'source_warehouse_id' => $sourceWarehouse,
                'destination_warehouse_id' => $destinationWarehouse,
                'created_by' => $userIds[array_rand($userIds)],
                'status' => 'completed',
                'metadata' => [],
            ]);
        }
    }
} 