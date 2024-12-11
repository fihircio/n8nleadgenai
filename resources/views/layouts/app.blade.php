@props([
    'headerBgColor' => theme('header', 'bgColor'),
    'pageBlogBgColor' => theme('global', 'pageBlogBgColor'),
    'pageBgColor' => theme('global', 'bgColor'),
    'spinnerColor' => theme('global', 'spinnerColor'),
    'spinnerTextColor' => theme('global', 'spinnerTextColor'),
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name') }}</title>
        <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        @include('livewire.pages.assets.default-merged-assets')
        @include('livewire.pages.assets.merged-assets')

        <x-banner />

        <div class="min-h-screen {{ $pageBgColor }}">
            @livewire('navigation-menu')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="{{ $headerBgColor }} shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                <div class="py-12 {{ $pageBgColor }}">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="{{ $pageBlogBgColor }} overflow-hidden shadow-xl sm:rounded-lg">
                            {{ $slot }}
                        </div>
                    </div>
                </div>
            </main>
        </div>

        @include('components.footer')

        @stack('modals')

        @livewireScripts

        <x-impersonate::banner/>
    </body>
</html>
