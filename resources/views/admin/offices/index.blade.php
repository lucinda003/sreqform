@php View::share('pageTitle', 'Offices Management'); @endphp
<x-app-layout>
    <x-slot name="header" style="display:none;"></x-slot>

    <x-db2-shell
        title="Offices Management"
        subtitle="Manage office locations and departments."
    >
        <x-slot name="actions">
            <button
                type="button"
                class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-slate-700"
                onclick="document.getElementById('create-office-dialog').showModal()"
            >
                Add Office
            </button>
        </x-slot>

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
                        <th scope="col" class="px-6 py-4 font-semibold">Parent Office</th>
                        <th scope="col" class="px-6 py-4 font-semibold">Office Name</th>
                        <th scope="col" class="px-6 py-4 font-semibold">Status</th>
                        <th scope="col" class="px-6 py-4 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($offices as $office)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 text-slate-700">{{ $office->parent_name ?? 'DOH CENTRAL OFFICE' }}</td>
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
                                        onclick="openEditDialog({{ $office->id }}, @js($office->parent_name ?? 'DOH CENTRAL OFFICE'), @js($office->name), {{ $office->is_active ? 'true' : 'false' }})"
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
                            <td colspan="4" class="px-6 py-8 text-center text-sm text-slate-500">No offices found. Create one to get started.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Create Dialog -->
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
                        <label class="auth-label block text-sm font-medium text-slate-700" for="parent_name">Parent Office</label>
                        <select class="auth-input mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm" id="parent_name" name="parent_name" required>
                            @foreach (($parentOfficeOptions ?? ['DOH CENTRAL OFFICE']) as $parentOfficeOption)
                                <option value="{{ $parentOfficeOption }}">{{ $parentOfficeOption }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="auth-label block text-sm font-medium text-slate-700" for="name">Office Name</label>
                        <input class="auth-input mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm" id="name" name="name" type="text" placeholder="e.g., Administrative Service" required autofocus>
                    </div>

                    <div class="mt-6 flex justify-end gap-3 border-t border-slate-100 pt-5">
                        <button type="button" class="rounded-lg px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100" onclick="document.getElementById('create-office-dialog').close()">Cancel</button>
                        <button type="submit" class="rounded-lg bg-slate-900 px-5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-slate-700 transition">Add Office</button>
                    </div>
                </form>
            </div>
        </dialog>

        <!-- Edit Dialog -->
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
                        <label class="auth-label block text-sm font-medium text-slate-700" for="edit_parent_name">Parent Office</label>
                        <select class="auth-input mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm" id="edit_parent_name" name="parent_name" required>
                            @foreach (($parentOfficeOptions ?? ['DOH CENTRAL OFFICE']) as $parentOfficeOption)
                                <option value="{{ $parentOfficeOption }}">{{ $parentOfficeOption }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="auth-label block text-sm font-medium text-slate-700" for="edit_name">Office Name</label>
                        <input class="auth-input mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm" id="edit_name" name="name" type="text" required>
                    </div>

                    <div>
                        <label class="flex items-center gap-3">
                            <input type="checkbox" id="edit_is_active" name="is_active" value="1" class="rounded border-slate-300 text-slate-600 shadow-sm focus:border-slate-500 focus:ring-slate-500">
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

        <script>
            function openEditDialog(id, parentName, name, isActive) {
                document.getElementById('edit_parent_name').value = parentName || 'DOH CENTRAL OFFICE';
                document.getElementById('edit_name').value = name;
                document.getElementById('edit_is_active').checked = isActive;
                
                const form = document.getElementById('edit-office-form');
                form.action = "/admin/offices/" + id;
                
                document.getElementById('edit-office-dialog').showModal();
            }
        </script>
    </x-db2-shell>
</x-app-layout>
