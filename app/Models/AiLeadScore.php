<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AiLeadScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id',
        'score',
        'scoring_factors',
        'enrichment_data',
        'status',
        'error_message'
    ];

    protected $casts = [
        'scoring_factors' => 'array',
        'enrichment_data' => 'array',
        'score' => 'integer'
    ];

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function isHot(): bool
    {
        return $this->score >= 80;
    }

    public function isWarm(): bool
    {
        return $this->score >= 50 && $this->score < 80;
    }

    public function isCold(): bool
    {
        return $this->score < 50;
    }
} 