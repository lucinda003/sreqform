<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="app-body {{ request()->routeIs('dashboard') ? 'dashboard-login-bg' : '' }} antialiased">
        <div class="app-shell min-h-screen">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="px-4 pb-3 pt-6 sm:px-6 lg:px-8">
                    <div class="mx-auto w-full max-w-6xl rounded-2xl border border-white/65 bg-white/70 px-5 py-4 shadow-lg backdrop-blur-xl sm:px-6">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="px-4 pb-8 sm:px-6 lg:px-8">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
