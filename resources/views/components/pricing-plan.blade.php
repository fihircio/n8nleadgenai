@props([
    'name',
    'price',
    'features',
    'highlighted' => false,
    'ctaBtnText' => __('Get Started'),
    'ctaSmText' => '',
    'ctaAction' => '#',
    'wireClick' => null,
    'alpineClick' => null,
    'planId' => null,
    'bgColor' => theme('pricingPlan', 'bgColor'),
    'highlightBorderColor' => theme('pricingPlan', 'highlightBorderColor'),
    'highlightBadgeColor' => theme('pricingPlan', 'highlightBadgeColor'),
    'highlightBadgeTextColor' => theme('pricingPlan', 'highlightBadgeTextColor'),
    'highlightBadgeText' => __('Best Value'),
    'nameColor' => theme('pricingPlan', 'nameColor'),
    'priceColor' => theme('pricingPlan', 'priceColor'),
    'featureColor' => theme('pricingPlan', 'featureColor'),
    'ctaBtnBgColor' => theme('pricingPlan', 'ctaBtnBgColor'),
    'ctaBtnTextColor' => theme('pricingPlan', 'ctaBtnTextColor'),
    'ctaBtnHoverBgColor' => theme('pricingPlan', 'ctaBtnHoverBgColor'),
    'ctaSmTextColor' => theme('pricingPlan', 'ctaSmTextColor'),
    'showCtaBtn' => true,
    'showCryptoBtn' => false,
    'cryptoBtnText' => __('Pay with Crypto'),
    'cryptoBtnBgColor' => theme('pricingPlan', 'cryptoBtnBgColor'),
    'cryptoBtnTextColor' => theme('pricingPlan', 'cryptoBtnTextColor'),
    'cryptoBtnBorderColor' => theme('pricingPlan', 'cryptoBtnBorderColor'),
    'cryptoBtnHoverBgColor' => theme('pricingPlan', 'cryptoBtnHoverBgColor'),
    'cryptoBtnAction' => '#',
    'cryptoWireClick' => null,
    'cryptoAlpineClick' => null,
    'cryptoPlanId' => null,
    'spinnerColor' => theme('pricingPlan', 'spinnerColor'),
])

<div class="w-full xl:w-1/3 lg:w-1/3 p-4">
    <div class="h-full {{ $bgColor }} max-w-sm mx-auto rounded-lg shadow-md p-7 flex flex-col @if($highlighted) relative border-2 {{ $highlightBorderColor }} @endif">
        @if($highlighted)
            <div class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                <span class="{{ $highlightBadgeColor }} {{ $highlightBadgeTextColor }} text-xs font-semibold px-3 py-1 rounded-full">
                    {{ $highlightBadgeText }}
                </span>
            </div>
        @endif

        <div class="flex-none">
            <h3 class="text-xl font-semibold mb-4 {{ $nameColor }}">{{ $name }}</h3>
            <p class="text-3xl font-bold mb-6 {{ $priceColor }}">{!! $price !!}</p>
        </div>

        <div class="flex-grow">
            <ul class="mb-6 space-y-2">
                @foreach($features as $feature)
                    <li class="{{ $featureColor }}">{!! $feature !!}</li>
                @endforeach
            </ul>
        </div>

        <div class="flex-none">
            @if($showCtaBtn)
                <div x-data="{ loading: false }">
                    <button
                        @if($wireClick)
                            x-on:click="if(!loading) {
                                loading = true;
                                setTimeout(() => {
                                    $wire.{{ $wireClick }}(@if($planId)'{{ $planId }}'@endif)
                                }, 500);
                                setTimeout(() => { loading = false }, 1200);
                            }"
                        @endif
                        @if($alpineClick)
                            x-on:click="if(!loading) {
                                loading = true;
                                setTimeout(() => {
                                    {{ $alpineClick }}(@if($planId)'{{ $planId }}'@endif, $event)
                                }, 500);
                                setTimeout(() => { loading = false }, 1200);
                            }"
                        @endif
                        @if($ctaAction)
                            x-on:click="if(!loading) {
                                loading = true;
                                setTimeout(() => {
                                    window.location.href = '{{ $ctaAction }}';
                                }, 500);
                            }"
                        @endif
                        @if($planId) id="{{ $planId }}" @endif
                        class="relative w-full flex items-center justify-center {{ $ctaBtnBgColor }} {{ $ctaBtnTextColor }} px-4 py-2 rounded-md {{ $ctaBtnHoverBgColor }} transition duration-300 mb-2"
                        :disabled="loading"
                    >
                        <span class="pointer-events-none">{!! $ctaBtnText !!}</span>
                        <svg x-show="loading" class="pointer-events-none animate-spin h-5 w-5 {{ $spinnerColor }} ml-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                </div>
            @endif

            @if($showCryptoBtn)
                <div x-data="{ loading: false }">
                    <button
                        @if($cryptoWireClick)
                            x-on:click="if(!loading) {
                                loading = true;
                                setTimeout(() => {
                                    $wire.{{ $cryptoWireClick }}(@if($cryptoPlanId)'{{ $cryptoPlanId }}'@endif)
                                }, 500);
                                setTimeout(() => { loading = false }, 1200);
                            }"
                        @endif
                        @if($cryptoAlpineClick)
                            x-on:click="if(!loading) {
                                loading = true;
                                setTimeout(() => {
                                    {{ $cryptoAlpineClick }}(@if($cryptoPlanId)'{{ $cryptoPlanId }}'@endif, $event)
                                }, 500);
                                setTimeout(() => { loading = false }, 1200);
                            }"
                        @endif
                        @if($cryptoPlanId) id="{{ $cryptoPlanId }}" @endif
                        class="relative w-full flex items-center justify-center mt-2 px-4 py-2 {{ $cryptoBtnBgColor }} {{ $cryptoBtnTextColor }} border {{ $cryptoBtnBorderColor }} rounded {{ $cryptoBtnHoverBgColor }} transition duration-300"
                        :disabled="loading"
                    >
                        <svg class="pointer-events-none w-5 h-5 mr-2" viewBox="0 0 64 64" fill="currentColor">
                            <path d="M63.04 39.741c-4.274 17.143-21.638 27.575-38.783 23.301C7.12 58.768-3.313 41.404.962 24.262 5.234 7.117 22.597-3.317 39.737.957c17.144 4.274 27.576 21.64 23.302 38.784z M46.11 27.441c.636-4.258-2.606-6.547-7.039-8.074l1.438-5.768-3.512-.875-1.4 5.616c-.922-.23-1.87-.447-2.812-.662l1.41-5.653-3.509-.875-1.439 5.766c-.764-.174-1.514-.346-2.242-.527l.004-.018-4.842-1.209-.934 3.75s2.605.597 2.55.634c1.422.355 1.68 1.296 1.636 2.042l-1.638 6.571c.098.025.225.061.365.117l-.37-.092-2.297 9.205c-.174.432-.615 1.08-1.609.834.035.051-2.552-.637-2.552-.637l-1.743 4.02 4.57 1.139c.85.213 1.683.436 2.502.646l-1.453 5.835 3.507.875 1.44-5.772c.957.26 1.887.5 2.797.726L27.504 50.8l3.511.875 1.453-5.823c5.987 1.133 10.49.676 12.383-4.738 1.527-4.36-.075-6.875-3.225-8.516 2.294-.531 4.022-2.04 4.483-5.157zM38.087 38.69c-1.086 4.36-8.426 2.004-10.807 1.412l1.928-7.729c2.38.594 10.011 1.77 8.88 6.317zm1.085-11.312c-.99 3.966-7.1 1.951-9.083 1.457l1.748-7.01c1.983.494 8.367 1.416 7.335 5.553z"/>
                        </svg>
                        <span class="pointer-events-none">{!! $cryptoBtnText !!}</span>
                        <svg x-show="loading" class="pointer-events-none animate-spin h-5 w-5 {{ $spinnerColor }} ml-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                </div>
            @endif

            <p class="text-center text-sm font-bold mt-1 {{ $ctaSmTextColor }}">{!! $ctaSmText !!}</p>
        </div>
    </div>
</div>
