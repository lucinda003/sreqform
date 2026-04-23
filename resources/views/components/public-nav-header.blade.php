@props(['active' => ''])

@php
    $navClass = static fn (string $key): string => $active === $key
        ? 'auth-login-nav-link is-active'
        : 'auth-login-nav-link';
@endphp

<header class="auth-login-topbar auth-public-nav-topbar">
    <a href="{{ route('service-requests.track') }}" class="auth-login-brand" aria-label="DOH home navigation">
        <img src="{{ asset('images/dohlogo.svg') }}" alt="DOH Logo" class="auth-login-brand-logo">
        <div>
            <h1 class="auth-login-brand-title">KMITS</h1>
            <p class="auth-login-brand-subtitle">Knowledge Management and Information Technology Service</p>
        </div>
    </a>

    <div class="auth-login-nav-shell">
        <nav class="auth-login-nav" aria-label="Public navigation">
            <a href="{{ route('login') }}" class="{{ $navClass('sign-in') }}" @if ($active === 'sign-in') aria-current="page" @endif>Sign In</a>
            <a href="{{ route('service-requests.track') }}" class="{{ $navClass('faq') }}" @if ($active === 'faq') aria-current="page" @endif>Service Request Form</a>
            <a href="{{ route('service-requests.track', ['tab' => 'contact']) }}" class="{{ $navClass('contact') }}" @if ($active === 'contact') aria-current="page" @endif>Contact Us</a>
            <a href="{{ route('service-requests.track', ['tab' => 'about']) }}" class="{{ $navClass('about') }}" @if ($active === 'about') aria-current="page" @endif>About</a>
        </nav>
    </div>

    <div class="auth-login-topbar-spacer" aria-hidden="true"></div>
</header>
