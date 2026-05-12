@props([
    'paginator',
    'label' => 'records',
])

@if ($paginator->total() > 0)
    @php
        $currentPage = $paginator->currentPage();
        $lastPage = $paginator->lastPage();
        $startPage = max(1, $currentPage - 2);
        $endPage = min($lastPage, $currentPage + 2);
        $pages = [];

        for ($page = $startPage; $page <= $endPage; $page++) {
            $pages[] = $page;
        }

        if (! in_array(1, $pages, true)) {
            array_unshift($pages, 1);
        }

        if (! in_array($lastPage, $pages, true)) {
            $pages[] = $lastPage;
        }

        $pages = array_values(array_unique($pages));
        sort($pages);
        $lastRenderedPage = null;
    @endphp

    <nav class="flex flex-col gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm sm:flex-row sm:items-center sm:justify-between" aria-label="Pagination">
        <p class="font-medium text-slate-600">
            Showing
            <span class="font-semibold text-slate-900">{{ $paginator->firstItem() }}</span>
            to
            <span class="font-semibold text-slate-900">{{ $paginator->lastItem() }}</span>
            of
            <span class="font-semibold text-slate-900">{{ $paginator->total() }}</span>
            {{ $label }}
        </p>

        @if ($paginator->hasPages())
            <div class="flex flex-wrap items-center gap-2">
                @if ($paginator->onFirstPage())
                    <span class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 font-semibold text-slate-400">Previous</span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" class="rounded-lg border border-slate-300 bg-white px-3 py-1.5 font-semibold text-slate-700 transition hover:bg-slate-50">Previous</a>
                @endif

                @foreach ($pages as $page)
                    @if ($lastRenderedPage !== null && $page > $lastRenderedPage + 1)
                        <span class="px-1 font-semibold text-slate-400">...</span>
                    @endif

                    @if ($page === $currentPage)
                        <span class="rounded-lg bg-slate-900 px-3 py-1.5 font-semibold text-white">{{ $page }}</span>
                    @else
                        <a href="{{ $paginator->url($page) }}" class="rounded-lg border border-slate-300 bg-white px-3 py-1.5 font-semibold text-slate-700 transition hover:bg-slate-50">{{ $page }}</a>
                    @endif

                    @php $lastRenderedPage = $page; @endphp
                @endforeach

                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" class="rounded-lg border border-slate-300 bg-white px-3 py-1.5 font-semibold text-slate-700 transition hover:bg-slate-50">Next</a>
                @else
                    <span class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 font-semibold text-slate-400">Next</span>
                @endif
            </div>
        @endif
    </nav>
@endif
