@php View::share('pageTitle', 'Change Password'); @endphp
<x-guest-layout>
    <x-public-nav-header active="sign-in" />

    <section class="auth-login-card-wrap auth-register-card-wrap">
        <div class="auth-login-card auth-register-card">
            <div class="auth-login-card-head">
                <img src="{{ asset('images/dohlogo.svg') }}" alt="DOH Logo" class="auth-login-card-logo auth-register-card-logo">
                <h2 class="auth-login-card-title auth-register-card-title">DEPARTMENT OF<br>HEALTH</h2>
            </div>

            <div class="auth-login-divider"></div>

            <h3 class="auth-aux-title">Set Your Password</h3>
            <p class="auth-aux-copy">Create a new password for your account</p>

            <form method="POST" action="{{ route('change-password.store') }}" class="auth-login-form auth-register-form mt-4">
                @csrf

                <div>
                    <label for="password" class="auth-login-label auth-register-label">New Password</label>
                    <div class="auth-login-input-wrap">
                        <svg viewBox="0 0 24 24" aria-hidden="true" class="auth-login-input-icon"><path fill="currentColor" d="M17 8h-1V6a4 4 0 0 0-8 0v2H7a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V10a2 2 0 0 0-2-2zm-6 8.73V18a1 1 0 0 0 2 0v-1.27a2 2 0 1 0-2 0zM10 8V6a2 2 0 0 1 4 0v2z"/></svg>
                        <input id="password" class="auth-login-input auth-register-input" type="password" name="password" required autocomplete="new-password" placeholder="Enter new password (min. 8 characters)" />
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div>
                    <label for="password_confirmation" class="auth-login-label auth-register-label">Confirm Password</label>
                    <div class="auth-login-input-wrap">
                        <svg viewBox="0 0 24 24" aria-hidden="true" class="auth-login-input-icon"><path fill="currentColor" d="M17 8h-1V6a4 4 0 0 0-8 0v2H7a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V10a2 2 0 0 0-2-2zm-6 8.73V18a1 1 0 0 0 2 0v-1.27a2 2 0 1 0-2 0zM10 8V6a2 2 0 0 1 4 0v2z"/></svg>
                        <input id="password_confirmation" class="auth-login-input auth-register-input" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm your password" />
                    </div>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <button type="submit" class="auth-login-button auth-register-button">Set Password</button>
            </form>
        </div>
    </section>
</x-guest-layout>
