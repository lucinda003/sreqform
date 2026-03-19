<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="auth-title">Service Request Form</h2>
            <p class="auth-subtitle">KMITS paper-style encoding form.</p>
        </div>
    </x-slot>

    <div class="mx-auto w-full max-w-6xl py-6">
        <div class="overflow-hidden rounded-2xl border border-slate-300 bg-white shadow-lg">
            <form method="POST" action="{{ route('service-requests.store') }}" class="space-y-0">
                @csrf

                <div class="border-b border-slate-300 px-4 py-3">
                    <table class="w-full border-collapse text-[11px] text-slate-800">
                        <tr>
                            <td class="w-16 border border-slate-400 align-top p-1 text-center">
                                <img src="{{ asset('images/dohlogo.svg') }}" alt="DOH Logo" class="mx-auto h-10 w-10 object-contain">
                            </td>
                            <td class="border border-slate-400 px-2 py-1 align-top">
                                <div class="font-semibold">Knowledge Management and Information Technology Service</div>
                                <div class="mt-2 border-t border-dashed border-slate-400 pt-2 text-center text-sm font-semibold">Service Request Form</div>
                            </td>
                            <td class="w-52 border border-slate-400 p-1 align-top">
                                <table class="w-full text-[10px]">
                                    <tr><td class="border-b border-slate-300 px-1 py-0.5">Page No.</td><td class="border-b border-slate-300 px-1 py-0.5 text-right">Page 1 of 1</td></tr>
                                    <tr><td class="border-b border-slate-300 px-1 py-0.5">Revision</td><td class="border-b border-slate-300 px-1 py-0.5 text-right">0</td></tr>
                                    <tr><td class="px-1 py-0.5">Effectivity</td><td class="px-1 py-0.5 text-right">May 02, 2014</td></tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="px-4 py-3">
                    <table class="w-full border-collapse text-[11px] text-slate-800">
                        <tr>
                            <td class="w-1/2 border border-slate-400 px-2 py-1 font-semibold">Reference Code:</td>
                            <td class="border border-slate-400 px-2 py-1">
                                <input id="department_code" name="department_code" class="auth-input !min-h-0 !rounded-none !border-0 !bg-transparent px-0 py-0 text-[11px]" required list="department-code-list" value="{{ old('department_code') }}" placeholder="Select approved department role">
                                <datalist id="department-code-list">
                                    @foreach ($departmentOptions as $departmentOption)
                                        <option value="{{ $departmentOption }}"></option>
                                    @endforeach
                                </datalist>
                                <x-input-error :messages="$errors->get('department_code')" class="mt-1" />
                            </td>
                        </tr>
                        <tr>
                            <td class="w-1/2 border border-slate-400 px-2 py-1">Date of Request (mm/dd/yyyy):</td>
                            <td class="border border-slate-400 px-2 py-1">
                                <input id="request_date" name="request_date" type="date" class="auth-input !min-h-0 !rounded-none !border-0 !bg-transparent px-0 py-0 text-[11px]" value="{{ old('request_date', now()->toDateString()) }}" required>
                                <x-input-error :messages="$errors->get('request_date')" class="mt-1" />
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="px-4 pb-3">
                    <table class="w-full border-collapse text-[11px] text-slate-800">
                        <tr>
                            <td class="w-14 border border-slate-400 px-1 py-1">2)</td>
                            <td class="border border-slate-400 px-2 py-1">Name of Contact Person:</td>
                            <td class="w-1/4 border border-slate-400 px-1 py-1">
                                <input name="contact_last_name" value="{{ old('contact_last_name') }}" class="auth-input !min-h-0 !rounded-none !border-0 !bg-transparent px-0 py-0 text-[11px]" placeholder="Last Name" required>
                            </td>
                            <td class="w-1/4 border border-slate-400 px-1 py-1">
                                <input name="contact_first_name" value="{{ old('contact_first_name') }}" class="auth-input !min-h-0 !rounded-none !border-0 !bg-transparent px-0 py-0 text-[11px]" placeholder="First Name" required>
                            </td>
                            <td class="w-1/4 border border-slate-400 px-1 py-1">
                                <input name="contact_middle_name" value="{{ old('contact_middle_name') }}" class="auth-input !min-h-0 !rounded-none !border-0 !bg-transparent px-0 py-0 text-[11px]" placeholder="Middle Name">
                            </td>
                        </tr>
                        <tr>
                            <td class="border border-slate-400 px-1 py-1">3)</td>
                            <td class="border border-slate-400 px-2 py-1">Office:</td>
                            <td colspan="3" class="border border-slate-400 px-1 py-1">
                                <input name="office" value="{{ old('office') }}" class="auth-input !min-h-0 !rounded-none !border-0 !bg-transparent px-0 py-0 text-[11px]" required>
                            </td>
                        </tr>
                        <tr>
                            <td class="border border-slate-400 px-1 py-1">4)</td>
                            <td class="border border-slate-400 px-2 py-1">Address:</td>
                            <td colspan="3" class="border border-slate-400 px-1 py-1">
                                <input name="address" value="{{ old('address') }}" class="auth-input !min-h-0 !rounded-none !border-0 !bg-transparent px-0 py-0 text-[11px]" required>
                            </td>
                        </tr>
                        <tr>
                            <td class="border border-slate-400 px-1 py-1">5)</td>
                            <td class="border border-slate-400 px-2 py-1">Landline:</td>
                            <td class="border border-slate-400 px-1 py-1">
                                <input name="landline" value="{{ old('landline') }}" class="auth-input !min-h-0 !rounded-none !border-0 !bg-transparent px-0 py-0 text-[11px]">
                            </td>
                            <td class="border border-slate-400 px-2 py-1">6) Fax No.</td>
                            <td class="border border-slate-400 px-1 py-1">
                                <input name="fax_no" value="{{ old('fax_no') }}" class="auth-input !min-h-0 !rounded-none !border-0 !bg-transparent px-0 py-0 text-[11px]">
                            </td>
                        </tr>
                        <tr>
                            <td class="border border-slate-400 px-1 py-1">7)</td>
                            <td class="border border-slate-400 px-2 py-1">Mobile No.</td>
                            <td colspan="3" class="border border-slate-400 px-1 py-1">
                                <input name="mobile_no" value="{{ old('mobile_no') }}" class="auth-input !min-h-0 !rounded-none !border-0 !bg-transparent px-0 py-0 text-[11px]" required>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" class="border border-slate-400 bg-slate-100 px-2 py-1 text-[11px] font-semibold">8) DESCRIPTION OF REQUEST: <span class="font-normal italic">(Please clarify write down the details of the request.)</span></td>
                        </tr>
                        <tr>
                            <td colspan="5" class="border border-slate-400 px-2 py-1">
                                <textarea name="description_request" class="auth-input !min-h-[120px] !rounded-none !border-0 !bg-transparent px-0 py-0 text-[11px]" required>{{ old('description_request') }}</textarea>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" class="border border-slate-400 px-2 py-1">
                                <div class="grid grid-cols-12 gap-2">
                                    <div class="col-span-2 text-[11px] font-semibold">9. APPROVED BY:</div>
                                    <div class="col-span-5">
                                        <input name="approved_by_name" value="{{ old('approved_by_name') }}" class="auth-input !min-h-0 !rounded-none !border-0 border-b border-slate-400 !bg-transparent px-0 py-0 text-[11px]" placeholder="Name & Signature of Head of Office" required>
                                    </div>
                                    <div class="col-span-5">
                                        <input name="approved_date" type="date" value="{{ old('approved_date', now()->toDateString()) }}" class="auth-input !min-h-0 !rounded-none !border-0 border-b border-slate-400 !bg-transparent px-0 py-0 text-[11px]" required>
                                    </div>
                                    <div class="col-span-2"></div>
                                    <div class="col-span-5">
                                        <input name="approved_by_position" value="{{ old('approved_by_position') }}" class="auth-input !min-h-0 !rounded-none !border-0 border-b border-slate-400 !bg-transparent px-0 py-0 text-[11px]" placeholder="Position" required>
                                    </div>
                                    <div class="col-span-5"></div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" class="border border-slate-400 bg-slate-100 px-2 py-1 text-center text-[11px] font-semibold">(For Knowledge Management and Information Technology Service only)</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="border border-slate-400 px-2 py-1">10. Date Received (mm/dd/yyyy):
                                <input name="kmits_date" type="date" value="{{ old('kmits_date', now()->toDateString()) }}" class="auth-input !min-h-0 !rounded-none !border-0 !bg-transparent px-0 py-0 text-[11px]" required>
                            </td>
                            <td colspan="2" class="border border-slate-400 px-2 py-1">11. Time Received (hh:mm):
                                <input name="time_received" type="time" value="{{ old('time_received') }}" class="auth-input !min-h-0 !rounded-none !border-0 !bg-transparent px-0 py-0 text-[11px]">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" class="border border-slate-400 px-2 py-1 font-semibold">12. ACTIONS TAKEN: <span class="font-normal italic">(Use separate sheet if necessary)</span></td>
                        </tr>
                        <tr>
                            <td colspan="5" class="border border-slate-400 p-0">
                                <table class="w-full border-collapse text-[11px]">
                                    <thead>
                                        <tr class="bg-slate-100">
                                            <th class="border border-slate-400 px-2 py-1">DATE</th>
                                            <th class="border border-slate-400 px-2 py-1">TIME</th>
                                            <th class="border border-slate-400 px-2 py-1">ACTION TAKEN</th>
                                            <th class="border border-slate-400 px-2 py-1">ACTION OFFICER</th>
                                            <th class="border border-slate-400 px-2 py-1">SIGNATURE</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for ($i = 0; $i < 5; $i++)
                                            <tr>
                                                <td class="border border-slate-300 px-1 py-1"><input name="action_log_date[]" type="date" value="{{ old('action_log_date.' . $i) }}" class="auth-input !min-h-0 !rounded-none !border-0 !bg-transparent px-0 py-0 text-[11px]"></td>
                                                <td class="border border-slate-300 px-1 py-1"><input name="action_log_time[]" type="time" value="{{ old('action_log_time.' . $i) }}" class="auth-input !min-h-0 !rounded-none !border-0 !bg-transparent px-0 py-0 text-[11px]"></td>
                                                <td class="border border-slate-300 px-1 py-1"><input name="action_log_action_taken[]" type="text" value="{{ old('action_log_action_taken.' . $i) }}" class="auth-input !min-h-0 !rounded-none !border-0 !bg-transparent px-0 py-0 text-[11px]"></td>
                                                <td class="border border-slate-300 px-1 py-1"><input name="action_log_action_officer[]" type="text" value="{{ old('action_log_action_officer.' . $i) }}" class="auth-input !min-h-0 !rounded-none !border-0 !bg-transparent px-0 py-0 text-[11px]"></td>
                                                <td class="border border-slate-300 px-2 py-1 text-slate-500">Manual signature</td>
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td class="border border-slate-400 px-2 py-1">13. NOTED BY:</td>
                            <td class="border border-slate-400 px-1 py-1">
                                <input name="noted_by_name" value="{{ old('noted_by_name') }}" class="auth-input !min-h-0 !rounded-none !border-0 !bg-transparent px-0 py-0 text-[11px]" placeholder="Name and Signature of Supervisor">
                            </td>
                            <td class="border border-slate-400 px-1 py-1 text-center">14.
                                <input name="noted_by_position" value="{{ old('noted_by_position') }}" class="auth-input !min-h-0 !rounded-none !border-0 !bg-transparent px-0 py-0 text-[11px] text-center" placeholder="Position">
                            </td>
                            <td colspan="2" class="border border-slate-400 px-1 py-1 text-center">15.
                                <input name="noted_by_date_signed" type="date" value="{{ old('noted_by_date_signed') }}" class="auth-input !min-h-0 !rounded-none !border-0 !bg-transparent px-0 py-0 text-[11px] text-center" placeholder="Date Signed">
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="flex items-center justify-between border-t border-slate-300 bg-slate-50 px-4 py-3">
                    <a href="{{ route('service-requests.index') }}" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:border-slate-500">Back</a>
                    <button type="submit" class="auth-button">Submit Service Request</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
