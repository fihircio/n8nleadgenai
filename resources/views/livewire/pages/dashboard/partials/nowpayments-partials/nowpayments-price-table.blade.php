@props([
    'spinnerColor' => theme('global', 'spinnerColor'),
    'spinnerTextColor' => theme('global', 'spinnerTextColor'),
])
<div x-data="subscriptionTable">
    <x-pricing-section title="{{ __('Pricing Plans') }}" class="py-4">
        <x-pricing-plan
            name="{{ $plans['first-tier']['name'] }}"
            price="{{ formatPrice(Number::currency(extractNumbers($plans['first-tier']['price_formatted']), in: $plans['first-tier']['currency'], locale: config('app.locale'))) }}<span class='text-sm font-normal'>{{ __('/month') }}</span>"
            :features="[
                '✅ Feature 1',
                '✅ Feature 2',
                '✅ Feature 3',
            ]"
            :crypto-plan-id="$plans['first-tier']['plan_id']"
            crypto-alpine-click="openModal"
            :show-cta-btn="false"
            :show-crypto-btn="true"
        />

        <x-pricing-plan
            name="{{ $plans['second-tier']['name'] }}"
            price="{{ formatPrice(Number::currency(extractNumbers($plans['second-tier']['price_formatted']), in: $plans['second-tier']['currency'], locale: config('app.locale'))) }}<span class='text-sm font-normal'>{{ __('/month') }}</span>"
            :features="[
                '✅ All Basic features',
                '✅ Feature 4',
                '✅ Feature 5',
            ]"
            :highlighted="true"
            cta-btn-text="{{ __('Choose Plan') }}"
            :crypto-plan-id="$plans['second-tier']['plan_id']"
            crypto-alpine-click="openModal"
            :show-cta-btn="false"
            :show-crypto-btn="true"
        />

        <x-pricing-plan
            name="{{ $plans['third-tier']['name'] }}"
            price="{{ formatPrice(Number::currency(extractNumbers($plans['third-tier']['price_formatted']), in: $plans['third-tier']['currency'], locale: config('app.locale'))) }}<span class='text-sm font-normal'>{{ __('/month') }}</span>"
            :features="[
                '✅ All Pro features',
                '✅ Feature 6',
                '✅ Feature 7',
            ]"
            cta-btn-text="{{ __('Choose Plan') }}"
            :crypto-plan-id="$plans['third-tier']['plan_id']"
            crypto-alpine-click="openModal"
            :show-cta-btn="false"
            :show-crypto-btn="true"
        />
    </x-pricing-section>

    <x-custom-modal id="slot-modal" maxWidth="xl">
        <x-slot name="title">
            {{ __('Proceed with Payment') }}
        </x-slot>

        <x-slot name="content">
            <p class="my-3">{{ __('Plan chosen: ') }} <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-gray-100 text-black-800 dark:bg-teal-800/30 dark:text-teal-500" x-text="planClicked"></span></p>
            <p>
                {{ __('By clicking "Proceed", you will receive an email containing a link to securely pay for your subscription through NOWPayments (please also check your spambox, the email might be there).') }}
            </p>
            <p>{{ __("Once NOWPayments receives your payment, you'll be automatically redirected back to your dashboard.") }}</p>
            <p>{{ __('Please ensure you complete the payment process through NOWPayments to activate your subscription.') }}</p>
            <p>{{ __('If you have any questions or encounter any issues, feel free to contact our support team.') }}</p>
        </x-slot>

        <x-slot name="footer">
            <button @click="proceedWithPayment" class="py-2 px-3 ml-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                {{ __('Proceed') }}
            </button>
            <button type="button" @click="closeModal" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800">{{ __('Cancel') }}</button>
        </x-slot>
    </x-custom-modal>
</div>
