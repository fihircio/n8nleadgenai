<?php

namespace App\Livewire\Page\Dashboard;

use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Renderless;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Dashboard')]
class Dashboard extends Component
{
    public $subTier;
    public $subStatus;
    public $subEndsAt;
    public $subscriptions;
    #[Locked]
    public $plans;
    #[Locked]
    public $billingProvider;
    public User $user;

    public function mount()
    {
        if (Session::has('redirect')) {
            return $this->redirect(Session::get('redirect'));
        }

        $sessionKey = Session::has('subscribeToPlan') ? 'subscribeToPlan' : (Session::has('subscribeToPlanWithCrypto') ? 'subscribeToPlanWithCrypto' : null);

        if ($sessionKey) {
            $this->dispatch('clickOnPlan', plan: Session::get($sessionKey));
            Session::forget($sessionKey);
        }

        $this->billingProvider = Auth::user()?->billing_provider;

        $user = Auth::user();

        $this->user = $user;

        if ($user->can('access admin panel')) {
            return redirect('/user/profile');
        }

        $this->plans = SubscriptionPlan::where('billing_provider', $this->billingProvider)->get()->keyBy('tier')->toArray();

        $subscription = $user->subscription();
        if (!empty(request()->query('NP_id'))) {
            if ($user->can('access premium features') && $subscription?->active()) {
                $this->redirectRoute('dashboard');
            }
            try {
                $webhook = new \Tcja\NOWPaymentsLaravel\Http\Controllers\WebhookController;
                $webhook->handleWebhook(request(), $user->nowpayments()->getPayment(request()->query('NP_id')));
            } catch (\Throwable $th) {}
        }
        $subscriptionNP = $this->user->subscriptionNP();
        if (!empty($subscriptionNP)) {
            if ($this->billingProvider === 'nowpayments') {
                if ($subscriptionNP->expired()) {
                    $subscriptionNP->revokePermissions();
                    $user->nowpayments()->deleteSubscription($subscriptionNP->nowpayments_id);
                } elseif ($subscriptionNP->pending()) {
                    if ($user->can('access premium features')) {
                        $subscriptionNP->revokePermissions();
                        $this->redirectRoute('dashboard');
                    }
                }
            }
        }
        if ($user->can('access premium features')) {
            $this->getSubStatus();
            $this->getSubTier();
        }
        $this->getSubData();
    }

    #[Renderless] // for paddle
    public function changePaymentMethod() {
        Auth::user()->subscription()->redirectToUpdatePaymentMethod();
    }

    public function getSubData()
    {
        if ($this->billingProvider === 'nowpayments') {
            if ($this->user && $this->user->subscriptionsNP()->count()) {
                $this->subscriptions = $this->user->subscriptionsNP;
            }
        } else {
            if ($this->user && $this->user->subscriptions()->count()) {
                $this->subscriptions = $this->user->subscriptions;
            }
        }
    }

    public function getSubStatus()
    {
        $subStatus = null;
        $subscriptions = $this->user->subscriptions()->latest()->first();
        if ($this->billingProvider === 'stripe') {
            $subStatus = $subscriptions->stripe_status ?? null;
        } elseif ($this->billingProvider === 'paddle') {
            $subStatus = $subscriptions->status ?? null;
        } elseif ($this->billingProvider === 'lemonsqueezy') {
            $subStatus = $subscriptions->status ?? null;
        } elseif ($this->billingProvider === 'nowpayments') {
            $subscriptions = $this->user->subscriptionsNP()->latest()->first();
            $subStatus = $subscriptions->status ?? null;
        }
        $this->subStatus = $subStatus;
        $this->subEndsAt = $subscriptions->ends_at ?? null;
    }

    public function getSubTier()
    {
        $tier = null;
        $subscriptions = $this->user->subscriptions();
        if ($this->billingProvider === 'stripe') {
            $tier = SubscriptionPlan::where('plan_id', $subscriptions->latest()->first()->stripe_price)->first()->name;
        } elseif ($this->billingProvider === 'paddle') {
            $tier = SubscriptionPlan::where('plan_id', $subscriptions->latest()->first()->items->first()->price_id)->first()->name;
        } elseif ($this->billingProvider === 'lemonsqueezy') {
            $tier = SubscriptionPlan::where('plan_id', $subscriptions->latest()->first()->variant_id)->first()->name;
        } elseif ($this->billingProvider === 'nowpayments') {
            $tier = SubscriptionPlan::where('plan_id', $this->user->subscriptionsNP()->latest()->first()->nowpayments_plan)->first()->name;
        }

        $this->subTier = $tier;
    }

    public function render()
    {
        if (Auth::user()->cannot('access premium features')) {
            if ($this->billingProvider === 'nowpayments') {
                $subscription = Auth::user()->subscriptionNP();
                if (!empty(request()->query('NP_id'))) {
                    if (empty($subscription) || $subscription->active()) {
                        $this->redirectRoute('dashboard');
                    } else {
                        return view('livewire.partials.loadingSpinner');
                    }
                } elseif (!empty($subscription)) {
                    if ($subscription->pending()) {
                        if ($subscription->onGracePeriod()) {
                            return view('livewire.partials.loadingSpinner')->with([
                                'message' => __('Please renew your subscription to continue using our service'),
                            ]);
                        }
                        return view('livewire.partials.loadingSpinner');
                    }
                }
            } elseif ($this->billingProvider === 'stripe') {
                if (request()->query('sub') === 'true') {
                    return view('livewire.partials.loadingSpinner');
                }
            } elseif ($this->billingProvider === 'lemonsqueezy') {
                if (request()->query('sub') === 'true') {
                    return view('livewire.partials.loadingSpinner');
                }
            }
        }

        return view('livewire.pages.dashboard.dashboard');
    }
}
