<x-pricing-section>
    <x-pricing-plan
        name="{{ $cryptoPlans['first-tier']['name'] }}"
        price="{{ formatPrice(Number::currency(extractNumbers($plans['first-tier']['price_formatted']), in: $plans['first-tier']['currency'], locale: config('app.locale'))) }}<span class='text-sm font-normal'>{{ __('/month') }}</span>"
        :features="[
            '✅ Feature 1',
            '✅ Feature 2',
            '✅ Feature 3',
        ]"
        :show-crypto-btn="true"
        :show-cta-btn="false"
        crypto-wire-click="subscribeToPlanWithCrypto"
        crypto-plan-id="{{ $cryptoPlans['first-tier']['plan_id'] }}"
    />

    <x-pricing-plan
        name="{{ $cryptoPlans['second-tier']['name'] }}"
        price="{{ formatPrice(Number::currency(extractNumbers($plans['second-tier']['price_formatted']), in: $plans['second-tier']['currency'], locale: config('app.locale'))) }}<span class='text-sm font-normal'>{{ __('/month') }}</span>"
        :features="[
            '✅ All Starter features',
            '✅ Feature 4',
            '✅ Feature 5',
        ]"
        :highlighted="true"
        :show-crypto-btn="true"
        :show-cta-btn="false"
        crypto-wire-click="subscribeToPlanWithCrypto"
        crypto-plan-id="{{ $cryptoPlans['second-tier']['plan_id'] }}"
    />

    <x-pricing-plan
        name="{{ $cryptoPlans['third-tier']['name'] }}"
        price="{{ formatPrice(Number::currency(extractNumbers($plans['third-tier']['price_formatted']), in: $plans['third-tier']['currency'], locale: config('app.locale'))) }}<span class='text-sm font-normal'>{{ __('/month') }}</span>"
        :features="[
            '✅ All Advanced features',
            '✅ Feature 6',
            '✅ Feature 7',
        ]"
        :show-crypto-btn="true"
        :show-cta-btn="false"
        crypto-wire-click="subscribeToPlanWithCrypto"
        crypto-plan-id="{{ $cryptoPlans['third-tier']['plan_id'] }}"
    />
</x-pricing-section>
