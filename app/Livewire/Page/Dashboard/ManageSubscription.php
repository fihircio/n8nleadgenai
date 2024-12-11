<?php

namespace App\Livewire\Page\Dashboard;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Renderless;
use Livewire\Component;

class ManageSubscription extends Component
{
    public $subTier;
    public $subStatus;
    public $subEndsAt;
    #[Locked]
    public $plans;
    public User $user;
    #[Locked]
    public $billingProvider;

    #[On('cancelPlan')]
    #[Renderless]
    public function cancelPlan()
    {
        if ($this->billingProvider === 'nowpayments') {
            Auth::user()->subscriptionNP()->cancel();
        } elseif ($this->billingProvider === 'stripe') {
            Auth::user()->subscription()->cancel();
        } elseif ($this->billingProvider === 'paddle') {
            Auth::user()->subscription()->cancel();
        } elseif ($this->billingProvider === 'lemonsqueezy') {
            Auth::user()->subscription()->cancel();
        }
        $this->dispatch('refreshThePage');
    }

    #[On('changePlan')]
    #[Renderless]
    public function changePlan($priceId = null)
    {
        $subscription = Auth::user()->subscription();
        if ($this->billingProvider === 'nowpayments') {
            $subscription = Auth::user()->subscriptionNP();
            $subscription->revokePermissions();
            Auth::user()->nowpayments()->deleteSubscription($subscription->nowpayments_id);
            $subscription->update(['status' => 'expired']);
        } elseif ($this->billingProvider === 'paddle') {
            $subscription->swapAndInvoice($priceId);
        }
        $this->dispatch('refreshThePage');
    }

    #[On('restorePlan')]
    #[Renderless]
    public function restorePlan()
    {
        $subscription = Auth::user()->subscription();
        if ($this->billingProvider === 'stripe') {
            $subscription->resume();
        } elseif ($this->billingProvider === 'paddle') {
            $subscription->stopCancelation();
        } elseif ($this->billingProvider === 'lemonsqueezy') {
            $subscription->resume();
        } elseif ($this->billingProvider === 'nowpayments') {
            Auth::user()->subscriptionNP()->resume();
        }
        $this->dispatch('refreshThePage');
    }

    public function render()
    {
        return view('livewire.pages.dashboard.manage-subscription');
    }
}
