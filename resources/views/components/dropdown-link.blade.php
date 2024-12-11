@props([
    'dropDownLinkClass' => theme('global', 'dropDownLinkClass')
    ])
<a
    {{ $attributes->merge(['class' => 'block w-full px-4 py-2 text-start text-sm leading-5 focus:outline-none transition duration-150 ease-in-out ' . $dropDownLinkClass]) }}
    {{ when(config('saashovel.SPA_UX'), 'wire:navigate') }}
>
    {{ $slot }}
</a>
