@props([
    'icon',
    'title',
    'bgColor' => theme('featureItem', 'bgColor'),
    'iconColor' => theme('featureItem', 'iconColor'),
    'titleColor' => theme('featureItem', 'titleColor'),
    'contentColor' => theme('featureItem', 'contentColor')
])

<div class="{{ $bgColor }} p-6 rounded-lg shadow-md">
    <svg class="h-12 w-12 mx-auto mb-4 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        {!! $icon !!}
    </svg>
    <h3 class="text-xl font-semibold mb-2 text-center {{ $titleColor }}">{{ $title }}</h3>
    <div class="{{ $contentColor }}">
        {{ $slot }}
    </div>
</div>
