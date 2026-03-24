<x-guest-layout>
    <header class="auth-login-topbar">
        <div class="auth-login-brand">
            <img src="{{ asset('images/dohlogo.svg') }}" alt="DOH Logo" class="auth-login-brand-logo">
            <div>
                <h1 class="auth-login-brand-title">DEPARTMENT OF HEALTH</h1>
                <p class="auth-login-brand-subtitle">Secure Access Portal</p>
            </div>
        </div>

        <div class="auth-login-top-actions">
            <a href="{{ route('service-requests.create') }}" class="auth-login-register">Service Request Form</a>
            <a href="{{ route('service-requests.track') }}" class="auth-login-register">Track Request</a>
        </div>
    </header>

    <section class="auth-login-card-wrap auth-track-page">
        <div class="auth-login-card">
            <div class="auth-login-card-head">
                <img src="{{ asset('images/dohlogo.svg') }}" alt="DOH Logo" class="auth-login-card-logo">
                <h2 class="auth-login-card-title">EMAIL FOR<br>REFERENCE CODE</h2>
            </div>

            <div class="auth-login-divider"></div>

            <p class="mb-3 text-sm text-slate-700">
                Request submitted. Please enter your email to receive updates for reference
                <span class="font-semibold">{{ $serviceRequest->reference_code }}</span>.
            </p>

            <form method="POST" action="{{ $signedActionUrl }}" class="auth-login-form">
                @csrf

                <div>
                    <label for="email_address" class="auth-login-label">Email Address</label>
                    <div class="auth-login-input-wrap">
                        <input
                            id="email_address"
                            name="email_address"
                            type="email"
                            value="{{ old('email_address', $serviceRequest->email_address) }}"
                            class="auth-login-input"
                            placeholder="name@example.com"
                            required
                        >
                    </div>
                    <x-input-error :messages="$errors->get('email_address')" class="mt-1" />
                </div>

                <button type="submit" class="auth-login-button">Save Email and Continue</button>
            </form>
        </div>
    </section>
</x-guest-layout>
