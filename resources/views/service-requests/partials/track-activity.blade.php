<div class="trk-activity-card">
    <div class="trk-activity-head">
        <p class="trk-activity-title">Latest Update</p>
        <span class="trk-activity-state">Auto refresh</span>
    </div>

    @forelse ($actionUpdates as $actionUpdate)
        @php
            $actionMeta = trim(implode(' | ', array_filter([
                trim(implode(' ', array_filter([
                    (string) data_get($actionUpdate, 'action_date_label', ''),
                    (string) data_get($actionUpdate, 'action_time_label', ''),
                ]))),
                data_get($actionUpdate, 'action_officer') ? 'Handled by ' . data_get($actionUpdate, 'action_officer') : '',
            ])));
        @endphp
        <div class="trk-activity-item">
            <p class="trk-activity-text">{{ (string) data_get($actionUpdate, 'action_taken', '') }}</p>
            @if ($actionMeta !== '')
                <p class="trk-activity-meta">{{ $actionMeta }}</p>
            @endif
        </div>
    @empty
        <p class="trk-activity-empty">No updates yet.</p>
    @endforelse
</div>
