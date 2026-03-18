<x-guest-layout>
    <div>
        <h2 class="auth-title">Create your account</h2>
        <p class="auth-subtitle">Set up your profile and start building.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-4">
        @csrf

        <div>
            <label for="name" class="auth-label">Name</label>
            <input id="name" class="auth-input" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div>
            <label for="email" class="auth-label">Email</label>
            <input id="email" class="auth-input" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <label for="password" class="auth-label">Password</label>
            <input id="password" class="auth-input" type="password" name="password" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <label for="password_confirmation" class="auth-label">Confirm password</label>
            <input id="password_confirmation" class="auth-input" type="password" name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="pt-1">
            <button type="submit" class="auth-button w-full">Register</button>
        </div>

        <p class="text-center text-sm text-slate-600">
            Already registered?
            <a class="auth-link" href="{{ route('login') }}">Sign in</a>
        </p>
    </form>
</x-guest-layout>
