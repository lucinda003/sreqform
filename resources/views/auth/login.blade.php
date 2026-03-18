<x-guest-layout>
    <div>
        <h2 class="auth-title">Welcome back</h2>
        <p class="auth-subtitle">Sign in to continue to your dashboard.</p>
    </div>

    <x-auth-session-status class="auth-success mt-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4">
        @csrf

        <div>
            <label for="email" class="auth-label">Email</label>
            <input id="email" class="auth-input" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <label for="password" class="auth-label">Password</label>
            <input id="password" class="auth-input" type="password" name="password" required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between gap-3">
            <label for="remember_me" class="inline-flex items-center text-sm text-slate-600">
                <input id="remember_me" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-slate-800 focus:ring-cyan-300" name="remember">
                <span class="ms-2">Remember me</span>
            </label>

            @if (Route::has('password.request'))
                <a class="auth-link" href="{{ route('password.request') }}">Forgot password?</a>
            @endif
        </div>

        <div class="pt-1">
            <button type="submit" class="auth-button w-full">Log in</button>
        </div>

        <p class="text-center text-sm text-slate-600">
            New here?
            <a class="auth-link" href="{{ route('register') }}">Create an account</a>
        </p>
    </form>
</x-guest-layout>
