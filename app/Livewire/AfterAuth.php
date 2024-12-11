<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class AfterAuth extends Component
{
    public function mount()
    {
        $user = Auth::user();
        if ($user->can('access admin panel')) {
            $this->redirectRoute('filament.admin.pages.dashboard');
        } else {
            $billingProvider = $user->billing_provider;
            $sessionKey = Session::has('subscribeToPlan') ? 'subscribeToPlan' : (Session::has('subscribeToPlanWithCrypto') ? 'subscribeToPlanWithCrypto' : null);
            if ($sessionKey) {
                $plan = Session::get($sessionKey);
                if ($billingProvider === 'stripe') {
                    Session::forget($sessionKey);
                    $this->redirectRoute('checkoutStripe', ['stripePriceId' => $plan]);
                } elseif ($billingProvider === 'lemonsqueezy') {
                    Session::forget($sessionKey);
                    $this->redirectRoute('checkoutLS', ['lemonsqueezyPriceId' => $plan]);
                } elseif ($billingProvider === 'paddle') {
                    $this->redirectRoute('dashboard');
                } elseif ($billingProvider === 'nowpayments') {
                    $this->redirectRoute('dashboard');
                }
            } else {
                $this->redirectRoute('dashboard');
            }
        }
    }
}
