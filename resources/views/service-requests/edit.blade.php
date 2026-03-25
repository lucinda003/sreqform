<x-app-layout>
    @php
        $isAdmin = strtoupper((string) auth()->user()?->department) === 'ADMIN';
        $hospitalOfficeMap = [
            'Philippine Heart Center' => 'East Avenue, Quezon City, Metro Manila',
            'National Kidney and Transplant Institute' => 'East Avenue, Quezon City, Metro Manila',
            'Lung Center of the Philippines' => 'Quezon Avenue, Quezon City, Metro Manila',
            'Philippine Children\'s Medical Center' => 'Quezon Avenue, Quezon City, Metro Manila',
            'National Center for Mental Health' => 'Nueve de Febrero, Mandaluyong City, Metro Manila',
            'Research Institute for Tropical Medicine' => '9002 Research Dr, Alabang, Muntinlupa',
            'Amang Rodriguez Memorial Medical Center' => 'Sumulong Hwy, Marikina, Metro Manila',
            'Dr. Jose N. Rodriguez Memorial Hospital and Sanitarium' => 'Tala, Caloocan City, Metro Manila',
            'Jose R. Reyes Memorial Medical Center' => 'Rizal Avenue, Sta. Cruz, Manila',
            'San Lazaro Hospital' => 'Quiricada St, Sta. Cruz, Manila',
            'Tondo Medical Center' => 'Honorio Lopez Blvd, Tondo, Manila',
            'Quirino Memorial Medical Center' => 'Project 4, Quezon City, Metro Manila',
            'East Avenue Medical Center' => 'East Avenue, Diliman, Quezon City',
            'Rizal Medical Center' => 'Pasig Blvd, Pasig, Metro Manila',
            'Las Piñas General Hospital and Satellite Trauma Center' => 'P. Diego Cera Ave, Las Piñas',
            'Valenzuela Medical Center' => 'Padrigal St, Karuhatan, Valenzuela',
            'Philippine General Hospital' => 'Taft Ave, Ermita, Manila',
            'Baguio General Hospital and Medical Center' => 'Gov. Pack Rd, Baguio City, Benguet',
            'Ilocos Training and Regional Medical Center' => 'Parian, San Fernando City, La Union',
            'Mariano Marcos Memorial Hospital and Medical Center' => 'Batac City, Ilocos Norte',
            'Cagayan Valley Medical Center' => 'Carig, Tuguegarao City, Cagayan',
            'Jose B. Lingad Memorial General Hospital' => 'San Fernando, Pampanga',
            'Bicol Regional Hospital and Medical Center' => 'Concepcion Pequeña, Naga City, Camarines Sur',
            'Batangas Medical Center' => 'Bihi-Road, Kumintang Ibaba, Batangas City',
            'Western Visayas Medical Center' => 'Q. Abeto St, Mandurriao, Iloilo City',
            'Vicente Sotto Memorial Medical Center' => 'B. Rodriguez St, Cebu City',
            'Corazon Locsin Montelibano Memorial Regional Hospital' => 'Lacson St, Bacolod City',
            'Eastern Visayas Medical Center' => 'Brgy. Bagasumbol, Tacloban City',
            'Zamboanga City Medical Center' => 'Dr. Evangelista St, Zamboanga City',
            'Northern Mindanao Medical Center' => 'Capitol Compound, Cagayan de Oro City',
            'Southern Philippines Medical Center' => 'J.P. Laurel Ave, Davao City',
            'Cotabato Regional and Medical Center' => 'Sinsuat Ave, Cotabato City',
            'Davao Regional Medical Center' => 'Apokon, Tagum City, Davao del Norte',
            'Maguindanao Provincial Hospital' => 'Shariff Aguak, Maguindanao',
        ];
    @endphp

    <div class="mx-auto w-full max-w-6xl py-6">
        <div class="mb-4 rounded-2xl border border-white/70 bg-white/85 p-4 shadow-lg backdrop-blur-xl">
            <div class="flex flex-wrap items-center gap-3">
                <p class="text-sm font-semibold text-slate-700">Status :</p>
                @php
                    $statusClasses = match ($serviceRequest->status) {
                        'checking' => 'border-sky-300 bg-sky-100 text-sky-800',
                        'approved' => 'border-emerald-300 bg-emerald-100 text-emerald-800',
                        'rejected' => 'border-rose-300 bg-rose-100 text-rose-800',
                        default => 'border-amber-300 bg-amber-100 text-amber-800',
                    };
                @endphp
                <span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold uppercase {{ $statusClasses }}">
                    {{ $serviceRequest->status }}
                </span>

                @if ($isAdmin)
                    <div class="ms-auto flex flex-wrap items-center gap-2">
                        <a href="{{ route('service-requests.print', $serviceRequest) }}" target="_blank" class="rounded-xl border border-slate-300 bg-slate-50 px-3 py-1.5 text-xs font-semibold uppercase text-slate-800 transition hover:bg-slate-100">Print</a>
                        <form method="POST" action="{{ route('service-requests.update-status', $serviceRequest) }}" class="flex flex-wrap items-center gap-2">
                            @csrf
                            @method('PATCH')
                            <button type="submit" name="status" value="pending" class="rounded-xl border border-amber-300 bg-amber-50 px-3 py-1.5 text-xs font-semibold uppercase text-amber-800 transition hover:bg-amber-100">Set Pending</button>
                            <button type="submit" name="status" value="approved" class="rounded-xl border border-emerald-300 bg-emerald-50 px-3 py-1.5 text-xs font-semibold uppercase text-emerald-800 transition hover:bg-emerald-100">Approve</button>
                            <button type="submit" name="status" value="rejected" class="rounded-xl border border-rose-300 bg-rose-50 px-3 py-1.5 text-xs font-semibold uppercase text-rose-800 transition hover:bg-rose-100">Reject</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-300 bg-white shadow-lg">
            <form method="POST" action="{{ route('service-requests.update', $serviceRequest) }}" enctype="multipart/form-data" class="space-y-0">
                @csrf
                @method('PUT')

                <fieldset @if ($isAdmin) disabled @endif>

                <div class="px-4 pb-3">
                    <table class="w-full border-collapse text-[12px] text-slate-900">
                        <tr>
                            <td class="border border-slate-400 px-2 py-1 font-semibold">Reference Code :
                                <span class="inline-block min-w-64 border-b border-slate-400 px-1 py-0.5 text-center">{{ $serviceRequest->reference_code }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="border border-slate-400 px-2 py-1">Department for Reference :
                                <span class="inline-block min-w-40 border-b border-slate-400 px-1 py-0.5 text-center">ADMIN</span>
                                <input type="hidden" id="department_code" name="department_code" value="ADMIN">
                                <x-input-error :messages="$errors->get('department_code')" class="mt-1" />
                            </td>
                        </tr>
                        <tr>
                            <td class="border border-slate-400 px-2 py-1">1) Date/Time of Request (mm/dd/yyyy h:m:s) :
                                <div class="ms-2 inline-flex items-center gap-2 align-middle">
                                    <input id="request_date" name="request_date" type="date" class="inline-block min-h-0 w-[170px] rounded-none border-0 border-b border-slate-400 bg-transparent px-0 py-0 text-[12px] align-middle focus:outline-none focus:ring-0" value="{{ old('request_date', $serviceRequest->request_date->toDateString()) }}" required>
                                    <input name="time_received" type="time" value="{{ old('time_received', $serviceRequest->time_received) }}" class="inline-block min-h-0 w-[130px] rounded-none border-0 border-b border-slate-400 bg-transparent px-0 py-0 text-[12px] align-middle focus:outline-none focus:ring-0">
                                </div>
                                <x-input-error :messages="$errors->get('request_date')" class="mt-1" />
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="px-4 pb-3">
                    <table class="w-full border-collapse text-[12px] text-slate-900">
                        <tr>
                            <td class="border border-slate-500 px-2 py-1">2) Request Category :
                                <select id="request_category" name="request_category" class="auth-input !inline-block align-middle !min-h-0 !w-[260px] !rounded-none !border-0 border-b border-slate-200 !bg-transparent px-0 py-0 text-[12px]">
                                    <option value="Technical Assistance" @selected(old('request_category', $serviceRequest->request_category) === 'Technical Assistance')>Technical Assistance</option>
                                    <option value="System Access" @selected(old('request_category', $serviceRequest->request_category) === 'System Access')>System Access</option>
                                    <option value="Network/Internet" @selected(old('request_category', $serviceRequest->request_category) === 'Network/Internet')>Network/Internet</option>
                                    <option value="Hardware Support" @selected(old('request_category', $serviceRequest->request_category) === 'Hardware Support')>Hardware Support</option>
                                    <option value="Software Installation" @selected(old('request_category', $serviceRequest->request_category) === 'Software Installation')>Software Installation</option>
                                    <option value="Data Request" @selected(old('request_category', $serviceRequest->request_category) === 'Data Request')>Data Request</option>
                                    <option value="Others" @selected(old('request_category', $serviceRequest->request_category) === 'Others')>Others</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="border border-slate-400 px-2 py-1">3) Application System Name : <input type="text" name="application_system_name" value="{{ old('application_system_name', $serviceRequest->application_system_name) }}" class="auth-input !inline-block !min-h-0 !w-[320px] !rounded-none !border-0 !bg-transparent px-1 py-0 text-[12px]"></td>
                        </tr>
                        <tr>
                            <td class="border border-slate-400 px-2 py-1">4) Expected Date / Time of Completion :
                                <input type="date" name="expected_completion_date" value="{{ old('expected_completion_date', optional($serviceRequest->expected_completion_date)->toDateString()) }}" class="inline-block min-h-0 w-[170px] rounded-none border-0 border-b border-slate-200 bg-transparent px-0 py-0 text-[12px] align-middle focus:outline-none focus:ring-0">
                                <input type="time" name="expected_completion_time" value="{{ old('expected_completion_time', $serviceRequest->expected_completion_time) }}" class="ms-2 inline-block min-h-0 w-[130px] rounded-none border-0 border-b border-slate-200 bg-transparent px-0 py-0 text-[12px] align-middle focus:outline-none focus:ring-0">
                            </td>
                        </tr>
                        <tr>
                            <td class="border border-slate-400 p-0">
                                <table class="w-full border-collapse table-fixed text-[12px]">
                                    <tr>
                                        <td class="border-0 px-2 py-1" style="width:32%;">5) Name of Contact Person :</td>
                                        <td class="border-0 border-b border-slate-400 px-1 py-1" style="width:17%;">
                                            <input name="contact_last_name" value="{{ old('contact_last_name', $serviceRequest->contact_last_name) }}" class="auth-input !min-h-0 !rounded-none !border-0 !bg-transparent px-0 py-0 text-center text-[12px]" required>
                                        </td>
                                        <td class="border-0 border-b border-slate-400 px-1 py-1" style="width:17%;">
                                            <input name="contact_first_name" value="{{ old('contact_first_name', $serviceRequest->contact_first_name) }}" class="auth-input !min-h-0 !rounded-none !border-0 !bg-transparent px-0 py-0 text-center text-[12px]" required>
                                        </td>
                                        <td class="border-0 border-b border-slate-400 px-1 py-1" style="width:17%;">
                                            <input name="contact_middle_name" value="{{ old('contact_middle_name', $serviceRequest->contact_middle_name) }}" class="auth-input !min-h-0 !rounded-none !border-0 !bg-transparent px-0 py-0 text-center text-[12px]">
                                        </td>
                                        <td class="border-0 border-b border-slate-400 px-1 py-1" style="width:17%;">
                                            <input type="text" name="contact_suffix_name" value="{{ old('contact_suffix_name', $serviceRequest->contact_suffix_name) }}" class="auth-input !min-h-0 !rounded-none !border-0 !bg-transparent px-0 py-0 text-center text-[12px]">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="border-0 px-2 py-1"></td>
                                        <td class="border-0 px-1 py-1 text-center">Last Name</td>
                                        <td class="border-0 px-1 py-1 text-center">First Name</td>
                                        <td class="border-0 px-1 py-1 text-center">Middle Name</td>
                                        <td class="border-0 px-1 py-1 text-center">Suffix Name</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td class="border border-slate-400 px-2 py-1">6) Office :
                                <input id="office" list="hospital-office-options" name="office" value="{{ old('office', $serviceRequest->office) }}" autocomplete="off" class="auth-input !inline-block !min-h-0 !w-[450px] !rounded-none !border-0 !bg-transparent px-1 py-0 text-[12px]" required>
                                <p class="mt-1 text-[11px] text-slate-500">Type or pick from the regional hospital list.</p>
                            </td>
                        </tr>
                        <tr>
                            <td class="border border-slate-400 px-2 py-1">7) Address :
                                <input id="address" name="address" value="{{ old('address', $serviceRequest->address) }}" class="auth-input !inline-block !min-h-0 !w-[450px] !rounded-none !border-0 !bg-transparent px-1 py-0 text-[12px]" required>
                            </td>
                        </tr>
                        <tr>
                            <td class="border border-slate-400 p-0">
                                <table class="w-full border-collapse table-fixed text-[12px]">
                                    <tr>
                                        <td class="border-0 border-r border-slate-400 px-2 py-1" style="width:23%;">8) Landline :
                                            <input name="landline" value="{{ old('landline', $serviceRequest->landline) }}" inputmode="numeric" pattern="[0-9]*" oninput="this.value=this.value.replace(/[^0-9]/g,'');" class="auth-input !min-h-0 !rounded-none !border-0 !bg-transparent px-0 py-0 text-[12px]">
                                        </td>
                                        <td class="border-0 border-r border-slate-400 px-2 py-1" style="width:23%;">9) Fax No :
                                            <input name="fax_no" value="{{ old('fax_no', $serviceRequest->fax_no) }}" inputmode="numeric" pattern="[0-9]*" oninput="this.value=this.value.replace(/[^0-9]/g,'');" class="auth-input !min-h-0 !rounded-none !border-0 !bg-transparent px-0 py-0 text-[12px]">
                                        </td>
                                        <td class="border-0 border-r border-slate-400 px-2 py-1" style="width:23%;">10) Mobile No :
                                            <input name="mobile_no" value="{{ old('mobile_no', $serviceRequest->mobile_no) }}" inputmode="numeric" pattern="[0-9]*" oninput="this.value=this.value.replace(/[^0-9]/g,'');" class="auth-input !min-h-0 !rounded-none !border-0 !bg-transparent px-0 py-0 text-[12px]" required>
                                        </td>
                                        <td class="border-0 px-2 py-1" style="width:31%;">11) Email Address :
                                            <input type="text" name="email_address" value="{{ old('email_address', $serviceRequest->email_address) }}" class="auth-input !min-h-0 !rounded-none !border-0 !bg-transparent px-0 py-0 text-[12px]">
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="px-4 pb-3">
                    <div class="border border-slate-400 border-b-4 px-2 py-1 text-[12px] font-semibold">12) DESCRIPTION OF REQUEST : <span class="font-normal italic">(Please clearly write down the details of the request.)</span></div>
                        <div class="border border-t-0 border-slate-400 border-b-4 px-2 py-1">
                            <textarea name="description_request" style="height: 240px; min-height: 240px;" class="auth-input !h-[240px] !min-h-[240px] !rounded-none !border-0 !bg-transparent px-0 py-0 text-[12px]" required>{{ old('description_request', $serviceRequest->description_request) }}</textarea>

                            @if ($isAdmin)
                                <div class="mt-3 border-t border-slate-300 pt-2">
                                    <p class="text-[12px] font-semibold text-slate-700">Uploaded Photos</p>

                                    <div id="uploaded-photos-content" class="mt-2">
                                        @if (is_array($serviceRequest->description_photos) && count($serviceRequest->description_photos) > 0)
                                            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 md:grid-cols-3">
                                                @foreach ($serviceRequest->description_photos as $photoPath)
                                                    <a href="{{ \Illuminate\Support\Facades\Storage::url($photoPath) }}" target="_blank" class="block overflow-hidden rounded-lg border border-slate-300 bg-white">
                                                        <img src="{{ \Illuminate\Support\Facades\Storage::url($photoPath) }}" alt="Service Request Photo" class="h-32 w-full object-cover">
                                                    </a>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-[12px] text-slate-600">No uploaded photos for this request yet.</p>
                                        @endif
                                    </div>
                                </div>
                            @endif
                    </div>
                    <x-input-error :messages="$errors->get('description_request')" class="mt-1" />
                </div>

                <div class="px-4 pb-3">
                    <table class="w-full border-collapse text-[12px] text-slate-900">
                        <tr>
                            <td class="w-48 border border-slate-400 px-2 py-1 font-semibold">13) APPROVED BY :</td>
                            <td class="border border-slate-400 px-2 py-1">
                                <div class="grid grid-cols-10 gap-3">
                                    <div class="col-span-6">
                                        <input name="approved_by_name" value="{{ old('approved_by_name', $serviceRequest->approved_by_name) }}" class="auth-input !min-h-0 !rounded-none !border-0 border-b border-slate-400 !bg-transparent px-0 py-0 text-[12px]" required>
                                        <p class="text-center">Name &amp; Signature of Head of Office</p>

                                        <input name="approved_by_position" value="{{ old('approved_by_position', $serviceRequest->approved_by_position) }}" class="mt-2 auth-input !min-h-0 !rounded-none !border-0 border-b border-slate-400 !bg-transparent px-0 py-0 text-[12px]" required>
                                        <p class="text-center">Position</p>
                                    </div>
                                    <div class="col-span-4">
                                        <input name="approved_date" type="date" value="{{ old('approved_date', $serviceRequest->approved_date->toDateString()) }}" class="auth-input !min-h-0 !rounded-none !border-0 border-b border-slate-400 !bg-transparent px-0 py-0 text-[12px]" required>
                                        <p class="text-center">Date Signed</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                </fieldset>

                @if ($isAdmin)
                    @php
                        $existingLogs = $serviceRequest->action_logs ?? [];
                        $logDates = old('action_log_date', collect($existingLogs)->pluck('date')->pad(5, '')->values()->all());
                        $logTimes = old('action_log_time', collect($existingLogs)->pluck('time')->pad(5, '')->values()->all());
                        $logActionDates = old('action_log_action_date', collect($existingLogs)->pluck('action_date')->pad(5, '')->values()->all());
                        $logActionTimes = old('action_log_action_time', collect($existingLogs)->pluck('action_time')->pad(5, '')->values()->all());
                        $logActions = old('action_log_action_taken', collect($existingLogs)->pluck('action_taken')->pad(5, '')->values()->all());
                        $logOfficers = old('action_log_action_officer', collect($existingLogs)->pluck('action_officer')->pad(5, '')->values()->all());
                    @endphp

                    <div class="px-4 pb-3">
                        <div class="rounded-xl border border-slate-300 bg-slate-50 p-3">
                            <h3 class="text-[12px] font-semibold uppercase tracking-[0.08em] text-slate-700">For knowledge management and information technology service only</h3>

                            <div class="mt-3 grid gap-3 md:grid-cols-3">
                                <label class="block text-[12px] text-slate-700">
                                    <span class="font-semibold">10. Date</span>
                                    <input type="date" name="kmits_date" value="{{ old('kmits_date', optional($serviceRequest->kmits_date)->toDateString() ?? now()->toDateString()) }}" class="mt-1 w-full rounded-md border-slate-300 text-[12px] shadow-sm focus:border-sky-500 focus:ring-sky-500">
                                </label>

                                <label class="block text-[12px] text-slate-700">
                                    <span class="font-semibold">11. Time Received</span>
                                    <input type="time" name="time_received" value="{{ old('time_received', $serviceRequest->time_received) }}" class="mt-1 w-full rounded-md border-slate-300 text-[12px] shadow-sm focus:border-sky-500 focus:ring-sky-500">
                                </label>

                                <label class="block text-[12px] text-slate-700 md:col-span-3">
                                    <span class="font-semibold">12. Actions Taken</span>
                                    <textarea name="actions_taken" rows="2" class="mt-1 w-full rounded-md border-slate-300 text-[12px] shadow-sm focus:border-sky-500 focus:ring-sky-500">{{ old('actions_taken', $serviceRequest->actions_taken) }}</textarea>
                                </label>
                            </div>

                            <div class="mt-4 overflow-x-auto rounded-lg border border-slate-300 bg-white">
                                <table class="min-w-full border-collapse text-[12px] text-slate-800">
                                    <thead class="bg-slate-100">
                                        <tr>
                                            <th class="border border-slate-300 px-2 py-1 text-left">Date (a) Received</th>
                                            <th class="border border-slate-300 px-2 py-1 text-left">Time (b) Received</th>
                                            <th class="border border-slate-300 px-2 py-1 text-left">Date (c) Accomplish</th>
                                            <th class="border border-slate-300 px-2 py-1 text-left">Time (d) Accomplish</th>
                                            <th class="border border-slate-300 px-2 py-1 text-left">Action Taken</th>
                                            <th class="border border-slate-300 px-2 py-1 text-left">Action Officer</th>
                                            <th class="border border-slate-300 px-2 py-1 text-left">Signature</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for ($i = 0; $i < 5; $i++)
                                            <tr>
                                                <td class="border border-slate-300 px-2 py-1"><input type="date" name="action_log_date[]" value="{{ $logDates[$i] ?? '' }}" class="w-full rounded-md border-slate-300 text-[12px] shadow-sm focus:border-sky-500 focus:ring-sky-500"></td>
                                                <td class="border border-slate-300 px-2 py-1"><input type="time" name="action_log_time[]" value="{{ $logTimes[$i] ?? '' }}" class="w-full rounded-md border-slate-300 text-[12px] shadow-sm focus:border-sky-500 focus:ring-sky-500"></td>
                                                <td class="border border-slate-300 px-2 py-1"><input type="date" name="action_log_action_date[]" value="{{ $logActionDates[$i] ?? '' }}" class="w-full rounded-md border-slate-300 text-[12px] shadow-sm focus:border-sky-500 focus:ring-sky-500"></td>
                                                <td class="border border-slate-300 px-2 py-1"><input type="time" name="action_log_action_time[]" value="{{ $logActionTimes[$i] ?? '' }}" class="w-full rounded-md border-slate-300 text-[12px] shadow-sm focus:border-sky-500 focus:ring-sky-500"></td>
                                                <td class="border border-slate-300 px-2 py-1"><input type="text" name="action_log_action_taken[]" value="{{ $logActions[$i] ?? '' }}" class="w-full rounded-md border-slate-300 text-[12px] shadow-sm focus:border-sky-500 focus:ring-sky-500"></td>
                                                <td class="border border-slate-300 px-2 py-1"><input type="text" name="action_log_action_officer[]" value="{{ $logOfficers[$i] ?? '' }}" class="w-full rounded-md border-slate-300 text-[12px] shadow-sm focus:border-sky-500 focus:ring-sky-500"></td>
                                                <td class="border border-slate-300 px-2 py-1 text-center text-[11px] text-slate-500">________________</td>
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4 grid gap-3 md:grid-cols-3">
                                <label class="block text-[12px] text-slate-700 md:col-span-3">
                                    <span class="font-semibold">13. Noted by (Name of Supervisor)</span>
                                    <input type="text" name="noted_by_name" value="{{ old('noted_by_name', $serviceRequest->noted_by_name) }}" class="mt-1 w-full rounded-md border-slate-300 text-[12px] shadow-sm focus:border-sky-500 focus:ring-sky-500">
                                </label>

                                <div class="text-[12px] text-slate-700">
                                    <span class="font-semibold">Signature</span>
                                    <div class="mt-2 h-[34px] rounded-md border border-dashed border-slate-300 bg-slate-50"></div>
                                </div>

                                <label class="block text-[12px] text-slate-700">
                                    <span class="font-semibold">14. Position</span>
                                    <input type="text" name="noted_by_position" value="{{ old('noted_by_position', $serviceRequest->noted_by_position) }}" class="mt-1 w-full rounded-md border-slate-300 text-[12px] shadow-sm focus:border-sky-500 focus:ring-sky-500">
                                </label>

                                <label class="block text-[12px] text-slate-700">
                                    <span class="font-semibold">15. Date Signed</span>
                                    <input type="date" name="noted_by_date_signed" value="{{ old('noted_by_date_signed', optional($serviceRequest->noted_by_date_signed)->toDateString()) }}" class="mt-1 w-full rounded-md border-slate-300 text-[12px] shadow-sm focus:border-sky-500 focus:ring-sky-500">
                                </label>
                            </div>

                            <x-input-error :messages="$errors->get('kmits_date')" class="mt-2" />
                            <x-input-error :messages="$errors->get('time_received')" class="mt-1" />
                            <x-input-error :messages="$errors->get('actions_taken')" class="mt-1" />
                            <x-input-error :messages="$errors->get('action_log_date')" class="mt-1" />
                            <x-input-error :messages="$errors->get('action_log_time')" class="mt-1" />
                            <x-input-error :messages="$errors->get('action_log_action_date')" class="mt-1" />
                            <x-input-error :messages="$errors->get('action_log_action_time')" class="mt-1" />
                            <x-input-error :messages="$errors->get('action_log_action_taken')" class="mt-1" />
                            <x-input-error :messages="$errors->get('action_log_action_officer')" class="mt-1" />
                            <x-input-error :messages="$errors->get('noted_by_name')" class="mt-1" />
                            <x-input-error :messages="$errors->get('noted_by_position')" class="mt-1" />
                            <x-input-error :messages="$errors->get('noted_by_date_signed')" class="mt-1" />
                        </div>
                    </div>
                @else
                    <input type="hidden" name="kmits_date" value="{{ old('kmits_date', optional($serviceRequest->kmits_date)->toDateString() ?? now()->toDateString()) }}">
                @endif

                <div class="flex items-center justify-between border-t border-slate-300 bg-slate-50 px-4 py-3">
                    <a href="{{ route('service-requests.index') }}" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:border-slate-500">Cancel</a>
                    <button type="submit" class="auth-button">Update Service Request</button>
                </div>
            </form>
        </div>
    </div>
    <datalist id="hospital-office-options">
        @foreach (array_keys($hospitalOfficeMap) as $hospitalOfficeOption)
            <option value="{{ $hospitalOfficeOption }}"></option>
        @endforeach
    </datalist>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const officeInput = document.getElementById('office');
            const addressInput = document.getElementById('address');
            const optionsList = document.getElementById('hospital-office-options');
            const officeAddressMap = @json($hospitalOfficeMap);

            if (!officeInput || !optionsList) {
                return;
            }

            const staticOptions = Object.keys(officeAddressMap);
            const initialDatalistOptions = Array.from(optionsList.querySelectorAll('option')).map(function (option) {
                return option.value;
            });

            if (initialDatalistOptions.length === 0) {
                return;
            }

            const setOptions = function (items) {
                optionsList.innerHTML = '';
                items.forEach(function (item) {
                    const option = document.createElement('option');
                    option.value = item;
                    optionsList.appendChild(option);
                });
            };

            officeInput.addEventListener('input', function () {
                const term = officeInput.value.trim();
                const termLower = term.toLowerCase();

                if (termLower === '') {
                    setOptions(staticOptions.slice(0, 50));
                    return;
                }

                const startsWithMatches = staticOptions.filter(function (item) {
                    return item.toLowerCase().startsWith(termLower);
                });
                const containsMatches = staticOptions.filter(function (item) {
                    return item.toLowerCase().includes(termLower);
                });
                const filtered = (startsWithMatches.length > 0 ? startsWithMatches : containsMatches).slice(0, 50);
                setOptions(filtered);
            });

            const syncAddress = function () {
                if (!addressInput) {
                    return;
                }

                const selectedOffice = officeInput.value.trim();
                const mappedAddress = officeAddressMap[selectedOffice] || '';

                if (mappedAddress !== '') {
                    addressInput.value = mappedAddress;
                }
            };

            officeInput.addEventListener('change', syncAddress);
            officeInput.addEventListener('blur', syncAddress);
        });

    </script>
</x-app-layout>
