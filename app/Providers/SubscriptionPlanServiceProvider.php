<?php

namespace App\Providers;

use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

/**
 * SubscriptionPlanServiceProvider
 *
 * This service provider is responsible for managing subscription plans
 * from various billing providers (Stripe, Paddle, LemonSqueezy, NOWPayments).
 * It fetches and caches the subscription plans from their respective APIs, and
 * updates them in the database.
 */
class SubscriptionPlanServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Temporarily disabled to allow cache clearing without DB connection
        return;
    }

    protected function migrationsHaveRun(): bool
    {
        return Schema::hasTable('cache') && Schema::hasTable('subscription_plans');
    }

    protected function updatePlans(): void
    {
        $providers = config('saashovel.BILLING_PROVIDER') === 'nowpayments' ? ['nowpayments'] : [config('saashovel.BILLING_PROVIDER'), 'nowpayments'];
        foreach ($providers as $provider) {
            if (empty(config('saashovel.STRIPE_KEY')) && $provider === 'stripe') {
                return;
            } elseif (empty(config('lemon-squeezy.api_key')) && $provider === 'lemonsqueezy') {
                return;
            } elseif (empty(config('saashovel.PADDLE_VENDOR_AUTH_CODE')) && $provider === 'paddle') {
                return;
            } elseif (empty(config('nowpayments.NOWPAYMENTS_API_KEY')) && $provider === 'nowpayments') {
                return;
            }
            Cache::remember("subscription_plans_{$provider}", now()->addDay(), function () use ($provider) {
                $plans = $this->fetchPlansForProvider($provider);
                $this->updatePlansInDatabase($plans, $provider);
                return $plans;
            });
        }
    }

    protected function fetchPlansForProvider($provider)
    {
        switch ($provider) {
            case 'stripe':
                return $this->fetchPlansFromStripe();
            case 'paddle':
                return $this->fetchPlansFromPaddle();
            case 'lemonsqueezy':
                return $this->fetchPlansFromLemonSqueezy();
            case 'nowpayments':
                return $this->fetchPlansFromNOWPayments();
            default:
                return collect();
        }
    }

    public function fetchPlansFromStripe()
    {
        try {
            \Stripe\Stripe::setApiKey(config('saashovel.STRIPE_SECRET'));

            $products = \Stripe\Product::all(['active' => true]);

            $plans = collect($products->data)
                ->filter(function ($product) {
                    return in_array($product->name, [config('saashovel.FIRST_TIER_SUBSCRIPTION_NAME'), config('saashovel.SECOND_TIER_SUBSCRIPTION_NAME'), config('saashovel.THIRD_TIER_SUBSCRIPTION_NAME')]);
                })
                ->flatMap(function ($product) {
                    $prices = \Stripe\Price::all(['product' => $product->id, 'active' => true]);

                    return collect($prices->data)->map(function ($price) use ($product) {
                        return [
                            'plan_id' => $price->id,
                            'name' => $product->name,
                            'currency' => strtoupper($price->currency) ?? config('saashovel.DEFAULT_CURRENCY'),
                            'price' => $price->unit_amount,
                            'price_formatted' => number_format($price->unit_amount / 100, 2) . ' ' . strtoupper($price->currency),
                        ];
                    });
                })
                ->sortBy('price')
                ->values();

            // Assign tiers based on price
            $tiers = ['first-tier', 'second-tier', 'third-tier'];
            $plans = $plans->map(function ($plan, $index) use ($tiers) {
                $plan['tier'] = $tiers[$index];
                return $plan;
            });

            return $plans;
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return ['error' => $e->getMessage()];
        } catch (\Exception $e) {
            return ['error' => 'An unexpected error occurred: ' . $e->getMessage()];
        }
    }

    protected function fetchPlansFromPaddle()
    {
        $paddleClient = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('saashovel.PADDLE_VENDOR_AUTH_CODE'),
        ])->baseUrl('https://sandbox-api.paddle.com');

        $response = $paddleClient->get('prices');

        if (!$response->successful()) {
            return collect();
        }

        $plans = collect($response->json('data'))
            ->filter(fn ($price) => in_array($price['name'], [config('saashovel.FIRST_TIER_SUBSCRIPTION_NAME'), config('saashovel.SECOND_TIER_SUBSCRIPTION_NAME'), config('saashovel.THIRD_TIER_SUBSCRIPTION_NAME')]))
            ->map(function ($price) {
                return [
                    'plan_id' => $price['id'],
                    'name' => $price['name'],
                    'currency' => $price['unit_price']['currency_code'] ?? config('saashovel.DEFAULT_CURRENCY'),
                    'price' => $price['unit_price']['amount'],
                    'price_formatted' => number_format($price['unit_price']['amount'] / 100, 2) . ' ' . $price['unit_price']['currency_code'],
                ];
            })
            ->sortBy('price')
            ->values();

        $tiers = ['first-tier', 'second-tier', 'third-tier'];
        $plans = $plans->map(function ($plan, $index) use ($tiers) {
            $plan['tier'] = $tiers[$index];
            return $plan;
        });

        return $plans;
    }

    protected function fetchPlansFromLemonSqueezy()
    {
        $lemonSqueezy = new \LemonSqueezy\Laravel\LemonSqueezy;

        $plans = collect($lemonSqueezy->api('GET', "products")->json('data'))
            ->filter(fn ($price) => in_array($price['attributes']['name'], [config('saashovel.FIRST_TIER_SUBSCRIPTION_NAME'), config('saashovel.SECOND_TIER_SUBSCRIPTION_NAME'), config('saashovel.THIRD_TIER_SUBSCRIPTION_NAME')]))
            ->map(function ($price) use ($lemonSqueezy) {
                return [
                    'plan_id' => $lemonSqueezy->api('GET', 'products/' . $price['id'] . '/variants')->json('data')[0]['id'],
                    'name' => $price['attributes']['name'],
                    'currency' => config('saashovel.DEFAULT_CURRENCY'),
                    'price' => $price['attributes']['price'],
                    'price_formatted' => $price['attributes']['price_formatted'],
                ];
            })
            ->sortBy('price')
            ->values();

        $tiers = ['first-tier', 'second-tier', 'third-tier'];
        $plans = $plans->map(function ($plan, $index) use ($tiers) {
            $plan['tier'] = $tiers[$index];
            return $plan;
        });

        return $plans;
    }

    protected function fetchPlansFromNOWPayments()
    {
        $titles = [
            config('saashovel.FIRST_TIER_SUBSCRIPTION_NAME'),
            config('saashovel.SECOND_TIER_SUBSCRIPTION_NAME'),
            config('saashovel.THIRD_TIER_SUBSCRIPTION_NAME'),
        ];

        $plans = collect(app(\Tcja\NOWPaymentsLaravel\NOWPayments::class)->getSubscriptionPlans())
            ->filter(function ($item) use ($titles) {
                return in_array($item['title'], $titles);
            })
            ->map(function ($price) {
                return [
                    'plan_id' => $price['id'],
                    'name' => $price['title'],
                    'currency' => $price['currency'] ?? config('saashovel.DEFAULT_CURRENCY'),
                    'price' => $price['amount'],
                    'price_formatted' => $price['amount'] . ' ' . strtoupper($price['currency']),
                ];
            })
            ->sortBy('price')
            ->values();

        $tiers = ['first-tier', 'second-tier', 'third-tier'];
        $plans = $plans->map(function ($plan, $index) use ($tiers) {
            $plan['tier'] = $tiers[$index];
            return $plan;
        });

        return $plans;
    }

    protected function updatePlansInDatabase($plans, $provider): void
    {
        $plans->each(function ($plan) use ($provider) {
            SubscriptionPlan::updateOrCreate(
                ['plan_id' => $plan['plan_id']],
                array_merge($plan, ['billing_provider' => $provider])
            );
        });
    }
}
