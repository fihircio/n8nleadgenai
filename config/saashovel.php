<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Stripe Client Portal Link (optional)
    |--------------------------------------------------------------------------
    |
    | This variable is used to store the link to the Stripe customer portal
    | where users can manage their subscriptions and billing information.
    |
    */

    'STRIPE_CLIENT_PORTAL_LINK' => env('STRIPE_CLIENT_PORTAL_LINK', ''),

    /*
    |--------------------------------------------------------------------------
    | Stripe/Paddle/Lemonsqueezy/NOWPayments subscription plan names
    |--------------------------------------------------------------------------
    |
    | Set the names of your subscription plans from your billing provider
    |
    */

    'FIRST_TIER_SUBSCRIPTION_NAME' => env('FIRST_TIER_SUBSCRIPTION_NAME', 'Starter'),
    'SECOND_TIER_SUBSCRIPTION_NAME' => env('SECOND_TIER_SUBSCRIPTION_NAME', 'Advanced'),
    'THIRD_TIER_SUBSCRIPTION_NAME' => env('THIRD_TIER_SUBSCRIPTION_NAME', 'Enthusiast'),

    /*
    |--------------------------------------------------------------------------
    | Stripe/Paddle API keys
    |--------------------------------------------------------------------------
    |
    | Here are all the relevant stripe API keys.
    |
    */

    'STRIPE_KEY' => env('STRIPE_KEY', ''),
    'STRIPE_SECRET' => env('STRIPE_SECRET', ''),
    'STRIPE_WEBHOOK_SECRET' => env('STRIPE_WEBHOOK_SECRET', ''),

    /*
    |--------------------------------------------------------------------------
    | Paddle Keys
    |--------------------------------------------------------------------------
    |
    | The Paddle vendor ID and auth code give you access to Paddle's
    | API. The "vendor_id" is used for Paddle.js, while the "vendor_auth_code"
    | is used to authenticate with private API endpoints.
    |
    */

    'PADDLE_VENDOR_ID' => env('PADDLE_VENDOR_ID'),
    'PADDLE_VENDOR_AUTH_CODE' => env('PADDLE_VENDOR_AUTH_CODE'),

    /*
    |--------------------------------------------------------------------------
    | Paddle Webhooks
    |--------------------------------------------------------------------------
    |
    | Your Paddle webhook secret is used to prevent unauthorized requests to
    | your Paddle webhook handling controllers.
    |
    */

    'PADDLE_WEBHOOK_SECRET' => env('PADDLE_WEBHOOK_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | Paddle Public Key
    |--------------------------------------------------------------------------
    |
    | This public key is used to verify webhook signatures.
    | You can find your public key in your Paddle account settings.
    |
    */

    'PADDLE_PUBLIC_KEY' => env('PADDLE_PUBLIC_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Paddle Client-Side Token
    |--------------------------------------------------------------------------
    |
    | This client-side token is used to initialize Paddle.js on the frontend.
    | It allows your application to communicate with Paddle's API for
    | client-side operations like displaying prices and opening checkouts.
    | You can find this token in your Paddle account settings.
    |
    */

    'PADDLE_CLIENT_SIDE_TOKEN' => env('PADDLE_CLIENT_SIDE_TOKEN'),

    /*
    |--------------------------------------------------------------------------
    | Lemon Squeezy Product ID
    |--------------------------------------------------------------------------
    |
    | This product ID is used to identify your product within Lemon Squeezy.
    | It allows your application to communicate with Lemon Squeezy's API to
    | perform actions related to this product, such as creating checkouts or
    | retrieving product details. You can find this ID in your Lemon Squeezy
    | dashboard.
    |
    */

    'LEMONSQUEEZY_PRODUCT_ID' => env('LEMONSQUEEZY_PRODUCT_ID'),

    /*
    |--------------------------------------------------------------------------
    | Manage user account for Stripe and Lemon Squeezy
    |--------------------------------------------------------------------------
    |
    | This variable lets you decide whether the user will be redirected to
    | Stripe's/Lemon Squeezy client portal via a new tab or redirected to it right away
    | from the same page.
    |
    */

    'REDIRECT_TO_CUSTOMER_PORTAL_IN_A_NEW_TAB' => env('REDIRECT_TO_CUSTOMER_PORTAL_IN_A_NEW_TAB', false),

    /*
    |--------------------------------------------------------------------------
    | Receive an email from the contact form
    |--------------------------------------------------------------------------
    |
    | This is used to send you an email whenever a user sends out the contact form.
    | Defaults to true but if you don't want it you will still receive the emails
    | in your dashboard at the "letters" tab.
    |
    */

    'SEND_EMAIL_CONTACT' => env('SEND_EMAIL_CONTACT', true),

    /*
    |--------------------------------------------------------------------------
    | Billing provider
    |--------------------------------------------------------------------------
    |
    | This determines which billing provider the app will use.
    |
    | Supported providers:
    | - nowpayments: For cryptocurrency payments
    | - paddle: For Paddle billing via Laravel Cashier
    | - lemonsqueezy: For LemonSqueezy billing
    | - stripe (default): For Stripe billing via Laravel Cashier
    |
    */

    'BILLING_PROVIDER' => env('BILLING_PROVIDER', 'stripe'),

    /*
    |--------------------------------------------------------------------------
    | Access Denied Error Message
    |--------------------------------------------------------------------------
    |
    | This message will be displayed to users who do not have the required
    | permissions to access a restricted page. You can customize this message
    | to fit your application's needs.
    |
    */

    'error_message' => 'Forbidden: This page is restricted to registered users.',

    /*
    |--------------------------------------------------------------------------
    | Cookie consent
    |--------------------------------------------------------------------------
    |
    | Enable or disable the cookie consent message, default value is "false"
    | thus disabled.
    |
    */

    'COOKIE_CONSENT' => env('COOKIE_CONSENT', false),

    /*
    |--------------------------------------------------------------------------
    | Enable Google Analytics Widgets
    |--------------------------------------------------------------------------
    |
    | This option controls whether the Google Analytics widgets should be
    | displayed in the admin panel. When set to true, various analytics
    | widgets will be added to the dashboard. Set to false to disable these widgets.
    |
    */

    'GOOGLE_ANALYTICS_WIDGETS' => env('GOOGLE_ANALYTICS_WIDGETS', false),

    /*
    |--------------------------------------------------------------------------
    | Terms and privacy policy
    |--------------------------------------------------------------------------
    |
    | Enable the terms and privacy policy checkbox on the login/register page
    |
    */

    'TERMS_AND_PRIVACY_POLICY' => env('TERMS_AND_PRIVACY_POLICY', false),

    /*
    |--------------------------------------------------------------------------
    | SPA-like Experience with Livewire
    |--------------------------------------------------------------------------
    |
    | Livewire offers a Single Page Application (SPA) experience through the
    | wire:navigate attribute. This feature:
    |
    | 1. Prevents full page reloads, avoiding re-downloading of assets
    | 2. Handles link clicks with a custom, faster implementation
    | 3. Requests new pages in the background with a loading indicator
    | 4. Updates URL, <title>, and <body> content dynamically
    |
    | Benefits:
    | - Significantly faster page load times (often twice as fast)
    | - SPA-like feel without complex JavaScript frameworks
    | - Seamless integration with Laravel and Livewire components
    |
    | Usage:
    | <a href="/route" wire:navigate>Link Text</a>
    |
    | This approach bridges the gap between traditional multi-page PHP
    | applications and modern SPA experiences, offering improved
    | performance and user experience with minimal complexity.
    |
    */

    'SPA_UX' => env('SPA_UX', true),

    /*
    |--------------------------------------------------------------------------
    | Theme setting for the app
    |--------------------------------------------------------------------------
    |
    | Set the default theme for the app.
    |
    */
    'APP_THEME' => env('APP_THEME', 'light'),

    /*
    |--------------------------------------------------------------------------
    | Default currency
    |--------------------------------------------------------------------------
    |
    | Set the default currency the app will use if it can't find any within the payment providers API
    |
    */
    'DEFAULT_CURRENCY' => env('DEFAULT_CURRENCY', 'USD'),

];
