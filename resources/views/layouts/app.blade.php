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
    <body class="app-body {{ request()->routeIs('dashboard') || request()->routeIs('admin.dashboard') ? 'dashboard-login-bg' : '' }} antialiased">
        @php
            $usesCustomDashboardLayout = request()->routeIs('dashboard')
                || request()->routeIs('admin.dashboard')
                || request()->routeIs('service-requests.index')
                || request()->routeIs('profile.*')
                || request()->routeIs('admin.users.*');
        @endphp
        <div class="app-shell min-h-screen">
            @unless ($usesCustomDashboardLayout)
                @include('layouts.navigation')
            @endunless

            <!-- Page Heading -->
            @isset($header)
                @unless ($usesCustomDashboardLayout)
                <header class="px-4 pb-3 pt-6 sm:px-6 lg:px-8">
                    <div class="mx-auto w-full max-w-6xl rounded-2xl border border-white/65 bg-white/70 px-5 py-4 shadow-lg backdrop-blur-xl sm:px-6">
                        {{ $header }}
                    </div>
                </header>
                @endunless
            @endisset

            <!-- Page Content -->
            <main class="{{ $usesCustomDashboardLayout ? '' : 'px-4 pb-8 sm:px-6 lg:px-8' }}">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
