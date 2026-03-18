<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="auth-title">Profile</h2>
            <p class="auth-subtitle">Manage your account details and security settings.</p>
        </div>
    </x-slot>

    <div class="mx-auto w-full max-w-6xl py-6 space-y-5">
        <div class="rounded-2xl border border-white/70 bg-white/75 p-5 shadow-lg backdrop-blur-xl sm:p-8">
            <div class="max-w-2xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="rounded-2xl border border-white/70 bg-white/75 p-5 shadow-lg backdrop-blur-xl sm:p-8">
            <div class="max-w-2xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="rounded-2xl border border-rose-200/80 bg-rose-50/70 p-5 shadow-lg backdrop-blur-xl sm:p-8">
            <div class="max-w-2xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
