<?php

return [
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

    'vendor_id' => env('PADDLE_VENDOR_ID'),
    'vendor_auth_code' => env('PADDLE_VENDOR_AUTH_CODE'),

    /*
    |--------------------------------------------------------------------------
    | Paddle Public Key
    |--------------------------------------------------------------------------
    |
    | This public key is used to verify webhook signatures.
    | You can find your public key in your Paddle account settings.
    |
    */

    'public_key' => env('PADDLE_PUBLIC_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Paddle Webhooks
    |--------------------------------------------------------------------------
    |
    | Your Paddle webhook secret is used to prevent unauthorized requests to
    | your Paddle webhook handling controllers.
    |
    */

    'webhook' => [
        'secret' => env('PADDLE_WEBHOOK_SECRET'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Currency
    |--------------------------------------------------------------------------
    |
    | This is the default currency that will be used when generating charges
    | from your application. Of course, you are welcome to use any of the
    | various world currencies that are currently supported via Paddle.
    |
    */

    'currency' => env('CASHIER_CURRENCY', 'USD'),

    /*
    |--------------------------------------------------------------------------
    | Currency Locale
    |--------------------------------------------------------------------------
    |
    | This is the default locale in which your money values are formatted in
    | for display. To utilize other locales besides the default en locale
    | verify you have the "intl" PHP extension installed on the system.
    |
    */

    'currency_locale' => env('CASHIER_CURRENCY_LOCALE', 'en'),
];
