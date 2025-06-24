<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'warehouse_id', 'quantity', 'reserved_quantity', 'batch_number', 'serial_number', 'expiry_date', 'shelf_location', 'status', 'metadata'
    ];

    protected $casts = [
        'metadata' => 'array',
        'expiry_date' => 'date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function stockTransactions()
    {
        return $this->hasMany('App\\Models\\StockTransaction', 'product_id', 'product_id');
    }
}
