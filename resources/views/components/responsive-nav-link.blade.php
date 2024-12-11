@props([
    'active',
    'responsiveNavClass' => theme('header', 'responsiveNavClass'),
    'responsiveNavClassActive' => theme('header', 'responsiveNavClassActive'),
])

@php
$classes = ($active ?? false)
            ? $responsiveNavClassActive
            : $responsiveNavClass;
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
