@props([
    'title',
    'subtitle',
    'ctaText',
    'ctaUrl',
    'titleColor' => theme('heroSection', 'titleColor'),
    'subtitleColor' => theme('heroSection', 'subtitleColor'),
    'ctaBackgroundColor' => theme('heroSection', 'ctaBackgroundColor'),
    'ctaTextColor' => theme('heroSection', 'ctaTextColor'),
    'ctaHoverBackgroundColor' => theme('heroSection', 'ctaHoverBackgroundColor'),
    'bgColor' => theme('heroSection', 'bgColor')
])

<section class="{{ $bgColor }}">
    <div class="container mx-auto px-4 py-6 sm:px-6 lg:px-8 max-w-7xl">
        <div class="flex flex-col md:flex-row items-center">
            <div class="md:w-2/3 mb-8 md:mb-0">
                <h1 class="text-4xl sm:text-5xl font-bold {{ $titleColor }} mb-4">{{ $title }}</h1>
                <p class="text-xl {{ $subtitleColor }} mb-6">{!! $subtitle !!}</p>
                <a href="{{ $ctaUrl }}" class="inline-block {{ $ctaBackgroundColor }} {{ $ctaTextColor }} px-6 py-3 rounded-md text-lg font-semibold {{ $ctaHoverBackgroundColor }} transition duration-300">{{ $ctaText }}</a>
            </div>
            <div class="md:w-1/3">
                @if (isset($svg))
                    {{ $svg }}
                @elseif (isset($image))
                    {{ $image }}
                @elseif (isset($video))
                    {{ $video }}
                @endif
                @if (isset($tagline))
                    {{ $tagline }}
                @endif
            </div>
        </div>
    </div>
</section>
