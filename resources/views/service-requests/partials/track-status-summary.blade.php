@php
    $trackTime = trim((string) $serviceRequest->time_received);
    if ($trackTime !== '') {
        try {
            $trackTime = \Carbon\Carbon::createFromFormat('H:i', $trackTime)->format('g:i A');
        } catch (\Throwable $e) {
            try {
                $trackTime = \Carbon\Carbon::createFromFormat('H:i:s', $trackTime)->format('g:i:s A');
            } catch (\Throwable $e2) {
            }
        }
    }

    $statusRaw = strtolower((string) $serviceRequest->status);
    $isCheckingStage = in_array($statusRaw, ['reviewing', 'checking'], true);
    $isApprovedStage = $statusRaw === 'approved';
    $isOngoingStage = $statusRaw === 'ongoing';
    $isBeyondPendingStage = in_array($statusRaw, ['approved', 'ongoing', 'completed', 'closed'], true);

    $steps = [
        ['key' => 'submitted', 'label' => 'Request Submitted', 'icon' => '&#10003;'],
        ['key' => 'pending', 'label' => $isBeyondPendingStage ? 'Done' : ($isCheckingStage ? 'Checking' : 'Pending'), 'icon' => '&#9201;'],
        ['key' => 'approved', 'label' => 'Ongoing', 'icon' => '&#10003;'],
        ['key' => 'completed', 'label' => 'Closed', 'icon' => '&#10003;'],
    ];

    $stepOrder = ['submitted' => 0, 'pending' => 1, 'reviewing' => 1, 'checking' => 1, 'approved' => 2, 'ongoing' => 2, 'completed' => 3, 'closed' => 3];
    $currentStep = $stepOrder[$statusRaw] ?? 0;

    $stepDateTimes = [
        1 => $isCheckingStage
            ? ($serviceRequest->checking_at ?? $serviceRequest->pending_at)
            : $serviceRequest->pending_at,
        2 => $serviceRequest->ongoing_at ?? $serviceRequest->approved_at,
        3 => $serviceRequest->completed_at ?? (($isApprovedStage || $isOngoingStage) ? ($serviceRequest->ongoing_at ?? $serviceRequest->approved_at) : null),
    ];
@endphp

<h2 class="trk-ref-title">REQUEST TRACKING STATUS</h2>
<p class="trk-ref-code">REFERENCE CODE NUMBER: {{ $serviceRequest->reference_code }}</p>

<div class="trk-stepper">
    @foreach ($steps as $i => $step)
        @php
            if ($isCheckingStage) {
                $state = $i <= 1 ? 'done' : 'pending';
            } elseif ($isOngoingStage) {
                $state = $i <= 2 ? 'done' : 'pending';
            } elseif ($isApprovedStage) {
                $state = 'done';
            } else {
                $state = $i < $currentStep ? 'done' : ($i === $currentStep ? 'active' : 'pending');
            }
        @endphp
        <div class="trk-step">
            <div class="trk-step-icon {{ $state }}">{!! $step['icon'] !!}</div>
            <div class="trk-step-label {{ $state === 'pending' ? $state : '' }}">{{ $step['label'] }}</div>
        </div>
    @endforeach
</div>

<div class="trk-nodes-row">
    @foreach ($steps as $i => $step)
        @php
            if ($isCheckingStage) {
                $state = $i <= 1 ? 'done' : 'pending';
            } elseif ($isOngoingStage) {
                $state = $i <= 2 ? 'done' : 'pending';
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

<div class="trk-stepper" style="margin-bottom:1.4rem;">
    @foreach ($steps as $i => $step)
        <div class="trk-step">
            @if ($i === 0)
                <div class="trk-step-date">
                    {{ $serviceRequest->request_date->format('F j, Y') }}<br>
                    {{ $trackTime }}
                </div>
            @elseif (! $isBeyondPendingStage && ! $isCheckingStage)
                <div class="trk-step-date estimated">
                    <strong>Estimated:</strong>
                    &mdash;
                </div>
            @else
                @php $stepDateTime = $stepDateTimes[$i] ?? null; @endphp
                <div class="trk-step-date">
                    @if ($stepDateTime)
                        {{ $stepDateTime->format('F j, Y') }}<br>
                        {{ $stepDateTime->format('g:i A') }}
                    @else
                        &mdash;
                    @endif
                </div>
            @endif
        </div>
    @endforeach
</div>
