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

            <h3 class="auth-aux-title">Forgot Password?</h3>
            <p class="auth-aux-copy">Please write your email to receive your confirmation code to set a new password.</p>

            <x-auth-session-status class="auth-success mt-4" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}" class="auth-login-form auth-register-form mt-4">
                @csrf

                <div>
                    <label for="email" class="auth-login-label auth-register-label">Email</label>
                    <div class="auth-login-input-wrap">
                        <svg viewBox="0 0 24 24" aria-hidden="true" class="auth-login-input-icon"><path fill="currentColor" d="M20 4H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2m0 4l-8 5L4 8V6l8 5l8-5z"/></svg>
                        <input id="email" class="auth-login-input auth-register-input" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="Enter your email" />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <button type="submit" class="auth-login-button auth-register-button">Confirm Email</button>
            </form>
        </div>
    </section>
</x-guest-layout>
