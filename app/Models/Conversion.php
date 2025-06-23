<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Conversion extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'lead_id',
        'ai_lead_score_id',
        'conversion_type',
        'status',
        'revenue',
        'deal_size',
        'conversion_date',
        'notes',
        'metadata',
    ];

    protected $casts = [
        'revenue' => 'decimal:2',
        'deal_size' => 'decimal:2',
        'conversion_date' => 'date',
        'metadata' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function aiLeadScore(): BelongsTo
    {
        return $this->belongsTo(AiLeadScore::class);
    }

    // Conversion types
    const TYPE_SALE = 'sale';
    const TYPE_MEETING = 'meeting';
    const TYPE_DEMO = 'demo';
    const TYPE_TRIAL = 'trial';
    const TYPE_SUBSCRIPTION = 'subscription';

    // Status options
    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_LOST = 'lost';
    const STATUS_DELAYED = 'delayed';

    public static function getConversionTypes(): array
    {
        return [
            self::TYPE_SALE => 'Sale',
            self::TYPE_MEETING => 'Meeting',
            self::TYPE_DEMO => 'Demo',
            self::TYPE_TRIAL => 'Trial',
            self::TYPE_SUBSCRIPTION => 'Subscription',
        ];
    }

    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_LOST => 'Lost',
            self::STATUS_DELAYED => 'Delayed',
        ];
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isRevenueGenerating(): bool
    {
        return $this->isCompleted() && $this->revenue > 0;
    }
} 