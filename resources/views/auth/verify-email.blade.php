<x-guest-layout>
    <div>
        <h2 class="auth-title">Verify your email</h2>
        <p class="auth-subtitle">
            Before continuing, open your inbox and click the verification link we sent.
            If it did not arrive, you can send a new one below.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="auth-success mt-4">
            A new verification link has been sent to your email address.
        </div>
    @endif

    <div class="mt-6 space-y-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <button type="submit" class="auth-button w-full">Resend Verification Email</button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="auth-link w-full text-center">
                Log out
            </button>
        </form>
    </div>
</x-guest-layout>
