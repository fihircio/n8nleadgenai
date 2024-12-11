@props([
    'title' => 'Why Choose SaaShovel?',
    'bgColor' => theme('featuresGrid', 'bgColor'),
    'titleColor' => theme('featuresGrid', 'titleColor'),
    'gridCols' => 'md:grid-cols-3'
])

<section id="features" class="{{ $bgColor }} py-6">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-center {{ $titleColor }} mb-8">{{ $title }}</h2>
        <div class="grid grid-cols-1 {{ $gridCols }} gap-8">
            {{ $slot }}
        </div>
    </div>
</section>
