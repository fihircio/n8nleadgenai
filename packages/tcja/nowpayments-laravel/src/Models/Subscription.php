<?php

namespace Tcja\NOWPaymentsLaravel\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use Carbon\Carbon;

/**
 * @property \Tcja\NOWPaymentsLaravel\Concerns\Billable $billable
 */
class Subscription extends Model
{
    use HasFactory;

    protected $table = 'nowpayments_subscriptions';

    protected $fillable = [
        'billable_id',
        'billable_type',
        'name',
        'nowpayments_id',
        'status',
        'nowpayments_plan',
        'quantity',
        'trial_ends_at',
        'ends_at',
        'paused_at',
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'ends_at' => 'datetime',
        'paused_at' => 'datetime',
    ];

    public function billable(): MorphTo
    {
        return $this->morphTo();
    }

    public function active(): bool
    {
        return $this->status === 'active' && !$this->ended();
    }

    public function onTrial(): bool
    {
        return $this->trial_ends_at && $this->trial_ends_at->isFuture();
    }

    public function paused(): bool
    {
        return $this->paused_at && $this->paused_at->isPast();
    }

    public function ended(): bool
    {
        return $this->ends_at && $this->ends_at->isPast();
    }

    public function valid(): bool
    {
        return $this->active() || $this->onTrial() || !$this->ended();
    }

    public function pending(): bool
    {
        return $this->status === 'pending';
    }

    public function incomplete(): bool
    {
        return $this->status === 'incomplete';
    }

    public function expired(): bool
    {
        return $this->status === 'expired';
    }

    public function onGracePeriod()
    {
        if (is_null($this->ends_at)) {
            return false;
        }

        return Carbon::now()->lt($this->ends_at) && Carbon::now()->gt($this->ends_at->subDays(7));
    }

    public function cancel()
    {
        $this->status = 'cancelled';
        $this->save();
    }

    public function resume()
    {
        $this->status = 'active';
        $this->save();
    }

    public function revokePermissions()
    {
        $user = User::find($this->billable_id);
        $user->syncRoles('user');
    }
}
