<x-app-layout>
    <x-slot name="header" style="display:none;"></x-slot>

    @php
        $tabLabels = [
            'pending' => 'Pending',
            'accepted' => 'Accepted',
            'rejected' => 'Rejected',
            'all' => 'All',
        ];
        $currentSearch = trim((string) ($search ?? ''));
    @endphp

    <x-db2-shell
        title="Chat Requests"
        subtitle="Dedicated queue for requestor-admin chat approvals."
    >
        <div class="space-y-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="inline-flex flex-wrap rounded-xl border border-slate-200 bg-slate-50 p-1">
                    @foreach ($tabLabels as $key => $label)
                        @php
                            $tabParams = ['chat_status' => $key];
                            if ($currentSearch !== '') {
                                $tabParams['q'] = $currentSearch;
                            }
                        @endphp
                        <a
                            href="{{ route('service-requests.chat-requests', $tabParams) }}"
                            class="rounded-lg px-3 py-1.5 text-sm font-semibold transition {{ $chatStatus === $key ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-700 hover:bg-slate-100' }}"
                        >
                            {{ $label }}
                        </a>
                    @endforeach
                </div>

                <form method="GET" action="{{ route('service-requests.chat-requests') }}" class="mt-3 flex flex-wrap items-center gap-2" data-srf-chat-auto-search-form>
                    <input type="hidden" name="chat_status" value="{{ $chatStatus }}">
                    <input
                        type="text"
                        name="q"
                        value="{{ $currentSearch }}"
                        placeholder="Search reference, name, office, or system"
                        class="w-full max-w-md rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-slate-500 focus:outline-none"
                        data-srf-chat-auto-search-input
                    >
                    <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
                        Search
                    </button>
                    @if ($currentSearch !== '')
                        <a
                            href="{{ route('service-requests.chat-requests', ['chat_status' => $chatStatus]) }}"
                            class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100"
                        >
                            Clear
                        </a>
                    @endif
                </form>
            </div>

            <div data-srf-chat-listing-content>
                @if ($chatRequests->count() > 0)
                    <div class="grid gap-4 lg:grid-cols-2">
                        @foreach ($chatRequests as $chatRequest)
                            @php
                                $chatStatusClass = match ((string) $chatRequest->contact_chat_status) {
                                    'accepted' => 'border-emerald-300 bg-emerald-100 text-emerald-800',
                                    'rejected' => 'border-rose-300 bg-rose-100 text-rose-800',
                                    default => 'border-amber-300 bg-amber-100 text-amber-800',
                                };

                                $requestStatusClass = match ((string) $chatRequest->status) {
                                    'checking' => 'border-sky-300 bg-sky-100 text-sky-800',
                                    'approved' => 'border-emerald-300 bg-emerald-100 text-emerald-800',
                                    'rejected' => 'border-rose-300 bg-rose-100 text-rose-800',
                                    default => 'border-amber-300 bg-amber-100 text-amber-800',
                                };

                                $requestedAt = $chatRequest->contact_chat_requested_at ?? $chatRequest->updated_at;
                            @endphp

                            <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                                <div class="flex flex-wrap items-start justify-between gap-3">
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-500">Reference</p>
                                        <p class="mt-1 text-base font-bold text-slate-900 break-all">{{ $chatRequest->reference_code }}</p>
                                    </div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="inline-flex rounded-full border px-2.5 py-1 text-xs font-semibold uppercase {{ $chatStatusClass }}">
                                            Chat {{ $chatRequest->contact_chat_status }}
                                        </span>
                                        <span class="inline-flex rounded-full border px-2.5 py-1 text-xs font-semibold uppercase {{ $requestStatusClass }}">
                                            {{ $chatRequest->status }}
                                        </span>
                                    </div>
                                </div>

                                <dl class="mt-4 grid gap-3 text-sm sm:grid-cols-2">
                                    <div>
                                        <dt class="text-xs font-semibold uppercase tracking-[0.1em] text-slate-500">Requester</dt>
                                        <dd class="mt-1 font-semibold text-slate-800 break-all">
                                            {{ $chatRequest->contact_last_name }}, {{ $chatRequest->contact_first_name }} {{ $chatRequest->contact_middle_name }}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs font-semibold uppercase tracking-[0.1em] text-slate-500">Office</dt>
                                        <dd class="mt-1 text-slate-700 break-all">{{ $chatRequest->office }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs font-semibold uppercase tracking-[0.1em] text-slate-500">System</dt>
                                        <dd class="mt-1 text-slate-700 break-all">{{ $chatRequest->application_system_name ?: 'N/A' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs font-semibold uppercase tracking-[0.1em] text-slate-500">Requested At</dt>
                                        <dd class="mt-1 text-slate-700">{{ $requestedAt ? $requestedAt->format('M d, Y h:i A') : 'N/A' }}</dd>
                                    </div>
                                </dl>

                                <div class="mt-4 flex justify-end">
                                    <a
                                        href="{{ route('service-requests.edit', $chatRequest) }}"
                                        class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700"
                                    >
                                        Open & Review
                                    </a>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-12 text-center text-slate-500">
                        No chat requests found for this filter.
                    </div>
                @endif

                @if ($chatRequests->hasPages())
                    <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <a
                                href="{{ $chatRequests->previousPageUrl() ?: '#' }}"
                                data-srf-chat-page-link
                                class="rounded-lg border px-4 py-2 text-sm font-semibold {{ $chatRequests->onFirstPage() ? 'cursor-not-allowed border-slate-200 text-slate-400' : 'border-slate-300 text-slate-700 hover:bg-slate-100' }}"
                                {{ $chatRequests->onFirstPage() ? 'aria-disabled=true' : '' }}
                            >
                                Previous
                            </a>

                            <p class="text-sm text-slate-600">
                                Showing {{ $chatRequests->firstItem() }} to {{ $chatRequests->lastItem() }} of {{ $chatRequests->total() }}
                            </p>

                            <a
                                href="{{ $chatRequests->nextPageUrl() ?: '#' }}"
                                data-srf-chat-page-link
                                class="rounded-lg border px-4 py-2 text-sm font-semibold {{ $chatRequests->hasMorePages() ? 'border-slate-300 text-slate-700 hover:bg-slate-100' : 'cursor-not-allowed border-slate-200 text-slate-400' }}"
                                {{ $chatRequests->hasMorePages() ? '' : 'aria-disabled=true' }}
                            >
                                Next
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <script>
            (function () {
                const form = document.querySelector('[data-srf-chat-auto-search-form]');
                const input = form ? form.querySelector('[data-srf-chat-auto-search-input]') : null;
                const listingContent = document.querySelector('[data-srf-chat-listing-content]');

                if (!form || !input || !listingContent) {
                    return;
                }

                let debounceTimer = null;
                let activeRequest = null;

                const bindPaginationLinks = function () {
                    listingContent.querySelectorAll('[data-srf-chat-page-link]').forEach(function (link) {
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
                        const nextListing = parsed.querySelector('[data-srf-chat-listing-content]');

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
