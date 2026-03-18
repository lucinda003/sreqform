<x-guest-layout>
    <div>
        <h2 class="auth-title">Reset request</h2>
        <p class="auth-subtitle">Enter your email and we will send a reset link.</p>
    </div>

    <x-auth-session-status class="auth-success mt-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="mt-6 space-y-4">
        @csrf

        <div>
            <label for="email" class="auth-label">Email</label>
            <input id="email" class="auth-input" type="email" name="email" value="{{ old('email') }}" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <button type="submit" class="auth-button w-full">Email Password Reset Link</button>
        </div>

        <p class="text-center text-sm text-slate-600">
            <a class="auth-link" href="{{ route('login') }}">Back to login</a>
        </p>
    </form>
</x-guest-layout>
