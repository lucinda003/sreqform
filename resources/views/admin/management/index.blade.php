@php View::share('pageTitle', 'Management'); @endphp
<x-app-layout>
    <x-slot name="header" style="display:none;"></x-slot>

    <x-db2-shell
        title="Management"
        subtitle="Manage offices and application systems."
    >
        <!-- Tabs Navigation -->
        <div class="mb-6 flex gap-2 border-b border-slate-200">
            <button 
                type="button"
                class="px-4 py-3 text-sm font-semibold transition"
                id="offices-tab"
                onclick="switchTab('offices')"
                data-tab="offices"
            >
                Offices
            </button>
            <button 
                type="button"
                class="px-4 py-3 text-sm font-semibold transition"
                id="systems-tab"
                onclick="switchTab('systems')"
                data-tab="systems"
            >
                Systems
            </button>
        </div>

        <!-- Offices Section -->
        <div id="offices-section" class="tab-section">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-slate-900">Offices</h3>
                <button
                    type="button"
                    class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-slate-700"
                    onclick="document.getElementById('create-office-dialog').showModal()"
                >
                    Add Office
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

            <!-- Offices Table -->
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <table class="min-w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-xs uppercase text-slate-500 border-b border-slate-200">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-semibold">Office Name</th>
                            <th scope="col" class="px-6 py-4 font-semibold">Status</th>
                            <th scope="col" class="px-6 py-4 font-semibold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($offices as $office)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 font-medium text-slate-900">{{ $office->name }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $office->is_active ? 'border border-emerald-200 bg-emerald-50 text-emerald-700' : 'border border-slate-200 bg-slate-50 text-slate-600' }}">
                                        {{ $office->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="inline-flex gap-2">
                                        <button 
                                            type="button" 
                                            class="rounded-lg px-3 py-1.5 text-xs font-semibold text-sky-700 hover:bg-sky-50 transition border border-transparent hover:border-sky-200"
                                            onclick="openEditOfficeDialog({{ $office->id }}, '{{ addslashes($office->name) }}', {{ $office->is_active ? 'true' : 'false' }})"
                                        >
                                            Edit
                                        </button>
                                        
                                        <form method="POST" action="{{ route('admin.offices.destroy', $office) }}" onsubmit="return confirm('Are you sure you want to delete this office?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-lg px-3 py-1.5 text-xs font-semibold text-rose-600 hover:bg-rose-50 transition border border-transparent hover:border-rose-200">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-8 text-center text-sm text-slate-500">No offices found. Create one to get started.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Systems Section -->
        <div id="systems-section" class="tab-section" style="display:none;">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-slate-900">Application Systems</h3>
                <button
                    type="button"
                    class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-slate-700"
                    onclick="document.getElementById('create-system-dialog').showModal()"
                >
                    Add System
                </button>
            </div>

            <!-- Systems Table -->
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <table class="min-w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-xs uppercase text-slate-500 border-b border-slate-200">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-semibold">System Name</th>
                            <th scope="col" class="px-6 py-4 font-semibold">Status</th>
                            <th scope="col" class="px-6 py-4 font-semibold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($systems as $system)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 font-medium text-slate-900">{{ $system->name }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $system->is_active ? 'border border-emerald-200 bg-emerald-50 text-emerald-700' : 'border border-slate-200 bg-slate-50 text-slate-600' }}">
                                        {{ $system->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="inline-flex gap-2">
                                        <button 
                                            type="button" 
                                            class="rounded-lg px-3 py-1.5 text-xs font-semibold text-sky-700 hover:bg-sky-50 transition border border-transparent hover:border-sky-200"
                                            onclick="openEditSystemDialog({{ $system->id }}, '{{ addslashes($system->name) }}', {{ $system->is_active ? 'true' : 'false' }})"
                                        >
                                            Edit
                                        </button>
                                        
                                        <form method="POST" action="{{ route('admin.application-systems.destroy', $system) }}" onsubmit="return confirm('Are you sure you want to delete this system?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-lg px-3 py-1.5 text-xs font-semibold text-rose-600 hover:bg-rose-50 transition border border-transparent hover:border-rose-200">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-8 text-center text-sm text-slate-500">No systems found. Create one to get started.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Create Office Dialog -->
        <dialog id="create-office-dialog" class="w-full max-w-lg rounded-2xl border border-slate-200 p-0 backdrop:bg-slate-900/40">
            <div class="rounded-2xl bg-white p-6 sm:p-8">
                <div class="flex items-center justify-between gap-3 border-b border-slate-100 pb-4">
                    <h3 class="text-lg font-bold text-slate-900">Add Office</h3>
                    <button
                        type="button"
                        class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition"
                        onclick="document.getElementById('create-office-dialog').close()"
                    >
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                    </button>
                </div>

                <form method="POST" action="{{ route('admin.offices.store') }}" class="mt-6 space-y-4">
                    @csrf

                    <div>
                        <label class="auth-label block text-sm font-medium text-slate-700" for="office_name">Office Name</label>
                        <input class="auth-input mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm" id="office_name" name="name" type="text" placeholder="e.g., Main Office, Branch Office" required autofocus>
                    </div>

                    <div class="mt-6 flex justify-end gap-3 border-t border-slate-100 pt-5">
                        <button type="button" class="rounded-lg px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100" onclick="document.getElementById('create-office-dialog').close()">Cancel</button>
                        <button type="submit" class="rounded-lg bg-slate-900 px-5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-slate-700 transition">Add Office</button>
                    </div>
                </form>
            </div>
        </dialog>

        <!-- Edit Office Dialog -->
        <dialog id="edit-office-dialog" class="w-full max-w-lg rounded-2xl border border-slate-200 p-0 backdrop:bg-slate-900/40">
            <div class="rounded-2xl bg-white p-6 sm:p-8">
                <div class="flex items-center justify-between gap-3 border-b border-slate-100 pb-4">
                    <h3 class="text-lg font-bold text-slate-900">Edit Office</h3>
                    <button
                        type="button"
                        class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition"
                        onclick="document.getElementById('edit-office-dialog').close()"
                    >
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                    </button>
                </div>

                <form id="edit-office-form" method="POST" action="" class="mt-6 space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="auth-label block text-sm font-medium text-slate-700" for="edit_office_name">Office Name</label>
                        <input class="auth-input mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm" id="edit_office_name" name="name" type="text" required>
                    </div>

                    <div>
                        <label class="flex items-center gap-3">
                            <input type="checkbox" id="edit_office_is_active" name="is_active" value="1" class="rounded border-slate-300 text-slate-600 shadow-sm focus:border-slate-500 focus:ring-slate-500">
                            <span class="text-sm font-medium text-slate-700">Active</span>
                        </label>
                    </div>

                    <div class="mt-6 flex justify-end gap-3 border-t border-slate-100 pt-5">
                        <button type="button" class="rounded-lg px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100" onclick="document.getElementById('edit-office-dialog').close()">Cancel</button>
                        <button type="submit" class="rounded-lg bg-sky-700 px-5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-sky-800 transition">Update Office</button>
                    </div>
                </form>
            </div>
        </dialog>

        <!-- Create System Dialog -->
        <dialog id="create-system-dialog" class="w-full max-w-lg rounded-2xl border border-slate-200 p-0 backdrop:bg-slate-900/40">
            <div class="rounded-2xl bg-white p-6 sm:p-8">
                <div class="flex items-center justify-between gap-3 border-b border-slate-100 pb-4">
                    <h3 class="text-lg font-bold text-slate-900">Add System</h3>
                    <button
                        type="button"
                        class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition"
                        onclick="document.getElementById('create-system-dialog').close()"
                    >
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                    </button>
                </div>

                <form method="POST" action="{{ route('admin.application-systems.store') }}" class="mt-6 space-y-4">
                    @csrf

                    <div>
                        <label class="auth-label block text-sm font-medium text-slate-700" for="system_name">System Name</label>
                        <input class="auth-input mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm" id="system_name" name="name" type="text" placeholder="e.g., Office Automation System, Document Management" required autofocus>
                    </div>

                    <div class="mt-6 flex justify-end gap-3 border-t border-slate-100 pt-5">
                        <button type="button" class="rounded-lg px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100" onclick="document.getElementById('create-system-dialog').close()">Cancel</button>
                        <button type="submit" class="rounded-lg bg-slate-900 px-5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-slate-700 transition">Add System</button>
                    </div>
                </form>
            </div>
        </dialog>

        <!-- Edit System Dialog -->
        <dialog id="edit-system-dialog" class="w-full max-w-lg rounded-2xl border border-slate-200 p-0 backdrop:bg-slate-900/40">
            <div class="rounded-2xl bg-white p-6 sm:p-8">
                <div class="flex items-center justify-between gap-3 border-b border-slate-100 pb-4">
                    <h3 class="text-lg font-bold text-slate-900">Edit System</h3>
                    <button
                        type="button"
                        class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition"
                        onclick="document.getElementById('edit-system-dialog').close()"
                    >
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                    </button>
                </div>

                <form id="edit-system-form" method="POST" action="" class="mt-6 space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="auth-label block text-sm font-medium text-slate-700" for="edit_system_name">System Name</label>
                        <input class="auth-input mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm" id="edit_system_name" name="name" type="text" required>
                    </div>

                    <div>
                        <label class="flex items-center gap-3">
                            <input type="checkbox" id="edit_system_is_active" name="is_active" value="1" class="rounded border-slate-300 text-slate-600 shadow-sm focus:border-slate-500 focus:ring-slate-500">
                            <span class="text-sm font-medium text-slate-700">Active</span>
                        </label>
                    </div>

                    <div class="mt-6 flex justify-end gap-3 border-t border-slate-100 pt-5">
                        <button type="button" class="rounded-lg px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100" onclick="document.getElementById('edit-system-dialog').close()">Cancel</button>
                        <button type="submit" class="rounded-lg bg-sky-700 px-5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-sky-800 transition">Update System</button>
                    </div>
                </form>
            </div>
        </dialog>

        <script>
            function switchTab(tab) {
                // Hide all sections
                document.querySelectorAll('.tab-section').forEach(el => el.style.display = 'none');
                document.querySelectorAll('[data-tab]').forEach(el => {
                    el.classList.remove('border-b-2', 'border-slate-900', 'text-slate-900');
                    el.classList.add('text-slate-600');
                });

                // Show selected section
                document.getElementById(tab + '-section').style.display = 'block';
                document.getElementById(tab + '-tab').classList.remove('text-slate-600');
                document.getElementById(tab + '-tab').classList.add('border-b-2', 'border-slate-900', 'text-slate-900');
            }

            // Initialize - show first tab
            switchTab('offices');

            function openEditOfficeDialog(id, name, isActive) {
                document.getElementById('edit_office_name').value = name;
                document.getElementById('edit_office_is_active').checked = isActive;
                
                const form = document.getElementById('edit-office-form');
                form.action = "/admin/offices/" + id;
                
                document.getElementById('edit-office-dialog').showModal();
            }

            function openEditSystemDialog(id, name, isActive) {
                document.getElementById('edit_system_name').value = name;
                document.getElementById('edit_system_is_active').checked = isActive;
                
                const form = document.getElementById('edit-system-form');
                form.action = "/admin/application-systems/" + id;
                
                document.getElementById('edit-system-dialog').showModal();
            }
        </script>
    </x-db2-shell>
</x-app-layout>
