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
    <body class="min-h-screen bg-white text-gray-900 antialiased">
        @php
            $isAuthPage = request()->routeIs('login')
                || request()->routeIs('register')
                || request()->routeIs('password.*')
                || request()->routeIs('verification.*')
                || request()->routeIs('confirm-password');
        @endphp

        @if ($isAuthPage)
            @include('partials.navbar-auth')
        @else
            @include('partials.navbar')
        @endif

        <main class="{{ $isAuthPage ? '' : 'py-8' }}">
            {{ $slot }}
        </main>

        @if ($isAuthPage)
            @include('partials.footer-auth')
        @else
            @include('partials.footer')
        @endif

        @stack('scripts')
    </body>
</html>
