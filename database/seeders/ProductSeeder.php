<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categoryIds = Category::pluck('id')->toArray();
        for ($i = 1; $i <= 20; $i++) {
            Product::create([
                'sku' => 'SKU' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'name' => 'Product ' . $i,
                'description' => 'Description for Product ' . $i,
                'category_id' => $categoryIds[array_rand($categoryIds)],
                'price' => rand(100, 10000) / 100,
                'cost' => rand(50, 9000) / 100,
                'barcode' => 'BAR' . str_pad($i, 8, '0', STR_PAD_LEFT),
                'unit' => 'piece',
                'track_serial' => (bool)rand(0, 1),
                'track_expiry' => (bool)rand(0, 1),
                'min_stock' => rand(0, 10),
                'max_stock' => rand(20, 100),
                'active' => true,
                'metadata' => [],
            ]);
        }
    }
} 