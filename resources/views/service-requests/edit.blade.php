<x-app-layout>
    @php
        $isAdmin = strtoupper((string) auth()->user()?->department) === 'ADMIN';
    @endphp

    <x-slot name="header">
        <div>
            <h2 class="auth-title">Edit Service Request</h2>
            <p class="auth-subtitle">Reference: {{ $serviceRequest->reference_code }}</p>
        </div>
    </x-slot>

    <div class="mx-auto w-full max-w-6xl py-6">
        <div class="rounded-2xl border border-white/70 bg-white/80 p-5 shadow-lg backdrop-blur-xl sm:p-8">
            <form method="POST" action="{{ route('service-requests.update', $serviceRequest) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                    <h3 class="text-sm font-semibold uppercase tracking-[0.12em] text-slate-700">Reference</h3>
                    <p class="mt-2 text-sm text-slate-600">Reference code stays fixed after submission.</p>
                    <p class="mt-2 text-lg font-semibold text-slate-900">{{ $serviceRequest->reference_code }}</p>
                    <div class="mt-3 grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="department_code" class="auth-label">Department Code</label>
                            <select id="department_code" name="department_code" class="auth-input" required>
                                @foreach ($departmentOptions as $departmentOption)
                                    <option value="{{ $departmentOption }}" @selected(old('department_code', $serviceRequest->department_code) === $departmentOption)>{{ $departmentOption }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('department_code')" class="mt-2" />
                            @if (! $isAdmin)
                                <p class="mt-1 text-xs text-slate-500">Only approved department roles are shown in this list.</p>
                            @endif
                        </div>
                        <div>
                            <label for="request_date" class="auth-label">Request Date</label>
                            <input id="request_date" name="request_date" type="date" class="auth-input" value="{{ old('request_date', $serviceRequest->request_date->toDateString()) }}" required>
                            <x-input-error :messages="$errors->get('request_date')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-semibold uppercase tracking-[0.12em] text-slate-700">Requester Details</h3>
                    <div class="mt-3 grid gap-4 sm:grid-cols-3">
                        <div>
                            <label for="contact_last_name" class="auth-label">Last Name</label>
                            <input id="contact_last_name" name="contact_last_name" type="text" class="auth-input" value="{{ old('contact_last_name', $serviceRequest->contact_last_name) }}" required>
                            <x-input-error :messages="$errors->get('contact_last_name')" class="mt-2" />
                        </div>
                        <div>
                            <label for="contact_first_name" class="auth-label">First Name</label>
                            <input id="contact_first_name" name="contact_first_name" type="text" class="auth-input" value="{{ old('contact_first_name', $serviceRequest->contact_first_name) }}" required>
                            <x-input-error :messages="$errors->get('contact_first_name')" class="mt-2" />
                        </div>
                        <div>
                            <label for="contact_middle_name" class="auth-label">Middle Name</label>
                            <input id="contact_middle_name" name="contact_middle_name" type="text" class="auth-input" value="{{ old('contact_middle_name', $serviceRequest->contact_middle_name) }}">
                            <x-input-error :messages="$errors->get('contact_middle_name')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="office" class="auth-label">Office</label>
                        <input id="office" name="office" type="text" class="auth-input" value="{{ old('office', $serviceRequest->office) }}" required>
                        <x-input-error :messages="$errors->get('office')" class="mt-2" />
                    </div>
                    <div>
                        <label for="address" class="auth-label">Address</label>
                        <input id="address" name="address" type="text" class="auth-input" value="{{ old('address', $serviceRequest->address) }}" required>
                        <x-input-error :messages="$errors->get('address')" class="mt-2" />
                    </div>
                    <div>
                        <label for="landline" class="auth-label">Landline</label>
                        <input id="landline" name="landline" type="text" class="auth-input" value="{{ old('landline', $serviceRequest->landline) }}">
                        <x-input-error :messages="$errors->get('landline')" class="mt-2" />
                    </div>
                    <div>
                        <label for="fax_no" class="auth-label">Fax No.</label>
                        <input id="fax_no" name="fax_no" type="text" class="auth-input" value="{{ old('fax_no', $serviceRequest->fax_no) }}">
                        <x-input-error :messages="$errors->get('fax_no')" class="mt-2" />
                    </div>
                    <div class="sm:col-span-2">
                        <label for="mobile_no" class="auth-label">Mobile No.</label>
                        <input id="mobile_no" name="mobile_no" type="text" class="auth-input" value="{{ old('mobile_no', $serviceRequest->mobile_no) }}" required>
                        <x-input-error :messages="$errors->get('mobile_no')" class="mt-2" />
                    </div>
                </div>

                <div>
                    <label for="description_request" class="auth-label">Description Request</label>
                    <p class="mb-2 text-xs text-slate-500">Please clarify and write down the details of the request.</p>
                    <textarea id="description_request" name="description_request" class="auth-input min-h-32" required>{{ old('description_request', $serviceRequest->description_request) }}</textarea>
                    <x-input-error :messages="$errors->get('description_request')" class="mt-2" />
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                    <h3 class="text-sm font-semibold uppercase tracking-[0.12em] text-slate-700">Approved By</h3>
                    <div class="mt-3 grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="approved_by_name" class="auth-label">Name of Head of Office</label>
                            <input id="approved_by_name" name="approved_by_name" type="text" class="auth-input" value="{{ old('approved_by_name', $serviceRequest->approved_by_name) }}" required>
                            <x-input-error :messages="$errors->get('approved_by_name')" class="mt-2" />
                        </div>
                        <div>
                            <label for="approved_by_position" class="auth-label">Position</label>
                            <input id="approved_by_position" name="approved_by_position" type="text" class="auth-input" value="{{ old('approved_by_position', $serviceRequest->approved_by_position) }}" required>
                            <x-input-error :messages="$errors->get('approved_by_position')" class="mt-2" />
                        </div>
                        <div>
                            <label for="approved_date" class="auth-label">Date Signed</label>
                            <input id="approved_date" name="approved_date" type="date" class="auth-input" value="{{ old('approved_date', $serviceRequest->approved_date->toDateString()) }}" required>
                            <x-input-error :messages="$errors->get('approved_date')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                    <h3 class="text-sm font-semibold uppercase tracking-[0.12em] text-slate-700">For knowledge management and information technology service only</h3>
                    <div class="mt-3 grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="kmits_date" class="auth-label">10. Date</label>
                            <input id="kmits_date" name="kmits_date" type="date" class="auth-input" value="{{ old('kmits_date', optional($serviceRequest->kmits_date)->toDateString() ?? now()->toDateString()) }}" required>
                            <x-input-error :messages="$errors->get('kmits_date')" class="mt-2" />
                        </div>
                        <div>
                            <label for="time_received" class="auth-label">11. Time Received</label>
                            <input id="time_received" name="time_received" type="time" class="auth-input" value="{{ old('time_received', $serviceRequest->time_received) }}">
                            <x-input-error :messages="$errors->get('time_received')" class="mt-2" />
                        </div>
                        <div class="sm:col-span-2">
                            <label for="actions_taken" class="auth-label">12. Actions Taken (use separate if necessary)</label>
                            <textarea id="actions_taken" name="actions_taken" class="auth-input min-h-24">{{ old('actions_taken', $serviceRequest->actions_taken) }}</textarea>
                            <x-input-error :messages="$errors->get('actions_taken')" class="mt-2" />
                        </div>
                    </div>

                    <div class="mt-4 overflow-x-auto rounded-xl border border-slate-200 bg-white">
                        <table class="min-w-full w-full table-fixed text-sm text-slate-700">
                            <thead class="bg-slate-100 text-xs uppercase tracking-[0.08em] text-slate-600">
                                <tr>
                                    <th class="px-3 py-2 text-center">Date</th>
                                    <th class="px-3 py-2 text-center">Time</th>
                                    <th class="px-3 py-2 text-center">Action Taken</th>
                                    <th class="px-3 py-2 text-center">Action Officer</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for ($i = 0; $i < 5; $i++)
                                    <tr class="border-t border-slate-100">
                                        <td class="px-2 py-2"><input name="action_log_date[]" type="date" class="auth-input !min-h-0 w-full py-2" value="{{ old('action_log_date.' . $i, data_get($serviceRequest->action_logs, $i . '.date')) }}"></td>
                                        <td class="px-2 py-2"><input name="action_log_time[]" type="time" class="auth-input !min-h-0 w-full py-2" value="{{ old('action_log_time.' . $i, data_get($serviceRequest->action_logs, $i . '.time')) }}"></td>
                                        <td class="px-2 py-2"><input name="action_log_action_taken[]" type="text" class="auth-input !min-h-0 w-full py-2" value="{{ old('action_log_action_taken.' . $i, data_get($serviceRequest->action_logs, $i . '.action_taken')) }}"></td>
                                        <td class="px-2 py-2"><input name="action_log_action_officer[]" type="text" class="auth-input !min-h-0 w-full py-2" value="{{ old('action_log_action_officer.' . $i, data_get($serviceRequest->action_logs, $i . '.action_officer')) }}"></td>
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 grid gap-4 sm:grid-cols-3">
                        <div>
                            <label for="noted_by_name" class="auth-label">13. Noted by (Name of Supervisor)</label>
                            <input id="noted_by_name" name="noted_by_name" type="text" class="auth-input" value="{{ old('noted_by_name', $serviceRequest->noted_by_name) }}">
                            <x-input-error :messages="$errors->get('noted_by_name')" class="mt-2" />
                        </div>
                        <div>
                            <label for="noted_by_position" class="auth-label">14. Position</label>
                            <input id="noted_by_position" name="noted_by_position" type="text" class="auth-input" value="{{ old('noted_by_position', $serviceRequest->noted_by_position) }}">
                            <x-input-error :messages="$errors->get('noted_by_position')" class="mt-2" />
                        </div>
                        <div>
                            <label for="noted_by_date_signed" class="auth-label">15. Date Signed</label>
                            <input id="noted_by_date_signed" name="noted_by_date_signed" type="date" class="auth-input" value="{{ old('noted_by_date_signed', optional($serviceRequest->noted_by_date_signed)->toDateString()) }}">
                            <x-input-error :messages="$errors->get('noted_by_date_signed')" class="mt-2" />
                        </div>
                    </div>

                    <p class="mt-3 text-xs text-slate-500">Signature fields are excluded from the online form and are for manual signing only.</p>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <button type="submit" class="auth-button">Update Service Request</button>
                    <a href="{{ route('service-requests.show', $serviceRequest) }}" class="rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:border-slate-500 hover:text-slate-900">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
