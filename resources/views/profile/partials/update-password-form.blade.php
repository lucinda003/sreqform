<form method="post" action="{{ route('password.update') }}" class="space-y-5">
    @csrf
    @method('put')

    <div>
        <label for="update_password_current_password" class="block text-sm font-semibold text-slate-700">Current Password</label>
        <input
            id="update_password_current_password"
            name="current_password"
            type="password"
            class="mt-1.5 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-800 outline-none transition focus:border-slate-500 focus:ring-1 focus:ring-slate-500"
            autocomplete="current-password"
            placeholder="Enter your current password"
        />
        <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
    </div>

    <div>
        <label for="update_password_password" class="block text-sm font-semibold text-slate-700">New Password</label>
        <input
            id="update_password_password"
            name="password"
            type="password"
            class="mt-1.5 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-800 outline-none transition focus:border-slate-500 focus:ring-1 focus:ring-slate-500"
            autocomplete="new-password"
            placeholder="Create a new password"
        />
        <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
    </div>

    <div>
        <label for="update_password_password_confirmation" class="block text-sm font-semibold text-slate-700">Confirm New Password</label>
        <input
            id="update_password_password_confirmation"
            name="password_confirmation"
            type="password"
            class="mt-1.5 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-800 outline-none transition focus:border-slate-500 focus:ring-1 focus:ring-slate-500"
            autocomplete="new-password"
            placeholder="Repeat the new password"
        />
        <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
    </div>

    <div class="flex flex-wrap items-center justify-end gap-3 border-t border-slate-100 pt-5">
        @if (session('status') === 'password-updated')
            <p
                x-data="{ show: true }"
                x-show="show"
                x-transition.opacity
                x-init="setTimeout(() => show = false, 2200)"
                class="rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700"
            >{{ __('Saved') }}</p>
        @endif

        <button
            type="submit"
            class="rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:ring-offset-2"
        >
            Update Password
        </button>
    </div>
</form>
