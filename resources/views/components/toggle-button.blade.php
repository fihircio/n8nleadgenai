@props([
    'showText' => 'See More...',
    'hideText' => 'Hide Sections',
    'isShowing' => false,
    'class' => theme('toggleButton', 'class'),
    'clickInView' => false,
])

<div class="bg-transparent text-center my-6" x-cloak x-data="{
    isShowing: {{ json_encode($isShowing) }},
    isLoading: false,
    init() {  // Initialize Intersection Observer
        if ({{ json_encode($clickInView) }}) { // Only if clickInView is true
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        this.$refs.button.click();
                        observer.unobserve(this.$refs.button);
                    }
                });
            });
            observer.observe(this.$refs.button);
        }
    }
}"
     x-init="init"
     {{ $attributes->merge(['class' => '']) }}>
    <button
        x-ref="button"
        @click="isLoading = true; setTimeout(() => { isLoading = false; isShowing = !isShowing; $dispatch('toggle-sections', isShowing) }, 300)"
        :class="{ '{{ $class }}': true, 'opacity-75 cursor-not-allowed': isLoading }"
        class="relative text-white px-6 py-3 rounded-md text-lg font-semibold transition duration-300"
        :disabled="isLoading"
    >
        <span x-show="!isLoading" x-text="isShowing ? '{{ $hideText }}' : '{{ $showText }}'"></span>
        <span x-show="isLoading" class="text-transparent">Loading...</span>
        <span x-show="isLoading" class="absolute inset-0 flex items-center justify-center">
            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </span>
    </button>
</div>
