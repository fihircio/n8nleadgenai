<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-50">
    <main class="w-full max-w-lg p-8 bg-white rounded-lg shadow-md text-center">
        <h1 class="text-3xl font-semibold text-gray-800 mb-4">@yield('code')</h1>
        <p class="text-gray-600 mb-6">@yield('message')</p>
        {{-- @yield('content') --}}
        <a href="/" class="inline-block mt-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300">{{ __('Return to Homepage') }}</a>
    </main>
</body>
</html>
