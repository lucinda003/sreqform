<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="auth-title">Dashboard</h2>
                <p class="auth-subtitle">DOH Service Request operations overview.</p>
            </div>
            <a href="{{ route('service-requests.create') }}" class="auth-button">New Request</a>
        </div>
    </x-slot>

    <div class="mx-auto w-full max-w-6xl py-6 space-y-5">
        <div class="rounded-2xl border border-white/70 bg-white/80 p-3 shadow-lg backdrop-blur-xl">
            <div class="flex flex-wrap items-center gap-2">
                @php
                    $rangeLabels = [
                        'all' => 'All',
                        'today' => 'Today',
                        'week' => 'This Week',
                    ];
                    $currentRangeLabel = $rangeLabels[$range] ?? 'All';
                @endphp

                @foreach ($rangeLabels as $key => $label)
                    <a
                        href="{{ route('dashboard', ['range' => $key]) }}"
                        class="rounded-full px-4 py-2 text-sm font-medium transition {{ $range === $key ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}"
                    >
                        {{ $label }}
                    </a>
                @endforeach

                <p class="ms-auto text-xs font-medium uppercase tracking-[0.12em] text-slate-500">Current View: {{ $currentRangeLabel }}</p>
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-7">
            <div class="rounded-2xl border border-white/70 bg-white/80 p-5 shadow-lg backdrop-blur-xl">
                <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">Requests ({{ $currentRangeLabel }})</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ number_format($totalRequests) }}</p>
            </div>

            <div class="rounded-2xl border border-white/70 bg-white/80 p-5 shadow-lg backdrop-blur-xl">
                <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">Today</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ number_format($todayRequests) }}</p>
            </div>

            <div class="rounded-2xl border border-white/70 bg-white/80 p-5 shadow-lg backdrop-blur-xl">
                <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">This Week</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ number_format($thisWeekRequests) }}</p>
            </div>

            <div class="rounded-2xl border border-white/70 bg-white/80 p-5 shadow-lg backdrop-blur-xl">
                <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">Active Offices</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ number_format($uniqueOffices) }}</p>
            </div>

            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5 shadow-lg backdrop-blur-xl">
                <p class="text-xs font-semibold uppercase tracking-[0.14em] text-amber-700">Pending</p>
                <p class="mt-2 text-3xl font-bold text-amber-800">{{ number_format($pendingRequests) }}</p>
            </div>

            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5 shadow-lg backdrop-blur-xl">
                <p class="text-xs font-semibold uppercase tracking-[0.14em] text-emerald-700">Approved</p>
                <p class="mt-2 text-3xl font-bold text-emerald-800">{{ number_format($approvedRequests) }}</p>
            </div>

            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-5 shadow-lg backdrop-blur-xl">
                <p class="text-xs font-semibold uppercase tracking-[0.14em] text-rose-700">Rejected</p>
                <p class="mt-2 text-3xl font-bold text-rose-800">{{ number_format($rejectedRequests) }}</p>
            </div>
        </div>

        <div class="grid gap-4 lg:grid-cols-3">
            <div class="rounded-2xl border border-white/70 bg-white/80 p-5 shadow-lg backdrop-blur-xl lg:col-span-2">
                <div class="flex items-center justify-between gap-2">
                    <h3 class="text-base font-semibold text-slate-900">Recent Service Requests ({{ $currentRangeLabel }})</h3>
                    <a href="{{ route('service-requests.index') }}" class="auth-link">View all</a>
                </div>

                <div class="mt-4 overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="text-left text-xs uppercase tracking-[0.1em] text-slate-500">
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
                                <tr class="border-t border-slate-200">
                                    <td class="py-3 pe-4 font-semibold text-slate-900">
                                        <a href="{{ route('service-requests.show', $request) }}" class="auth-link">{{ $request->reference_code }}</a>
                                    </td>
                                    <td class="py-3 pe-4 text-slate-700">{{ $request->office }}</td>
                                    <td class="py-3 pe-4 text-slate-700">{{ $request->contact_last_name }}, {{ $request->contact_first_name }}</td>
                                    <td class="py-3 pe-4">
                                        @php
                                            $statusClasses = match ($request->status) {
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
                <div class="rounded-2xl border border-white/70 bg-white/80 p-5 shadow-lg backdrop-blur-xl">
                    <h3 class="text-base font-semibold text-slate-900">Quick Actions</h3>
                    <div class="mt-3 space-y-2">
                        <a href="{{ route('service-requests.create') }}" class="auth-button w-full">Create Request</a>
                        <a href="{{ route('service-requests.index') }}" class="block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-center text-sm font-medium text-slate-700 transition hover:border-slate-500 hover:text-slate-900">Manage Requests</a>
                        <a href="{{ route('profile.edit') }}" class="block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-center text-sm font-medium text-slate-700 transition hover:border-slate-500 hover:text-slate-900">Profile Settings</a>
                    </div>
                </div>

                <div class="rounded-2xl border border-cyan-200 bg-cyan-50 p-5 shadow-lg backdrop-blur-xl">
                    <h3 class="text-base font-semibold text-cyan-900">Workflow Active</h3>
                    <p class="mt-2 text-sm text-cyan-800">Approval status tracking is enabled. You can now set each request as Pending, Approved, or Rejected.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
