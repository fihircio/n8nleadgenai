<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Analytics extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'metric_type',
        'metric_name',
        'metric_value',
        'metadata',
        'date',
    ];

    protected $casts = [
        'metric_value' => 'float',
        'metadata' => 'array',
        'date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Helper methods for different metric types
    public static function trackLeadScore($userId, $score, $leadData = [])
    {
        return self::create([
            'user_id' => $userId,
            'metric_type' => 'lead_scoring',
            'metric_name' => 'score',
            'metric_value' => $score,
            'metadata' => $leadData,
            'date' => now(),
        ]);
    }

    public static function trackTemplateUsage($userId, $templateId, $cost, $success = true)
    {
        return self::create([
            'user_id' => $userId,
            'metric_type' => 'template_usage',
            'metric_name' => 'purchase',
            'metric_value' => $cost,
            'metadata' => [
                'template_id' => $templateId,
                'success' => $success,
            ],
            'date' => now(),
        ]);
    }

    public static function trackConversion($userId, $leadScore, $converted, $revenue = 0)
    {
        return self::create([
            'user_id' => $userId,
            'metric_type' => 'conversion',
            'metric_name' => 'lead_conversion',
            'metric_value' => $converted ? 1 : 0,
            'metadata' => [
                'lead_score' => $leadScore,
                'revenue' => $revenue,
            ],
            'date' => now(),
        ]);
    }
} 