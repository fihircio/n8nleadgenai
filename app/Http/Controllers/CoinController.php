<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $user->addCoins($request->amount);
        return response()->json(['balance' => $user->getCoinBalance()]);
    }

    // Subtract coins from the authenticated user
    public function withdraw(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);
        $user = $request->user();
        $user->subtractCoins($request->amount);
        return response()->json(['balance' => $user->getCoinBalance()]);
    }
}
