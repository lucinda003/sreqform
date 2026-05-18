<div class="mx-auto w-full max-w-6xl py-6 space-y-5" data-dashboard-panel-scope>
    @php
        $isSuperAdmin = strtolower(trim((string) (auth()->user()?->role ?? ''))) === 'super admin';
        $receiverStats = $receiverStats ?? collect();
        $receiverSummary = $receiverSummary ?? [
            'total' => 0,
            'received_open' => 0,
            'assigned_open' => 0,
        ];
        $rangeLabels = [
            'all' => 'All',
            'today' => 'Today',
            'week' => 'This Week',
        ];
        $currentRangeLabel = $rangeLabels[$range] ?? 'All';
        $statusRows = [
            ['label' => 'Pending', 'count' => $pendingRequests, 'color' => '#f59e0b'],
            ['label' => 'Checking', 'count' => $checkingRequests, 'color' => '#0ea5e9'],
            ['label' => 'Approved', 'count' => $approvedRequests, 'color' => '#10b981'],
        ];
        $topOfficeCount = (int) (($officeBreakdown ?? collect())->max() ?? 0);
        $topRegionCount = (int) (($regionBreakdown ?? collect())->max() ?? 0);
    @endphp

    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-500">Overview</p>
                <h3 class="mt-1 text-xl font-bold text-slate-900">Service Request Activity</h3>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <div class="inline-flex rounded-xl border border-slate-200 bg-slate-50 p-1">
                    @foreach ($rangeLabels as $key => $label)
                        <a
                            href="{{ route('dashboard', ['range' => $key]) }}"
                            data-dashboard-ajax-link
                            class="rounded-lg px-3 py-1.5 text-sm font-semibold transition {{ $range === $key ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-700 hover:bg-slate-100' }}"
                        >
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-xl border border-slate-200 bg-slate-50/70 p-4">
                <p class="text-[11px] font-semibold uppercase tracking-[0.1em] text-slate-500">Total ({{ $currentRangeLabel }})</p>
                <p class="mt-1 text-3xl font-bold text-slate-900">{{ number_format($totalRequests) }}</p>
            </div>
            <div class="rounded-xl border border-amber-200 bg-amber-50/70 p-4">
                <p class="text-[11px] font-semibold uppercase tracking-[0.1em] text-amber-700">Unresolved</p>
                <p class="mt-1 text-3xl font-bold text-slate-900">{{ number_format($unresolvedRequests ?? 0) }}</p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-slate-50/70 p-4">
                <p class="text-[11px] font-semibold uppercase tracking-[0.1em] text-slate-500">Today</p>
                <p class="mt-1 text-3xl font-bold text-slate-900">{{ number_format($todayRequests) }}</p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-4">
                <p class="text-[11px] font-semibold uppercase tracking-[0.1em] text-slate-500">Active Offices</p>
                <p class="mt-1 text-3xl font-bold text-slate-900">{{ number_format($uniqueOffices) }}</p>
            </div>
        </div>

        <div class="mt-3 grid gap-3 sm:grid-cols-3">
            <a href="{{ route('service-requests.index', ['status' => 'pending']) }}" class="rounded-xl border border-slate-200 bg-white p-4 transition hover:-translate-y-0.5 hover:border-amber-300 hover:bg-slate-50 hover:shadow-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-300">
                <p class="text-[11px] font-semibold uppercase tracking-[0.1em] text-slate-500">Pending</p>
                <p class="mt-1 text-2xl font-bold text-slate-900">{{ number_format($pendingRequests) }}</p>
            </a>
            <a href="{{ route('service-requests.index', ['status' => 'checking']) }}" class="rounded-xl border border-slate-200 bg-white p-4 transition hover:-translate-y-0.5 hover:border-sky-300 hover:bg-slate-50 hover:shadow-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-300">
                <p class="text-[11px] font-semibold uppercase tracking-[0.1em] text-slate-500">Checking</p>
                <p class="mt-1 text-2xl font-bold text-slate-900">{{ number_format($checkingRequests) }}</p>
            </a>
            <a href="{{ route('service-requests.index', ['status' => 'approved']) }}" class="rounded-xl border border-slate-200 bg-white p-4 transition hover:-translate-y-0.5 hover:border-emerald-300 hover:bg-slate-50 hover:shadow-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-300">
                <p class="text-[11px] font-semibold uppercase tracking-[0.1em] text-slate-500">Approved</p>
                <p class="mt-1 text-2xl font-bold text-slate-900">{{ number_format($approvedRequests) }}</p>
            </a>
        </div>
    </div>

    <div class="grid gap-4 lg:grid-cols-3">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm lg:col-span-2" data-dashboard-left-content="needs-attention">
            <div class="flex items-center justify-between gap-2">
                <h3 class="text-base font-semibold text-slate-900">Needs Attention</h3>
                <a href="{{ route('service-requests.index') }}" class="auth-link">View all</a>
            </div>

            <div class="mt-4 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="text-left text-xs uppercase tracking-[0.1em] text-slate-600">
                        <tr>
                            <th class="py-2 pe-4">Reference</th>
                            <th class="py-2 pe-4">Office</th>
                            <th class="py-2 pe-4">Status</th>
                            <th class="py-2 pe-4">Open Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse (($needsAttentionRequests ?? collect()) as $request)
                            @php
                                $currentUserId = (int) (auth()->id() ?? 0);
                                $isSuperAdmin = strtolower(trim((string) (auth()->user()?->role ?? ''))) === 'super admin';
                                $canOpen = $isSuperAdmin
                                    || ((int) ($request->received_by_user_id ?? 0) === $currentUserId
                                        && (string) $request->status !== 'approved');
                                $ageBase = $request->checking_at ?? $request->pending_at ?? $request->created_at;
                                $statusClasses = match ($request->status) {
                                    'checking' => 'border-sky-300 bg-sky-100 text-sky-800',
                                    default => 'border-amber-300 bg-amber-100 text-amber-800',
                                };
                            @endphp
                            <tr class="border-t border-slate-200/90 hover:bg-slate-50">
                                <td class="py-3 pe-4 font-semibold text-slate-900 whitespace-nowrap">
                                    @if ($canOpen)
                                        <a href="{{ route('service-requests.show', $request) }}" class="auth-link">{{ $request->reference_code }}</a>
                                    @else
                                        <span>{{ $request->reference_code }}</span>
                                    @endif
                                </td>
                                <td class="py-3 pe-4 text-slate-700">{{ $request->office }}</td>
                                <td class="py-3 pe-4">
                                    <span class="inline-flex rounded-full border px-2.5 py-1 text-xs font-semibold uppercase {{ $statusClasses }}">
                                        {{ $request->status }}
                                    </span>
                                </td>
                                <td class="py-3 pe-4 text-slate-700 whitespace-nowrap">{{ $ageBase ? $ageBase->diffForHumans(null, true) . ' open' : 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-6 text-center text-slate-500">No pending or checking requests.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($isSuperAdmin)
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm lg:col-span-2 hidden" data-dashboard-left-content="receivers-list">
                <div class="flex items-center justify-between gap-2 mb-4">
                    <h3 class="text-base font-semibold text-slate-900">Recent Receivers</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full w-full text-sm">
                        <thead class="bg-slate-100 text-xs uppercase tracking-[0.1em] text-slate-600">
                            <tr>
                                <th class="px-4 py-3 text-center">Name</th>
                                <th class="px-4 py-3 text-center">Role</th>
                                <th class="px-4 py-3 text-center">Department</th>
                                <th class="px-4 py-3 text-center">Received</th>
                                <th class="px-4 py-3 text-center">First Assign</th>
                                <th class="px-4 py-3 text-center">Last Assigned</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($receiverStats as $receiver)
                                @php
                                    $lastReceived = $receiver->last_received_at
                                        ? \Illuminate\Support\Carbon::parse($receiver->last_received_at)
                                        : null;
                                    $firstAssigned = $receiver->first_assigned_at
                                        ? \Illuminate\Support\Carbon::parse($receiver->first_assigned_at)
                                        : null;
                                    $lastAssigned = $receiver->last_assigned_at
                                        ? \Illuminate\Support\Carbon::parse($receiver->last_assigned_at)
                                        : null;
                                @endphp
                                <tr class="border-t border-slate-200 hover:bg-slate-50">
                                    <td class="px-4 py-3 text-center font-semibold text-slate-900">{{ $receiver->name }}</td>
                                    <td class="px-4 py-3 text-center text-slate-700 uppercase">{{ $receiver->role ?? 'No Role' }}</td>
                                    <td class="px-4 py-3 text-center text-slate-700 uppercase">{{ $receiver->department ?? 'N/A' }}</td>
                                    <td class="px-4 py-3 text-center text-slate-700">
                                        {{ $lastReceived ? $lastReceived->format('M d, Y g:i A') : 'N/A' }}
                                    </td>
                                    <td class="px-4 py-3 text-center text-slate-700">
                                        {{ $firstAssigned ? $firstAssigned->format('M d, Y g:i A') : 'N/A' }}
                                    </td>
                                    <td class="px-4 py-3 text-center text-slate-700">
                                        {{ $lastAssigned ? $lastAssigned->format('M d, Y g:i A') : 'N/A' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-slate-500">
                                        No active receivers yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <div class="space-y-4" data-dashboard-panel-root>
            @if ($isSuperAdmin)
                <div class="flex items-center justify-between rounded-xl border border-slate-200 bg-white px-3 py-2">
                    <div>
                        <p class="text-[10px] font-semibold uppercase tracking-[0.1em] text-slate-500">Side Panel</p>
                        <p class="text-xs text-slate-500">Switch view</p>
                    </div>
                    <div class="inline-flex rounded-xl border border-slate-200 bg-slate-50 p-1">
                        <button
                            type="button"
                            data-dashboard-panel-tab="snapshot"
                            aria-pressed="true"
                            class="rounded-lg px-3 py-1.5 text-xs font-semibold bg-slate-900 text-white shadow-sm"
                        >
                            Snapshot
                        </button>
                        <button
                            type="button"
                            data-dashboard-panel-tab="receivers"
                            aria-pressed="false"
                            class="rounded-lg px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-100"
                        >
                            Receivers
                        </button>
                    </div>
                </div>

                <div class="space-y-4 hidden" data-dashboard-panel-content="receivers">
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <h3 class="text-base font-semibold text-slate-900">Recent Receivers</h3>
                                <p class="text-xs text-slate-500">Users with active received requests</p>
                            </div>
                            <span class="rounded-full border border-slate-200 bg-slate-50 px-2.5 py-1 text-xs font-semibold text-slate-600">
                                {{ $receiverSummary['total'] ?? 0 }}
                            </span>
                        </div>
                        <div class="mt-4 space-y-3">
                            @forelse ($receiverStats->take(4) as $receiver)
                                @php
                                    $lastReceived = $receiver->last_received_at
                                        ? \Illuminate\Support\Carbon::parse($receiver->last_received_at)
                                        : null;
                                @endphp
                                <div class="flex items-start justify-between gap-3 border-b border-slate-100 pb-3 last:border-b-0 last:pb-0">
                                    <div>
                                        <div class="text-sm font-semibold text-slate-900">{{ $receiver->name }}</div>
                                        <div class="text-xs uppercase tracking-wide text-slate-500">{{ $receiver->role ?? 'No Role' }}</div>
                                        @if ($lastReceived)
                                            <div class="mt-1 text-xs text-slate-500">Last: {{ $lastReceived->format('M d, g:i A') }}</div>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm font-semibold text-slate-900">{{ $receiver->received_open_count ?? 0 }}</div>
                                        <div class="text-[10px] uppercase tracking-wide text-slate-500">Received</div>
                                    </div>
                                </div>
                            @empty
                                <div class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-600">
                                    No active receivers yet.
                                </div>
                            @endforelse
                        </div>
                        <a
                            href="{{ route('service-requests.index', ['receivers' => 'all']) }}"
                            class="mt-4 inline-flex items-center text-sm font-semibold text-slate-700 hover:text-slate-900"
                        >
                            View all receivers
                        </a>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <h3 class="text-base font-semibold text-slate-900">Open workload</h3>
                        <div class="mt-4 grid grid-cols-2 gap-3">
                            <div class="rounded-xl border border-slate-200 bg-slate-50/70 p-3">
                                <p class="text-[11px] font-semibold uppercase tracking-[0.1em] text-slate-500">Received</p>
                                <p class="mt-1 text-2xl font-bold text-slate-900">{{ $receiverSummary['received_open'] ?? 0 }}</p>
                            </div>
                            <div class="rounded-xl border border-slate-200 bg-slate-50/70 p-3">
                                <p class="text-[11px] font-semibold uppercase tracking-[0.1em] text-slate-500">Assigned</p>
                                <p class="mt-1 text-2xl font-bold text-slate-900">{{ $receiverSummary['assigned_open'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="space-y-4" data-dashboard-panel-content="snapshot">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <h3 class="text-base font-semibold text-slate-900">Status Snapshot</h3>
                    <div class="mt-3 space-y-3 text-sm text-slate-700">
                        @foreach ($statusRows as $row)
                            @php
                                $ratePrecise = $totalRequests > 0 ? (($row['count'] / $totalRequests) * 100) : 0;
                                $rate = (int) round($ratePrecise);
                                $barWidth = $row['count'] > 0 ? min(max($ratePrecise, 4), 100) : 0;
                            @endphp
                            <div>
                                <div class="flex items-center justify-between">
                                    <span>{{ $row['label'] }}</span>
                                    <span class="font-semibold">{{ number_format($row['count']) }} ({{ $rate }}%)</span>
                                </div>
                                <div class="mt-1 h-2 rounded-full bg-slate-100">
                                    <div class="h-2 rounded-full" style="width: {{ number_format($barWidth, 2, '.', '') }}%; background-color: {{ $row['color'] }};"></div>
                                </div>
                            </div>
                        @endforeach
                        <div class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-600">
                            Unresolved requests: <span class="font-semibold text-slate-800">{{ number_format($unresolvedRequests ?? 0) }}</span><br>
                            Request messages: <span class="font-semibold text-slate-800">{{ number_format($requestMessages ?? 0) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid gap-4 lg:grid-cols-3">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm lg:col-span-2">
            <div class="flex items-center justify-between gap-2">
                <h3 class="text-base font-semibold text-slate-900">Recent Service Requests</h3>
                <a href="{{ route('service-requests.index') }}" class="auth-link">View all</a>
            </div>

            <div class="mt-4 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="text-left text-xs uppercase tracking-[0.1em] text-slate-600">
                        <tr>
                            <th class="py-2 pe-4">Reference</th>
                            <th class="py-2 pe-4">Office</th>
                            <th class="py-2 pe-4">Contact Person</th>
                            <th class="py-2 pe-4">Status</th>
                            <th class="py-2 pe-4">Submitted</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentRequests as $request)
                            @php
                                $currentUserId = (int) (auth()->id() ?? 0);
                                $isSuperAdmin = strtolower(trim((string) (auth()->user()?->role ?? ''))) === 'super admin';
                                $canOpen = $isSuperAdmin
                                    || ((int) ($request->received_by_user_id ?? 0) === $currentUserId
                                        && (string) $request->status !== 'approved');
                                $statusClasses = match ($request->status) {
                                    'checking' => 'border-sky-300 bg-sky-100 text-sky-800',
                                    'approved' => 'border-emerald-300 bg-emerald-100 text-emerald-800',
                                    'rejected' => 'border-rose-300 bg-rose-100 text-rose-800',
                                    default => 'border-amber-300 bg-amber-100 text-amber-800',
                                };
                            @endphp
                            <tr class="border-t border-slate-200/90 hover:bg-slate-50">
                                <td class="py-3 pe-4 font-semibold text-slate-900 whitespace-nowrap">
                                    @if ($canOpen)
                                        <a href="{{ route('service-requests.show', $request) }}" class="auth-link">{{ $request->reference_code }}</a>
                                    @else
                                        <span class="text-slate-900">{{ $request->reference_code }}</span>
                                    @endif
                                </td>
                                <td class="py-3 pe-4 text-slate-700">{{ $request->office }}</td>
                                <td class="py-3 pe-4 text-slate-700">{{ $request->contact_last_name }}, {{ $request->contact_first_name }}</td>
                                <td class="py-3 pe-4">
                                    <span class="inline-flex rounded-full border px-2.5 py-1 text-xs font-semibold uppercase {{ $statusClasses }}">
                                        {{ $request->status }}
                                    </span>
                                </td>
                                <td class="py-3 pe-4 text-slate-700">{{ $request->created_at->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-6 text-center text-slate-500">No requests yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="space-y-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h3 class="text-base font-semibold text-slate-900">Requests By Office</h3>
                <div class="mt-3 space-y-3 text-sm text-slate-700">
                    @forelse (($officeBreakdown ?? collect()) as $office => $count)
                        @php
                            $officeWidth = $topOfficeCount > 0 ? (($count / $topOfficeCount) * 100) : 0;
                        @endphp
                        <div>
                            <div class="flex items-start justify-between gap-3">
                                <span class="leading-snug">{{ $office }}</span>
                                <span class="font-semibold">{{ number_format($count) }}</span>
                            </div>
                            <div class="mt-1 h-2 rounded-full bg-slate-100">
                                <div class="h-2 rounded-full bg-teal-600" style="width: {{ number_format($officeWidth, 2, '.', '') }}%;"></div>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-600">
                            No office activity yet.
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h3 class="text-base font-semibold text-slate-900">Requests By Region</h3>
                <div class="mt-3 space-y-3 text-sm text-slate-700">
                    @forelse (($regionBreakdown ?? collect()) as $region => $count)
                        @php
                            $regionWidth = $topRegionCount > 0 ? (($count / $topRegionCount) * 100) : 0;
                        @endphp
                        <div>
                            <div class="flex items-start justify-between gap-3">
                                <span class="leading-snug">{{ $region }}</span>
                                <span class="font-semibold">{{ number_format($count) }}</span>
                            </div>
                            <div class="mt-1 h-2 rounded-full bg-slate-100">
                                <div class="h-2 rounded-full bg-indigo-600" style="width: {{ number_format($regionWidth, 2, '.', '') }}%;"></div>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-600">
                            No region activity yet.
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between gap-2">
                    <h3 class="text-base font-semibold text-slate-900">Recent Activities</h3>
                    <a href="{{ route('service-requests.chat-requests') }}" class="auth-link text-xs">View all</a>
                </div>

                <div class="mt-3 space-y-2">
                    @forelse (($recentChatRequests ?? collect())->take(4) as $activity)
                        @php
                            $chatActivityStatus = strtolower((string) ($activity->contact_chat_status ?? 'pending'));
                            $activityStatusClass = match ($chatActivityStatus) {
                                'accepted' => 'bg-emerald-100 text-emerald-700',
                                'rejected' => 'bg-rose-100 text-rose-700',
                                default => 'bg-amber-100 text-amber-700',
                            };
                            $requestedAt = $activity->contact_chat_requested_at ?? $activity->updated_at;
                        @endphp
                        <div class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5">
                            <div class="flex items-center justify-between gap-2">
                                <span class="font-mono text-xs text-slate-800">{{ $activity->reference_code }}</span>
                                <span class="rounded-full px-2 py-1 text-[10px] font-semibold uppercase {{ $activityStatusClass }}">
                                    {{ $chatActivityStatus }}
                                </span>
                            </div>
                            <p class="mt-1 text-xs text-slate-600">{{ $activity->office }} | {{ $requestedAt ? $requestedAt->format('M d, Y g:i A') : 'N/A' }}</p>
                        </div>
                    @empty
                        <div class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-600">
                            No recent chat activities yet.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        const scope = document.querySelector('[data-dashboard-panel-scope]');
        if (!scope || scope.dataset.dashboardPanelBound === 'true') {
            return;
        }

        const root = scope.querySelector('[data-dashboard-panel-root]');
        if (!root) {
            return;
        }

        const tabButtons = Array.from(root.querySelectorAll('[data-dashboard-panel-tab]'));
        const panels = Array.from(root.querySelectorAll('[data-dashboard-panel-content]'));
        const leftContents = Array.from(document.querySelectorAll('[data-dashboard-left-content]'));
        
        if (tabButtons.length === 0 || panels.length === 0) {
            return;
        }

        scope.dataset.dashboardPanelBound = 'true';

        const switchButton = scope.querySelector('[data-dashboard-panel-switch]');
        const switchTrack = switchButton ? switchButton.querySelector('[data-dashboard-panel-switch-track]') : null;
        const switchThumb = switchButton ? switchButton.querySelector('[data-dashboard-panel-switch-thumb]') : null;

        const setSwitchState = function (isActive) {
            if (!switchButton || !switchTrack || !switchThumb) {
                return;
            }

            switchButton.setAttribute('aria-pressed', isActive ? 'true' : 'false');
            switchButton.classList.toggle('bg-slate-900', isActive);
            switchButton.classList.toggle('text-white', isActive);
            switchButton.classList.toggle('border-slate-900', isActive);
            switchButton.classList.toggle('hover:bg-slate-800', isActive);
            switchButton.classList.toggle('bg-white', !isActive);
            switchButton.classList.toggle('text-slate-700', !isActive);
            switchButton.classList.toggle('border-slate-200', !isActive);
            switchButton.classList.toggle('hover:bg-slate-100', !isActive);

            switchTrack.classList.toggle('bg-emerald-500', isActive);
            switchTrack.classList.toggle('bg-slate-200', !isActive);
            switchThumb.classList.toggle('translate-x-3', isActive);
        };

        const setActiveTab = function (target) {
            // Toggle right sidebar panels
            panels.forEach(function (panel) {
                const isActive = panel.dataset.dashboardPanelContent === target;
                panel.classList.toggle('hidden', !isActive);
            });

            // Toggle left column content
            leftContents.forEach(function (content) {
                const shouldShow = (target === 'snapshot' && content.dataset.dashboardLeftContent === 'needs-attention') ||
                                   (target === 'receivers' && content.dataset.dashboardLeftContent === 'receivers-list');
                content.classList.toggle('hidden', !shouldShow);
            });

            // Update tab button states
            tabButtons.forEach(function (button) {
                const isActive = button.dataset.dashboardPanelTab === target;
                button.setAttribute('aria-pressed', isActive ? 'true' : 'false');
                button.classList.toggle('bg-slate-900', isActive);
                button.classList.toggle('text-white', isActive);
                button.classList.toggle('shadow-sm', isActive);
                button.classList.toggle('text-slate-700', !isActive);
                button.classList.toggle('hover:bg-slate-100', !isActive);
            });

            setSwitchState(target === 'receivers');
        };

        let initialTab = 'snapshot';
        const pressedButton = tabButtons.find(function (button) {
            return button.getAttribute('aria-pressed') === 'true';
        });
        if (pressedButton) {
            initialTab = pressedButton.dataset.dashboardPanelTab || initialTab;
        }

        if (switchButton && switchButton.getAttribute('aria-pressed') === 'true') {
            initialTab = 'receivers';
        }

        setActiveTab(initialTab);

        tabButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                setActiveTab(button.dataset.dashboardPanelTab);
            });
        });

        if (switchButton) {
            switchButton.addEventListener('click', function () {
                const isActive = switchButton.getAttribute('aria-pressed') === 'true';
                setActiveTab(isActive ? 'snapshot' : 'receivers');
            });
        }
    })();
</script>
