<?php

namespace Tcja\NOWPaymentsLaravel\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyWebhookSignature
{
    public function handle(Request $request, Closure $next)
    {
        if (!$this->isValidSignature($request)) {
            abort(403, 'Invalid signature.');
        }

        return $next($request);
    }

    private function isValidSignature(Request $request): bool
    {
        $signature = $request->header('x-nowpayments-sig');

        $payload = $request->getContent();

        $secret = config('nowpayments.NOWPAYMENTS_IPN_SECRET');

        $expectedSignature = hash_hmac('sha512', $payload, $secret);

        return hash_equals($signature, $expectedSignature);
    }
}
