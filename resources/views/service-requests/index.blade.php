@php View::share('pageTitle', 'Service Requests'); @endphp
<x-app-layout>
    <x-slot name="header" style="display:none;"></x-slot>

    @php
        $statusFilterValue = trim((string) ($statusFilter ?? ''));
        $assignedFilterValue = trim((string) ($assignedFilter ?? ''));
        $receivedFilterValue = trim((string) ($receivedFilter ?? ''));
        $isExplicitStatusFilter = in_array($statusFilterValue, ['pending', 'checking', 'approved', 'archived'], true);
        $isArchiveView = in_array($statusFilterValue, ['archived', 'approved'], true);
        $isAssignedView = $assignedFilterValue === 'me';
        $isReceivedView = $receivedFilterValue === 'me';
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

        $receivedParams = ['received' => 'me'];
        if ($searchQuery !== '') {
            $receivedParams['q'] = $searchQuery;
        }
        if ($chatFilter !== '') {
            $receivedParams['chat_request'] = $chatFilter;
        }

        $assignedParams = ['assigned' => 'me'];
        if ($searchQuery !== '') {
            $assignedParams['q'] = $searchQuery;
        }
        if ($chatFilter !== '') {
            $assignedParams['chat_request'] = $chatFilter;
        }

        $clearParams = [];
        if ($isExplicitStatusFilter) {
            $clearParams['status'] = $statusFilterValue;
        }
        if ($isReceivedView) {
            $clearParams['received'] = 'me';
        }
        if ($isAssignedView) {
            $clearParams['assigned'] = 'me';
        }
        if ($chatFilter !== '') {
            $clearParams['chat_request'] = $chatFilter;
        }
    @endphp

    <x-db2-shell
        :title="$isArchiveView ? 'Archive' : ($isReceivedView ? 'Receive' : ($isAssignedView ? 'Assigned' : 'Service Requests'))"
        :subtitle="$isArchiveView ? 'Approved request records.' : ($isReceivedView ? 'Requests you have received.' : ($isAssignedView ? 'Requests assigned to you.' : 'Track submitted DOH service request forms.'))"
    >
        @if (session('status'))
            <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                {{ session('status') }}
            </div>
        @endif

        <div class="mb-4 inline-flex rounded-xl border border-slate-200 bg-slate-50 p-1">
            <a
                href="{{ route('service-requests.index', $activeParams) }}"
                data-srf-section-link
                class="rounded-lg px-3 py-1.5 text-sm font-semibold transition {{ ! $isArchiveView && ! $isAssignedView && ! $isReceivedView ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-700 hover:bg-slate-100' }}"
            >
                Active Requests
            </a>
            <a
                href="{{ route('service-requests.index', $receivedParams) }}"
                data-srf-section-link
                class="rounded-lg px-3 py-1.5 text-sm font-semibold transition {{ $isReceivedView ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-700 hover:bg-slate-100' }}"
            >
                Receive
            </a>
            <a
                href="{{ route('service-requests.index', $assignedParams) }}"
                data-srf-section-link
                class="rounded-lg px-3 py-1.5 text-sm font-semibold transition {{ $isAssignedView ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-700 hover:bg-slate-100' }}"
            >
                Assigned
            </a>
            <a
                href="{{ route('service-requests.index', $archiveParams) }}"
                data-srf-section-link
                class="rounded-lg px-3 py-1.5 text-sm font-semibold transition {{ $isArchiveView ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-700 hover:bg-slate-100' }}"
            >
                Archive
            </a>
        </div>

        <form method="GET" action="{{ route('service-requests.index') }}" class="mb-4 flex flex-wrap items-center gap-2" data-srf-auto-search-form>
            @if ($isExplicitStatusFilter)
                <input type="hidden" name="status" value="{{ $statusFilterValue }}">
            @endif
            @if ($isReceivedView)
                <input type="hidden" name="received" value="me">
            @endif
            @if ($isAssignedView)
                <input type="hidden" name="assigned" value="me">
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
                        <thead class="bg-slate-100 text-xs uppercase tracking-[0.1em] text-slate-600">
                            <tr>
                                <th class="px-4 py-3 text-center">Reference</th>
                                <th class="px-4 py-3 text-center">Contact Person</th>
                                <th class="px-4 py-3 text-center">Office</th>
                                <th class="px-4 py-3 text-center">Status</th>
                                @if ($isReceivedView)
                                    <th class="px-4 py-3 text-center">Assigned To</th>
                                @endif
                                @if ($isAssignedView)
                                    <th class="px-4 py-3 text-center">Assigned By</th>
                                @endif
                                <th class="px-4 py-3 text-center">Request Date</th>
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
                                                default => 'border-amber-300 bg-amber-100 text-amber-800',
                                            };
                                        @endphp
                                    <span class="inline-flex rounded-full border px-2.5 py-1 text-xs font-semibold uppercase {{ $statusClasses }}">
                                            {{ $serviceRequest->status }}
                                        </span>
                                    </td>
                                    @if ($isReceivedView)
                                        <td class="px-4 py-3 text-center text-slate-700">
                                            @if ($serviceRequest->assignedUser && (int) ($serviceRequest->assigned_to_user_id ?? 0) !== (int) ($serviceRequest->received_by_user_id ?? 0))
                                                <div class="font-semibold text-slate-900">{{ $serviceRequest->assignedUser->name }}</div>
                                                <div class="text-xs uppercase tracking-wide text-slate-500">{{ $serviceRequest->assignedUser->role ?? 'No Role' }}</div>
                                            @else
                                                <span class="text-slate-400">N/A</span>
                                            @endif
                                        </td>
                                    @endif
                                    @if ($isAssignedView)
                                        <td class="px-4 py-3 text-center text-slate-700">
                                            @if ($serviceRequest->assignedByUser)
                                                <div class="font-semibold text-slate-900">{{ $serviceRequest->assignedByUser->name }}</div>
                                                <div class="text-xs uppercase tracking-wide text-slate-500">{{ $serviceRequest->assignedByUser->role ?? 'No Role' }}</div>
                                            @else
                                                <span class="text-slate-400">N/A</span>
                                            @endif
                                        </td>
                                    @endif
                                    <td class="px-4 py-3 text-center text-slate-700">{{ $serviceRequest->request_date->format('M d, Y') }}</td>
                                    <td class="px-4 py-3 text-center whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-3">
                                            @if ($isReceivedView || $isAssignedView || $isArchiveView)
                                                <a href="{{ route('service-requests.show', ['serviceRequest' => $serviceRequest] + request()->only(['status', 'received', 'assigned', 'q', 'chat_request'])) }}" class="auth-link">Open</a>
                                            @else
                                                <button
                                                    type="button"
                                                    class="auth-link"
                                                    onclick="openReceiveDialog('{{ route('service-requests.receive', $serviceRequest) }}', @js($serviceRequest->reference_code))"
                                                >
                                                    Receive
                                                </button>
                                            @endif
                                            @php
                                                $userRole = Auth::user()->role ?? '';
                                                $currentUserId = (int) Auth::id();
                                                $assignedToUserId = (int) ($serviceRequest->assigned_to_user_id ?? 0);
                                                $canAssign = ($isReceivedView || $isAssignedView)
                                                    && in_array($userRole, ['admin', 'supervisor', 'technical support'], true)
                                                    && (
                                                        $assignedToUserId === $currentUserId
                                                        || ($assignedToUserId === 0 && (int) ($serviceRequest->received_by_user_id ?? 0) === $currentUserId)
                                                    );
                                            @endphp
                                            @if ($canAssign)
                                                <button type="button" onclick="openAssignDialog({{ $serviceRequest->id }}, '{{ $serviceRequest->reference_code }}')" class="auth-link">Assign</button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ ($isAssignedView || $isReceivedView) ? 7 : 6 }}" class="px-4 py-8 text-center text-slate-500">
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

                const fetchAndRender = async function (url, options) {
                    const settings = options || {};
                    if (activeRequest) {
                        activeRequest.abort();
                    }

                    activeRequest = new AbortController();

                    try {
                        const requestedUrl = new URL(url, window.location.origin);
                        const ajaxUrl = new URL(@json(route('service-requests.ajax')), window.location.origin);
                        ajaxUrl.search = requestedUrl.search;

                        const response = await fetch(ajaxUrl.toString(), {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            },
                            signal: activeRequest.signal,
                        });

                        if (!response.ok) {
                            return;
                        }

                        const payload = await response.json();
                        const html = String(payload.html || '');
                        const parsed = new DOMParser().parseFromString(html, 'text/html');
                        const nextListing = parsed.querySelector('[data-srf-listing-content]');

                        if (!nextListing) {
                            return;
                        }

                        listingContent.innerHTML = nextListing.innerHTML;
                        bindPaginationLinks();
                        if (settings.updateHistory !== false) {
                            window.history.replaceState({}, '', requestedUrl.pathname + requestedUrl.search);
                        }
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
                window.srfRefreshServiceRequestListing = function () {
                    return fetchAndRender(window.location.href, { updateHistory: false });
                };

                window.clearInterval(window.srfServiceRequestListingPollId);
                window.srfServiceRequestListingPollId = window.setInterval(function () {
                    const assignDialog = document.getElementById('assign-request-dialog');
                    if (assignDialog && assignDialog.open) {
                        return;
                    }

                    window.srfRefreshServiceRequestListing();
                }, 4000);
            })();
        </script>

        <!-- Receive Dialog -->
        <dialog id="receive-request-dialog" class="w-full max-w-md rounded-2xl border border-slate-200 p-0 backdrop:bg-slate-900/40">
            <div class="rounded-2xl bg-white p-6 sm:p-8">
                <div class="border-b border-slate-100 pb-4">
                    <h3 class="text-lg font-bold text-slate-900">Receive Request?</h3>
                    <p class="mt-1 text-sm text-slate-600">This request will move to your Receive list.</p>
                    <p class="mt-2 text-xs font-semibold uppercase tracking-wide text-slate-500" id="receive-request-ref">Reference: Loading...</p>
                </div>

                <form id="receive-request-form" method="POST" action="" class="mt-6">
                    @csrf
                    @method('PATCH')

                    <div class="flex justify-end gap-3">
                        <button
                            type="button"
                            class="rounded-lg px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100"
                            onclick="document.getElementById('receive-request-dialog').close()"
                        >
                            Cancel
                        </button>
                        <button type="submit" class="rounded-lg bg-slate-900 px-5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-slate-700 transition">
                            Yes, Receive
                        </button>
                    </div>
                </form>
            </div>
        </dialog>

        <!-- Assign Dialog -->
        <dialog id="assign-request-dialog" class="w-full max-w-md rounded-2xl border border-slate-200 p-0 backdrop:bg-slate-900/40">
            <div class="rounded-2xl bg-white p-6 sm:p-8">
                <div class="flex items-center justify-between gap-3 border-b border-slate-100 pb-4">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Assign Request</h3>
                        <p class="text-xs text-slate-500 mt-1" id="assign-request-ref">Loading...</p>
                    </div>
                    <button
                        type="button"
                        class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition"
                        onclick="document.getElementById('assign-request-dialog').close()"
                    >
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                    </button>
                </div>

                <form id="assign-request-form" method="POST" action="" class="mt-6 grid gap-5">
                    @csrf
                    @method('PATCH')
                    
                    <div>
                        <label class="auth-label block text-sm font-medium text-slate-700" for="assign_to_user">Assign to User</label>
                        <select class="auth-input mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm" id="assign_to_user" name="assigned_to_user_id" required>
                            <option value="" disabled selected>Select user...</option>
                            @forelse ($assignableUsers ?? [] as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->role ?? 'No Role' }})</option>
                            @empty
                                <option value="" disabled>No users available</option>
                            @endforelse
                        </select>
                    </div>

                    <div class="mt-2 flex justify-end gap-3 border-t border-slate-100 pt-5">
                        <button type="button" class="rounded-lg px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100" onclick="document.getElementById('assign-request-dialog').close()">Cancel</button>
                        <button type="submit" class="rounded-lg px-5 py-2 text-sm font-semibold text-white shadow-sm transition hover:opacity-90" style="background:#0f766e; color:#fff;">Assign</button>
                </form>
            </div>
        </dialog>

        <script>
            function openReceiveDialog(action, referenceCode) {
                document.getElementById('receive-request-ref').textContent = 'Reference: ' + referenceCode;
                const form = document.getElementById('receive-request-form');
                form.action = action;
                document.getElementById('receive-request-dialog').showModal();
            }

            function openAssignDialog(requestId, referenceCode) {
                document.getElementById('assign-request-ref').textContent = 'Reference: ' + referenceCode;
                const form = document.getElementById('assign-request-form');
                form.action = '/service-requests/' + requestId + '/assign';
                document.getElementById('assign-request-dialog').showModal();
            }

            (function () {
                const form = document.getElementById('assign-request-form');
                const dialog = document.getElementById('assign-request-dialog');

                if (!form || !dialog) {
                    return;
                }

                form.addEventListener('submit', async function (event) {
                    event.preventDefault();

                    const submitButton = form.querySelector('button[type="submit"]');
                    if (submitButton) {
                        submitButton.disabled = true;
                    }

                    try {
                        const response = await fetch(form.action, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                            body: new FormData(form),
                        });

                        if (!response.ok) {
                            form.submit();
                            return;
                        }

                        form.reset();
                        dialog.close();

                        if (typeof window.srfRefreshServiceRequestListing === 'function') {
                            await window.srfRefreshServiceRequestListing();
                        }
                    } catch (error) {
                        form.submit();
                    } finally {
                        if (submitButton) {
                            submitButton.disabled = false;
                        }
                    }
                });
            })();
        </script>
    </x-db2-shell>
</x-app-layout>
