<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 15; $i++) {
            Category::create([
                'name' => 'Category ' . $i,
                'slug' => Str::slug('Category ' . $i),
                'description' => 'Description for Category ' . $i,
                'parent_id' => null,
                'sort_order' => $i,
                'active' => true,
            ]);
        }
    }
} 