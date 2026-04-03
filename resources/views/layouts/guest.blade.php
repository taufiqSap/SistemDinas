<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('head')
    </head>
    <body class="min-h-screen bg-gray-900 text-gray-100 antialiased">
        @include('partials.navbar')

        <main class="{{ request()->routeIs('login') ? '' : 'py-8' }}">
            {{ $slot }}
        </main>

        @unless (request()->routeIs('login'))
            @include('partials.footer')
        @endunless

        @stack('scripts')
    </body>
</html>
