@props([
    'active',
    'navClass' => theme('header', 'navClass'),
    'navClassActive' => theme('header', 'navClassActive'),
])

@php
$classes = ($active ?? false)
            ? $navClassActive
            : $navClass;
@endphp


<a {{ when(config('saashovel.SPA_UX'), 'wire:navigate') }} {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
