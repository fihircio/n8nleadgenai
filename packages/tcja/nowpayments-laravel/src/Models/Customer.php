<?php

namespace Tcja\NOWPaymentsLaravel\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'nowpayments_customers';

    protected $fillable = [
        'billable_id',
        'billable_type',
        'nowpayments_id',
        'name',
        'email',
        'trial_ends_at',
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
    ];

    public function billable(): MorphTo
    {
        return $this->morphTo();
    }
}
