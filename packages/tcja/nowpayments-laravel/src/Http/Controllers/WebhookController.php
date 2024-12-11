<?php

namespace Tcja\NOWPaymentsLaravel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tcja\NOWPaymentsLaravel\Models\Subscription;
use Tcja\NOWPaymentsLaravel\Models\Payment;
use Tcja\NOWPaymentsLaravel\Models\Customer;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Tcja\NOWPaymentsLaravel\Events\WebhookReceived;

class WebhookController
{
    public function handleWebhook(Request $request, $payloadFromUrl = null)
    {
         if ($payloadFromUrl) {
            $this->handlePaymentWebhook($payloadFromUrl);
         } else {
            // Process the webhook payload
            $payload = $request->json()->all();
            Log::info(['$payload' => $payload]);

            //WebhookReceived::dispatch(['type' => 'subscription_created', 'data' => $payload]);
            if (isset($payload['status'])) {
                return $this->handleSubscriptionWebhook($payload);
            }

            return response('Unhandled webhook type', 400);
         }
    }

    protected function handlePaymentWebhook(array $payload)
    {
        if ($payload['payment_status'] === 'finished') {
            $subscription = Auth::user()->subscriptionNP();

            /* $subscription->update([
                'status' => 'active',
            ]); */

            Payment::updateOrCreate(
                [
                    'nowpayments_payment_id' => $payload['payment_id'], // Use the correct field name
                    'nowpayments_invoice_id' => $payload['invoice_id'],
                ],
                [
                    'billable_id' => Auth::user()->id,
                    'billable_type' => 'App\Models\User',
                    'status' => $payload['payment_status'],
                    'outcome_amount' => $payload['outcome_amount'],
                    'outcome_currency' => $payload['outcome_currency'],
                    'price_amount' => $payload['price_amount'],
                    'price_currency' => $payload['price_currency'],
                    'pay_amount' => $payload['pay_amount'],
                    'pay_currency' => $payload['pay_currency'],
                    'pay_address' => $payload['pay_address'],
                    'actually_paid' => $payload['actually_paid'],
                    //'fee' => $payload['fee'], // Assuming you're handling JSON serialization/deserialization
                    'purchase_id' => $payload['purchase_id'],
                    'updated_at' => $payload['updated_at'], // This is from the webhook, not Laravel's default
                ]
            );

            // Update the customer's trial end date if applicable
            if ($subscription->billable) {
                $subscription->billable->update([
                    'trial_ends_at' => null, // End trial if it was active
                ]);
            }
        }

        return response('Success', 200);
    }

    protected function handleSubscriptionWebhook(array $payload)
    {
        // Find the user based on the email
        $user = \App\Models\User::where('email', $payload['email'])->first();

        if (!$user) {
            Log::error("User not found for email: {$payload['email']}");
            return response('User not found', 404);
        }

        Customer::updateOrCreate(
            ['billable_id' => $user->id],
            [
                'nowpayments_id' => $payload['id'],
                'email' => $payload['email'],
                'trial_ends_at' => null,
                'billable_type' => 'App\Models\User', // Adjust this to your User model namespace
            ]
        );

        $status = $this->mapStatus($payload['status']);

        $subscriptionData = [
            'billable_id' => $user->id, // Use the user's ID instead of the customer's ID
            'billable_type' => 'App\Models\User', // Adjust this to your User model namespace
            'name' => 'default', // You might want to use a more specific name
            'nowpayments_plan' => $payload['plan_id'],
            'status' => $status,
            'quantity' => 1, // Assuming 1 quantity per subscription, adjust if needed
        ];

        if ($status === 'active') {
            $subscriptionData['ends_at'] = Carbon::parse($payload['expire_date']);
        } elseif ($status === 'expired') {
            $subscriptionData['ends_at'] = now();
        }

        $subscription = Subscription::updateOrCreate(
            ['nowpayments_id' => $payload['id']],
            $subscriptionData
        );

        // Handle specific status actions
        if ($status === 'partially_paid') {
            // You might want to notify the user or take some action
            Log::warning("Subscription {$subscription->id} is partially paid.");
        }

        WebhookReceived::dispatch(['type' => 'subscription_created', 'data' => $payload]);
    }

    protected function mapStatus(string $nowpaymentsStatus): string
    {
        return match ($nowpaymentsStatus) {
            'PAID' => 'active',
            'WAITING_PAY' => 'pending',
            'PARTIALLY_PAID' => 'incomplete',
            'EXPIRED' => 'expired',
            default => 'incomplete',
        };
    }
}
