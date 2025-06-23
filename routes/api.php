<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CoinController;
use App\Http\Controllers\AutomationController;
use App\Http\Controllers\AiTemplateController;
use App\Http\Controllers\AiLeadScoringController;
use App\Http\Controllers\AnalyticsController;

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
    
    // AI Lead Scoring Routes
    Route::post('leads/{lead}/score', [AiLeadScoringController::class, 'scoreLead']);
    Route::get('leads/{lead}/score', [AiLeadScoringController::class, 'getScore']);
    Route::post('leads/{lead}/rescore', [AiLeadScoringController::class, 'rescoreLead']);
    
    // Analytics Routes
    Route::get('/analytics/user', [AnalyticsController::class, 'getUserAnalytics']);
    Route::get('/analytics/global', [AnalyticsController::class, 'getGlobalAnalytics']);
});

require __DIR__.'/coin_result_api.php';
