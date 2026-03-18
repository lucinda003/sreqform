<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <div>
                <h2 class="auth-title">Account Management</h2>
                <p class="auth-subtitle">Select a user first, then edit department and approval details.</p>
            </div>
            <button
                type="button"
                class="rounded-xl border border-slate-300 px-3 py-2 text-xs font-semibold uppercase tracking-[0.12em] text-slate-700 transition hover:border-slate-500"
                onclick="document.getElementById('create-account-dialog').showModal()"
            >
                Create Account
            </button>
        </div>
    </x-slot>

    <div class="mx-auto w-full max-w-6xl space-y-5 py-6">
        @if (session('status'))
            <div class="auth-success">{{ session('status') }}</div>
        @endif

        <div class="grid gap-5 lg:grid-cols-[minmax(280px,320px)_1fr]">
            <div class="rounded-2xl border border-white/70 bg-white/85 p-5 shadow-lg backdrop-blur-xl">
                <h3 class="text-base font-semibold text-slate-900">Select User</h3>
                <form method="GET" action="{{ route('admin.users.index') }}" class="mt-4 space-y-3">
                    <div>
                        <label class="auth-label" for="user_id">Account</label>
                        <select class="auth-input" id="user_id" name="user_id" onchange="this.form.submit()">
                            <option value="">Select a user to edit</option>
                            @foreach ($users as $userOption)
                                <option value="{{ $userOption->id }}" @selected($selectedUserId === $userOption->id)>
                                    {{ $userOption->name }} ({{ $userOption->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>

                <div class="mt-4 rounded-xl border border-slate-200 bg-slate-50 p-3">
                    <p class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-500">Quick Summary</p>
                    <p class="mt-2 text-sm text-slate-700">Total Accounts: {{ $users->count() }}</p>
                    <p class="mt-1 text-sm text-slate-700">Admins (ADMIN): {{ $users->where('department', 'ADMIN')->count() }}</p>
                    <p class="mt-1 text-sm text-slate-700">Approved Departments: {{ $users->where('department_status', 'approved')->count() }}</p>
                </div>
            </div>

            <div class="space-y-5">
                <div class="rounded-2xl border border-white/70 bg-white/85 p-5 shadow-lg backdrop-blur-xl sm:p-8">
                    <h3 class="text-base font-semibold text-slate-900">Edit Selected Account</h3>

                        @if ($selectedUser)
                            <form method="POST" action="{{ route('admin.users.update', $selectedUser) }}" class="mt-4 space-y-4">
                                @csrf
                                @method('PUT')

                                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                                    <div>
                                        <label class="auth-label" for="selected_name">Name</label>
                                        <input class="auth-input" id="selected_name" name="name" type="text" value="{{ $selectedUser->name }}" required>
                                    </div>

                                    <div>
                                        <label class="auth-label" for="selected_email">Email</label>
                                        <input class="auth-input" id="selected_email" name="email" type="email" value="{{ $selectedUser->email }}" required>
                                    </div>

                                    <div>
                                        <label class="auth-label" for="selected_department">Department</label>
                                        <input class="auth-input" id="selected_department" name="department" type="text" value="{{ $selectedUser->department }}">
                                    </div>

                                    <div>
                                        <label class="auth-label" for="selected_department_status">Department Status</label>
                                        <select class="auth-input" id="selected_department_status" name="department_status" required>
                                            <option value="pending" @selected($selectedUser->department_status === 'pending')>Pending</option>
                                            <option value="approved" @selected($selectedUser->department_status === 'approved')>Approved</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="auth-label" for="selected_password">New Password (optional)</label>
                                        <input class="auth-input" id="selected_password" name="password" type="password">
                                    </div>
                                </div>

                                <div class="flex items-center justify-between gap-3">
                                    <p class="text-xs text-slate-500">User ID: {{ $selectedUser->id }}</p>
                                    <button type="submit" class="auth-button">Save Changes</button>
                                </div>
                            </form>
                        @else
                            <p class="mt-4 text-sm text-slate-500">Select a user first to show the edit form.</p>
                        @endif
                </div>
            </div>
        </div>

        <dialog id="create-account-dialog" class="w-full max-w-3xl rounded-2xl border border-slate-200 p-0 backdrop:bg-slate-900/30">
            <div class="rounded-2xl bg-white p-5 sm:p-8">
                <div class="flex items-center justify-between gap-3">
                    <h3 class="text-base font-semibold text-slate-900">Create Account</h3>
                    <button type="button" class="rounded-lg border border-slate-300 px-2.5 py-1.5 text-xs font-semibold uppercase tracking-[0.12em] text-slate-600" onclick="document.getElementById('create-account-dialog').close()">Close</button>
                </div>

                <form method="POST" action="{{ route('admin.users.store') }}" class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @csrf

                    <div>
                        <label class="auth-label" for="name">Name</label>
                        <input class="auth-input" id="name" name="name" type="text" required>
                    </div>

                    <div>
                        <label class="auth-label" for="email">Email</label>
                        <input class="auth-input" id="email" name="email" type="email" required>
                    </div>

                    <div>
                        <label class="auth-label" for="password">Password</label>
                        <input class="auth-input" id="password" name="password" type="password" required>
                    </div>

                    <div>
                        <label class="auth-label" for="department">Department</label>
                        <input class="auth-input" id="department" name="department" type="text" placeholder="e.g. DOH-NCR or ADMIN" required>
                    </div>

                    <div>
                        <label class="auth-label" for="department_status">Department Status</label>
                        <select class="auth-input" id="department_status" name="department_status" required>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                        </select>
                    </div>

                    <div class="sm:col-span-2 lg:col-span-3">
                        <button type="submit" class="auth-button">Create Account</button>
                    </div>
                </form>
            </div>
        </dialog>
    </div>
</x-app-layout>
