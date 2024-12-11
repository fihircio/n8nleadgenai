<?php

use Illuminate\Support\Facades\Route;
use Tcja\NOWPaymentsLaravel\Http\Controllers\WebhookController;
use Tcja\NOWPaymentsLaravel\Http\Middleware\VerifyWebhookSignature;

Route::group(['middleware' => ['api']], function () {
    Route::post(config('nowpayments.NOWPAYMENTS_WEBHOOK_PATH'), [WebhookController::class, 'handleWebhook'])
        ->name('nowpayments.webhook')
        ->middleware(VerifyWebhookSignature::class);
});
