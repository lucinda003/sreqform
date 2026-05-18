@php View::share('pageTitle', 'Dashboard'); @endphp
<x-app-layout>
    <x-slot name="header" style="display:none;"></x-slot>

    <x-db2-shell>
        @include('partials.dashboard-overview')
    </x-db2-shell>
</x-app-layout>
