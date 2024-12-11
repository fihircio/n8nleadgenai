@props([
    'name',
    'tabId',
    'icon',
    'mediaType' => '',
    'mediaUrl' => '',
    'title' => '',
    'description' => '',
    'features' => [],
    'checkmarkColor' => theme('interactiveFeaturesSection', 'checkmarkColor'),
])

<div
    data-tab-id="{{ $tabId }}"
    data-name="{{ $name }}"
    data-media-type="{{ $mediaType }}"
    data-media-url="{{ $mediaUrl }}"
>
    <div class="tab-icon" style="display: none;">{!! $icon !!}</div>
    <div data-tab-content>
        @if($title)
            <h3 class="text-2xl font-semibold mb-4">{{ $title }}</h3>
        @endif

        @if($description)
            <p>{{ $description }}</p>
        @endif

        @if(!empty($features))
            <ul class="list-disc pl-1 mt-2">
                @foreach($features as $feature)
                    <li class="flex items-center">
                        <!-- Checkmark SVG with customizable color -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="inline w-6 h-6 mt-1 {{ $checkmarkColor }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="ml-2">
                            {!! $feature !!}
                        </span>
                    </li>
                @endforeach
            </ul>
        @endif

        <!-- If you still want to include extra content, keep the slot -->
        {{ $slot }}
    </div>
</div>
