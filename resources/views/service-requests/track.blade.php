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
            @if ($referenceCode === '')
                <a href="{{ route('login') }}" class="auth-login-register">Login</a>
            @else
                <a href="{{ route('service-requests.create') }}" class="auth-login-register">Create Service Request</a>
            @endif
        </div>
    </header>

    <section class="auth-login-card-wrap" style="max-width: 420px; width: 100%;">
        <div class="auth-login-card">
            <div class="auth-login-card-head">
                <h2 class="auth-login-card-title">TRACK REQUEST</h2>
            </div>

            <div class="auth-login-divider"></div>

            <form method="GET" action="{{ route('service-requests.track') }}" class="auth-login-form">
                <div>
                    <label for="reference_code" class="auth-login-label">Reference Number</label>
                    <div class="auth-login-input-wrap">
                        <svg xmlns="http://www.w3.org/2000/svg" class="auth-login-input-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <circle cx="9" cy="9" r="5"></circle>
                            <path d="M13 13l4 4"></path>
                        </svg>
                        <input
                            id="reference_code"
                            name="reference_code"
                            value="{{ $referenceCode }}"
                            class="auth-login-input"
                            placeholder="Enter reference number"
                            required
                        >
                    </div>
                </div>

                <button type="submit" class="auth-login-button">Proceed</button>

                @if ($referenceCode === '')
                    <p class="auth-track-separator"><span>No reference number yet?</span></p>

                    <a href="{{ route('service-requests.create') }}" class="auth-login-secondary">Create Service Request</a>
                @endif
            </form>

            @if (session('status'))
                <div class="mt-4 rounded-xl border border-emerald-300 bg-emerald-50 px-3 py-2 text-sm font-medium text-emerald-700">
                    {{ session('status') }}
                </div>
            @endif

            @if ($referenceCode !== '')
                <div class="mt-4 text-sm text-slate-700">
                    @if ($serviceRequest)
                        @php
                            $trackTime = trim((string) $serviceRequest->time_received);
                            if ($trackTime !== '') {
                                try {
                                    $trackTime = \Carbon\Carbon::createFromFormat('H:i', $trackTime)->format('g:i A');
                                } catch (\Throwable $exception) {
                                    try {
                                        $trackTime = \Carbon\Carbon::createFromFormat('H:i:s', $trackTime)->format('g:i:s A');
                                    } catch (\Throwable $innerException) {
                                        // Keep raw value if parsing fails.
                                    }
                                }
                            }

                            $statusClasses = match (strtolower((string) $serviceRequest->status)) {
                                'approved' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                                'rejected' => 'bg-rose-50 text-rose-800 border-rose-200',
                                'checking' => 'bg-sky-50 text-sky-800 border-sky-200',
                                default => 'bg-amber-50 text-amber-800 border-amber-200',
                            };
                        @endphp
                        <div class="rounded-xl border border-slate-300 bg-white p-4 shadow-sm">
                            <div class="flex items-center justify-between gap-3">
                                <p class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-600">Request Summary</p>
                                <span class="inline-flex items-center rounded-full border px-2.5 py-1 text-[11px] font-semibold uppercase {{ $statusClasses }}">
                                    {{ strtoupper($serviceRequest->status) }}
                                </span>
                            </div>

                            <div class="mt-3 grid gap-2 rounded-lg border border-slate-300 bg-slate-50 p-3 sm:grid-cols-2">
                                <div>
                                    <p class="text-[11px] font-semibold uppercase tracking-[0.08em] text-slate-500">Date/Time of Request</p>
                                    <p class="mt-1 font-medium text-slate-800">{{ $serviceRequest->request_date->format('m/d/Y') }}{{ $trackTime !== '' ? ' - ' : '' }}{{ $trackTime }}</p>
                                </div>

                                <div>
                                    <p class="text-[11px] font-semibold uppercase tracking-[0.08em] text-slate-500">Request Category</p>
                                    <p class="mt-1 font-medium text-slate-800">{{ $serviceRequest->request_category ?: 'N/A' }}</p>
                                </div>

                                <div class="sm:col-span-2">
                                    <p class="text-[11px] font-semibold uppercase tracking-[0.08em] text-slate-500">Application System Name</p>
                                    <p class="mt-1 font-medium text-slate-800">{{ $serviceRequest->application_system_name ?: 'N/A' }}</p>
                                </div>
                            </div>

                            <div class="mt-3 flex items-center justify-end gap-2">
                                <form method="POST" action="{{ route('service-requests.track.send-edit-link', ['referenceCode' => $serviceRequest->reference_code]) }}">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center rounded-md border border-sky-300 bg-sky-50 px-4 py-2 text-xs font-semibold uppercase tracking-[0.08em] text-sky-900 transition hover:border-sky-400 hover:bg-sky-100">Send Edit Link</button>
                                </form>
                                <a href="{{ route('service-requests.track.view', ['referenceCode' => $serviceRequest->reference_code]) }}" target="_blank" class="inline-flex items-center rounded-md border border-slate-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-[0.08em] text-slate-900 transition hover:border-slate-400 hover:bg-slate-50">Print Request Form</a>
                            </div>
                        </div>
                    @else
                        <div class="rounded-xl border border-rose-200 bg-rose-50/90 px-3 py-2 text-rose-700">
                            No request found for that reference code.
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </section>
</x-guest-layout>
