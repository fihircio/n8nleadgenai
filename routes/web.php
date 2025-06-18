<?php

use App\Http\Middleware\IsAjaxRequest;
use App\Livewire\AfterAuth;
use App\Livewire\Page\Home\Home;
use App\Livewire\Page\Marketplace\WorkflowMarketplace;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Livewire\Page\Leads\AiLeadScoring;

Route::get('/', Home::class)->name('home');

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/coins/history', function () {
        return view('coins.history');
    });
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/afterAuth', AfterAuth::class)->name('afterauth');

    // This is used to poll the status of a subscription
    Route::get('/pollStatus/{userId}', function (Request $request, $userId) {
        $nowpayments = $request->query('np');
        if ($nowpayments === 'true') {
            $status = User::find($userId)->subscriptionNP()->status ?? null;
        } else {
            if (config('saashovel.BILLING_PROVIDER') === 'stripe') {
                $status = User::find($userId)->subscription()->stripe_status ?? null;
            } elseif (config('saashovel.BILLING_PROVIDER') === 'paddle') {
                $status = User::find($userId)->subscription()->status ?? null;
            } elseif (config('saashovel.BILLING_PROVIDER') === 'lemonsqueezy') {
                $status = User::find($userId)->subscription()->status ?? null;
            }
        }

        $data = [
            'status' => $status,
        ];
        return response()->json($data);
    })->middleware(IsAjaxRequest::class);

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Stripe billing route
    Route::get('/billing', function (Request $request) {
        return $request->user()->redirectToBillingPortal(route('dashboard'));
    })->name('billing-sp');

    // LemonSqueezy billing route
    Route::get('/billing-ls', function (Request $request) {
        return $request->user()->redirectToCustomerPortal();
    })->name('billing-ls');

    // NOWPayments billing route
    Route::post('/billing-np/{nowpaymentsPlanId}', function (Request $request, $nowpaymentsPlanId) {
        $request->user()->nowpayments()->createSubscription($request->user()->email, '', $nowpaymentsPlanId);
        return response()->json(['message' => 'Success'], 200);
    })->middleware(IsAjaxRequest::class)->name('billing-np');

    Route::get('/checkout-ls/{lemonsqueezyPriceId}', function (Request $request, $lemonsqueezyPriceId) {
        return $checkout =
        $request->user()->subscribe($lemonsqueezyPriceId)
            ->withName($request->user()->name)
            ->withEmail($request->user()->email)
            /* ->withBillingAddress('FR', '10038') */
            ->redirectTo(route('dashboard') . '?sub=true');
    })->name('checkoutLS');

    Route::get('/checkout/{stripePriceId}', function (Request $request, $stripePriceId) {
        try {
            $quantity = 1;
            return $request->user()->checkout([$stripePriceId => $quantity], [
                'success_url' => route('dashboard')  . '?sub=true',
                'cancel_url' => route('dashboard')  . '?sub=false',
                'mode' => 'subscription'
            ]);
        } catch (\Throwable $th) {
            return redirect()->route('home');
        }
    })->name('checkoutStripe');

    // Delete socialite user
    Route::post('/deleteUser', function (Request $request, \App\Actions\Jetstream\DeleteUser $deleteUser) {
        $deleteUser->delete($request->user());
        return response()->json(['message' => 'USER_DELETED'], 200);
    })->middleware(IsAjaxRequest::class)->name('deleteUserSocialite');

    // Premium routes
    Route::middleware(['auth', 'coins:10'])->get('/premium/silver', function (\Illuminate\Http\Request $request) {
        $user = $request->user();
        // Deduct coins only if not already deducted for this session/feature
        if (!session('silver_access_granted')) {
            $user->subtractCoins(10);
            session(['silver_access_granted' => true]);
        }
        return view('premium.silver');
    });

    Route::middleware(['auth', 'coins:50'])->get('/premium/gold', function (\Illuminate\Http\Request $request) {
        $user = $request->user();
        if (!session('gold_access_granted')) {
            $user->subtractCoins(50);
            session(['gold_access_granted' => true]);
        }
        return view('premium.gold');
    });

    Route::middleware(['auth', 'coins:100'])->get('/premium/platinum', function (\Illuminate\Http\Request $request) {
        $user = $request->user();
        if (!session('platinum_access_granted')) {
            $user->subtractCoins(100);
            session(['platinum_access_granted' => true]);
        }
        return view('premium.platinum');
    });

    Route::middleware(['auth'])->get('/marketplace', WorkflowMarketplace::class)->name('marketplace');

    // AI Lead Scoring routes
    Route::middleware(['auth'])->group(function () {
        // Regular user route - no coin requirement for viewing
        Route::get('/leads/scoring', AiLeadScoring::class)
            ->name('leads.ai-lead-scoring');
    });
});
