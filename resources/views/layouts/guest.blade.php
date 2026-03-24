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
    <body class="auth-body {{ request()->routeIs('login') || request()->routeIs('register') || request()->routeIs('password.request') || request()->routeIs('verification.notice') || request()->routeIs('password.reset') || request()->routeIs('service-requests.track') || request()->routeIs('service-requests.capture-email') ? 'auth-login-body' : '' }} {{ request()->routeIs('login') ? 'auth-login-page' : '' }} {{ request()->routeIs('register') ? 'auth-register-page' : '' }} {{ request()->routeIs('password.request') ? 'auth-forgot-page' : '' }} {{ request()->routeIs('service-requests.track') || request()->routeIs('service-requests.capture-email') ? 'auth-track-page' : '' }} antialiased">
        @if (request()->routeIs('login') || request()->routeIs('register') || request()->routeIs('password.request') || request()->routeIs('verification.notice') || request()->routeIs('password.reset') || request()->routeIs('service-requests.track') || request()->routeIs('service-requests.capture-email'))
            <div class="auth-login-shell">
                <div class="auth-login-aurora"></div>
                <div class="auth-login-wave auth-login-wave-one"></div>
                <div class="auth-login-wave auth-login-wave-two"></div>
                {{ $slot }}
            </div>
        @else
            <div class="auth-shell">
                <div class="auth-blob auth-blob-one"></div>
                <div class="auth-blob auth-blob-two"></div>

                <section class="auth-card auth-stagger">
                    <div class="auth-logo-wrap">
                        <x-application-logo class="auth-logo" />
                    </div>
                    {{ $slot }}
                </section>
            </div>
        @endif
    </body>
</html>
