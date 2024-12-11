<?php

namespace App\Listeners;

use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Tcja\NOWPaymentsLaravel\Events\WebhookReceived;
use Tcja\NOWPaymentsLaravel\Models\Customer;

class NowPaymentsWebhook
{
    public function handle(WebhookReceived $event): Response
    {
        $payload = $event->payload;
        $eventType = $payload['type'];

        //Log::info(['type' => $eventType, 'data' => $payload['data']]);

        if ($eventType === 'subscription_created') {
            if ($payload['data']['status'] === 'PAID') {
                $this->handleSubscriptionCreated($payload['data']);
            }
        }

        return new Response('Webhook received and processed successfully', 200);
    }

    protected function handleSubscriptionTier($data)
    {
        $tier = SubscriptionPlan::where('plan_id', $data['plan_id'])->first()->tier;

        $nowPaymentsId = $data['id'];
        $user = User::find(Customer::where('nowpayments_id', $nowPaymentsId)->first()->billable_id);

        if ($tier === 'first-tier') {
            $user->syncRoles('premium-first-tier');
        } elseif ($tier === 'second-tier') {
            $user->syncRoles('premium-second-tier');
        } elseif ($tier === 'third-tier') {
            $user->syncRoles('premium-third-tier');
        }
    }

    protected function handleSubscriptionUpdated($subscription)
    {
        $nowPaymentsId = $subscription['id'];
        $user = User::find(Customer::where('nowpayments_id', $nowPaymentsId)->first()->billable_id);

        if ($user) {
            if ($user->subscription()->onGracePeriod()) {
                return;
            }

            $this->handleSubscriptionTier($subscription);
        }
    }

    protected function handleSubscriptionCreated($subscription)
    {
        $nowPaymentsId = $subscription['id'];
        $user = User::find(Customer::where('nowpayments_id', $nowPaymentsId)->first()->billable_id);

        if ($user) {
            $this->handleSubscriptionTier($subscription);
        }
    }


    protected function handleSubscriptionExpired($subscription)
    {
        $nowPaymentsId = $subscription['id'];
        $user = User::find(Customer::where('nowpayments_id', $nowPaymentsId)->first()->billable_id);

        if ($user) {
            $user->syncRoles('user');
        }
    }
}
