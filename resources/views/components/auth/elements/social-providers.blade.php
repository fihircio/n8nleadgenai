@props([
    'socialProviders' => \Devdojo\Auth\Helper::activeProviders(),
    'separator' => true,
    'separator_text' => __('or')
])
@if(count($socialProviders))
    @if($separator && config('devdojo.auth.settings.social_providers_location') != 'top')
        <x-auth::elements.separator class="my-6">{{ $separator_text }}</x-auth::elements.separator>
    @endif
    <div class="relative space-y-2 w-full">
        @foreach($socialProviders as $slug => $provider)
            <x-auth::elements.social-button :$slug :$provider />
        @endforeach
    </div>
    @if (config('saashovel.TERMS_AND_PRIVACY_POLICY'))
        <span class="text-xs mt-1 select-none italic">
            {!! __('*By signing in with a third-party provider, you automatically agree to the :terms_of_service and :privacy_policy', [
                    'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Terms of Service').'</a>',
                    'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Privacy Policy').'</a>',
            ]) !!}
        </span>
    @endif
    @if($separator && config('devdojo.auth.settings.social_providers_location') == 'top')
        <x-auth::elements.separator class="my-6">{{ $separator_text }}</x-auth::elements.separator>
    @endif
@endif
