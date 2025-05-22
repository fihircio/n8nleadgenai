<?php

namespace App\Models;

//use Illuminate\Foundation\Auth\User as Authenticatable;
//use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Devdojo\Auth\Models\User as AuthUser;
use LemonSqueezy\Laravel\Order;
use Tcja\NOWPaymentsLaravel\Concerns\BillableNP;
use Tcja\NOWPaymentsLaravel\Models\Payment;
use Bavix\Wallet\Traits\HasWallet;
use Illuminate\Support\Str;

class User extends /* Authenticatable */ AuthUser implements FilamentUser, \Bavix\Wallet\Interfaces\Wallet
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasRoles;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use BillableNP;
    use HasWallet;

    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            return $this->hasRole('admin');
        }
        return false;
    }

    public function LSOrders()
    {
        return $this->hasMany(Order::class, 'billable_id');
    }

    public function NPPayments()
    {
        return $this->hasMany(Payment::class, 'billable_id');
    }

    public function isSocialite()
    {
        return empty($this->socialProviders()->first()) ? false : true;
    }

    /**
     * Add coins to the user's wallet.
     */
    public function addCoins(int|float $amount, ?array $meta = []): \Bavix\Wallet\Models\Transaction
    {
        return $this->deposit($amount, $meta);
    }

    /**
     * Subtract coins from the user's wallet.
     */
    public function subtractCoins(int|float $amount, ?array $meta = []): \Bavix\Wallet\Models\Transaction
    {
        return $this->withdraw($amount, $meta);
    }

    /**
     * Get the user's coin balance.
     */
    public function getCoinBalance(): int|float
    {
        return $this->balance;
    }

    /**
     * Fetch wallet transactions for this user.
     * Override to match Bavix\Wallet\Interfaces\Wallet signature.
     */
    public function transactions(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(\Bavix\Wallet\Models\Transaction::class, 'payable');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'billing_driver',
        'password',
        'referral_code',
        'referred_by',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function ($user) {
            if (empty($user->referral_code)) {
                $user->referral_code = strtoupper(Str::random(8));
            }
        });
    }

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    public function referrals()
    {
        return $this->hasMany(User::class, 'referred_by');
    }
}
