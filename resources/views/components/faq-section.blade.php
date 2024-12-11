@props([
    'title' => 'Frequently Asked Questions',
    'bgColor' => theme('faqSection', 'bgColor'),
    'titleColor' => theme('faqSection', 'titleColor')
])

<section x-cloak class="{{ $bgColor }} py-6">
    <div class="max-w-4xl container mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold {{ $titleColor }} mb-8 text-center">{{ $title }}</h2>

        {{ $slot }}

    </div>
</section>
