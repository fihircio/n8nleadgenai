@props([
    'plans' => [],
    'pricingId' => 'pricing',
    'class' => 'py-10',
    'title' => __('Pricing'),
    'bgColor' => theme('pricingSection', 'bgColor'),
    'titleColor' => theme('pricingSection', 'titleColor'),
    'titleSize' => 'text-3xl',
    'titleWeight' => 'font-bold',
    'titleAlignment' => 'text-center',
    'titleMargin' => 'mb-8',
])

<section x-cloak id="{{ $pricingId }}" class="{{ $class }} {{ $bgColor }}">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="{{ $titleSize }} {{ $titleWeight }} {{ $titleAlignment }} {{ $titleColor }} {{ $titleMargin }}">{{ $title }}</h2>
        <div class="flex flex-col lg:flex-row lg:flex-wrap justify-center -mx-4">
            {{ $slot }}
        </div>
    </div>
</section>
