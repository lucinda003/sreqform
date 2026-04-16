<x-app-layout>
    <x-slot name="header" style="display:none;"></x-slot>

    <x-db2-shell>
        <div class="mx-auto w-full max-w-6xl py-6 space-y-5">
            @php
                $rangeLabels = [
                    'all' => 'All',
                    'today' => 'Today',
                    'week' => 'This Week',
                ];
                $currentRangeLabel = $rangeLabels[$range] ?? 'All';
            @endphp

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-500">Overview</p>
                        <h3 class="mt-1 text-xl font-bold text-slate-900">Service Request Activity</h3>
                    </div>

                    <div class="inline-flex rounded-xl border border-slate-200 bg-slate-50 p-1">
                        @foreach ($rangeLabels as $key => $label)
                            <a
                                href="{{ route('dashboard', ['range' => $key]) }}"
                                class="rounded-lg px-3 py-1.5 text-sm font-semibold transition {{ $range === $key ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-700 hover:bg-slate-100' }}"
                            >
                                {{ $label }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="rounded-xl border border-slate-200 bg-slate-50/50 p-4">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.1em] text-slate-500">Total ({{ $currentRangeLabel }})</p>
                        <p class="mt-1 text-3xl font-bold text-slate-900">{{ number_format($totalRequests) }}</p>
                    </div>
                    <div class="rounded-xl border border-slate-200 bg-slate-50/50 p-4">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.1em] text-slate-500">Today</p>
                        <p class="mt-1 text-3xl font-bold text-slate-900">{{ number_format($todayRequests) }}</p>
                    </div>
                    <div class="rounded-xl border border-slate-200 bg-slate-50/50 p-4">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.1em] text-slate-500">This Week</p>
                        <p class="mt-1 text-3xl font-bold text-slate-900">{{ number_format($thisWeekRequests) }}</p>
                    </div>
                    <div class="rounded-xl border border-slate-200 bg-white p-4">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.1em] text-slate-500">Active Offices</p>
                        <p class="mt-1 text-2xl font-bold text-slate-900">{{ number_format($uniqueOffices) }}</p>
                    </div>
                </div>

                <div class="mt-3 flex flex-wrap gap-3">
                    <a href="{{ route('service-requests.index', ['status' => 'pending']) }}" class="w-full max-w-[170px] rounded-xl border border-slate-200 bg-white p-4 transition hover:-translate-y-0.5 hover:border-amber-300 hover:bg-slate-50 hover:shadow-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-300">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.1em] text-slate-500">Pending</p>
                        <p class="mt-1 text-2xl font-bold text-slate-900">{{ number_format($pendingRequests) }}</p>
                    </a>
                    <a href="{{ route('service-requests.index', ['status' => 'checking']) }}" class="w-full max-w-[170px] rounded-xl border border-slate-200 bg-white p-4 transition hover:-translate-y-0.5 hover:border-sky-300 hover:bg-slate-50 hover:shadow-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-300">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.1em] text-slate-500">Checking</p>
                        <p class="mt-1 text-2xl font-bold text-slate-900">{{ number_format($checkingRequests) }}</p>
                    </a>
                    <a href="{{ route('service-requests.index', ['status' => 'approved']) }}" class="w-full max-w-[170px] rounded-xl border border-slate-200 bg-white p-4 transition hover:-translate-y-0.5 hover:border-emerald-300 hover:bg-slate-50 hover:shadow-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-300">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.1em] text-slate-500">Approved</p>
                        <p class="mt-1 text-2xl font-bold text-slate-900">{{ number_format($approvedRequests) }}</p>
                    </a>
                    <a href="{{ route('service-requests.index', ['status' => 'rejected']) }}" class="w-full max-w-[170px] rounded-xl border border-slate-200 bg-white p-4 transition hover:-translate-y-0.5 hover:border-rose-300 hover:bg-slate-50 hover:shadow-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-300">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.1em] text-slate-500">Rejected</p>
                        <p class="mt-1 text-2xl font-bold text-slate-900">{{ number_format($rejectedRequests) }}</p>
                    </a>
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
                                    <tr class="border-t border-slate-200/90 hover:bg-slate-50">
                                        <td class="py-3 pe-4 font-semibold text-slate-900 whitespace-nowrap">
                                            <a href="{{ route('service-requests.show', $request) }}" class="auth-link">{{ $request->reference_code }}</a>
                                        </td>
                                        <td class="py-3 pe-4 text-slate-700">{{ $request->office }}</td>
                                        <td class="py-3 pe-4 text-slate-700">{{ $request->contact_last_name }}, {{ $request->contact_first_name }}</td>
                                        <td class="py-3 pe-4">
                                            @php
                                                $statusClasses = match ($request->status) {
                                                    'checking' => 'border-sky-300 bg-sky-100 text-sky-800',
                                                    'approved' => 'border-emerald-300 bg-emerald-100 text-emerald-800',
                                                    'rejected' => 'border-rose-300 bg-rose-100 text-rose-800',
                                                    default => 'border-amber-300 bg-amber-100 text-amber-800',
                                                };
                                            @endphp
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
                        @php
                            $resolvedRequests = $approvedRequests + $rejectedRequests;
                            $pendingRatePrecise = $totalRequests > 0 ? (($pendingRequests / $totalRequests) * 100) : 0;
                            $approvalRatePrecise = $totalRequests > 0 ? (($approvedRequests / $totalRequests) * 100) : 0;
                            $rejectionRatePrecise = $totalRequests > 0 ? (($rejectedRequests / $totalRequests) * 100) : 0;

                            $pendingRate = (int) round($pendingRatePrecise);
                            $approvalRate = (int) round($approvalRatePrecise);
                            $rejectionRate = (int) round($rejectionRatePrecise);

                            $pendingBarWidth = $pendingRequests > 0 ? min(max($pendingRatePrecise, 4), 100) : 0;
                            $approvalBarWidth = $approvedRequests > 0 ? min(max($approvalRatePrecise, 4), 100) : 0;
                            $rejectionBarWidth = $rejectedRequests > 0 ? min(max($rejectionRatePrecise, 4), 100) : 0;
                        @endphp
                        <h3 class="text-base font-semibold text-slate-900">Status Snapshot</h3>
                        <div class="mt-3 space-y-3 text-sm text-slate-700">
                            <div>
                                <div class="flex items-center justify-between">
                                    <span>Pending</span>
                                    <span class="font-semibold">{{ number_format($pendingRequests) }} ({{ $pendingRate }}%)</span>
                                </div>
                                <div class="mt-1 h-2 rounded-full bg-slate-100">
                                    <div class="h-2 rounded-full" style="width: {{ number_format($pendingBarWidth, 2, '.', '') }}%; background-color: #f59e0b;"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex items-center justify-between">
                                    <span>Approved</span>
                                    <span class="font-semibold">{{ number_format($approvedRequests) }} ({{ $approvalRate }}%)</span>
                                </div>
                                <div class="mt-1 h-2 rounded-full bg-slate-100">
                                    <div class="h-2 rounded-full" style="width: {{ number_format($approvalBarWidth, 2, '.', '') }}%; background-color: #10b981;"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex items-center justify-between">
                                    <span>Rejected</span>
                                    <span class="font-semibold">{{ number_format($rejectedRequests) }} ({{ $rejectionRate }}%)</span>
                                </div>
                                <div class="mt-1 h-2 rounded-full bg-slate-100">
                                    <div class="h-2 rounded-full" style="width: {{ number_format($rejectionBarWidth, 2, '.', '') }}%; background-color: #f43f5e;"></div>
                                </div>
                            </div>
                                <div class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-600">
                                    Resolved requests: <span class="font-semibold text-slate-800">{{ number_format($resolvedRequests) }}</span><br>
                                    Request messages: <span class="font-semibold text-slate-800">{{ number_format($requestMessages ?? 0) }}</span>
                            </div>
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
    </x-db2-shell>
</x-app-layout>
