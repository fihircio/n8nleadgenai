<?php

namespace App\Listeners;

use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Events\WebhookReceived;
use App\Models\User;
use Illuminate\Http\Response;

class StripeWebhook
{
    public function handle(WebhookReceived $event): Response
    {
        ['type' => $type, 'data' => $data] = $event->payload;
        //Log::info(['type' => $type, 'data' => $data]);

        if ($type === 'customer.created') {
            $this->handleUserCreated($data['object']);
        }

        if ($type === 'customer.subscription.deleted') {
            //Log::info(['type' => $type, 'data' => $data]);
            $this->handleSubscriptionCancelled($data['object']);
        } elseif ($type === 'customer.subscription.updated') {
            //Log::info(['type' => $type, 'data' => $data]);
            $this->handleSubscriptionUpdated($data['object']);
        } elseif ($type === 'customer.subscription.created') {
            //Log::info(['type' => $type, 'data' => $data]);
            $this->handleSubscriptionCreated($data['object']);
        }

        return new Response('Webhook received and processed successfully', 200);
    }

    protected function handleSubscriptionTier($data)
    {
        $tier = SubscriptionPlan::where('plan_id', $data['plan']['id'])->first()->tier;

        $user = User::where('stripe_id', $data['customer'])->first();

        if ($tier === 'first-tier') {
            $user->syncRoles('premium-first-tier');
        } elseif ($tier === 'second-tier') {
            $user->syncRoles('premium-second-tier');
        } elseif ($tier === 'third-tier') {
            $user->syncRoles('premium-third-tier');
        }
    }

    protected function handleUserCreated($data)
    {
        $user = User::where('email', $data['email'])->first();
        if ($user) {
            $user->stripe_id = $data['id'];
            $user->save();
        }
    }

    protected function handleSubscriptionUpdated($subscription)
    {
        $user = User::where('stripe_id', $subscription['customer'])->first();

        if ($user) {
            $this->handleSubscriptionTier($subscription);
        } else {
            Log::warning("User with Stripe ID {$subscription['customer']} not found.");
        }
    }

    protected function handleSubscriptionCreated($subscription)
    {
        $user = User::where('stripe_id', $subscription['customer'])->first();

        if ($user) {
            // Ensure the user's stripe_status is not "incomplete"
            if ($user->stripe_status === 'incomplete') {
                Log::info("User {$user->id} has an incomplete subscription. No permissions granted.");
                return;
            }

            $this->handleSubscriptionTier($subscription);
            Log::info("Subscription {$subscription['id']} processed for user {$user->id}");
        } else {
            Log::warning("User with Stripe ID {$subscription['customer']} not found.");
        }
    }


    protected function handleSubscriptionCancelled($subscription)
    {
        $user = User::where('stripe_id', $subscription['customer'])->first();

        if ($user) {
            $user->syncRoles('user');
        } else {
            Log::warning("User with Stripe ID {$subscription['customer']} not found.");
        }
    }
}
