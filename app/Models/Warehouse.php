<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'code', 'address', 'city', 'state', 'country', 'postal_code', 'phone', 'email', 'is_default', 'active'
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'active' => 'boolean',
    ];

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'inventories');
    }

    public function stockTransactions()
    {
        return $this->hasMany('App\\Models\\StockTransaction');
    }
}
