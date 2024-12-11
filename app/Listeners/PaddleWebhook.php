<?php

namespace App\Listeners;

use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Http\Response;
use Laravel\Paddle\Events\WebhookReceived;
use Illuminate\Support\Facades\Log;
use Laravel\Paddle\Customer;

class PaddleWebhook
{
    /**
     * Handle received Paddle webhooks.
     */
    public function handle(WebhookReceived $event): Response
    {
        $payload = $event->payload;
        $eventType = $payload['event_type'];

        //Log::info("Event Type: {$eventType}", 'payload_data' => $payload['data']);

        if ($eventType === 'customer.created') {
            $this->handleUserCreated($payload['data']);
            //Log::info($payload['data']);
        }

        if ($eventType === 'subscription.canceled') {
            //Log::info($payload['data']);
            $this->handleSubscriptionCancelled($payload['data']);
        } elseif ($eventType === 'subscription.updated') {
            //Log::info($payload['data']);
            $this->handleSubscriptionUpdated($payload['data']);
        } elseif ($eventType === 'subscription.created') {
            //Log::info($payload['data']);
            $this->handleSubscriptionCreated($payload['data']);
        }

        return new Response('Webhook received and processed successfully', 200);
    }

    protected function handleSubscriptionTier($data)
    {
        if ($data['status'] === 'canceled') {
            return;
        }

        $tier = SubscriptionPlan::where('plan_id', $data['items'][0]['price']['id'])->first()->tier;

        $paddleUserId = $data['customer_id'];
        $user = User::find(Customer::where('paddle_id', $paddleUserId)->first()->billable_id);

        if (!$user) {
            Log::warning("User with Paddle ID {$paddleUserId} not found for subscription tier update.");
            return;
        }

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
            Customer::updateOrCreate(
                ['billable_id' => $user->id, 'billable_type' => get_class($user)],
                [
                    'paddle_id' => $data['id'] ?? null,
                    'name' => $data['name'] ?? $user->name,
                    'email' => $data['email'],
                    'trial_ends_at' => $data['trial_ends_at'] ?? $user->trial_ends_at,
                ]
            );
        } else {
            Log::warning('User not found for Paddle webhook with email: ' . $data['email']);
        }
    }

    protected function handleSubscriptionUpdated($subscription)
    {
        $this->handleSubscriptionTier($subscription);
    }

    protected function handleSubscriptionCreated($subscription)
    {
        $paddleUserId = $subscription['customer_id'];
        $user = User::find(Customer::where('paddle_id', $paddleUserId)->first()?->billable_id);

        if ($user) {
            if ($user->status === 'incomplete') {
                Log::info("User {$user->id} has an incomplete subscription. No permissions granted.");
                return;
            }

            $this->handleSubscriptionTier($subscription);
        } else {
            Log::warning("User with Paddle ID {$subscription['customer_id']} not found.");
        }
    }


    protected function handleSubscriptionCancelled($subscription)
    {
        $paddleUserId = $subscription['customer_id'];
        $user = User::find(Customer::where('paddle_id', $paddleUserId)->first()->billable_id);

        if ($user) {
            $user->syncRoles('user');
        } else {
            Log::warning("User with Paddle ID {$subscription['customer_id']} not found.");
        }
    }
}
