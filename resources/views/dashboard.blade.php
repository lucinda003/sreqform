<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="auth-title">Admin Dashboard</h2>
                <p class="auth-subtitle">Quick view of requests, statuses, and actions.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('service-requests.index') }}" class="rounded-xl border border-teal-200 bg-white px-4 py-2.5 text-sm font-semibold text-teal-900 transition hover:bg-teal-50">Manage Requests</a>
                <a href="{{ route('service-requests.create') }}" class="rounded-xl bg-teal-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-teal-600">New Request</a>
            </div>
        </div>
    </x-slot>

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
                <div class="w-full max-w-[170px] rounded-xl border border-slate-200 bg-white p-4">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.1em] text-slate-500">Pending</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900">{{ number_format($pendingRequests) }}</p>
                </div>
                <div class="w-full max-w-[170px] rounded-xl border border-slate-200 bg-white p-4">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.1em] text-slate-500">Approved</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900">{{ number_format($approvedRequests) }}</p>
                </div>
                <div class="w-full max-w-[170px] rounded-xl border border-slate-200 bg-white p-4">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.1em] text-slate-500">Rejected</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900">{{ number_format($rejectedRequests) }}</p>
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
                                <tr class="border-t border-slate-200/90 hover:bg-slate-50">
                                    <td class="py-3 pe-4 font-semibold text-slate-900 break-all">
                                        <a href="{{ route('service-requests.show', $request) }}" class="auth-link">{{ $request->reference_code }}</a>
                                    </td>
                                    <td class="py-3 pe-4 text-slate-700 break-all">{{ $request->office }}</td>
                                    <td class="py-3 pe-4 text-slate-700 break-all">{{ $request->contact_last_name }}, {{ $request->contact_first_name }}</td>
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
                    <h3 class="text-base font-semibold text-slate-900">Quick Actions</h3>
                    <div class="mt-3 space-y-2">
                        <a href="{{ route('service-requests.create') }}" class="auth-button w-full">Create Request</a>
                        <a href="{{ route('service-requests.index') }}" class="block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-center text-sm font-medium text-slate-700 transition hover:border-slate-500 hover:text-slate-900">Manage Requests</a>
                        <a href="{{ route('profile.edit') }}" class="block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-center text-sm font-medium text-slate-700 transition hover:border-slate-500 hover:text-slate-900">Profile Settings</a>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <h3 class="text-base font-semibold text-slate-900">Workflow Guide</h3>
                    <p class="mt-2 text-sm text-slate-600">Status flow is active: Pending, Checking, Approved, and Rejected. Open a request to update KMITS fields and status.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
