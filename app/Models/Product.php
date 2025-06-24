<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sku', 'name', 'description', 'category_id', 'price', 'cost', 'barcode', 'unit',
        'track_serial', 'track_expiry', 'min_stock', 'max_stock', 'active', 'metadata'
    ];

    protected $casts = [
        'metadata' => 'array',
        'track_serial' => 'boolean',
        'track_expiry' => 'boolean',
        'active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    public function warehouses()
    {
        return $this->belongsToMany(Warehouse::class, 'inventories');
    }

    public function stockTransactions()
    {
        return $this->hasMany('App\\Models\\StockTransaction');
    }
}
