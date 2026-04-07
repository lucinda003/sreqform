<x-app-layout>
    <x-slot name="header" style="display:none;"></x-slot>

    @php
        $isArchiveView = ($statusFilter ?? '') === 'archived';
        $searchQuery = trim((string) ($search ?? ''));
        $chatFilter = trim((string) ($chatRequestFilter ?? ''));

        $activeParams = [];
        if ($searchQuery !== '') {
            $activeParams['q'] = $searchQuery;
        }
        if ($chatFilter !== '') {
            $activeParams['chat_request'] = $chatFilter;
        }

        $archiveParams = ['status' => 'archived'];
        if ($searchQuery !== '') {
            $archiveParams['q'] = $searchQuery;
        }
        if ($chatFilter !== '') {
            $archiveParams['chat_request'] = $chatFilter;
        }

        $clearParams = $isArchiveView ? ['status' => 'archived'] : [];
        if ($chatFilter !== '') {
            $clearParams['chat_request'] = $chatFilter;
        }
    @endphp

    <x-db2-shell
        :title="$isArchiveView ? 'Archive' : 'Service Requests'"
        :subtitle="$isArchiveView ? 'Approved and rejected request records.' : 'Track submitted DOH service request forms.'"
    >
        @if (session('status'))
            <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                {{ session('status') }}
            </div>
        @endif

        <div class="mb-4 inline-flex rounded-xl border border-slate-200 bg-slate-50 p-1">
            <a
                href="{{ route('service-requests.index', $activeParams) }}"
                class="rounded-lg px-3 py-1.5 text-sm font-semibold transition {{ ! $isArchiveView ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-700 hover:bg-slate-100' }}"
            >
                Active Requests
            </a>
            <a
                href="{{ route('service-requests.index', $archiveParams) }}"
                class="rounded-lg px-3 py-1.5 text-sm font-semibold transition {{ $isArchiveView ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-700 hover:bg-slate-100' }}"
            >
                Archive
            </a>
        </div>

        <form method="GET" action="{{ route('service-requests.index') }}" class="mb-4 flex flex-wrap items-center gap-2" data-srf-auto-search-form>
            @if ($isArchiveView)
                <input type="hidden" name="status" value="archived">
            @endif
            @if ($chatFilter !== '')
                <input type="hidden" name="chat_request" value="{{ $chatFilter }}">
            @endif
            <input
                type="text"
                name="q"
                value="{{ $searchQuery }}"
                placeholder="Search reference, name, office, or system"
                class="w-full max-w-md rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-slate-500 focus:outline-none"
                data-srf-auto-search-input
            >
            <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
                Search
            </button>
            @if ($searchQuery !== '')
                <a
                    href="{{ route('service-requests.index', $clearParams) }}"
                    class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100"
                >
                    Clear
                </a>
            @endif
        </form>

        @if ($chatFilter !== '')
            <div class="mb-4 rounded-lg border border-sky-200 bg-sky-50 px-3 py-2 text-xs font-semibold text-sky-800">
                Filter active: Chat request = {{ strtoupper($chatFilter) }}
            </div>
        @endif

        <div data-srf-listing-content>
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
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
                                <tr class="border-t border-slate-200 hover:bg-slate-50">
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
                                    <td colspan="6" class="px-4 py-8 text-center text-slate-500">
                                        {{ $isArchiveView ? 'No archived requests yet.' : 'No service requests yet.' }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if ($serviceRequests->hasPages())
                <div class="mt-4 flex flex-wrap items-center justify-between gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3">
                    <a
                        href="{{ $serviceRequests->previousPageUrl() ?: '#' }}"
                        data-srf-page-link
                        class="rounded-lg border px-4 py-2 text-sm font-semibold {{ $serviceRequests->onFirstPage() ? 'cursor-not-allowed border-slate-200 text-slate-400' : 'border-slate-300 text-slate-700 hover:bg-slate-100' }}"
                        {{ $serviceRequests->onFirstPage() ? 'aria-disabled=true' : '' }}
                    >
                        Previous
                    </a>

                    <p class="text-sm text-slate-600">
                        Showing {{ $serviceRequests->firstItem() }} to {{ $serviceRequests->lastItem() }} of {{ $serviceRequests->total() }}
                    </p>

                    <a
                        href="{{ $serviceRequests->nextPageUrl() ?: '#' }}"
                        data-srf-page-link
                        class="rounded-lg border px-4 py-2 text-sm font-semibold {{ $serviceRequests->hasMorePages() ? 'border-slate-300 text-slate-700 hover:bg-slate-100' : 'cursor-not-allowed border-slate-200 text-slate-400' }}"
                        {{ $serviceRequests->hasMorePages() ? '' : 'aria-disabled=true' }}
                    >
                        Next
                    </a>
                </div>
            @endif
        </div>

        <script>
            (function () {
                const form = document.querySelector('[data-srf-auto-search-form]');
                const input = form ? form.querySelector('[data-srf-auto-search-input]') : null;
                const listingContent = document.querySelector('[data-srf-listing-content]');

                if (!form || !input || !listingContent) {
                    return;
                }

                let debounceTimer = null;
                let activeRequest = null;

                const bindPaginationLinks = function () {
                    listingContent.querySelectorAll('[data-srf-page-link]').forEach(function (link) {
                        link.addEventListener('click', function (event) {
                            const href = link.getAttribute('href') || '';
                            if (href === '' || href === '#') {
                                return;
                            }

                            event.preventDefault();
                            fetchAndRender(href);
                        });
                    });
                };

                const buildSearchUrl = function () {
                    const action = form.getAttribute('action') || window.location.pathname;
                    const url = new URL(action, window.location.origin);
                    const formData = new FormData(form);

                    formData.forEach(function (value, key) {
                        const normalized = String(value).trim();
                        if (normalized !== '') {
                            url.searchParams.set(key, normalized);
                        }
                    });

                    return url.toString();
                };

                const fetchAndRender = async function (url) {
                    if (activeRequest) {
                        activeRequest.abort();
                    }

                    activeRequest = new AbortController();

                    try {
                        const response = await fetch(url, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'text/html',
                            },
                            signal: activeRequest.signal,
                        });

                        if (!response.ok) {
                            return;
                        }

                        const html = await response.text();
                        const parsed = new DOMParser().parseFromString(html, 'text/html');
                        const nextListing = parsed.querySelector('[data-srf-listing-content]');

                        if (!nextListing) {
                            return;
                        }

                        listingContent.innerHTML = nextListing.innerHTML;
                        bindPaginationLinks();
                        window.history.replaceState({}, '', url);
                    } catch (error) {
                        if (error && error.name === 'AbortError') {
                            return;
                        }
                    }
                };

                const runSearch = function () {
                    fetchAndRender(buildSearchUrl());
                };

                form.addEventListener('submit', function (event) {
                    event.preventDefault();
                    runSearch();
                });

                input.addEventListener('input', function () {
                    window.clearTimeout(debounceTimer);
                    debounceTimer = window.setTimeout(runSearch, 300);
                });

                bindPaginationLinks();
            })();
        </script>
    </x-db2-shell>
</x-app-layout>
