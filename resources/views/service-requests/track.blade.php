<x-guest-layout>
<style>
    .trk-wrap {
        font-family: 'Poppins', 'Space Grotesk', sans-serif;
        min-height: 100vh;
        padding-bottom: 2rem;
    }

    /* ── Search card ── */
    .trk-search-card {
        background: rgba(255,255,255,0.82);
        border: 1px solid rgba(147,176,173,0.6);
        border-radius: 1.1rem;
        padding: 1.5rem 1.35rem 1.25rem;
        box-shadow: 0 8px 18px rgba(26,73,69,0.18);
        max-width: 420px;
        margin: 0 auto;
    }

    /* ── Status card ── */
    .trk-status-card {
        position: relative;
        z-index: 5;
        background: rgba(255,255,255,0.88);
        border: 1px solid rgba(147,176,173,0.5);
        border-radius: 1.1rem;
        padding: 1.6rem 1.8rem 1.4rem;
        box-shadow: 0 8px 24px rgba(26,73,69,0.18);
        max-width: 820px;
        margin: 0 auto;
    }

    .trk-ref-title {
        font-size: 1.55rem;
        font-weight: 700;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: #1a5c54;
        text-align: center;
        margin: 0 0 4px;
    }

    .trk-ref-code {
        font-size: 0.78rem;
        font-weight: 600;
        letter-spacing: 0.1em;
        text-align: center;
        color: #2d7a6e;
        margin: 0 0 1.4rem;
    }

    /* ── Stepper ── */
    .trk-stepper {
        display: flex;
        align-items: flex-start;
        justify-content: center;
        gap: 0;
        margin-bottom: 1.6rem;
        position: relative;
    }

    .trk-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
        position: relative;
    }

    .trk-step-icon {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
        margin-bottom: 6px;
        position: relative;
        z-index: 2;
    }

    .trk-step-icon.done    { background: #d1fae5; color: #059669; }
    .trk-step-icon.active  { background: #fef9c3; color: #d97706; }
    .trk-step-icon.rejected { background: #fee2e2; color: #dc2626; }
    .trk-step-icon.pending { background: #f1f5f9; color: #94a3b8; }

    .trk-step-label {
        font-size: 9px;
        font-weight: 600;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        text-align: center;
        color: #475569;
        max-width: 72px;
        line-height: 1.3;
        margin-bottom: 8px;
    }

    .trk-step-label.pending { color: #94a3b8; }
    .trk-step-label.rejected { color: #dc2626; }

    .trk-nodes-row {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 6px;
        width: 100%;
    }

    .trk-nodes-row .trk-step:not(:last-child)::after {
        content: '';
        position: absolute;
        top: 19px;
        left: 50%;
        width: 100%;
        height: 3px;
        background: #cbd5e1;
        z-index: 0;
    }

    .trk-nodes-row .trk-step.done:not(:last-child)::after {
        background: #059669;
    }

    .trk-nodes-row .trk-step.rejected:not(:last-child)::after {
        background: #dc2626;
    }

    .trk-node {
        width: 22px;
        height: 22px;
        border-radius: 50%;
        border: 3px solid #cbd5e1;
        background: #fff;
        flex-shrink: 0;
        position: relative;
        z-index: 2;
    }
    .trk-node.done    { background: #059669; border-color: #059669; }
    .trk-node.active  { background: #fff; border-color: #0f766e; border-width: 3px; box-shadow: 0 0 0 3px rgba(15,118,110,0.2); }
    .trk-node.rejected { background: #dc2626; border-color: #dc2626; }
    .trk-node.pending { background: #fff; border-color: #cbd5e1; }

    .trk-step-date {
        font-size: 9px;
        color: #64748b;
        text-align: center;
        line-height: 1.4;
    }
    .trk-step-date.estimated { color: #94a3b8; }
    .trk-step-date strong {
        display: block;
        font-size: 8px;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #94a3b8;
    }

    /* ── Detail box ── */
    .trk-detail-box {
        background: rgba(15,118,110,0.07);
        border: 1px solid rgba(15,118,110,0.18);
        border-radius: 0.75rem;
        padding: 1rem 1.2rem;
        margin-bottom: 1.2rem;
    }

    .trk-detail-row {
        display: grid;
        grid-template-columns: 180px 1fr;
        gap: 6px 12px;
        padding: 6px 0;
        border-bottom: 1px solid rgba(15,118,110,0.1);
        align-items: baseline;
        font-size: 13px;
    }
    .trk-detail-row:last-child { border-bottom: none; }

    .trk-detail-label {
        font-weight: 700;
        color: #1e293b;
        font-size: 13px;
    }

    .trk-detail-value {
        color: #334155;
        font-size: 13px;
    }

    .trk-status-badge {
        font-size: 1.35rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        color: #0f766e;
    }

    /* ── Action bar ── */
    .trk-action-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 1rem;
        flex-wrap: wrap;
        gap: 8px;
    }

    .trk-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-family: 'Poppins', sans-serif;
        font-size: 12px;
        font-weight: 600;
        padding: 8px 18px;
        border-radius: 8px;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.18s;
        border: 1.5px solid transparent;
    }

    .trk-btn-back {
        background: #fff;
        border-color: #cbd5e1;
        color: #475569;
    }
    .trk-btn-back:hover { border-color: #94a3b8; color: #1e293b; }

    .trk-btn-contact {
        background: #1e293b;
        color: #fff;
        border-color: #1e293b;
    }
    .trk-btn-contact:hover { background: #0f172a; }

    .trk-btn-print {
        background: #fff;
        border-color: #cbd5e1;
        color: #1e293b;
    }
    .trk-btn-print:hover { border-color: #94a3b8; background: #f8fafc; }

    .trk-btn-right { display: flex; gap: 8px; flex-wrap: wrap; }

    /* ── Summary card (no request found / basic) ── */
    .trk-summary-card {
        border: 1px solid #e2e8f0;
        border-radius: 0.85rem;
        background: #fff;
        padding: 1rem;
        box-shadow: 0 2px 8px rgba(15,23,42,0.06);
    }

    .trk-chat-card {
        border-top: 1px solid #dbe4ea;
        background: #fff;
        padding: 0.9rem;
    }

    .trk-chat-title {
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #115e59;
        margin: 0 0 0.6rem;
    }

    .trk-chat-list {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        max-height: 240px;
        overflow-y: auto;
        padding-right: 0.3rem;
    }

    .trk-chat-item {
        display: flex;
    }

    .trk-chat-item.admin {
        justify-content: flex-start;
    }

    .trk-chat-item.requestor {
        justify-content: flex-end;
    }

    .trk-chat-bubble {
        max-width: min(560px, 88%);
        border-radius: 0.75rem;
        padding: 0.55rem 0.7rem;
        border: 1px solid #d1d5db;
    }

    .trk-chat-bubble.admin {
        background: #f1f5f9;
        border-color: #cbd5e1;
    }

    .trk-chat-bubble.requestor {
        background: #ecfeff;
        border-color: #99f6e4;
    }

    .trk-chat-meta {
        font-size: 0.65rem;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        color: #64748b;
        margin: 0 0 0.2rem;
        font-weight: 700;
    }

    .trk-chat-text {
        margin: 0;
        font-size: 0.83rem;
        color: #0f172a;
        white-space: pre-wrap;
        word-break: break-word;
    }

    .trk-chat-empty {
        font-size: 0.82rem;
        color: #64748b;
        margin: 0;
    }

    .trk-chat-input {
        width: 100%;
        margin-top: 0.7rem;
        min-height: 76px;
        border: 1px solid #cbd5e1;
        border-radius: 0.65rem;
        padding: 0.65rem 0.72rem;
        font-size: 0.84rem;
        resize: vertical;
    }

    .trk-chat-input:focus {
        outline: none;
        border-color: #0f766e;
        box-shadow: 0 0 0 3px rgba(15,118,110,0.12);
    }

    .trk-chat-submit {
        margin-top: 0.5rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: none;
        border-radius: 0.65rem;
        padding: 0.48rem 0.95rem;
        background: #115e59;
        color: #fff;
        font-size: 0.77rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        cursor: pointer;
    }

    .trk-chat-submit:hover {
        background: #0f4b47;
    }

    .trk-chat-locked {
        margin-top: 0.7rem;
        border-radius: 0.65rem;
        border: 1px solid #cbd5e1;
        background: #f8fafc;
        padding: 0.55rem 0.65rem;
        font-size: 0.78rem;
        color: #334155;
    }

    .trk-chat-request-status {
        margin-top: 0.6rem;
        font-size: 0.76rem;
        font-weight: 600;
        color: #475569;
    }

    .trk-chat-request-status.pending {
        color: #92400e;
    }

    .trk-chat-request-status.accepted {
        color: #065f46;
    }

    .trk-chat-request-status.rejected {
        color: #b91c1c;
    }

    .trk-chat-request-status.none {
        color: #475569;
    }

    .trk-chat-widget {
        position: fixed;
        right: 18px;
        bottom: 16px;
        width: min(370px, calc(100vw - 24px));
        border: 1px solid #cbd5e1;
        border-radius: 14px;
        box-shadow: 0 24px 40px rgba(15,23,42,0.24);
        background: #fff;
        overflow: hidden;
        z-index: 60;
        display: none;
    }

    .trk-chat-widget.open {
        display: block;
    }

    .trk-chat-widget-head {
        background: linear-gradient(135deg, #0f766e 0%, #134e4a 100%);
        color: #fff;
        padding: 0.75rem 0.85rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    .trk-chat-widget-title {
        margin: 0;
        font-size: 0.83rem;
        font-weight: 700;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }

    .trk-chat-widget-subtitle {
        margin: 0.15rem 0 0;
        font-size: 0.72rem;
        color: rgba(255,255,255,0.85);
    }

    .trk-chat-widget-close {
        width: 28px;
        height: 28px;
        border-radius: 999px;
        border: 1px solid rgba(255,255,255,0.5);
        background: rgba(255,255,255,0.12);
        color: #fff;
        font-size: 16px;
        line-height: 1;
        cursor: pointer;
    }

    .trk-chat-widget-close:hover {
        background: rgba(255,255,255,0.22);
    }

    @media (max-width: 640px) {
        .trk-chat-widget {
            left: 10px;
            right: 10px;
            width: auto;
            bottom: 10px;
        }
    }
</style>

@if ($trackAccessRequired && $trackAccessGranted && $trackAccessExpiresAt && $serviceRequest)
    @php
        $trackRefreshAfterSeconds = max(1, ((int) $trackAccessExpiresAt) - now()->timestamp + 1);
    @endphp
    <meta http-equiv="refresh" content="{{ $trackRefreshAfterSeconds }};url={{ route('service-requests.track', ['reference_code' => $serviceRequest->reference_code]) }}">
@endif

<div
    class="trk-wrap"
    data-track-access-expires-at="{{ $trackAccessExpiresAt ?? '' }}"
    data-track-reference-code="{{ $serviceRequest?->reference_code ?? '' }}"
>
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

    {{-- Search form --}}
    <section style="position:relative; z-index:5; max-width:420px; width:100%; margin: 1.4rem auto 1.2rem; padding: 0 1rem;">
           @if (! ($referenceCode !== '' && $serviceRequest && (! $trackAccessRequired || $trackAccessGranted)))
           <div class="trk-search-card">
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
                        <input id="reference_code" name="reference_code" value="{{ $referenceCode }}"
                            class="auth-login-input" placeholder="Enter reference number" required>
                    </div>
                </div>
                <button type="submit" class="auth-login-button">Proceed</button>
                @if ($referenceCode === '')
                    <p class="auth-track-separator"><span>No reference number yet?</span></p>
                    <a href="{{ route('service-requests.create') }}" class="auth-login-secondary">Create Service Request</a>
                @endif
            </form>

            @if ($serviceRequest && $trackAccessRequired && ! $trackAccessGranted)
                <div class="mt-4 rounded-xl border border-sky-200 bg-sky-50/90 p-3">
                    <p class="text-sm font-semibold text-slate-700">Verification required before proceeding.</p>
                    <p class="mt-1 text-xs text-slate-600">A 6-digit code will be sent to {{ $maskedTrackEmail }}.</p>

                    <form method="POST" action="{{ route('service-requests.track.verify.send-code', ['referenceCode' => $serviceRequest->reference_code]) }}" class="mt-3">
                        @csrf
                        <button type="submit" class="auth-login-secondary w-full">Send Code to Email</button>
                    </form>

                    <form method="POST" action="{{ route('service-requests.track.verify', ['referenceCode' => $serviceRequest->reference_code]) }}" class="auth-login-form mt-3">
                        @csrf
                        <div>
                            <label for="track_code" class="auth-login-label">Verification Code</label>
                            <div class="auth-login-input-wrap">
                                <input
                                    id="track_code"
                                    name="code"
                                    type="text"
                                    inputmode="numeric"
                                    autocomplete="one-time-code"
                                    maxlength="6"
                                    pattern="[0-9]{6}"
                                    value="{{ old('code') }}"
                                    class="auth-login-input"
                                    placeholder="Enter 6-digit code"
                                    required
                                >
                            </div>
                            <x-input-error :messages="$errors->get('code')" class="mt-1" />
                        </div>
                        <button type="submit" class="auth-login-button">Verify and Continue</button>
                    </form>
                </div>
            @endif

            @if (session('status'))
                <div class="mt-4 rounded-xl border border-emerald-300 bg-emerald-50 px-3 py-2 text-sm font-medium text-emerald-700">
                    {{ session('status') }}
                </div>
            @endif
        </div>
            @endif
    </section>

    {{-- Status tracking card --}}
    @if ($referenceCode !== '' && (! $trackAccessRequired || $trackAccessGranted))
        <section style="position:relative; z-index:5; padding: 0 1rem 2rem; max-width: 860px; margin: 0 auto;">
            @if (session('status'))
                <div class="mb-4 rounded-xl border border-emerald-300 bg-emerald-50 px-3 py-2 text-sm font-medium text-emerald-700">
                    {{ session('status') }}
                </div>
            @endif

            @if ($serviceRequest)
                @php
                    $trackTime = trim((string) $serviceRequest->time_received);
                    if ($trackTime !== '') {
                        try {
                            $trackTime = \Carbon\Carbon::createFromFormat('H:i', $trackTime)->format('g:i A');
                        } catch (\Throwable $e) {
                            try {
                                $trackTime = \Carbon\Carbon::createFromFormat('H:i:s', $trackTime)->format('g:i:s A');
                            } catch (\Throwable $e2) {}
                        }
                    }

                    $statusRaw = strtolower((string) $serviceRequest->status);
                    $isCheckingStage = in_array($statusRaw, ['reviewing', 'checking'], true);
                    $isApprovedStage = $statusRaw === 'approved';
                    $isRejectedStage = $statusRaw === 'rejected';
                    $isBeyondPendingStage = in_array($statusRaw, ['approved', 'rejected', 'completed', 'closed'], true);
                    $chatLocked = in_array($statusRaw, ['approved', 'rejected', 'completed', 'closed'], true);

                    $steps = [
                        ['key' => 'submitted',  'label' => 'Request Submitted',   'icon' => '✓'],
                        ['key' => 'pending',    'label' => $isBeyondPendingStage ? 'Done' : ($isCheckingStage ? 'Checking' : 'Pending'), 'icon' => '⏱'],
                        ['key' => 'approved',   'label' => $isRejectedStage ? 'Rejected' : 'Approved', 'icon' => $isRejectedStage ? '✕' : '✓'],
                        ['key' => 'completed',  'label' => $isRejectedStage ? 'Closed' : ($isApprovedStage ? 'Completed' : 'Completed/Closed'), 'icon' => $isRejectedStage ? '✕' : '✓'],
                    ];

                    $stepOrder = ['submitted' => 0, 'pending' => 1, 'reviewing' => 1, 'checking' => 1, 'approved' => 2, 'rejected' => 2, 'completed' => 3, 'closed' => 3];
                    $currentStep = $stepOrder[$statusRaw] ?? 0;

                    $statusLabel = match($statusRaw) {
                        'submitted' => 'SUBMITTED',
                        'pending', 'reviewing', 'checking' => 'PENDING',
                        'forwarded' => 'FORWARDED TO DEPT.',
                        'approved' => 'APPROVED',
                        'completed', 'closed' => 'COMPLETED',
                        default => strtoupper($serviceRequest->status),
                    };

                    $stepDateTimes = [
                        1 => $isCheckingStage
                            ? ($serviceRequest->checking_at ?? $serviceRequest->pending_at)
                            : $serviceRequest->pending_at,
                        2 => $isRejectedStage
                            ? $serviceRequest->rejected_at
                            : $serviceRequest->approved_at,
                        3 => $isRejectedStage
                            ? ($serviceRequest->rejected_at ?? $serviceRequest->completed_at)
                            : ($serviceRequest->completed_at ?? ($isApprovedStage ? $serviceRequest->approved_at : null)),
                    ];
                @endphp

                <div class="trk-status-card">
                    <h2 class="trk-ref-title">REQUEST TRACKING STATUS</h2>
                    <p class="trk-ref-code">REFERENCE CODE NUMBER: {{ $serviceRequest->reference_code }}</p>

                    {{-- Icons row --}}
                    <div class="trk-stepper">
                        @foreach ($steps as $i => $step)
                            @php
                                if ($isCheckingStage) {
                                    $state = $i <= 1 ? 'done' : 'pending';
                                } elseif ($isRejectedStage) {
                                    $state = $i <= 1 ? 'done' : 'rejected';
                                } elseif ($isApprovedStage) {
                                    $state = 'done';
                                } else {
                                    $state = $i < $currentStep ? 'done' : ($i === $currentStep ? 'active' : 'pending');
                                }
                            @endphp
                            <div class="trk-step">
                                <div class="trk-step-icon {{ $state }}">{{ $step['icon'] }}</div>
                                <div class="trk-step-label {{ in_array($state, ['pending', 'rejected'], true) ? $state : '' }}">{{ $step['label'] }}</div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Nodes + connectors row --}}
                    <div class="trk-nodes-row">
                        @foreach ($steps as $i => $step)
                            @php
                                if ($isCheckingStage) {
                                    $state = $i <= 1 ? 'done' : 'pending';
                                } elseif ($isRejectedStage) {
                                    $state = $i <= 1 ? 'done' : 'rejected';
                                } elseif ($isApprovedStage) {
                                    $state = 'done';
                                } else {
                                    $state = $i < $currentStep ? 'done' : ($i === $currentStep ? 'active' : 'pending');
                                }
                            @endphp
                            <div class="trk-step {{ $state }}">
                                <div class="trk-node {{ $state }}"></div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Dates row --}}
                    <div class="trk-stepper" style="margin-bottom:1.4rem;">
                        @foreach ($steps as $i => $step)
                            @php
                                if ($isCheckingStage) {
                                    $state = $i <= 1 ? 'done' : 'pending';
                                } elseif ($isRejectedStage) {
                                    $state = $i <= 1 ? 'done' : 'rejected';
                                } elseif ($isApprovedStage) {
                                    $state = 'done';
                                } else {
                                    $state = $i < $currentStep ? 'done' : ($i === $currentStep ? 'active' : 'pending');
                                }
                            @endphp
                            <div class="trk-step">
                                @if ($i === 0)
                                    <div class="trk-step-date">
                                        {{ $serviceRequest->request_date->format('F j, Y') }}<br>
                                        {{ $trackTime }}
                                    </div>
                                @elseif (! $isBeyondPendingStage && ! $isCheckingStage)
                                    <div class="trk-step-date estimated">
                                        <strong>Estimated:</strong>
                                        —
                                    </div>
                                @else
                                    @php $stepDateTime = $i === 0 ? null : ($stepDateTimes[$i] ?? null); @endphp
                                    <div class="trk-step-date">
                                        @if ($i === 0)
                                            {{ $serviceRequest->request_date->format('F j, Y') }}<br>
                                            {{ $trackTime }}
                                        @elseif ($stepDateTime)
                                            {{ $stepDateTime->format('F j, Y') }}<br>
                                            {{ $stepDateTime->format('g:i A') }}
                                        @else
                                            —
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    {{-- Detail box --}}
                    <div class="trk-detail-box">
                        <div class="trk-detail-row">
                            <span class="trk-detail-label">Office:</span>
                            <span class="trk-detail-value">{{ $serviceRequest->office ?: 'N/A' }}</span>
                        </div>
                        <div class="trk-detail-row">
                            <span class="trk-detail-label">Request Category:</span>
                            <span class="trk-detail-value">{{ $serviceRequest->request_category ?: 'N/A' }}</span>
                        </div>
                        <div class="trk-detail-row">
                            <span class="trk-detail-label">Application System:</span>
                            <span class="trk-detail-value">{{ $serviceRequest->application_system_name ?: 'N/A' }}</span>
                        </div>
                        @if ($serviceRequest->latest_notes ?? null)
                        <div class="trk-detail-row">
                            <span class="trk-detail-label">Latest Notes:</span>
                            <span class="trk-detail-value">{{ $serviceRequest->latest_notes }}</span>
                        </div>
                        @endif
                        @if ($serviceRequest->estimated_completion ?? null)
                        <div class="trk-detail-row">
                            <span class="trk-detail-label">Estimated To Completion:</span>
                            <span class="trk-detail-value">{{ $serviceRequest->estimated_completion }}</span>
                        </div>
                        @endif
                        @if ($serviceRequest->processed_by ?? null)
                        <div class="trk-detail-row">
                            <span class="trk-detail-label">Processed By:</span>
                            <span class="trk-detail-value">{{ $serviceRequest->processed_by }}</span>
                        </div>
                        @endif
                    </div>

                    {{-- Action bar --}}
                    <div class="trk-action-bar">
                        <a href="{{ route('service-requests.track') }}" class="trk-btn trk-btn-back">← Back</a>
                        <div class="trk-btn-right">
                            @if (! $chatLocked)
                                <button
                                    type="button"
                                    class="trk-btn trk-btn-contact"
                                    data-chat-toggle
                                    data-chat-access="{{ $chatAccepted ? 'accepted' : ($chatStatus !== null && $chatStatus !== '' ? $chatStatus : 'none') }}"
                                    data-chat-request-endpoint="{{ route('service-requests.track.chat-request', ['referenceCode' => $serviceRequest->reference_code]) }}"
                                >
                                    <svg width="13" height="13" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 10c0-4.4 3.6-8 8-8s8 3.6 8 8-3.6 8-8 8H2l2-2"/></svg>
                                    <span data-chat-toggle-label>
                                        @if ($chatAccepted)
                                            Contact Admin Personnel
                                        @elseif ($chatStatus === 'pending')
                                            Chat Request Pending
                                        @elseif ($chatStatus === 'rejected')
                                            Request Chat Again
                                        @else
                                            Request Chat with Admin
                                        @endif
                                    </span>
                                </button>
                            @endif
                            <a href="{{ route('service-requests.track.view', ['referenceCode' => $serviceRequest->reference_code]) }}" target="_blank" class="trk-btn trk-btn-print">
                                <svg width="13" height="13" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="2" width="12" height="5" rx="1"/><path d="M4 7H2a1 1 0 00-1 1v6a1 1 0 001 1h2v3h12v-3h2a1 1 0 001-1V8a1 1 0 00-1-1h-2"/></svg>
                                Print Status Report
                            </a>
                        </div>
                    </div>

                    @if (! $chatLocked)
                        @php
                            $chatStatusNotice = match ($chatStatus) {
                                'accepted' => 'Chat request accepted. You can now contact admin personnel.',
                                'pending' => 'Chat request sent. Waiting for admin approval.',
                                'rejected' => 'Previous chat request was declined. You can request again.',
                                default => 'Request chat approval first before messaging admin personnel.',
                            };

                            $chatStatusClass = match ($chatStatus) {
                                'accepted' => 'accepted',
                                'pending' => 'pending',
                                'rejected' => 'rejected',
                                default => 'none',
                            };
                        @endphp
                        <p class="trk-chat-request-status {{ $chatStatusClass }}" data-chat-request-status data-chat-request-state="{{ $chatStatusClass }}">{{ $chatStatusNotice }}</p>
                    @endif
                </div>

                @if (! $chatLocked)
                    <div class="trk-chat-widget" data-chat-widget>
                        <div class="trk-chat-widget-head">
                            <div>
                                <p class="trk-chat-widget-title">Contact Admin Personnel</p>
                                <p class="trk-chat-widget-subtitle">Messenger-style support chat</p>
                            </div>
                            <button type="button" class="trk-chat-widget-close" data-chat-close aria-label="Close chat">×</button>
                        </div>

                        <div class="trk-chat-card">
                            <div class="trk-chat-list" data-chat-list data-chat-endpoint="{{ route('service-requests.track.messages.index', ['referenceCode' => $serviceRequest->reference_code]) }}">
                                @forelse ($chatMessages as $chatMessage)
                                    @php
                                        $isAdminMessage = strtolower((string) $chatMessage->sender_type) === 'admin';
                                        $senderLabel = $isAdminMessage
                                            ? ('Admin' . (filled($chatMessage->senderUser?->name) ? ' - ' . $chatMessage->senderUser->name : ''))
                                            : 'Requestor';
                                    @endphp
                                    <div class="trk-chat-item {{ $isAdminMessage ? 'admin' : 'requestor' }}">
                                        <div class="trk-chat-bubble {{ $isAdminMessage ? 'admin' : 'requestor' }}">
                                            <p class="trk-chat-meta">{{ $senderLabel }} • {{ $chatMessage->created_at?->format('M j, Y g:i A') }}</p>
                                            <p class="trk-chat-text">{{ $chatMessage->message }}</p>
                                        </div>
                                    </div>
                                @empty
                                    <p class="trk-chat-empty">No chat messages yet.</p>
                                @endforelse
                            </div>

                            <form method="POST" action="{{ route('service-requests.track.messages.store', ['referenceCode' => $serviceRequest->reference_code]) }}" data-chat-enter-form>
                                @csrf
                                <textarea name="message" class="trk-chat-input" placeholder="Type your message for admin personnel..." maxlength="1000" required>{{ old('message') }}</textarea>
                                <x-input-error :messages="$errors->get('message')" class="mt-1" />
                                <p class="mt-1 hidden text-xs text-rose-600" data-chat-error></p>
                                <p class="mt-1 text-[11px] text-slate-500">Press Enter to send. Use Shift+Enter for a new line.</p>
                                <button type="submit" class="trk-chat-submit">Send Message</button>
                            </form>
                        </div>
                    </div>
                @endif

            @else
                <div style="position:relative; z-index:5; max-width:420px; margin: 0 auto;">
                    <div class="rounded-xl border border-rose-200 bg-rose-50/90 px-3 py-2 text-rose-700 text-sm">
                        No request found for that reference code.
                    </div>
                </div>
            @endif
        </section>
    @endif
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const trackWrap = document.querySelector('.trk-wrap');
        const trackBaseUrl = '{{ route('service-requests.track') }}';
        const trackReferenceCode = trackWrap ? String(trackWrap.dataset.trackReferenceCode || '') : '';
        const trackAccessExpiry = trackWrap ? Number(trackWrap.dataset.trackAccessExpiresAt || '') : 0;
        const chatForms = document.querySelectorAll('[data-chat-enter-form]');
        const chatWidget = document.querySelector('[data-chat-widget]');
        const chatOpenButtons = document.querySelectorAll('[data-chat-toggle]');
        const chatCloseButton = document.querySelector('[data-chat-close]');
        const chatStatusLine = document.querySelector('[data-chat-request-status]');

        const redirectToTrackVerification = function () {
            const params = new URLSearchParams();

            if (trackReferenceCode !== '') {
                params.set('reference_code', trackReferenceCode);
            }

            window.location.href = params.toString() !== ''
                ? (trackBaseUrl + '?' + params.toString())
                : trackBaseUrl;
        };

        const checkTrackAccessExpiry = function () {
            if (trackAccessExpiry <= 0) {
                return false;
            }

            const nowUnix = Math.floor(Date.now() / 1000);
            if (nowUnix >= trackAccessExpiry) {
                redirectToTrackVerification();
                return true;
            }

            return false;
        };

        if (trackAccessExpiry > 0) {
            if (!checkTrackAccessExpiry()) {
                const expiryWatcher = window.setInterval(function () {
                    if (checkTrackAccessExpiry()) {
                        window.clearInterval(expiryWatcher);
                    }
                }, 1000);

                window.addEventListener('focus', checkTrackAccessExpiry);
                document.addEventListener('visibilitychange', function () {
                    if (document.visibilityState === 'visible') {
                        checkTrackAccessExpiry();
                    }
                });
            }
        }

        const openChatWidget = function () {
            if (chatWidget) {
                chatWidget.classList.add('open');
            }
        };

        const closeChatWidget = function () {
            if (chatWidget) {
                chatWidget.classList.remove('open');
            }
        };

        const setChatStatusLine = function (state, message) {
            if (!chatStatusLine) {
                return;
            }

            const normalizedState = ['accepted', 'pending', 'rejected', 'none'].includes(state) ? state : 'none';
            chatStatusLine.classList.remove('accepted', 'pending', 'rejected', 'none');
            chatStatusLine.classList.add(normalizedState);
            chatStatusLine.dataset.chatRequestState = normalizedState;
            chatStatusLine.textContent = message;
        };

        const setChatToggleLabel = function (button, state) {
            const labelEl = button.querySelector('[data-chat-toggle-label]');
            if (!labelEl) {
                return;
            }

            if (state === 'accepted') {
                labelEl.textContent = 'Contact Admin Personnel';
                return;
            }

            if (state === 'pending') {
                labelEl.textContent = 'Chat Request Pending';
                return;
            }

            if (state === 'rejected') {
                labelEl.textContent = 'Request Chat Again';
                return;
            }

            labelEl.textContent = 'Request Chat with Admin';
        };

        const normalizeChatState = function (state) {
            return ['accepted', 'pending', 'rejected', 'none'].includes(state) ? state : 'none';
        };

        const defaultStatusMessage = function (state) {
            if (state === 'accepted') {
                return 'Chat request accepted. You can now contact admin personnel.';
            }

            if (state === 'pending') {
                return 'Chat request sent. Waiting for admin approval.';
            }

            if (state === 'rejected') {
                return 'Previous chat request was declined. You can request again.';
            }

            return 'Request chat approval first before messaging admin personnel.';
        };

        const applyChatAccessState = function (state, customMessage, autoOpen) {
            const normalizedState = normalizeChatState(String(state || 'none').toLowerCase());

            chatOpenButtons.forEach(function (button) {
                button.dataset.chatAccess = normalizedState;
                setChatToggleLabel(button, normalizedState);
            });

            setChatStatusLine(normalizedState, customMessage || defaultStatusMessage(normalizedState));

            if (autoOpen && normalizedState === 'accepted') {
                openChatWidget();
            }
        };

        const requestChatAccess = async function (button) {
            const requestEndpoint = button.dataset.chatRequestEndpoint || '';
            if (requestEndpoint === '') {
                return;
            }

            button.disabled = true;
            button.style.opacity = '0.7';

            try {
                const body = new URLSearchParams();
                body.set('_token', csrfToken);

                const response = await fetch(requestEndpoint, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: body.toString(),
                });

                if (!response.ok) {
                    setChatStatusLine('none', 'Unable to submit chat request right now. Please try again.');
                    return;
                }

                const payload = await response.json();
                const nextStateRaw = String(payload.status || 'pending').toLowerCase();
                const nextState = normalizeChatState(nextStateRaw === '' ? 'none' : nextStateRaw);
                const currentState = normalizeChatState(String(button.dataset.chatAccess || 'none').toLowerCase());
                applyChatAccessState(nextState, payload.message || null, currentState !== 'accepted' && nextState === 'accepted');
            } catch (error) {
                setChatStatusLine('none', 'Unable to submit chat request right now. Please try again.');
            } finally {
                button.disabled = false;
                button.style.opacity = '1';
            }
        };

        if (chatOpenButtons.length > 0) {
            const initialState = normalizeChatState(String(chatOpenButtons[0].dataset.chatAccess || 'none').toLowerCase());
            applyChatAccessState(initialState, null, false);
        }

        chatOpenButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                const chatAccessState = String(button.dataset.chatAccess || 'none').toLowerCase();

                if (chatAccessState === 'accepted') {
                    openChatWidget();
                    return;
                }

                requestChatAccess(button);
            });
        });

        if (chatCloseButton) {
            chatCloseButton.addEventListener('click', closeChatWidget);
        }

        const escapeHtml = function (value) {
            return String(value)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        };

        chatForms.forEach(function (form) {
            const textarea = form.querySelector('textarea[name="message"]');
            const chatCard = form.closest('.trk-chat-card');
            const chatList = chatCard ? chatCard.querySelector('[data-chat-list]') : null;
            const chatEndpoint = chatList ? chatList.dataset.chatEndpoint : '';
            const errorBox = form.querySelector('[data-chat-error]');

            if (!textarea || !chatList || chatEndpoint === '') {
                return;
            }

            const renderMessages = function (messages, scrollToBottom) {
                if (!Array.isArray(messages) || messages.length === 0) {
                    chatList.innerHTML = '<p class="trk-chat-empty">No chat messages yet.</p>';
                    return;
                }

                chatList.innerHTML = messages.map(function (message) {
                    const senderType = String(message.sender_type || '').toLowerCase() === 'admin' ? 'admin' : 'requestor';
                    const senderLabel = escapeHtml(message.sender_label || '');
                    const createdAt = escapeHtml(message.created_at_label || '');
                    const text = escapeHtml(message.message || '').replace(/\n/g, '<br>');

                    return '<div class="trk-chat-item ' + senderType + '">' +
                        '<div class="trk-chat-bubble ' + senderType + '">' +
                        '<p class="trk-chat-meta">' + senderLabel + ' • ' + createdAt + '</p>' +
                        '<p class="trk-chat-text">' + text + '</p>' +
                        '</div>' +
                        '</div>';
                }).join('');

                if (scrollToBottom) {
                    chatList.scrollTop = chatList.scrollHeight;
                }
            };

            const loadMessages = async function (scrollToBottom) {
                try {
                    const response = await fetch(chatEndpoint, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });

                    if (!response.ok) {
                        return;
                    }

                    const payload = await response.json();
                    const polledStateRaw = payload.chat_accepted
                        ? 'accepted'
                        : String(payload.chat_status || 'none').toLowerCase();
                    const polledState = normalizeChatState(polledStateRaw === '' ? 'none' : polledStateRaw);
                    const currentState = chatOpenButtons.length > 0
                        ? normalizeChatState(String(chatOpenButtons[0].dataset.chatAccess || 'none').toLowerCase())
                        : 'none';

                    applyChatAccessState(polledState, null, currentState !== 'accepted' && polledState === 'accepted');
                    renderMessages(payload.messages || [], scrollToBottom);
                } catch (error) {
                    // Keep the last rendered list if background refresh fails.
                }
            };

            const sendMessage = async function () {
                const messageValue = textarea.value.trim();
                if (messageValue === '') {
                    return;
                }

                if (errorBox) {
                    errorBox.classList.add('hidden');
                    errorBox.textContent = '';
                }

                const body = new URLSearchParams();
                body.set('_token', csrfToken);
                body.set('message', messageValue);

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: body.toString(),
                    });

                    if (response.ok) {
                        textarea.value = '';
                        openChatWidget();
                        await loadMessages(true);
                        return;
                    }

                    if (response.status === 422) {
                        const payload = await response.json();
                        const messageError = payload?.errors?.message?.[0] || 'Unable to send message.';

                        if (errorBox) {
                            errorBox.textContent = messageError;
                            errorBox.classList.remove('hidden');
                            return;
                        }
                    }

                    if (response.status === 403) {
                        const payload = await response.json();
                        const messageError = payload?.message || 'Chat is hidden until admin accepts your request.';

                        if (errorBox) {
                            errorBox.textContent = messageError;
                            errorBox.classList.remove('hidden');
                        }
                        setChatStatusLine('pending', messageError);
                        return;
                    }

                    form.submit();
                } catch (error) {
                    form.submit();
                }
            };

            form.addEventListener('submit', function (event) {
                event.preventDefault();
                sendMessage();
            });

            textarea.addEventListener('keydown', function (event) {
                if (event.key === 'Enter' && !event.shiftKey) {
                    event.preventDefault();
                    sendMessage();
                }
            });

            loadMessages(true);
            window.setInterval(function () {
                loadMessages(false);
            }, 4000);
        });

        if (chatWidget && (document.querySelector('[data-chat-error]:not(.hidden)') || @json(old('message', '')) !== '')) {
            openChatWidget();
        }
    });
</script>
</x-guest-layout>