<?php

return [
    /*
     * Your NOWPayments API key. This is used to authenticate your requests to the NOWPayments API.
     * You can find this key in your NOWPayments account settings.
     */
    'NOWPAYMENTS_API_KEY' => env('NOWPAYMENTS_API_KEY'),

    /*
     * Your NOWPayments IPN (Instant Payment Notification) secret. This is used to verify the
     * authenticity of incoming webhooks from NOWPayments.
     * You can also find this secret in your NOWPayments account settings.
     */
    'NOWPAYMENTS_IPN_SECRET' => env('NOWPAYMENTS_IPN_SECRET'),

    /*
     * The default currency you want to use for payments and subscriptions.
     * If not set, it will default to 'USD'.
     */
    'NOWPAYMENTS_CURRENCY' => env('NOWPAYMENTS_CURRENCY', 'USD'),

    /*
     * The route path in your Laravel application where NowPayments will send webhooks.
     * Make sure this matches the route you define in your `routes.php` file.
     * If not set, it will default to 'nowpayments/webhook'.
     */
    'NOWPAYMENTS_WEBHOOK_PATH' => env('NOWPAYMENTS_WEBHOOK_PATH', 'nowpayments/webhook'),

    /*
     * Your NOWPayments account email. This is used for authentication when required.
     * Only provide this if you need to use endpoints that require JWT authentication.
     */
    'NOWPAYMENTS_EMAIL' => env('NOWPAYMENTS_EMAIL'),

    /*
     * Your NOWPayments account password. This is used for authentication when required.
     * Only provide this if you need to use endpoints that require JWT authentication.
     */
    'NOWPAYMENTS_PASSWORD' => env('NOWPAYMENTS_PASSWORD'),

    /*
     * The base URL for the NOWPayments API. This allows you to switch between sandbox and production environments.
     * Default is set to the sandbox environment.
     */
    'NOWPAYMENTS_BASE_URL' => env('NOWPAYMENTS_BASE_URL', 'https://api-sandbox.nowpayments.io/v1/'),
];
