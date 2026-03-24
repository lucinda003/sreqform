<x-guest-layout>
    <header class="auth-login-topbar">
        <div class="auth-login-brand">
            <img src="{{ asset('images/dohlogo.svg') }}" alt="DOH Logo" class="auth-login-brand-logo">
            <div>
                <h1 class="auth-login-brand-title">DEPARTMENT OF HEALTH</h1>
                <p class="auth-login-brand-subtitle">Secure Access Portal</p>
            </div>
        </div>

        <a href="{{ route('login') }}" class="auth-login-register">Login</a>
    </header>

    <section class="auth-login-card-wrap auth-register-card-wrap">
        <div class="auth-login-card auth-register-card">
            <div class="auth-login-card-head">
                <img src="{{ asset('images/dohlogo.svg') }}" alt="DOH Logo" class="auth-login-card-logo auth-register-card-logo">
                <h2 class="auth-login-card-title auth-register-card-title">DEPARTMENT OF<br>HEALTH</h2>
            </div>

            <div class="auth-login-divider"></div>

            <h3 class="auth-aux-title">Verify Email Address</h3>
            <p class="auth-aux-copy">Verification code is sent to {{ auth()->user()?->email ?? 'your email address' }}</p>

            @if (session('status') == 'verification-link-sent')
                <div class="auth-success mt-3">A new verification link has been sent to your email address.</div>
            @endif

            <div class="auth-code-boxes" aria-hidden="true">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
            </div>

            <form method="POST" action="{{ route('verification.send') }}" class="mt-3">
                @csrf
                <button type="submit" class="auth-login-button auth-register-button">Confirm Code</button>
            </form>

            <div class="auth-verify-meta">
                <span>00: 28</span>
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="auth-verify-resend">Resend Confirmation Code</button>
                </form>
            </div>

            <form method="POST" action="{{ route('logout') }}" class="mt-2 text-center">
                @csrf
                <button type="submit" class="auth-link">Log out</button>
            </form>
        </div>
    </section>
</x-guest-layout>
