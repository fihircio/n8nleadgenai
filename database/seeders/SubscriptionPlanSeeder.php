<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubscriptionPlan;

class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'plan_id' => 'plan_first_tier',
                'name' => 'Starter',
                'tier' => 'first-tier',
                'currency' => 'USD',
                'price' => 1000,
                'price_formatted' => '10.00 USD',
                'billing_provider' => 'stripe',
            ],
            [
                'plan_id' => 'plan_second_tier',
                'name' => 'Pro',
                'tier' => 'second-tier',
                'currency' => 'USD',
                'price' => 2000,
                'price_formatted' => '20.00 USD',
                'billing_provider' => 'stripe',
            ],
            [
                'plan_id' => 'plan_third_tier',
                'name' => 'Enterprise',
                'tier' => 'third-tier',
                'currency' => 'USD',
                'price' => 5000,
                'price_formatted' => '50.00 USD',
                'billing_provider' => 'stripe',
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::updateOrCreate(
                ['plan_id' => $plan['plan_id']],
                $plan
            );
        }
    }
} 