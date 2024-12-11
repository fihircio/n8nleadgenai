@props([
    'sectionTitleTextColor' => theme('global', 'sectionTitleTextColor'),
])

<div class="md:col-span-1 flex justify-between">
    <div class="px-4 sm:px-0">
        <h3 class="text-lg font-medium {{ $sectionTitleTextColor }}">{{ $title }}</h3>

        <p class="mt-1 text-sm {{ preg_replace('/text-(\w+)-\d+/', 'text-$1-600', $sectionTitleTextColor) }}">
            {{ $description }}
        </p>
    </div>

    <div class="px-4 sm:px-0">
        {{ $aside ?? '' }}
    </div>
</div>
