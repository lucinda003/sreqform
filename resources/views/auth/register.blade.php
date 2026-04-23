@php View::share('pageTitle', 'Register'); @endphp
<x-guest-layout>
    <x-public-nav-header active="sign-in" />

    <section class="auth-login-card-wrap auth-register-card-wrap">
        <div class="auth-login-card auth-register-card">
            <div class="auth-login-card-head">
                <img src="{{ asset('images/dohlogo.svg') }}" alt="DOH Logo" class="auth-login-card-logo auth-register-card-logo">
                <h2 class="auth-login-card-title auth-register-card-title">DEPARTMENT OF<br>HEALTH</h2>
            </div>

            <div class="auth-login-divider"></div>

            <form method="POST" action="{{ route('register') }}" class="auth-login-form auth-register-form">
                @csrf

                <div>
                    <label for="email" class="auth-login-label auth-register-label">Email</label>
                    <div class="auth-login-input-wrap">
                        <svg viewBox="0 0 24 24" aria-hidden="true" class="auth-login-input-icon"><path fill="currentColor" d="M20 4H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2m0 4l-8 5L4 8V6l8 5l8-5z"/></svg>
                        <input id="email" class="auth-login-input auth-register-input" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="Enter your email" />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div>
                    <label for="name" class="auth-login-label auth-register-label">Username</label>
                    <div class="auth-login-input-wrap">
                        <svg viewBox="0 0 24 24" aria-hidden="true" class="auth-login-input-icon"><path fill="currentColor" d="M12 12a5 5 0 1 0-5-5a5 5 0 0 0 5 5zm0 2c-4.42 0-8 1.79-8 4v2h16v-2c0-2.21-3.58-4-8-4z"/></svg>
                        <input id="name" class="auth-login-input auth-register-input" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Enter your username" />
                    </div>
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <label for="password" class="auth-login-label auth-register-label">Password</label>
                    <div class="auth-login-input-wrap">
                        <svg viewBox="0 0 24 24" aria-hidden="true" class="auth-login-input-icon"><path fill="currentColor" d="M17 8h-1V6a4 4 0 0 0-8 0v2H7a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V10a2 2 0 0 0-2-2zm-6 8.73V18a1 1 0 0 0 2 0v-1.27a2 2 0 1 0-2 0zM10 8V6a2 2 0 0 1 4 0v2z"/></svg>
                        <input id="password" class="auth-login-input auth-register-input" type="password" name="password" required autocomplete="new-password" placeholder="Enter your password" />
                    </div>
                    <input id="password_confirmation" type="hidden" name="password_confirmation">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="auth-login-meta">
                    <label for="register_remember_me" class="auth-login-remember">
                        <input id="register_remember_me" type="checkbox">
                        <span>Remember me</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="auth-login-forgot" href="{{ route('password.request') }}">Forgot Password?</a>
                    @endif
                </div>

                <button type="submit" class="auth-login-button auth-register-button">Login to Account</button>
            </form>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const passwordInput = document.getElementById('password');
            const confirmInput = document.getElementById('password_confirmation');

            if (!passwordInput || !confirmInput) {
                return;
            }

            const syncConfirmation = function () {
                confirmInput.value = passwordInput.value;
            };

            passwordInput.addEventListener('input', syncConfirmation);
            syncConfirmation();
        });
    </script>
</x-guest-layout>
