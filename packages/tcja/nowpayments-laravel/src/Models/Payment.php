<?php

namespace Tcja\NOWPaymentsLaravel\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'nowpayments_payments';

    protected $fillable = [
        'billable_id',
        'billable_type',
        'subscription_id',
        'nowpayments_payment_id',
        'nowpayments_invoice_id',
        'status',
        'outcome_amount',
        'outcome_currency',
        'price_amount',
        'price_currency',
        'pay_amount',
        'pay_currency',
        'pay_address',
        'actually_paid',
        'actually_paid_at_fiat',
        'fee',
        'purchase_id',
        'updated_at', // This is from the webhook, not Laravel's default updated_at
    ];

    protected $casts = [
        'fee' => 'array',
    ];

    public function billable(): MorphTo
    {
        return $this->morphTo();
    }
}
