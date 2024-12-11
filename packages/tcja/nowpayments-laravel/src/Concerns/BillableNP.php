<?php

namespace Tcja\NOWPaymentsLaravel\Concerns;

use Tcja\NOWPaymentsLaravel\Models\Subscription;
use Tcja\NOWPaymentsLaravel\Models\CustomerNP;
use Tcja\NOWPaymentsLaravel\Models\Payment;
use Tcja\NOWPaymentsLaravel\NOWPayments;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait BillableNP
{
    public function nowpayments(): NOWPayments
    {
        return new NOWPayments($this);
    }

    public function subscriptionsNP(): MorphMany
    {
        return $this->morphMany(Subscription::class, 'billable')->orderBy('created_at', 'desc');
    }

    public function subscriptionNP(?string $name = 'default'): ?Subscription
    {
        return $this->subscriptionsNP()->where('name', $name)->first();
    }

    public function onTrialNP(string $type = 'default'): bool
    {
        $subscription = $this->subscriptionNP($type);

        if ($subscription && $subscription->status === 'trial' ) {
            return true;
        }

        return false;
    }

    public function customerNP(): MorphOne
    {
        return $this->morphOne(CustomerNP::class, 'billable');
    }

    public function payment(): MorphOne
    {
        return $this->morphOne(Payment::class, 'billable');
    }

    public function createAsCustomerNP(array $customerData = []): Customer
    {
        if ($this->customer) {
            return $this->customer;
        }

        return $this->customerNP()->create(array_merge([
            'nowpayments_id' => $this->nowpayments()->createCustomer($this)->id,
        ], $customerData));
    }

    public function cancel()
    {
        $this->status = 'cancelled';
        $this->save();
    }

    private function revokePermissions()
    {
        $user = User::find($this->billable_id);
        $user->syncRoles('user');
    }

    public function resume()
    {
        $this->status = 'active';
        $this->save();
    }

    public function subscribedNP(string $name = 'default'): bool
    {
        $subscription = $this->subscriptionNP($name);

        return $subscription && $subscription->valid();
    }

    public function newSubscriptionNP(string $name, string $plan)
    {
        return $this->nowpayments()->createSubscription($this, $name, $plan);
    }
}
