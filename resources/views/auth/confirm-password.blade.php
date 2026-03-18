<x-guest-layout>
    <div>
        <h2 class="auth-title">Confirm your password</h2>
        <p class="auth-subtitle">This action requires an additional confirmation step.</p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="mt-6 space-y-4">
        @csrf

        <div>
            <label for="password" class="auth-label">Password</label>
            <input id="password" class="auth-input" type="password" name="password" required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <button type="submit" class="auth-button w-full">Confirm</button>
        </div>
    </form>
</x-guest-layout>
