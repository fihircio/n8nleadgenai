<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'plan_id',
        'name',
        'tier',
        'currency',
        'price',
        'price_formatted',
        'billing_provider',
    ];
}
