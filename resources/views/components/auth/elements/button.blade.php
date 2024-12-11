@props([
    'type' => 'primary',
    'size' => 'md',
    'tag' => 'button',
    'href' => '/',
    'submit' => false,
    'rounded' => 'full'
])

@php
    $sizeClasses = match ($size) {
        'sm' => 'px-2.5 py-1.5 text-xs font-medium rounded-' . $rounded,
        'md' => 'px-4 py-2.5 text-sm font-medium rounded-' . $rounded,
        'lg' => 'px-5 py-3  text-sm font-medium rounded-' . $rounded,
        'xl' => 'px-6 py-3.5 text-base font-medium rounded-' . $rounded,
        '2xl' => 'px-7 py-4 text-base font-medium rounded-' . $rounded
    };
@endphp

@php
    $typeClasses = match ($type) {
        'primary' => '',
        'secondary' => 'bg-zinc-100 border text-gray-500 hover:text-gray-700 border-zinc-100 dark:focus:ring-offset-gray-900 dark:border-gray-400/10 active:bg-white dark:focus:ring-gray-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-200/60 dark:bg-gray-800/50 dark:hover:bg-gray-800/70 dark:text-gray-400 focus:shadow-outline',
        'success' => 'bg-green-600 text-white hover:bg-green-600/90 focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-900 focus:bg-green-700/90 focus:ring-green-700',
        'info' => 'bg-blue-600 text-white hover:bg-blue-600/90 focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-900 focus:bg-blue-700/90 focus:ring-blue-700',
        'warning' => 'bg-amber-500 text-white hover:bg-amber-500/90 focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-900 focus:bg-amber-600/90 focus:ring-amber-600',
        'danger' => 'bg-red-600 text-white hover:bg-red-600/90 focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-900 focus:bg-red-700/90 focus:ring-red-700',
    };

    $loadingTarget = $attributes['wire:target'];
@endphp

@php
switch ($tag ?? 'button') {
    case 'button':
        $tagAttr = ($submit) ? 'button type="submit"' : 'button type="button"';
        $tagClose = 'button';
        break;
    case 'a':
        $link = $href ?? '';
        $tagAttr = 'a  href="' . $link . '"';
        $tagClose = 'a';
        break;
    default:
        $tagAttr = 'button type="button"';
        $tagClose = 'button';
        break;
}
@endphp

<div @if (config('saashovel.TERMS_AND_PRIVACY_POLICY')) x-data="authHandler" @endif>
    @if (config('saashovel.TERMS_AND_PRIVACY_POLICY'))
        <div id="terms-checkbox-container" x-show="isRegisterPage" class="mb-4 select-none">
            <label class="flex items-center">
                <input type="checkbox" x-model="terms" @change="updateTermsState" id="terms-checkbox" class="form-checkbox cursor-pointer">
                <span class="ml-2 text-sm text-gray-600 cursor-pointer">
                    {!! __('I agree to the :terms_of_service and :privacy_policy', [
                            'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Terms of Service').'</a>',
                            'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Privacy Policy').'</a>',
                    ]) !!}
                </span>
            </label>
            <span x-transition x-show="termsError" x-text="termsError" class="text-red-600 text-sm block mt-1"></span>
        </div>
    @endif

    <{!! $tagAttr !!} {!! $attributes->except(['class']) !!} class="@if($type == 'primary'){{ 'auth-component-button' }}@endif {{ $sizeClasses }} {{ $typeClasses }} opacity-[95%] hover:opacity-100 focus:ring-2 focus:ring-offset-2 cursor-pointer inline-flex items-center w-full justify-center disabled:opacity-50 font-semibold focus:outline-none" style="@if($type == 'primary') color:{{ config('devdojo.auth.appearance.color.button_text') }}; background-color:{{ config('devdojo.auth.appearance.color.button') }}; @endif">
        <svg xmlns="http://www.w3.org/2000/svg" wire:loading @if(isset($loadingTarget)) wire:target="{{ $loadingTarget }}" @endif viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1.5 w-4 h-4 animate-spin"><path d="M21 12a9 9 0 1 1-6.219-8.56"></path></svg>
        {{ $slot }}
    </{{ $tagClose }}>
</div>

@if (config('saashovel.TERMS_AND_PRIVACY_POLICY'))
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('authHandler', () => ({
                isRegisterPage: false,
                terms: false,
                termsError: '',

                init() {
                    this.checkCurrentPage();
                    this.setupButtonHandling();
                },

                checkCurrentPage() {
                    this.isRegisterPage = window.location.href.includes('register');
                },

                setupButtonHandling() {
                    const submitButton = this.$el.querySelector('[data-auth="submit-button"]');
                    if (submitButton) {
                        submitButton.addEventListener('click', this.handleButtonClick.bind(this));
                    }
                },

                handleButtonClick(event) {
                    if (this.isRegisterPage && !this.terms) {
                        event.preventDefault();
                        this.termsError = "{!! __('You must agree to the Terms of Service and Privacy Policy.') !!}";
                        const termsCheckboxContainer = document.getElementById('terms-checkbox-container');
                        if (termsCheckboxContainer) {
                            termsCheckboxContainer.scrollIntoView({ behavior: 'smooth', block: 'end' });
                        }
                    }
                    // For login, do nothing and let the default action proceed
                },

                updateTermsState() {
                    this.termsError = '';
                }
            }));
        });
    </script>
@endif
