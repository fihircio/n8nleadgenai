@props([
    'id',
    'title',
    'description',
    'mediaType' => '',
    'mediaSrc' => '',
    'bgColor' => theme('verticalFeatureSection', 'bgColor'),
    'titleColor' => theme('verticalFeatureSection', 'titleColor'),
    'descriptionColor' => theme('verticalFeatureSection', 'descriptionColor'),
    'listItemColor' => theme('verticalFeatureSection', 'listItemColor'),
    'ctaText' => null,
    'ctaUrl' => '#',
    'ctaBgColor' => theme('verticalFeatureSection', 'ctaBgColor'),
    'ctaTextColor' => theme('verticalFeatureSection', 'ctaTextColor'),
    'ctaHoverBgColor' => theme('verticalFeatureSection', 'ctaHoverBgColor')
])

<section id="{{ $id }}" class="{{ $bgColor }} py-6">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col items-center">
            <div class="w-full max-w-3xl text-center my-2">
                <h2 class="text-3xl font-bold {{ $titleColor }} mb-4">{{ $title }}</h2>
                <p class="text-xl {{ $descriptionColor }} mb-6">{{ $description }}</p>
            </div>
            @if($mediaType !== '')
                <div class="w-full max-w-2xl my-5">
                    @if($mediaType === 'video')
                        <video controls preload="metadata" class="shadow-lg w-full h-auto rounded-lg">
                            <source src="{{ asset($mediaSrc) }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    @elseif($mediaType === 'image')
                        <img src="{{ asset($mediaSrc) }}" alt="{{ $title }}" class="w-full h-auto rounded-lg shadow-lg">
                    @elseif($mediaType === 'emoji')
                        <p class="text-center text-[12rem] leading-none">{{ $mediaSrc }}</p>
                    @endif
                </div>
            @endif
            <div class="w-full max-w-2xl">
                <ul class="list-disc list-inside {{ $listItemColor }} mb-8 text-left">
                    {{ $slot }}
                </ul>
                @if($ctaText)
                    <div class="text-center">
                        <a href="{{ $ctaUrl }}"
                           class="inline-block {{ $ctaBgColor }} {{ $ctaTextColor }} px-6 py-3 rounded-md text-lg font-semibold {{ $ctaHoverBgColor }} transition duration-300">
                            {{ $ctaText }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
