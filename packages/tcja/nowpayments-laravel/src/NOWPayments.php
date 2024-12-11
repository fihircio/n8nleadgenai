<?php

namespace Tcja\NOWPaymentsLaravel;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class NOWPayments
{
    private string $apiKey;
    private string $baseUrl;
    private ?string $email = null;
    private ?string $password = null;

    public function __construct()
    {
        $this->apiKey = config('nowpayments.NOWPAYMENTS_API_KEY');
        $this->baseUrl = config('nowpayments.NOWPAYMENTS_BASE_URL');
        $this->email = config('nowpayments.NOWPAYMENTS_EMAIL');
        $this->password = config('nowpayments.NOWPAYMENTS_PASSWORD');
    }

    private function getToken()
    {
        if (!$this->email || !$this->password) {
            return null;
        }

        if (Cache::has('nowpayments_token')) {
            return Cache::get('nowpayments_token');
        }

        $response = Http::post($this->baseUrl . 'auth', [
            'email' => $this->email,
            'password' => $this->password,
        ]);

        if ($response->successful()) {
            $token = $response->json()['token'];
            Cache::put('nowpayments_token', $token, 240);
            return $token;
        }

        throw new \Exception('Failed to obtain authentication token');
    }

    private function getHttpClient($requiresAuth = false)
    {
        $headers = [
            'x-api-key' => $this->apiKey,
            'Content-Type' => 'application/json',
        ];

        if ($requiresAuth) {
            $token = $this->getToken();
            if ($token) {
                $headers['Authorization'] = 'Bearer ' . $token;
            }
        }

        return Http::withHeaders($headers)->baseUrl($this->baseUrl);
    }

    public function updateSubscriptionPlan(string $planId, array $data)
    {
        $response = $this->getHttpClient(true) // Requires authentication
            ->patch("subscriptions/plans/{$planId}", $data);

        return $response->json();
    }

    public function deleteSubscription(string $subscriptionId)
    {
        $response = $this->getHttpClient(true)
            ->delete("subscriptions/{$subscriptionId}");

        return $response->json();
    }

    public function getPayment(string $paymentId)
    {
        $response = $this->getHttpClient()->get("payment/{$paymentId}");

        return $response->json();
    }

    public function getSubscription(string $subscriptionId)
    {
        $response = $this->getHttpClient()->get("subscriptions/{$subscriptionId}");

        return $response->json()['result'];
    }

    public function getSubscriptions(array $params = [])
    {
        $response = $this->getHttpClient()->get('subscriptions', $params);

        return $response->json()['result'];
    }

    public function getSubscriptionPlans(array $params = [])
    {
        $response = $this->getHttpClient()->get('subscriptions/plans', $params);

        return $response->json()['result'];
    }

    public function getSubscriptionPlan(string $planId)
    {
        $response = $this->getHttpClient()->get("subscriptions/plans/{$planId}");

        return $response->json()['result'];
    }

    public function createSubscription($billable, string $name, string $planId)
    {
        $payload = [
            'subscription_plan_id' => $planId,
            'email' => $billable,
        ];

        $response = $this->getHttpClient(true)->post('subscriptions', $payload);

        return $response->json();
    }

    // Add more methods as needed based on the API documentation (e.g., cancelSubscription, pauseSubscription, etc.)
}
