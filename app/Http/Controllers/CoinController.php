<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class CoinController extends Controller
{
    // Get the authenticated user's coin balance
    public function balance(Request $request)
    {
        $user = $request->user();
        return response()->json(['balance' => $user->getCoinBalance()]);
    }

    // Add coins to the authenticated user
    public function deposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);
        $user = $request->user();
        // Fraud detection: limit deposits to 5 per hour
        $key = 'coin_deposit:' . $user->id;
        $attempts = cache()->get($key, 0);
        if ($attempts >= 5) {
            \Log::warning('Fraud detection: Too many deposit attempts', [
                'user_id' => $user->id,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'action' => 'deposit',
            ]);
            return response()->json(['error' => 'Too many deposit attempts. Please try again later.'], 429);
        }
        cache()->put($key, $attempts + 1, now()->addHour());
        $user->addCoins($request->amount);
        \Log::info('Coin deposit', [
            'user_id' => $user->id,
            'amount' => $request->amount,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'action' => 'deposit',
        ]);
        return response()->json(['balance' => $user->getCoinBalance()]);
    }

    // Subtract coins from the authenticated user
    public function withdraw(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);
        $user = $request->user();
        // Fraud detection: limit withdrawals to 5 per hour
        $key = 'coin_withdraw:' . $user->id;
        $attempts = cache()->get($key, 0);
        if ($attempts >= 5) {
            \Log::warning('Fraud detection: Too many withdraw attempts', [
                'user_id' => $user->id,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'action' => 'withdraw',
            ]);
            return response()->json(['error' => 'Too many withdraw attempts. Please try again later.'], 429);
        }
        cache()->put($key, $attempts + 1, now()->addHour());
        $user->subtractCoins($request->amount);
        \Log::info('Coin withdraw', [
            'user_id' => $user->id,
            'amount' => $request->amount,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'action' => 'withdraw',
        ]);
        return response()->json(['balance' => $user->getCoinBalance()]);
    }

    // Get the authenticated user's coin transaction history
    public function history(Request $request)
    {
        $user = $request->user();
        $transactions = $user->transactions()->latest()->limit(50)->get()->map(function($tx) {
            return [
                'id' => $tx->id,
                'type' => $tx->type,
                'amount' => $tx->amount,
                'meta' => $tx->meta,
                'created_at' => $tx->created_at,
            ];
        });
        return response()->json(['transactions' => $transactions]);
    }
}
