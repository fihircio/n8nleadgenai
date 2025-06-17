<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CoinController;
use App\Http\Controllers\AutomationController;
use App\Http\Controllers\AiTemplateController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/coins/balance', [CoinController::class, 'balance']);
    Route::post('/coins/deposit', [CoinController::class, 'deposit']);
    Route::post('/coins/withdraw', [CoinController::class, 'withdraw']);
    Route::post('/automation/trigger', [AutomationController::class, 'trigger']);
    Route::apiResource('ai-templates', AiTemplateController::class);
    Route::post('ai-templates/{template}/generate', [AiTemplateController::class, 'generate']);
});

require __DIR__.'/coin_result_api.php';
