<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CoinController;

Route::middleware(['auth:sanctum'])->group(function () {
    // ...existing coin routes...
    Route::get('/coins/history', [CoinController::class, 'history']);
});
