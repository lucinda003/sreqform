<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="auth-title">Service Requests</h2>
                <p class="auth-subtitle">Track submitted DOH service request forms.</p>
            </div>
        </div>
    </x-slot>

    <div class="mx-auto w-full max-w-6xl py-6">
        @if (session('status'))
            <div class="auth-success mb-4">{{ session('status') }}</div>
        @endif

        <div class="overflow-hidden rounded-2xl border border-white/70 bg-white/85 shadow-lg backdrop-blur-xl">
            <div class="overflow-x-auto">
                <table class="min-w-full w-full text-sm">
                    <thead class="bg-slate-100 text-left text-xs uppercase tracking-[0.1em] text-slate-600">
                        <tr>
                            <th class="px-4 py-3">Reference</th>
                            <th class="px-4 py-3">Contact Person</th>
                            <th class="px-4 py-3">Office</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Request Date</th>
                            <th class="px-4 py-3 text-center whitespace-nowrap">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($serviceRequests as $serviceRequest)
                            <tr class="border-t border-slate-200">
                                <td class="px-4 py-3 text-center font-semibold text-slate-900 break-all">{{ $serviceRequest->reference_code }}</td>
                                <td class="px-4 py-3 text-center text-slate-700 break-all">
                                    {{ $serviceRequest->contact_last_name }}, {{ $serviceRequest->contact_first_name }} {{ $serviceRequest->contact_middle_name }}
                                </td>
                                <td class="px-4 py-3 text-center text-slate-700 break-all">{{ $serviceRequest->office }}</td>
                                <td class="px-4 py-3 text-center">
                                    @php
                                        $statusClasses = match ($serviceRequest->status) {
                                            'checking' => 'border-sky-300 bg-sky-100 text-sky-800',
                                            'approved' => 'border-emerald-300 bg-emerald-100 text-emerald-800',
                                            'rejected' => 'border-rose-300 bg-rose-100 text-rose-800',
                                            default => 'border-amber-300 bg-amber-100 text-amber-800',
                                        };
                                    @endphp
                                    <span class="inline-flex rounded-full border px-2.5 py-1 text-xs font-semibold uppercase {{ $statusClasses }}">
                                        {{ $serviceRequest->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center text-slate-700">{{ $serviceRequest->request_date->format('M d, Y') }}</td>
                                <td class="px-4 py-3 text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-3">
                                        <a href="{{ route('service-requests.show', $serviceRequest) }}" class="auth-link">Open</a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-slate-500">No service requests yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">{{ $serviceRequests->links() }}</div>
    </div>
</x-app-layout>
