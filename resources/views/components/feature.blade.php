@props([
    'id',
    'title',
    'description',
    'mediaType' => 'image',
    'mediaSrc' => '',
    'mediaPosition' => 'right',
    'bgColor' => theme('featureSection', 'bgColor'),
    'titleColor' => theme('featureSection', 'titleColor'),
    'descriptionColor' => theme('featureSection', 'descriptionColor'),
    'listItemColor' => theme('featureSection', 'listItemColor'),
    'ctaText' => null,
    'ctaUrl' => '#',
    'ctaBgColor' => theme('featureSection', 'ctaBgColor'),
    'ctaTextColor' => theme('featureSection', 'ctaTextColor'),
    'ctaHoverBgColor' => theme('featureSection', 'ctaHoverBgColor')
])

<section id="{{ $id }}" class="{{ $bgColor }} py-6">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row{{ $mediaPosition === 'left' ? '-reverse' : '' }} items-center">
            <div class="md:w-1/2 my-4 {{ $mediaPosition === 'right' ? 'md:pr-8' : 'md:pl-8' }}">
                <h2 class="text-3xl font-bold {{ $titleColor }} mb-4">{{ $title }}</h2>
                <p class="text-xl {{ $descriptionColor }} mb-6">{{ $description }}</p>
                <ul class="list-disc list-inside {{ $listItemColor }} mb-6">
                    {{ $slot }}
                </ul>
                @if($ctaText)
                    <a href="{{ $ctaUrl }}"
                       class="inline-block {{ $ctaBgColor }} {{ $ctaTextColor }} px-6 py-3 rounded-md text-lg font-semibold {{ $ctaHoverBgColor }} transition duration-300">
                        {{ $ctaText }}
                    </a>
                @endif
            </div>
            <div class="md:w-1/2 py-2">
                @if($mediaType === 'video')
                    <video controls preload="metadata" class="shadow-lg w-full h-auto rounded-lg">
                        <source src="{{ asset($mediaSrc) }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                @elseif($mediaType === 'image')
                    <img src="{{ asset($mediaSrc) }}" alt="{{ $title }}" class="w-full h-auto rounded-lg shadow-lg">
                @elseif($mediaType === 'emoji')
                    <p class="text-center text-[8rem] sm:text-[12rem] md:text-[16rem] lg:text-[20rem] xl:text-[22rem] leading-none">{{ $mediaSrc }}</p>
                @endif
            </div>
        </div>
    </div>
</section>
