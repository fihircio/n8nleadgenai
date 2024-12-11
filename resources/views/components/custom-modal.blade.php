@props([
    'id' => 'modal',
    'maxWidth' => '2xl',
    'bgColor' => theme('customModal', 'bgColor'),
    'textColor' => theme('customModal', 'textColor'),
    'overlayColor' => theme('customModal', 'overlayColor'),
    'overlayOpacity' => theme('customModal', 'overlayOpacity'),
    'titleSize' => 'text-lg',
    'titleWeight' => 'font-medium',
    'footerBgColor' => theme('customModal', 'footerBgColor'),
    'confirmBtnBgColor' => theme('customModal', 'confirmBtnBgColor'),
    'confirmBtnHoverBgColor' => theme('customModal', 'confirmBtnHoverBgColor'),
    'confirmBtnTextColor' => theme('customModal', 'confirmBtnTextColor'),
    'cancelBtnBgColor' => theme('customModal', 'cancelBtnBgColor'),
    'cancelBtnHoverBgColor' => theme('customModal', 'cancelBtnHoverBgColor'),
    'cancelBtnTextColor' => theme('customModal', 'cancelBtnTextColor'),
    'cancelBtnBorderColor' => theme('customModal', 'cancelBtnBorderColor'),
])

@php
$maxWidth = [
    'sm' => 'sm:max-w-sm',
    'md' => 'sm:max-w-md',
    'lg' => 'sm:max-w-lg',
    'xl' => 'sm:max-w-xl',
    '2xl' => 'sm:max-w-2xl',
][$maxWidth ?? '2xl'];
@endphp

<div
    x-data="modal"
    x-show="isOpen"
    x-on:keydown.escape.window="close"
    x-on:open-modal.window="open($event.detail)"
    x-transition:enter="ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 overflow-y-auto"
    style="display: none;"
>
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div
            x-show="isOpen"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 transition-opacity {{ $overlayColor }} {{ $overlayOpacity }}"
            x-on:click="close"
            aria-hidden="true"
        ></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div
            x-show="isOpen"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="inline-block overflow-hidden text-left align-middle transition-all transform {{ $bgColor }} rounded-lg shadow-xl sm:my-8 {{ $maxWidth }} w-full max-h-[90vh] mx-auto"
        >
            <div class="px-4 pt-5 pb-4 {{ $bgColor }} sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="{{ $titleSize }} {{ $titleWeight }} leading-6 {{ $textColor }} mb-3">
                            @if(isset($title))
                                {{ $title }}
                            @else
                                <span x-text="title"></span>
                            @endif
                        </h3>
                        <div class="mt-2 {{ $textColor }}">
                            @if(isset($content))
                                {{ $content }}
                            @else
                                <div x-html="content"></div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-4 py-3 {{ $footerBgColor }} sm:px-6 sm:flex sm:flex-row-reverse">
                @if(isset($footer))
                    {{ $footer }}
                @else
                    <button
                        type="button"
                        class="inline-flex justify-center w-full px-4 py-2 text-base font-medium {{ $confirmBtnTextColor }} {{ $confirmBtnBgColor }} border border-transparent rounded-md shadow-sm {{ $confirmBtnHoverBgColor }} focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
                        x-text="confirmButtonText"
                        @click="confirm"
                    ></button>
                    <button
                        type="button"
                        class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium {{ $cancelBtnTextColor }} {{ $cancelBtnBgColor }} border {{ $cancelBtnBorderColor }} rounded-md shadow-sm {{ $cancelBtnHoverBgColor }} focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        @click="close"
                        x-text="cancelButtonText"
                    >
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function modal() {
    return {
        isOpen: false,
        title: '',
        content: '',
        confirmButtonText: '',
        cancelButtonText: '{{ __("Cancel") }}',
        confirmAction: null,

        open(data) {
            this.isOpen = true;
            this.title = data.title || '';
            this.content = data.content || '';
            this.confirmButtonText = data.confirmButtonText || '{{ __("Confirm") }}';
            this.cancelButtonText = data.cancelButtonText || '{{ __("Cancel") }}';
            this.confirmAction = data.confirmAction || function() {};
        },

        close() {
            this.isOpen = false;
        },

        confirm() {
            if (typeof this.confirmAction === 'function') {
                const shouldClose = this.confirmAction();
                if (shouldClose === false) {
                    // Do not close the modal
                    return;
                }
            }
            this.close();
        }
    };
}
</script>
