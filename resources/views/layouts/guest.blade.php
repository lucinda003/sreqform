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
    <body class="auth-body antialiased">
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
    </body>
</html>
