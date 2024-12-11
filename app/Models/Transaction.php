<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Paddle\Transaction as PaddleTransaction;

class Transaction extends PaddleTransaction
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'billable_type',
        'billable_id',
        'paddle_id',
        'paddle_subscription_id',
        'invoice_number',
        'status',
        'total',
        'tax',
        'currency',
        'billed_at',
    ];

    /**
     * Get the owning billable model.
     */
    public function billable()
    {
        return $this->morphTo();
    }
}
