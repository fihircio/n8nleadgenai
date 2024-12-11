@props([
    'footerClass' => theme('footer', 'footerClass'),
])

<footer class="{{ $footerClass }}">
    <div class="container mx-auto px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">  <!-- Changed to 4 columns -->
            <div>
                <a class="flex items-center mb-1" href="{{ route('home') }}">
                    <x-application-logo class="h-8 w-8 text-lime-600" />
                    <strong class="md:text-lg ml-1">SaaShovel</strong>
                </a>
                <p class="text-sm text-gray-400">Stop digging, start building!</p>
                <p class="text-sm text-gray-400">&copy; 2024 SaaShovel. All rights reserved.</p>
            </div>
            <div>
                <h4 class="text-lg font-medium mb-4 text-gray-400">{{ __('LINKS') }}</h4>
                <ul class="space-y-2">
                    <li><a href="/#pricing">{{ __('Pricing') }}</a></li>
                    <li><a href="#">{{ __('Support') }}</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-lg font-medium mb-4 text-gray-400">{{ __('LEGAL') }}</h4>
                <ul class="space-y-2">
                    <li><a {{ when(config('saashovel.SPA_UX'), 'wire:navigate') }} href="{{ route('terms.show') }}">{{ __('Terms of services') }}</a></li>
                    <li><a {{ when(config('saashovel.SPA_UX'), 'wire:navigate') }} href="{{ route('policy.show') }}">{{ __('Privacy policy') }}</a></li>
                </ul>
            </div>
            {{-- <div>
                <h4 class="text-lg font-medium mb-4 text-gray-400">Another title here</h4>
                <ul class="space-y-2">
                    <li><a href="#">Link</a></li>
                </ul>
            </div> --}}
        </div>
    </div>
</footer>

