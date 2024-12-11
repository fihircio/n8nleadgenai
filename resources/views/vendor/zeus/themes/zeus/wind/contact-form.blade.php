<div>
    <x-slot name="header">
        <h2>{{ __('Contact us') }}</h2>
    </x-slot>

    <div class="mt-6"></div>

    <x-slot name="breadcrumbs">
        <li class="flex items-center">
            {{ __('Contact us') }}
        </li>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <x-filament::section>
            {{ __('Add your text here.') }} <br><br>
            <i>Path: "resources/views/vendor/zeus/themes/zeus/wind/contact-form.blade.php"</i>
        </x-filament::section>
    </div>

    @php
        $colors = \Illuminate\Support\Arr::toCssStyles([
            \Filament\Support\get_color_css_variables('primary', shades: [50, 100, 200, 300, 400, 500, 600, 700, 800, 900]),
        ]);
    @endphp

    @if($sent)
        @include(app('windTheme').'.submitted')
    @else
        <form wire:submit.prevent="store">
            <div style="{{ $colors }}" class="max-w-4xl mx-auto my-4 px-4">
                {{ $this->form }}
                <div class="p-4 text-center">
                    {{-- <x-filament::button type="submit">
                        {{ __('Send') }}
                    </x-filament::button> --}}
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75 transition duration-300 ease-in-out" wire:loading.attr="disabled">{{ __('Send') }}</button>
                </div>
            </div>
        </form>

    @endif
</div>
