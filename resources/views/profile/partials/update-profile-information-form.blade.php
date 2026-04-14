<form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
</form>

<form method="post" action="{{ route('profile.update') }}" class="space-y-6">
    @csrf
    @method('patch')

    <div class="grid gap-5 md:grid-cols-2">
        <div class="md:col-span-2">
            <label for="name" class="block text-sm font-semibold text-slate-700">Full Name</label>
            <input
                id="name"
                name="name"
                type="text"
                class="mt-1.5 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-800 outline-none transition focus:border-slate-500 focus:ring-1 focus:ring-slate-500"
                value="{{ old('name', $user->name) }}"
                required
                autofocus
                autocomplete="name"
            />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <label for="email" class="block text-sm font-semibold text-slate-700">Email Address</label>
            <input
                id="email"
                name="email"
                type="email"
                class="mt-1.5 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-800 outline-none transition focus:border-slate-500 focus:ring-1 focus:ring-slate-500"
                value="{{ old('email', $user->email) }}"
                required
                autocomplete="username"
            />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <div>
            <label for="department" class="block text-sm font-semibold text-slate-700">Department Code</label>
            <input
                id="department"
                type="text"
                value="{{ $user->department ?: 'N/A' }}"
                readonly
                class="mt-1.5 w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm font-semibold text-slate-700 outline-none"
            />

            @php
                $departmentStatusClasses = $user->department_status === 'approved'
                    ? 'border-emerald-200 bg-emerald-50 text-emerald-700'
                    : 'border-amber-200 bg-amber-50 text-amber-700';
            @endphp

            <div class="mt-2 flex items-center justify-between gap-2">
                <p class="text-xs text-slate-500">Department code is assigned by admin.</p>
                <span class="inline-flex rounded-full border px-2.5 py-1 text-xs font-semibold uppercase {{ $departmentStatusClasses }}">
                    {{ $user->department_status ?? 'pending' }}
                </span>
            </div>
        </div>
    </div>

    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
        <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3">
            <p class="text-sm text-amber-800">
                {{ __('Your email address is unverified.') }}
                <button form="send-verification" class="font-semibold underline underline-offset-2 hover:text-amber-900">
                    {{ __('Send verification link') }}
                </button>
            </p>

            @if (session('status') === 'verification-link-sent')
                <p class="mt-2 text-xs font-semibold uppercase tracking-[0.12em] text-emerald-700">
                    {{ __('A new verification link was sent.') }}
                </p>
            @endif
        </div>
    @endif

    <div class="flex flex-wrap items-center justify-end gap-3 border-t border-slate-100 pt-5">
        @if (session('status') === 'profile-updated')
            <p
                x-data="{ show: true }"
                x-show="show"
                x-transition.opacity
                x-init="setTimeout(() => show = false, 2200)"
                class="rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700"
            >{{ __('Saved') }}</p>
        @endif

        <button
            type="button"
            class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-slate-400 hover:bg-slate-50"
            x-on:click="togglePassword()"
            x-text="showPassword ? 'Hide Password Security' : 'Open Password Security'"
        ></button>

        <button
            type="button"
            class="rounded-xl border border-rose-300 px-4 py-2 text-sm font-semibold text-rose-700 transition hover:border-rose-400 hover:bg-rose-50"
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        >Delete Account</button>

        <button
            type="submit"
            class="rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:ring-offset-2"
        >
            Save Profile
        </button>
    </div>
</form>
