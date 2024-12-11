@props([
    'bgColor' => theme('global', 'pageBlogBgColor'),
    'textColor' => theme('global', 'textColor'),
    'pageBorder' => theme('global', 'pageBorder'),
    'subHeaderClass' => theme('header', 'subHeaderClass'),
])

<div>
    <x-slot name="header">
        <h2 class="{{ $subHeaderClass }}">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('clickOnPlan', (event) => {
                setTimeout(() => {
                    document.getElementById(event.plan).click();
                }, 300);
            });
        });
    </script>
    @if (!empty(request()->query('NP_id')))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                hideElementsAndShowSpinner(['subInfos', 'subHistory', 'appLogo', 'buttonsManageSub', 'pricingTitle', 'pricing']);
            });
        </script>
    @endif
    <div class="p-6 lg:p-8 {{ $bgColor }} {{ $pageBorder }} {{ $textColor }}">
        <x-application-logo id="appLogo" />
        <div id="subInfos">
            @can('access premium features')
                <p>{{ __('You have an active paid subscription.') }}</p>
            @else
                <p class="text-center">{{ __('You do not have an active paid subscription. Please, chose a plan.') }}</p>
            @endcan
            @can('access first tier features')
                <p>{{ __('Your sub is first tier') }}</p>
            @endcan
            @can('access second tier features')
                <p>{{ __('Your sub is second tier') }}</p>
            @endcan
            @can('access third tier features')
                <p>{{ __('Your sub is third tier') }}</p>
            @endcan
            @if ($user->onTrial())
                <p>{{ __('Your trial ends on: ') }} <span class="font-bold">{{ optional($user->subscriptions()->latest()->first()->trial_ends_at)->translatedFormat('j F Y') ?: '-' }}</span></p>
            @endif
            @if (!empty($subStatus))
                @if ($subStatus === 'canceled' || $subStatus === 'cancelled')
                    <p class="my-3">{{ __('Your subscription is canceled but will remain active till ') }}<span class="font-bold">{{ Carbon\Carbon::parse($subEndsAt)->translatedFormat('F j, Y \a\t g:i A') }}</span>{{ __(', you can restore it by clicking on the button below.') }}</p>
                @elseif ($subStatus === 'incomplete')
                    <p class="my-3">{{ __('Your subscription is incomplete. Please contact us, mentioning your subscription ID: ') }}<b> {{ $subscriptions[0]->stripe_id }}</b></p>
                @elseif ($subStatus === 'active' || $subStatus === 'trialing' || $subStatus === 'on_trial')
                    <p class="my-3">{{ __('Current plan: ') }}<b>{{ $subTier }}</b></p>
                @endif
                @if (!empty($subEndsAt) && ($subStatus !== 'canceled' &&  $subStatus !== 'cancelled'))
                    <div id="subEndsAt" class="mt-2 bg-red-500 text-sm text-white rounded-lg p-4" role="alert" tabindex="-1" aria-labelledby="hs-solid-color-danger-label">
                        <svg class="h-5 w-5 text-white-500 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m5.25 4.5 7.5 7.5-7.5 7.5m6-15 7.5 7.5-7.5 7.5" />
                        </svg>
                        {{ __('Your subscription ends on ') }}<span class="font-bold">{{ Carbon\Carbon::parse($subEndsAt)->translatedFormat('F j, Y \a\t g:i A') }}.</span> @if ($billingProvider === 'nowpayments') {{ __('You\'ll receive an email reminder to renew your subscription 1-2 days before it expires.') }} @endif
                    </div>
                    <p class="text-red-500 font-bold my-3"></p>
                @endif
            @endif
        </div>
        <br>
        @can('access premium features')
            <livewire:page.dashboard.manage-subscription :$subEndsAt :$subStatus :$subTier :$plans :$billingProvider :$user />
        @endcan
        <div id="showSpinner"></div>
        @cannot('access premium features')
            @if ($billingProvider === 'stripe')
                @include('livewire.pages.dashboard.partials.stripe-partials.stripe-price-table')
            @elseif ($billingProvider === 'paddle')
                @include('livewire.pages.dashboard.partials.paddle-partials.paddle-price-table')
            @elseif ($billingProvider === 'lemonsqueezy')
                @include('livewire.pages.dashboard.partials.lemonsqueezy-partials.lemonsqueezy-price-table')
            @elseif ($billingProvider === 'nowpayments')
                @include('livewire.pages.dashboard.partials.nowpayments-partials.nowpayments-price-table')
            @endif
        @endcannot

        @php $hasSubHistory = $billingProvider === 'nowpayments' ? $user->subscriptionNP()?->exists() ?? false : $user->subscription()?->exists() ?? false; @endphp
        @if ($hasSubHistory)
            @php
                if ($billingProvider != 'nowpayments') {
                    $getSubscriptionDetails = function($subscription) use ($plans, $user, $billingProvider) {
                        if ($billingProvider === 'stripe' || $billingProvider === 'paddle') {
                            $method = 'as' . ucfirst($billingProvider) . 'Subscription';
                            $subscriptionData = $subscription->$method();
                        } elseif ($billingProvider === 'lemonsqueezy') {
                            $subscriptionData = $user->LSOrders()->latest()->first();
                        }

                        $amount = null;
                        $currency = null;
                        $currentPeriodEnd = null;
                        $endDate = '-'; // Set default value for endDate

                        // Handle Stripe, Paddle, and Lemonsqueezy Differences
                        if ($billingProvider === 'stripe') {
                            $amount = $subscriptionData->plan->amount / 100;
                            $currency = strtoupper($subscriptionData->plan->currency);
                            $currentPeriodEnd = \Carbon\Carbon::createFromTimestamp($subscriptionData->current_period_end);
                        } elseif ($billingProvider === 'paddle') {
                            $amount = $subscriptionData['items'][0]['price']['unit_price']['amount'] / 100;
                            $currency = $subscriptionData['currency_code'] ?? null;
                            $currentPeriodEnd = \Carbon\Carbon::parse($subscriptionData['current_billing_period']['ends_at'] ?? null);
                        } elseif ($billingProvider === 'lemonsqueezy') {
                            $filteredplans = array_filter($plans, fn($item) => $item['plan_id'] === $subscription->variant_id);
                            $amount = str_replace('/month', '', reset($filteredplans)['price']);
                            $currency = $subscriptionData->currency;
                            $currentPeriodEnd = $subscription->renews_at;
                        }

                        // Format endDate only if $currentPeriodEnd is a valid Carbon instance
                        if ($currentPeriodEnd instanceof \Carbon\Carbon) {
                            $endDate = $currentPeriodEnd->translatedFormat('j F Y');
                        }

                        return [
                            'amount' => $amount,
                            'currency' => $currency,
                            'endDate' => $endDate,
                        ];
                    };
                }
            @endphp
            <div class="mt-10" id="subHistory">
                <h3 class="text-2xl {{ $textColor }} dark:text-white text-center">{{ __('Subscription History') }}</h3>
                <div class="overflow-x-auto {{ preg_replace('/text-(\w+)-\d+/', 'text-$1-800', $textColor) }}">
                    <table class="min-w-full divide-y divide-gray-200 my-3">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Subscription ID') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Status') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Start Date') }}</th>
                                @php
                                    $onTrial = $billingProvider === 'nowpayments' ? $user->onTrialNP() : $user->onTrial();
                                    $subs = $billingProvider === 'nowpayments' ? $user->subscriptionsNP() : $user->subscriptions();
                                @endphp
                                @if ($onTrial)
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Trial End Date') }}</th>
                                @endif
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('End Date') }}</th>
                                @if (!$onTrial && empty($subs->first()->ends_at))
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Next Billing Date') }}</th>
                                @endif
                                @if ($billingProvider === 'nowpayments')
                                    @if (!empty($user->payment()->latest()->first()))
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Amount') }}</th>
                                    @else
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Tier') }}</th>
                                    @endif
                                @else
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Amount') }}</th>
                                @endif

                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($subscriptions as $subscription)
                            <tr class="{{ ($loop->first && ($billingProvider === 'stripe' ? $subscription->stripe_status === 'active' : $subscription->status === 'active')) ? 'bg-blue-100' : 'text-neutral-600/25' }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{
                                            $billingProvider === 'stripe'
                                            ? $subscription->stripe_id
                                            : ($billingProvider === 'paddle'
                                                ? $subscription->paddle_id
                                                : ($billingProvider === 'lemonsqueezy'
                                                ? $subscription->lemon_squeezy_id
                                                : ($billingProvider === 'nowpayments'
                                                    ? $subscription->nowpayments_id
                                                    : null)))
                                        }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{
                                            $billingProvider === 'stripe'
                                            ? $subscription->stripe_status
                                            : ($billingProvider === 'paddle'
                                                ? $subscription->status
                                                : ($billingProvider === 'lemonsqueezy'
                                                ? $subscription->status
                                                : ($billingProvider === 'nowpayments'
                                                    ? $subscription->status
                                                    : null)))
                                        }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $subscription->created_at->translatedFormat('j F Y') }}</td>
                                    @if ($onTrial)
                                        <td class="px-6 py-4 whitespace-nowrap">{{ optional($subscription->trial_ends_at)->translatedFormat('j F Y') ?: '-' }}</td>
                                    @endif
                                    <td class="px-6 py-4 whitespace-nowrap">{{ optional($subscription->ends_at)->translatedFormat('j F Y') ?: '-' }}</td>
                                    @if ($billingProvider !== 'nowpayments' && !$onTrial && empty($user->subscriptions()->first()->ends_at))
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $getSubscriptionDetails($subscription)['endDate'] ?? '-' }}</td>
                                    @endif
                                    @if ($billingProvider === 'nowpayments')
                                        @if (!empty($user->NPPayments->reverse()->values()[$loop->index]->price_currency))
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ $user->NPPayments->reverse()->values()[$loop->index]->price_amount . ' ' . strtoupper($user->NPPayments->reverse()->values()[$loop->index]->price_currency) }}{{ __('/month') }}
                                            </td>
                                        @else
                                            <td class="px-6 py-4 whitespace-nowrap">{{ App\Models\SubscriptionPlan::where('plan_id', $subscription->nowpayments_plan)->first()->price_formatted }}{{ __('/month') }}</td>
                                        @endif
                                    @else
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{
                                                $billingProvider === 'stripe'
                                                    ? $getSubscriptionDetails($subscription)['amount'] . ' ' . strtoupper($getSubscriptionDetails($subscription)['currency'])
                                                    : ($billingProvider === 'paddle'
                                                        ? $getSubscriptionDetails($subscription)['amount'] . ' ' . strtoupper($getSubscriptionDetails($subscription)['currency'])
                                                        : ($billingProvider === 'lemonsqueezy'
                                                            ? $getSubscriptionDetails($subscription)['amount'] / 100 . ' ' . strtoupper($getSubscriptionDetails($subscription)['currency'])
                                                            : null))
                                            }}{{ __('/month') }}
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center">{{ __('No subscriptions found') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>
