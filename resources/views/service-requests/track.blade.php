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
            <a href="{{ route('login') }}" class="auth-login-register">Admin Login</a>
        </div>
    </header>

    <section class="auth-login-card-wrap">
        <div class="auth-login-card">
            <div class="auth-login-card-head">
                <img src="{{ asset('images/dohlogo.svg') }}" alt="DOH Logo" class="auth-login-card-logo">
                <h2 class="auth-login-card-title">TRACK YOUR<br>REQUEST</h2>
            </div>

            <div class="auth-login-divider"></div>

            <form method="GET" action="{{ route('service-requests.track') }}" class="auth-login-form">
                <input type="hidden" name="view" value="1">
                <div>
                    <label for="reference_code" class="auth-login-label">Reference Code</label>
                    <div class="auth-login-input-wrap">
                        <input
                            id="reference_code"
                            name="reference_code"
                            value="{{ $referenceCode }}"
                            class="auth-login-input"
                            placeholder="Enter reference code"
                            required
                        >
                    </div>
                </div>

                <button type="submit" class="auth-login-button">Track Request</button>
            </form>

            @if (session('status'))
                <div class="mt-4 rounded-xl border border-emerald-300 bg-emerald-50 px-3 py-2 text-sm font-medium text-emerald-700">
                    {{ session('status') }}
                </div>
            @endif

            @if ($referenceCode !== '')
                <div class="mt-4 rounded-xl border border-teal-200 bg-white/80 p-3 text-sm text-slate-700">
                    @if ($serviceRequest)
                        <p><span class="font-semibold">Reference:</span> {{ $serviceRequest->reference_code }}</p>
                        <p class="mt-1"><span class="font-semibold">Status:</span> <span class="inline-block rounded-full bg-teal-50 px-2 py-0.5 text-xs font-semibold text-teal-700">{{ strtoupper($serviceRequest->status) }}</span></p>
                        <p class="mt-1"><span class="font-semibold">Date Filed:</span> {{ $serviceRequest->request_date->format('M d, Y') }}</p>
                        <p class="mt-1"><span class="font-semibold">Office:</span> {{ $serviceRequest->office }}</p>
                    @else
                        <p class="text-rose-600">No request found for that reference code.</p>
                    @endif
                </div>
            @endif
        </div>
    </section>
</x-guest-layout>
