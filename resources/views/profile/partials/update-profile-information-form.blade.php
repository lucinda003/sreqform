<section>
    <header>
        <h2 class="text-lg font-semibold text-slate-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-slate-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="auth-label">Name</label>
            <input id="name" name="name" type="text" class="auth-input" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <label for="email" class="auth-label">Email</label>
            <input id="email" name="email" type="email" class="auth-input" value="{{ old('email', $user->email) }}" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="mt-2 text-sm text-slate-700">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="auth-link">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="auth-success mt-2">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <label for="department" class="auth-label">Department Code</label>
            <select id="department" name="department" class="auth-input" required>
                <option value="Role 1" @selected(old('department', $user->department) === 'Role 1')>Role 1</option>
                <option value="Role 2" @selected(old('department', $user->department) === 'Role 2')>Role 2</option>
                <option value="Role 3" @selected(old('department', $user->department) === 'Role 3')>Role 3</option>
                <option value="Role 4" @selected(old('department', $user->department) === 'Role 4')>Role 4</option>
                <option value="Role 5" @selected(old('department', $user->department) === 'Role 5')>Role 5</option>
                <option value="Role 6" @selected(old('department', $user->department) === 'Role 6')>Role 6</option>
                <option value="Role 7" @selected(old('department', $user->department) === 'Role 7')>Role 7</option>
                <option value="Role 8" @selected(old('department', $user->department) === 'Role 8')>Role 8</option>
                <option value="Role 9" @selected(old('department', $user->department) === 'Role 9')>Role 9</option>
                <option value="ADMIN" @selected(old('department', $user->department) === 'ADMIN')>ADMIN</option>
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('department')" />
            <p class="mt-1 text-xs text-slate-500">Role-based users can access requests for their own department role only.</p>
            @php
                $departmentStatusClasses = $user->department_status === 'approved'
                    ? 'border-emerald-300 bg-emerald-100 text-emerald-800'
                    : 'border-amber-300 bg-amber-100 text-amber-800';
            @endphp
            <span class="mt-2 inline-flex rounded-full border px-2.5 py-1 text-xs font-semibold uppercase {{ $departmentStatusClasses }}">
                Department {{ $user->department_status ?? 'pending' }}
            </span>
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="auth-button">Save</button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-slate-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
