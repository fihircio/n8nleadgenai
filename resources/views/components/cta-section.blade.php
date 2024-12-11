@props([
    'title',
    'description',
    'ctaText',
    'ctaUrl',
    'bgColor' => theme('ctaSection', 'bgColor'),
    'imagePosition' => 'right',
    'titleColor' => theme('ctaSection', 'titleColor'),
    'titleSize' => 'text-3xl',
    'titleWeight' => 'font-bold',
    'titleMargin' => 'mb-4',
    'descriptionColor' => theme('ctaSection', 'descriptionColor'),
    'descriptionSize' => 'text-xl',
    'descriptionMargin' => 'mb-6',
    'ctaBgColor' => theme('ctaSection', 'ctaBgColor'),
    'ctaTextColor' => theme('ctaSection', 'ctaTextColor'),
    'ctaHoverBgColor' => theme('ctaSection', 'ctaHoverBgColor'),
    'ctaFontWeight' => 'font-semibold',
    'ctaRounded' => 'rounded-md',
    'ctaPadding' => 'px-6 py-3',
    'ctaTransition' => 'transition duration-300',
    'sectionPadding' => 'sm:py-12 md:py-16 py-5',
    'containerMaxWidth' => 'max-w-7xl',
    'imageSpacing' => 'md:pl-8',
])

<section class="{{ $bgColor }} {{ $sectionPadding }}">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 {{ $containerMaxWidth }}">
        <div class="flex flex-col {{ $imagePosition === 'left' ? 'md:flex-row-reverse' : 'md:flex-row' }} items-center">
            <div class="md:w-1/2 mb-8 md:mb-0 {{ $imagePosition === 'left' ? $imageSpacing : str_replace('pl-', 'pr-', $imageSpacing) }}">
                <h2 class="{{ $titleSize }} {{ $titleWeight }} {{ $titleColor }} {{ $titleMargin }}">{{ $title }}</h2>
                <p class="{{ $descriptionSize }} {{ $descriptionColor }} {{ $descriptionMargin }}">{{ $description }}</p>
                <a href="{{ $ctaUrl }}" class="inline-block {{ $ctaBgColor }} {{ $ctaTextColor }} {{ $ctaPadding }} {{ $ctaRounded }} {{ $ctaFontWeight }} {{ $ctaHoverBgColor }} {{ $ctaTransition }}">{{ $ctaText }}</a>
            </div>
            <div class="md:w-1/2">
                @if (isset($image))
                    {{ $image }}
                @elseif (isset($video))
                    {{ $video }}
                @elseif (isset($svg))
                    {{ $svg }}
                @endif
            </div>
        </div>
    </div>
</section>
