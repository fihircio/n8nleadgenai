@php
    $containerParentClasses = match(config('devdojo.auth.appearance.alignment.container')){
        'left' => 'sm:ml-0 h-full',
        'center' => 'sm:mx-auto',
        'right' => 'sm:mr-0 h-full',
    };

    $containerClasses = match(config('devdojo.auth.appearance.alignment.container')){
        'left' => 'sm:border-r',
        'center' => 'sm:border sm:rounded-xl',
        'right' => 'sm:border-l',
    };
@endphp

<div id="auth-container-parent" class="relative w-full sm:max-w-md {{ $containerParentClasses }}">
    <div id="auth-container" class="flex relative top-0 z-20 flex-col justify-center items-stretch px-10 py-8 w-full h-screen bg-white border-gray-200 sm:top-auto sm:h-full {{ $containerClasses }}">
        {{ $slot }}
    </div>
</div>
<script>
    function decodeTitleEntities() {
        const title = document.title;
        const textarea = document.createElement('textarea');
        textarea.innerHTML = title;
        const decodedTitle = textarea.value;

        if (decodedTitle !== title) {
            document.title = decodedTitle;
        }
    }

    // Run on initial page load
    document.addEventListener('DOMContentLoaded', decodeTitleEntities);

    // Run after Livewire updates
    if (typeof window.Livewire === 'object') {
        window.Livewire.hook('element.init', (message, component) => {
            decodeTitleEntities();
        });
    }
</script>
