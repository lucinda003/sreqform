<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ isset($pageTitle) && $pageTitle !== '' ? $pageTitle : config('app.name', 'Laravel') }}</title>
        <link rel="icon" type="image/svg+xml" href="{{ asset('images/dohlogo.svg') }}">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    @php
        $usesAuthLoginShell = request()->routeIs('login')
            || request()->routeIs('register')
            || request()->routeIs('password.request')
            || request()->routeIs('password.reset')
            || request()->routeIs('change-password.*')
            || request()->routeIs('verification.notice')
            || request()->routeIs('service-requests.track')
            || request()->routeIs('service-requests.track.edit')
            || request()->routeIs('service-requests.capture-email')
            || request()->routeIs('service-requests.create')
            || request()->routeIs('service-requests.edit');
    @endphp

    <body class="auth-body {{ $usesAuthLoginShell ? 'auth-login-body' : '' }} {{ request()->routeIs('login') ? 'auth-login-page' : '' }} {{ request()->routeIs('register') ? 'auth-register-page' : '' }} {{ request()->routeIs('password.request') || request()->routeIs('password.reset') || request()->routeIs('change-password.*') ? 'auth-forgot-page' : '' }} {{ request()->routeIs('service-requests.track') || request()->routeIs('service-requests.track.edit') || request()->routeIs('service-requests.capture-email') || request()->routeIs('service-requests.create') || request()->routeIs('service-requests.edit') ? 'auth-track-page' : '' }} antialiased">
        @if ($usesAuthLoginShell)
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
