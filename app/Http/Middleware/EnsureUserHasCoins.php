<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasCoins
{
    /**
     * The minimum number of coins required to access the route.
     */
    protected int $minCoins;

    public function __construct(int $minCoins = 1)
    {
        $this->minCoins = $minCoins;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $minCoins = null): Response
    {
        $user = $request->user();
        $min = $minCoins ?? $this->minCoins;
        if (!$user || $user->getCoinBalance() < $min) {
            abort(403, 'Insufficient coins to access this feature.');
        }
        return $next($request);
    }
}
