@props([
    'bgColor' => theme('global', 'bgColor'),
    'spinnerColor' => theme('global', 'spinnerColor'),
    'spinnerTextColor' => theme('global', 'spinnerTextColor'),
])

@php
    /**
     * !!! CRITICAL SECURITY NOTICE !!!
     * DO NOT REMOVE OR MODIFY THIS ACCESS CONTROL CHECK!
     *
     * This code block implements essential page-level authorization.
     * Removing or bypassing these checks will expose protected pages
     * to unauthorized users, potentially leading to data breaches
     * and security vulnerabilities.
     *
     * If you need to modify access controls, please update the permissions
     * configuration in the admin panel instead of removing this check.
     */

    $pageAccess = app('pageAccess');
    if ($pageAccess->checkAccessDenied(auth()->user(), $pageAccess->getCurrentPage())) {
        abort(403, $pageAccess->getErrorMessage());
    }
    if (empty($title) && !request()->routeIs('home')) {
        $uriPage = empty(config('zeus-sky.uri.page')) ? '' : config('zeus-sky.uri.page') . '/';
        $prefix = config('zeus-sky.prefix') . '/';
        $currentPage = str_replace($prefix . $uriPage, '', request()->path());
        $title = ucfirst($currentPage);
    }
@endphp

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
        <style>
            .custom-logo svg {
                width: 3rem !important;
                height: 3rem !important;
                margin-left: auto !important;
                margin-right: auto !important;
                margin-bottom: 0 !important;
            }
        </style>
        <!-- Styles -->
        @livewireStyles

        @if (config('saashovel.COOKIE_CONSENT'))
            @cookieconsentscripts
        @endif
    </head>
    <body class="flex flex-col min-h-screen font-sans">
        @if (config('saashovel.SPA_UX'))
            @include('livewire.pages.assets.default-merged-assets')
        @endif

        @include('livewire.pages.assets.merged-assets')

        <div class="flex-grow {{ $bgColor }}">
            @include('components.header')
            <main>
                {{ $slot }}
            </main>
        </div>

        @include('components.footer')

        @if (config('saashovel.COOKIE_CONSENT'))
            @cookieconsentview
        @endif

        @livewireScripts

        <x-impersonate::banner/>
    </body>
</html>
