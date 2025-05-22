<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CoinController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/coins/balance', [CoinController::class, 'balance']);
    Route::post('/coins/deposit', [CoinController::class, 'deposit']);
    Route::post('/coins/withdraw', [CoinController::class, 'withdraw']);
});
