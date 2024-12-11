<?php

namespace App;

/**
 * BillableTraitSelector
 *
 * This class is responsible for dynamically selecting the appropriate Billable trait
 * based on the configured billing provider in the application.
 *
 * It uses the 'saashovel.BILLING_PROVIDER' configuration to determine which
 * Billable trait should be aliased for use in the application.
 */
class BillableTraitSelector
{
    /**
     * Select and alias the appropriate Billable trait.
     *
     * This method reads the configured billing provider and creates an alias
     * for the corresponding Billable trait. This allows the application to
     * switch between different billing providers without changing the codebase.
     *
     * Supported providers:
     * - nowpayments: For cryptocurrency payments
     * - paddle: For Paddle billing via Laravel Cashier
     * - lemonsqueezy: For LemonSqueezy billing
     * - stripe (default): For Stripe billing via Laravel Cashier
     */
    public static function selectTrait()
    {
        $provider = config('saashovel.BILLING_PROVIDER');

        if ($provider === 'paddle') {
            class_alias(\Laravel\Paddle\Billable::class, 'App\Billable');
        } elseif ($provider === 'lemonsqueezy') {
            class_alias(\LemonSqueezy\Laravel\Billable::class, 'App\Billable');
        } else {
            class_alias(\Laravel\Cashier\Billable::class, 'App\Billable');
        }
    }
}
