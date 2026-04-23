@php View::share('pageTitle', 'Sign In'); @endphp
<x-guest-layout>
    <x-public-nav-header active="sign-in" />

    <section class="auth-login-card-wrap">
        <x-auth-session-status class="auth-success mb-4" :status="session('status')" />

        <div class="auth-login-card">
            <div class="auth-login-card-head auth-login-card-head-simple">
                <div class="auth-login-card-copy">
                    <h2 class="auth-login-card-title">Login to our site</h2>
                    <p class="auth-login-card-subtitle">Enter your username and password to log on:</p>
                </div>
            </div>

            <div class="auth-login-divider"></div>

            <form method="POST" action="{{ route('login') }}" class="auth-login-form">
                @csrf

                <div>
                    <label for="username" class="auth-login-label">Username</label>
                    <div class="auth-login-input-wrap">
                        <svg viewBox="0 0 24 24" aria-hidden="true" class="auth-login-input-icon"><path fill="currentColor" d="M12 12a5 5 0 1 0-5-5a5 5 0 0 0 5 5zm0 2c-4.42 0-8 1.79-8 4v2h16v-2c0-2.21-3.58-4-8-4z"/></svg>
                        <input id="username" class="auth-login-input" type="text" name="username" value="{{ old('username') }}" required autofocus autocomplete="username" placeholder="Enter your username" />
                    </div>
                    <x-input-error :messages="$errors->get('username')" class="mt-2" />
                </div>

                <div>
                    <label for="password" class="auth-login-label">Password</label>
                    <div class="auth-login-input-wrap">
                        <svg viewBox="0 0 24 24" aria-hidden="true" class="auth-login-input-icon"><path fill="currentColor" d="M17 8h-1V6a4 4 0 0 0-8 0v2H7a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V10a2 2 0 0 0-2-2zm-6 8.73V18a1 1 0 0 0 2 0v-1.27a2 2 0 1 0-2 0zM10 8V6a2 2 0 0 1 4 0v2z"/></svg>
                        <input id="password" class="auth-login-input" type="password" name="password" required autocomplete="current-password" placeholder="Enter your password" />
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="auth-login-meta">
                    <label for="remember_me" class="auth-login-remember">
                        <input id="remember_me" type="checkbox" name="remember">
                        <span>Remember me</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="auth-login-forgot" href="{{ route('password.request') }}">Forgot Password?</a>
                    @endif
                </div>

                <button type="submit" class="auth-login-button">Sign In</button>
            </form>
        </div>
    </section>
</x-guest-layout>
