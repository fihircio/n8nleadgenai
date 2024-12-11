@props([
    'pageBlogBgColor' => theme('global', 'pageBlogBgColor'),
    'textColor' => theme('global', 'textColor'),
    'textHeadingsColor' => 'prose-headings:' . theme('global', 'textColor'),
])

<style>
    .markdown-content h2,
    .markdown-content h3 {
        margin-top: 0.6em !important;
        margin-bottom: 0.3em !important;
    }
    ul {
        margin-top: 0.2em !important;
        margin-bottom: 0.2em !important;
    }
    p {
        margin-top: 0.2em !important;
        margin-bottom: 0.4em !important;
    }
</style>
<x-guest-layout>
    <div class="pt-4">
        <div class="min-h-screen flex flex-col items-center pt-6 sm:pt-0">
            <div>
                <x-authentication-card-logo />
            </div>

            <div class="markdown-content w-full sm:max-w-2xl mt-6 p-6 {{ $pageBlogBgColor }} {{ $textColor }} {!! $textHeadingsColor !!} prose-a:text-blue-600 shadow-md overflow-hidden sm:rounded-lg prose mb-5">
                {!! $policy !!}
            </div>
        </div>
    </div>
</x-guest-layout>
