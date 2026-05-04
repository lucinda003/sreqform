<!-- Action Buttons -->
<div class="mb-5 flex flex-wrap items-center gap-2">
    <button
        type="button"
        class="rounded-xl border border-emerald-300 bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-800 shadow-sm hover:bg-emerald-100"
        onclick="document.getElementById('create-department-dialog').showModal()"
    >
        Add Department Code
    </button>
    <button
        type="button"
        class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-slate-700"
        onclick="document.getElementById('create-account-dialog').showModal()"
    >
        New Account
    </button>
</div>

@if (session('status'))
    <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">{{ session('status') }}</div>
@endif

@if ($errors->any())
    <div class="mb-5 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-800">
        <ul class="list-inside list-disc">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid gap-6">
    <!-- Users Table -->
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden flex flex-col min-w-0">
        <div class="overflow-x-auto min-w-0 flex-1">
            <table class="min-w-full text-left text-sm text-slate-600 whitespace-nowrap">
                <thead class="bg-slate-50 text-xs uppercase text-slate-500 border-b border-slate-200">
                    <tr>
                        <th scope="col" class="px-6 py-4 font-semibold">User - ({{ $users->count() }})</th>
                        <th scope="col" class="px-6 py-4 font-semibold">Department Code</th>
                        <th scope="col" class="px-6 py-4 font-semibold">Status</th>
                        <th scope="col" class="px-6 py-4 font-semibold text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @foreach ($users as $user)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <p class="font-bold text-slate-900">{{ $user->username }}</p>
                            </td>
                            <td class="px-6 py-4">
                                @if ($user->department !== null && trim((string)$user->department) !== '')
                                    <span class="inline-flex rounded-full border border-slate-200 bg-slate-100 px-2.5 py-1 text-xs font-bold text-slate-700 tracking-wider">
                                        {{ $user->department }}
                                    </span>
                                @else
                                    <span class="text-slate-400 italic">None</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusCls = match ($user->department_status) {
                                        'approved' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
                                        'pending' => 'border-amber-200 bg-amber-50 text-amber-700',
                                        default => 'border-slate-200 bg-slate-50 text-slate-600',
                                    };
                                @endphp
                                <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold uppercase {{ $statusCls }}">
                                    {{ $user->department_status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="inline-flex gap-2">
                                    <button 
                                        type="button" 
                                        class="rounded-lg px-3 py-1.5 text-xs font-semibold text-sky-700 hover:bg-sky-50 hover:text-sky-800 transition border border-transparent hover:border-sky-200"
                                        onclick="openEditDialog({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ addslashes($user->email) }}', '{{ addslashes($user->department) }}', '{{ $user->department_status }}', '{{ addslashes($user->role ?? '') }}')"
                                    >
                                        Edit
                                    </button>
                                    
                                    @if (strtoupper((string) $user->department) !== 'ADMIN')
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Are you sure you want to delete this user? This cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-lg px-3 py-1.5 text-xs font-semibold text-rose-600 hover:bg-rose-50 hover:text-rose-800 transition border border-transparent hover:border-rose-200">
                                                Delete
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @if ($users->isEmpty())
            <div class="py-12 px-6 text-center">
                <p class="text-sm font-medium text-slate-500">No accounts found.</p>
            </div>
        @endif
    </div>
</div>

<!-- Department Code Dialog -->
<dialog id="create-department-dialog" class="w-full max-w-lg rounded-2xl border border-slate-200 p-0 backdrop:bg-slate-900/40">
    <div class="rounded-2xl bg-white p-6 sm:p-8">
        <div class="flex items-center justify-between gap-3 border-b border-slate-100 pb-4">
            <h3 class="text-lg font-bold text-slate-900">Add Department Code</h3>
            <button
                type="button"
                class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition"
                onclick="document.getElementById('create-department-dialog').close()"
            >
                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
            </button>
        </div>

        <form method="POST" action="{{ route('admin.department-codes.store') }}" class="mt-6 grid gap-5">
            @csrf

            <div>
                <label class="auth-label block text-sm font-medium text-slate-700" for="department_code_new">Department Code</label>
                <input class="auth-input mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm uppercase" id="department_code_new" name="code" type="text" maxlength="30" placeholder="e.g. KMITS" required>
                <p class="mt-1.5 text-[11px] text-slate-500 leading-tight">Code will be converted to uppercase and added to dropdown options.</p>
            </div>

            <div class="mt-2 flex justify-end gap-3 border-t border-slate-100 pt-5">
                <button type="button" class="rounded-lg px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100" onclick="document.getElementById('create-department-dialog').close()">Cancel</button>
                <button type="submit" class="rounded-lg bg-slate-900 px-5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-slate-800 transition">Save Code</button>
            </div>
        </form>
    </div>
</dialog>

<!-- Create Dialog -->
<dialog id="create-account-dialog" class="w-full max-w-2xl rounded-2xl border border-slate-200 p-0 backdrop:bg-slate-900/40">
    <div class="rounded-2xl bg-white p-6 sm:p-8">
        <div class="flex items-center justify-between gap-3 border-b border-slate-100 pb-4">
            <h3 class="text-lg font-bold text-slate-900">Create New Account</h3>
            <button
                type="button"
                class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition"
                onclick="document.getElementById('create-account-dialog').close()"
            >
                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
            </button>
        </div>

        <form method="POST" action="{{ route('admin.users.store') }}" class="mt-6 grid gap-5 sm:grid-cols-2">
            @csrf
            <div class="sm:col-span-2">
                <label class="auth-label block text-sm font-medium text-slate-700" for="name">Full Name</label>
                <input class="auth-input mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm" id="name" name="name" type="text" autocomplete="name" required>
            </div>

            <div class="sm:col-span-2">
                <label class="auth-label block text-sm font-medium text-slate-700" for="email">Email Address</label>
                <input class="auth-input mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm" id="email" name="email" type="email" autocomplete="email" required>
            </div>

            <div class="sm:col-span-2">
                <label class="auth-label block text-sm font-medium text-slate-700" for="department_code">Department Code</label>
                <select class="auth-input mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm" id="department_code" name="department_code" required>
                    <option value="" disabled @selected((string) old('department_code', '') === '')>Select department code</option>
                    @foreach ($departmentCodes as $departmentCode)
                        <option value="{{ $departmentCode }}" @selected((string) old('department_code') === (string) $departmentCode)>
                            {{ $departmentCode }}
                        </option>
                    @endforeach
                </select>
                <p class="mt-1.5 text-[11px] text-slate-500 leading-tight">Use the Add Department Code button to manage dropdown options. New accounts are set to pending and become approved after first password setup.</p>
            </div>

            <div class="sm:col-span-2">
                <label class="auth-label block text-sm font-medium text-slate-700" for="role">User Role</label>
                <select class="auth-input mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm" id="role" name="role" required>
                    <option value="" disabled @selected((string) old('role', '') === '')>Select role</option>
                    <option value="super admin" @selected((string) old('role') === 'super admin')>Super Admin</option>
                    <option value="admin" @selected((string) old('role') === 'admin')>Admin</option>
                    <option value="supervisor" @selected((string) old('role') === 'supervisor')>Supervisor</option>
                    <option value="technical support" @selected((string) old('role') === 'technical support')>Technical Support</option>
                </select>
            </div>

            <div class="sm:col-span-2 mt-4 flex justify-end gap-3 border-t border-slate-100 pt-5">
                <button type="button" class="rounded-lg px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100" onclick="document.getElementById('create-account-dialog').close()">Cancel</button>
                <button type="submit" class="rounded-lg bg-slate-900 px-5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-slate-700 transition">Create Account</button>
            </div>
        </form>
    </div>
</dialog>

<!-- Edit Dialog -->
<dialog id="edit-account-dialog" class="w-full max-w-2xl rounded-2xl border border-slate-200 p-0 backdrop:bg-slate-900/40">
    <div class="rounded-2xl bg-white p-6 sm:p-8">
        <div class="flex items-center justify-between gap-3 border-b border-slate-100 pb-4">
            <div>
                <h3 class="text-lg font-bold text-slate-900">Edit Account</h3>
                <p class="text-xs text-slate-500 mt-1" id="edit-user-caption">Loading...</p>
            </div>
            <button
                type="button"
                class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition"
                onclick="document.getElementById('edit-account-dialog').close()"
            >
                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
            </button>
        </div>

        <form id="edit-user-form" method="POST" action="" class="mt-6 grid gap-5 sm:grid-cols-2">
            @csrf
            @method('PUT')
            
            <div class="sm:col-span-2">
                <label class="auth-label block text-sm font-medium text-slate-700" for="edit_name">Full Name</label>
                <input class="auth-input mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm" id="edit_name" name="name" type="text" autocomplete="name" required>
            </div>

            <div>
                <label class="auth-label block text-sm font-medium text-slate-700" for="edit_email">Email Address</label>
                <input class="auth-input mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm" id="edit_email" name="email" type="email" autocomplete="email" required>
            </div>

            <div>
                <label class="auth-label block text-sm font-medium text-slate-700" for="edit_password">Reset Password</label>
                <input class="auth-input mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm" id="edit_password" name="password" type="password" autocomplete="new-password" placeholder="Leave empty to keep current">
            </div>

            <div>
                <label class="auth-label block text-sm font-medium text-slate-700" for="edit_role">User Role</label>
                <select class="auth-input mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm" id="edit_role" name="role" required>
                    <option value="">No Role</option>
                    <option value="super admin">Super Admin</option>
                    <option value="admin">Admin</option>
                    <option value="supervisor">Supervisor</option>
                    <option value="technical support">Technical Support</option>
                </select>
            </div>

            <div>
                <label class="auth-label block text-sm font-medium text-slate-700" for="edit_department_code">Department Code</label>
                <input class="auth-input mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm uppercase" id="edit_department_code" name="department_code" type="text" maxlength="30" required>
                <p class="mt-1.5 text-[11px] text-slate-500 leading-tight">Use "ADMIN" for the single global admin.</p>
            </div>

            <div>
                <label class="auth-label block text-sm font-medium text-slate-700" for="edit_department_status">Account Status</label>
                <select class="auth-input mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm" id="edit_department_status" name="department_status" required>
                    <option value="approved">Approved</option>
                    <option value="pending">Pending</option>
                </select>
            </div>

            <div class="sm:col-span-2 mt-4 flex justify-end gap-3 border-t border-slate-100 pt-5">
                <button type="button" class="rounded-lg px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100" onclick="document.getElementById('edit-account-dialog').close()">Cancel</button>
                <button type="submit" class="rounded-lg bg-sky-700 px-5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-sky-800 transition">Update Account</button>
            </div>
        </form>
    </div>
</dialog>

<script>
    function openEditDialog(id, name, email, department, status, role) {
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_email').value = email;
        document.getElementById('edit_department_code').value = department;
        document.getElementById('edit_department_status').value = status;
        document.getElementById('edit_role').value = role;
        document.getElementById('edit_password').value = '';
        
        document.getElementById('edit-user-caption').textContent = 'User ID: ' + id;
        
        const form = document.getElementById('edit-user-form');
        form.action = "/admin/users/" + id;
        
        document.getElementById('edit-account-dialog').showModal();
    }
</script>
