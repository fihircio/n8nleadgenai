<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutomationResult extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'status', 'result',
    ];
    protected $casts = [
        'result' => 'array',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
