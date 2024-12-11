<?php

namespace App\Listeners;

use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use LemonSqueezy\Laravel\Customer;
use LemonSqueezy\Laravel\Events\WebhookReceived;

class LemonSqueezyWebhook
{
    /**
     * Handle received LemonSqueezy webhooks.
     */
    public function handle(WebhookReceived $event): Response
    {
        $payload = $event->payload['data'];
        $eventType = $event->payload['meta']['event_name'];

        //Log::info(['type' => $eventType, 'data' => $payload]);

        if ($eventType === 'subscription_expired') {
            //Log::info($payload['attributes']);
            $this->handleSubscriptionExpired($payload['attributes']);
        } elseif ($eventType === 'subscription_updated') {
            //Log::info($payload['attributes']);
            $this->handleSubscriptionUpdated($payload['attributes']);
        } elseif ($eventType === 'subscription_created') {
            //Log::info($payload['attributes']);
            $this->handleSubscriptionCreated($payload['attributes']);
        }

        return new Response('Webhook received and processed successfully', 200);
    }

    protected function handleSubscriptionTier($data)
    {
        $tier = SubscriptionPlan::where('plan_id', $data['variant_id'])->first()->tier;

        $lemondSqueezyId = $data['customer_id'];
        $user = User::find(Customer::where('lemon_squeezy_id', $lemondSqueezyId)->first()->billable_id);

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
        $lemondSqueezyId = $subscription['customer_id'];
        $user = User::find(Customer::where('lemon_squeezy_id', $lemondSqueezyId)->first()->billable_id);
        if ($user) {
            if ($user->subscription()?->onGracePeriod()) {
                return;
            }
            $this->handleSubscriptionTier($subscription);
        }
    }

    protected function handleSubscriptionCreated($subscription)
    {
        $lemondSqueezyId = $subscription['customer_id'];
        $user = User::find(Customer::where('lemon_squeezy_id', $lemondSqueezyId)->first()->billable_id);

        if ($user) {
            $this->handleSubscriptionTier($subscription);
        }
    }


    protected function handleSubscriptionExpired($subscription)
    {
        $lemondSqueezyId = $subscription['customer_id'];
        $user = User::find(Customer::where('lemon_squeezy_id', $lemondSqueezyId)->first()->billable_id);
        if ($user) {
            $user->syncRoles('user');
        }
    }
}
