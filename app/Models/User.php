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
use App\Billable;
use LemonSqueezy\Laravel\Order;
use Tcja\NOWPaymentsLaravel\Concerns\BillableNP;
use Tcja\NOWPaymentsLaravel\Models\Payment;

class User extends /* Authenticatable */ AuthUser implements FilamentUser/* , MustVerifyEmail */
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasRoles;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use Billable;
    use BillableNP;

    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            return $this->hasRole('admin');
        }
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
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'billing_driver',
        'password',
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
}
