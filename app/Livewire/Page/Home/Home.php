<?php

namespace App\Livewire\Page\Home;

use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Renderless;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('SaaShovel')]
class Home extends Component
{
    #[Locked]
    public $plans;
    #[Locked]
    public $cryptoPlans;
    #[Locked]
    public $billingProvider;

    #[Renderless]
    public function subscribeToPlan($plan)
    {
        Session::put('billing_provider', $this->billingProvider);
        Session::put('subscribeToPlan', $plan);
        $this->redirectRoute('register');
    }

    #[Renderless]
    public function subscribeToPlanWithCrypto($plan)
    {
        Session::put('billing_provider', 'nowpayments');
        Session::put('subscribeToPlanWithCrypto', $plan);
        $this->redirectRoute('register');
    }

    public function mount()
    {
        if (Session::has('subscribeToPlan') || Session::has('subscribeToPlanWithCrypto')) {
            Session::forget(['subscribeToPlan', 'subscribeToPlanWithCrypto', 'billing_provider']);
        }

        /*
            This is for NOWPayments. If you need to update subscription plan data on the fly,
            you can use the code below to do it through NOWPayments' API.
            If you don't use NOWPayments, you can delete this code.

            dd(
                app(\Tcja\NOWPaymentsLaravel\NOWPayments::class)->updateSubscriptionPlan('PLAN-ID', [
                "title"=> "title",
                "interval_day"=> 30,
                "amount"=> 19,
                "partially_paid_url" => "http://your-test-env-url.test/dashboard",
                "success_url" => "http://your-test-env-url.test/dashboard",
                "cancel_url" => "http://your-test-env-url.test/dashboard",
                "currency"=> "usd",
                "ipn_callback_url"=> "http://your-test-env-url.test/nowpayments/webhook",
                ]),
                app(\Tcja\NOWPaymentsLaravel\NOWPayments::class)->updateSubscriptionPlan('PLAN-ID', [
                "title"=> "title",
                "interval_day"=> 30,
                "amount"=> 29,
                "partially_paid_url" => "http://your-test-env-url.test/dashboard",
                "success_url" => "http://your-test-env-url.test/dashboard",
                "cancel_url" => "http://your-test-env-url.test/dashboard",
                //"currency"=> "usd",
                "ipn_callback_url"=> "http://your-test-env-url.test/nowpayments/webhook",
                ]),
                app(\Tcja\NOWPaymentsLaravel\NOWPayments::class)->updateSubscriptionPlan('PLAN-ID', [
                "title"=> "Lolz",
                "interval_day"=> 30,
                "amount"=> 39,
                "partially_paid_url" => "http://your-test-env-url.test/dashboard",
                "success_url" => "http://your-test-env-url.test/dashboard",
                "cancel_url" => "http://your-test-env-url.test/dashboard",
                "currency"=> "usd",
                "ipn_callback_url"=> "http://your-test-env-url.test/nowpayments/webhook",
                ])
            );
        */

        $this->billingProvider = Auth::user()?->billing_provider ?? config('saashovel.BILLING_PROVIDER');
        $this->plans = SubscriptionPlan::where('billing_provider', $this->billingProvider)->get()->keyBy('tier')->toArray();
        $this->cryptoPlans = SubscriptionPlan::where('billing_provider', 'nowpayments')->get()->keyBy('tier')->toArray();
    }

    #[Layout('layouts.guest')]
    public function render()
    {
         return view(Auth::check() && Auth::user()->can('access premium features') ? 'livewire.pages.home.home' : 'livewire.pages.landing-page.landing-page');
    }
}
