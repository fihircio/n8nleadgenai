<x-pricing-section>
    <x-pricing-plan
        name="{{ $plans['first-tier']['name'] ?? 'Default Plan Name' }}"
        price="{{ isset($plans['first-tier']) ? formatPrice(Number::currency(extractNumbers($plans['first-tier']['price_formatted']), in: $plans['first-tier']['currency'], locale: config('app.locale'))) : '0.00' }}<span class='text-sm font-normal'>{{ __('/month') }}</span>"
        :features="[
            '✅ Feature 1',
            '✅ Feature 2',
            '✅ Feature 3',
        ]"
        wire-click="subscribeToPlan"
        cta-action="#"
        cta-btn-text="{{ __('Choose Plan') }}"
        plan-id="{{ $plans['first-tier']['plan_id'] ?? '' }}"
        show-crypto-btn="{{ when(!empty($cryptoPlans), 'true') }}"
        crypto-wire-click="{{ when(!empty($cryptoPlans), 'subscribeToPlanWithCrypto') }}"
        crypto-plan-id="{{ !empty($cryptoPlans) ? ($cryptoPlans['first-tier']['plan_id'] ?? '') : '' }}"
    />

    <x-pricing-plan
        name="{{ $plans['second-tier']['name'] ?? 'Default Plan Name' }}"
        price="{{ isset($plans['second-tier']) ? formatPrice(Number::currency(extractNumbers($plans['second-tier']['price_formatted']), in: $plans['second-tier']['currency'], locale: config('app.locale'))) : '0.00' }}<span class='text-sm font-normal'>{{ __('/month') }}</span>"
        :features="[
            '✅ All Starter features',
            '✅ Feature 4',
            '✅ Feature 5',
        ]"
        :highlighted="true"
        wire-click="subscribeToPlan"
        cta-action="#"
        cta-btn-text="<b>{{ __('Choose Plan') }}</b>"
        plan-id="{{ $plans['second-tier']['plan_id'] ?? '' }}"
        show-crypto-btn="{{ when(!empty($cryptoPlans), 'true') }}"
        crypto-wire-click="{{ when(!empty($cryptoPlans), 'subscribeToPlanWithCrypto') }}"
        crypto-plan-id="{{ !empty($cryptoPlans) ? ($cryptoPlans['second-tier']['plan_id'] ?? '') : '' }}"
    />

    <x-pricing-plan
        name="{{ $plans['third-tier']['name'] ?? 'Default Plan Name' }}"
        price="{{ isset($plans['third-tier']) ? formatPrice(Number::currency(extractNumbers($plans['third-tier']['price_formatted']), in: $plans['third-tier']['currency'], locale: config('app.locale'))) : '0.00' }}<span class='text-sm font-normal'>{{ __('/month') }}</span>"
        :features="[
            '✅ All Advanced features',
            '✅ Feature 6',
            '✅ Feature 7',
        ]"
        wire-click="subscribeToPlan"
        cta-action="#"
        cta-btn-text="{{ __('Choose Plan') }}"
        plan-id="{{ $plans['third-tier']['plan_id'] ?? '' }}"
        show-crypto-btn="{{ when(!empty($cryptoPlans), 'true') }}"
        crypto-wire-click="{{ when(!empty($cryptoPlans), 'subscribeToPlanWithCrypto') }}"
        crypto-plan-id="{{ !empty($cryptoPlans) ? ($cryptoPlans['third-tier']['plan_id'] ?? '') : '' }}"
    />
</x-pricing-section>
