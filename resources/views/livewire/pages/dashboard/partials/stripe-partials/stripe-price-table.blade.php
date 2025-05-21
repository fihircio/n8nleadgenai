<x-pricing-section title="{{ __('Pricing Plans') }}" class="py-4">
    <x-pricing-plan
        :name="'Basic Plan'"
        price="$10<span class='text-sm font-normal'>{{ __('/month') }}</span>"
        :features="[
            '✅ Feature 1',
            '✅ Feature 2',
            '✅ Feature 3',
        ]"
        :cta-btn-text="__('Choose Plan')"
        :cta-action="'#'"
    />

    <x-pricing-plan
        :name="'Standard Plan'"
        price="$20<span class='text-sm font-normal'>{{ __('/month') }}</span>"
        :features="[
            '✅ All Starter features',
            '✅ Feature 4',
            '✅ Feature 5',
        ]"
        :cta-btn-text="__('Choose Plan')"
        :cta-action="'#'"
    />

    <x-pricing-plan
        :name="'Premium Plan'"
        price="$30<span class='text-sm font-normal'>{{ __('/month') }}</span>"
        :features="[
            '✅ All Advanced features',
            '✅ Feature 6',
            '✅ Feature 7',
        ]"
        :cta-btn-text="__('Choose Plan')"
        :cta-action="'#'"
    />
</x-pricing-section>
