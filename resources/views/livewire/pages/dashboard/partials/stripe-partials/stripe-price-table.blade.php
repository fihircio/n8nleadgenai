<x-pricing-section title="{{ __('Pricing Plans') }}" class="py-4">
    <x-pricing-plan
        :name="$plans['first-tier']['name']"
        price="{{ formatPrice(Number::currency(extractNumbers($plans['first-tier']['price_formatted']), in: $plans['first-tier']['currency'], locale: config('app.locale'))) }}<span class='text-sm font-normal'>{{ __('/month') }}</span>"
        :features="[
            '✅ Feature 1',
            '✅ Feature 2',
            '✅ Feature 3',
        ]"
        :cta-btn-text="__('Choose Plan')"
        :cta-action="route('checkoutStripe', ['stripePriceId' => $plans['first-tier']['plan_id']])"
    />

    <x-pricing-plan
        :name="$plans['second-tier']['name']"
        price="{{ formatPrice(Number::currency(extractNumbers($plans['second-tier']['price_formatted']), in: $plans['second-tier']['currency'], locale: config('app.locale'))) }}<span class='text-sm font-normal'>{{ __('/month') }}</span>"
        :features="[
            '✅ All Starter features',
            '✅ Feature 4',
            '✅ Feature 5',
        ]"
        :cta-btn-text="__('Choose Plan')"
        :cta-action="route('checkoutStripe', ['stripePriceId' => $plans['second-tier']['plan_id']])"
    />

    <x-pricing-plan
        :name="$plans['third-tier']['name']"
        price="{{ formatPrice(Number::currency(extractNumbers($plans['third-tier']['price_formatted']), in: $plans['third-tier']['currency'], locale: config('app.locale'))) }}<span class='text-sm font-normal'>{{ __('/month') }}</span>"
        :features="[
            '✅ All Advanced features',
            '✅ Feature 6',
            '✅ Feature 7',
        ]"
        :cta-btn-text="__('Choose Plan')"
        :cta-action="route('checkoutStripe', ['stripePriceId' => $plans['third-tier']['plan_id']])"
    />
</x-pricing-section>
