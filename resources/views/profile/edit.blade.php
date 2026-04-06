<x-app-layout>
    <x-slot name="header" style="display:none;"></x-slot>

    <x-db2-shell title="Profile" subtitle="Manage your account details and security settings.">
        <div class="max-w-3xl space-y-5">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-8">
                <div class="max-w-2xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-8">
                <div class="max-w-2xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-5 shadow-sm sm:p-8">
                <div class="max-w-2xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </x-db2-shell>
</x-app-layout>
