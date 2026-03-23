<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="auth-title">Service Request Details</h2>
                <p class="auth-subtitle">Reference: {{ $serviceRequest->reference_code }}</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('service-requests.edit', $serviceRequest) }}" class="rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:border-slate-500 hover:text-slate-900">Edit</a>
                <a href="{{ route('service-requests.pdf', $serviceRequest) }}" class="rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:border-slate-500 hover:text-slate-900">Download PDF</a>
                <a href="{{ route('service-requests.print', $serviceRequest) }}" target="_blank" class="auth-button">Print Form</a>
                <a href="{{ route('service-requests.index') }}" class="rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:border-slate-500 hover:text-slate-900">Back to List</a>
            </div>
        </div>
    </x-slot>

    <div class="mx-auto w-full max-w-6xl py-6">
        @if (session('status'))
            <div class="auth-success mb-4">{{ session('status') }}</div>
        @endif

        <div class="mb-4 rounded-2xl border border-white/70 bg-white/85 p-4 shadow-lg backdrop-blur-xl">
            <div class="flex flex-wrap items-center gap-3">
                <p class="text-sm font-semibold text-slate-700">Current Status:</p>
                @php
                    $statusClasses = match ($serviceRequest->status) {
                        'approved' => 'border-emerald-300 bg-emerald-100 text-emerald-800',
                        'rejected' => 'border-rose-300 bg-rose-100 text-rose-800',
                        default => 'border-amber-300 bg-amber-100 text-amber-800',
                    };
                @endphp
                <span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold uppercase {{ $statusClasses }}">
                    {{ $serviceRequest->status }}
                </span>

                @if ($canManageStatus)
                    <form method="POST" action="{{ route('service-requests.update-status', $serviceRequest) }}" class="ms-auto flex flex-wrap items-center gap-2">
                        @csrf
                        @method('PATCH')
                        <select name="status" class="auth-input !w-auto">
                            <option value="pending" @selected($serviceRequest->status === 'pending')>Pending</option>
                            <option value="approved" @selected($serviceRequest->status === 'approved')>Approved</option>
                            <option value="rejected" @selected($serviceRequest->status === 'rejected')>Rejected</option>
                        </select>
                        <button type="submit" class="auth-button">Update Status</button>
                    </form>
                @endif
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <div class="rounded-2xl border border-white/70 bg-white/85 p-5 shadow-lg backdrop-blur-xl">
                <h3 class="text-sm font-semibold uppercase tracking-[0.12em] text-slate-600">Reference</h3>
                <p class="mt-2 text-xl font-semibold text-slate-900">{{ $serviceRequest->reference_code }}</p>
                <p class="mt-1 text-sm text-slate-600">Date: {{ $serviceRequest->request_date->format('M d, Y') }}</p>
                <p class="mt-1 text-sm text-slate-600">Department: {{ $serviceRequest->department_code }}</p>
            </div>

            <div class="rounded-2xl border border-white/70 bg-white/85 p-5 shadow-lg backdrop-blur-xl">
                <h3 class="text-sm font-semibold uppercase tracking-[0.12em] text-slate-600">Contact Person</h3>
                <p class="mt-2 text-slate-900">
                    {{ $serviceRequest->contact_last_name }}, {{ $serviceRequest->contact_first_name }} {{ $serviceRequest->contact_middle_name }}
                </p>
                <p class="mt-2 text-sm text-slate-700">Office: {{ $serviceRequest->office }}</p>
                <p class="mt-1 text-sm text-slate-700">Address: {{ $serviceRequest->address }}</p>
                <p class="mt-1 text-sm text-slate-700">Landline: {{ $serviceRequest->landline ?: 'N/A' }}</p>
                <p class="mt-1 text-sm text-slate-700">Fax No: {{ $serviceRequest->fax_no ?: 'N/A' }}</p>
                <p class="mt-1 text-sm text-slate-700">Mobile No: {{ $serviceRequest->mobile_no }}</p>
            </div>
        </div>

        <div class="mt-4 rounded-2xl border border-white/70 bg-white/85 p-5 shadow-lg backdrop-blur-xl">
            <h3 class="text-sm font-semibold uppercase tracking-[0.12em] text-slate-600">Description Request</h3>
            <p class="mt-2 whitespace-pre-line break-all text-sm leading-relaxed text-slate-700">{{ $serviceRequest->description_request }}</p>
        </div>

        <div class="mt-4 rounded-2xl border border-white/70 bg-white/85 p-5 shadow-lg backdrop-blur-xl">
            <h3 class="text-sm font-semibold uppercase tracking-[0.12em] text-slate-600">Approved By</h3>
            <p class="mt-2 text-sm text-slate-700">Name: {{ $serviceRequest->approved_by_name }}</p>
            <p class="mt-1 text-sm text-slate-700">Signature: __________________________</p>
            <p class="mt-1 text-sm text-slate-700">Position: {{ $serviceRequest->approved_by_position }}</p>
            <p class="mt-1 text-sm text-slate-700">Date Signed: {{ $serviceRequest->approved_date->format('M d, Y') }}</p>
        </div>

        <div class="mt-4 rounded-2xl border border-white/70 bg-white/85 p-5 shadow-lg backdrop-blur-xl">
            <h3 class="text-sm font-semibold uppercase tracking-[0.12em] text-slate-600">For knowledge management and information technology service only</h3>
            <p class="mt-2 text-sm text-slate-700">10. Date: {{ optional($serviceRequest->kmits_date)->format('M d, Y') ?: 'N/A' }}</p>
            <p class="mt-1 text-sm text-slate-700">11. Time Received: {{ $serviceRequest->time_received ?: 'N/A' }}</p>
            <p class="mt-1 text-sm text-slate-700">12. Actions Taken: {{ $serviceRequest->actions_taken ?: 'N/A' }}</p>

            <div class="mt-4 overflow-x-auto rounded-xl border border-slate-200 bg-white">
                <table class="min-w-full text-sm text-slate-700">
                    <thead class="bg-slate-100 text-xs uppercase tracking-[0.08em] text-slate-600">
                        <tr>
                            <th class="px-3 py-2 text-left">Date</th>
                            <th class="px-3 py-2 text-left">Time</th>
                            <th class="px-3 py-2 text-left">Action Taken</th>
                            <th class="px-3 py-2 text-left">Action Officer</th>
                            <th class="px-3 py-2 text-left">Signature</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($serviceRequest->action_logs ?? [] as $row)
                            <tr class="border-t border-slate-100">
                                <td class="px-3 py-2">{{ $row['date'] ?? '-' }}</td>
                                <td class="px-3 py-2">{{ $row['time'] ?? '-' }}</td>
                                <td class="px-3 py-2">{{ $row['action_taken'] ?? '-' }}</td>
                                <td class="px-3 py-2">{{ $row['action_officer'] ?? '-' }}</td>
                                <td class="px-3 py-2">________________</td>
                            </tr>
                        @empty
                            @for ($i = 0; $i < 5; $i++)
                                <tr class="border-t border-slate-100">
                                    <td class="px-3 py-2">-</td>
                                    <td class="px-3 py-2">-</td>
                                    <td class="px-3 py-2">-</td>
                                    <td class="px-3 py-2">-</td>
                                    <td class="px-3 py-2">________________</td>
                                </tr>
                            @endfor
                        @endforelse
                    </tbody>
                </table>
            </div>

            <p class="mt-4 text-sm text-slate-700">13. Noted by (Name of Supervisor): {{ $serviceRequest->noted_by_name ?: 'N/A' }}</p>
            <p class="mt-1 text-sm text-slate-700">Signature: __________________________</p>
            <p class="mt-1 text-sm text-slate-700">14. Position: {{ $serviceRequest->noted_by_position ?: 'N/A' }}</p>
            <p class="mt-1 text-sm text-slate-700">15. Date Signed: {{ optional($serviceRequest->noted_by_date_signed)->format('M d, Y') ?: 'N/A' }}</p>
        </div>
    </div>
</x-app-layout>
