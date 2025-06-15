<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkflowTemplate extends Model
{
    protected $fillable = [
        'title',
        'category',
        'icon',
        'description',
        'coin_cost',
        'inputs',
        'sample_output',
    ];

    protected $casts = [
        'inputs' => 'array',
    ];
}
