<?php

use App\Http\Middleware\IsAjaxRequest;
use App\Livewire\AfterAuth;
use App\Livewire\Page\Dashboard\Dashboard;
use App\Livewire\Page\Home\Home;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', Home::class)->name('home');

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

    Route::get('/dashboard', Dashboard::class)->name('dashboard');

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

    Route::get('/products', \App\Livewire\ProductIndex::class)->name('products.index');
    Route::get('/products/create', \App\Livewire\ProductForm::class)->name('products.create');
    Route::get('/products/{product}/edit', \App\Livewire\ProductForm::class)->name('products.edit');
});
