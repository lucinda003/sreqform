<x-app-layout>
    @php
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

    <x-slot name="header">
        <div>
            <h2 class="auth-title">Service Request Form</h2>
            <p class="auth-subtitle">KMITS paper layout encoding form.</p>
        </div>
    </x-slot>

    <div class="mx-auto w-full max-w-6xl py-6">
        <div class="overflow-hidden rounded-2xl border border-slate-300 bg-white shadow-lg">
            <form method="POST" action="{{ route('service-requests.store') }}" class="space-y-0">
                @csrf

                <div class="px-4 pb-3">
                    <table class="w-full border-collapse text-[12px] text-slate-900">
                        <tr>
                            <td class="border border-slate-400 px-2 py-1 font-semibold">Reference Code :
                                <span class="inline-block min-w-64 border-b border-slate-400 px-1 py-0.5 text-center">Auto-generated after submit</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="border border-slate-400 px-2 py-1">Department for Reference :
                                <select id="department_code" name="department_code" class="auth-input !min-h-0 !w-[260px] !rounded-none !border-0 border-b border-slate-400 !bg-transparent px-0 py-0 text-[12px]" required>
                                    <option value="" disabled @selected(old('department_code') === null)>Select approved department role</option>
                                    @foreach ($departmentOptions as $departmentOption)
                                        <option value="{{ $departmentOption }}" @selected(old('department_code') === $departmentOption)>{{ $departmentOption }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('department_code')" class="mt-1" />
                            </td>
                        </tr>
                        <tr>
                            <td class="border border-slate-400 px-2 py-1">1) Date/Time of Request (mm/dd/yyyy h:m:s) :
                                <div class="ms-2 inline-flex items-center gap-2 align-middle">
                                    <input id="request_date" name="request_date" type="date" class="inline-block min-h-0 w-[170px] rounded-none border-0 border-b border-slate-400 bg-transparent px-0 py-0 text-[12px] align-middle focus:outline-none focus:ring-0" value="{{ old('request_date', now()->toDateString()) }}" required>
                                    <input name="time_received" type="time" value="{{ old('time_received', now()->format('H:i')) }}" class="inline-block min-h-0 w-[130px] rounded-none border-0 border-b border-slate-400 bg-transparent px-0 py-0 text-[12px] align-middle focus:outline-none focus:ring-0">
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
                                <select id="request_category" name="request_category" class="inline-block align-middle min-h-0 w-[260px] rounded-none border-0 border-b border-slate-200 bg-transparent px-0 py-0 text-[12px] focus:outline-none focus:ring-0">
                                    <option value="Technical Assistance" @selected(old('request_category') === 'Technical Assistance')>Technical Assistance</option>
                                    <option value="System Access" @selected(old('request_category') === 'System Access')>System Access</option>
                                    <option value="Network/Internet" @selected(old('request_category') === 'Network/Internet')>Network/Internet</option>
                                    <option value="Hardware Support" @selected(old('request_category') === 'Hardware Support')>Hardware Support</option>
                                    <option value="Software Installation" @selected(old('request_category') === 'Software Installation')>Software Installation</option>
                                    <option value="Data Request" @selected(old('request_category') === 'Data Request')>Data Request</option>
                                    <option value="Others" @selected(old('request_category') === 'Others')>Others</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="border border-slate-400 px-2 py-1">3) Application System Name : <input type="text" name="application_system_name" value="{{ old('application_system_name') }}" class="auth-input !inline-block !min-h-0 !w-[320px] !rounded-none !border-0 !bg-transparent px-1 py-0 text-[12px]"></td>
                        </tr>
                        <tr>
                            <td class="border border-slate-400 px-2 py-1">4) Expected Date / Time of Completion :
                                <input type="date" name="expected_completion_date" value="{{ old('expected_completion_date') }}" class="inline-block min-h-0 w-[170px] rounded-none border-0 border-b border-slate-200 bg-transparent px-0 py-0 text-[12px] align-middle focus:outline-none focus:ring-0">
                                <input type="time" name="expected_completion_time" value="{{ old('expected_completion_time') }}" class="ms-2 inline-block min-h-0 w-[130px] rounded-none border-0 border-b border-slate-200 bg-transparent px-0 py-0 text-[12px] align-middle focus:outline-none focus:ring-0">
                            </td>
                        </tr>
                        <tr>
                            <td class="border border-slate-400 p-0">
                                <table class="w-full border-collapse table-fixed text-[12px]">
                                    <tr>
                                        <td class="border-0 px-2 py-1" style="width:32%;">5) Name of Contact Person :</td>
                                        <td class="border-0 border-b border-slate-400 px-1 py-1" style="width:17%;">
                                            <input name="contact_last_name" value="{{ old('contact_last_name') }}" class="auth-input !min-h-0 !rounded-none !border-0 !bg-transparent px-0 py-0 text-center text-[12px]" required>
                                        </td>
                                        <td class="border-0 border-b border-slate-400 px-1 py-1" style="width:17%;">
                                            <input name="contact_first_name" value="{{ old('contact_first_name') }}" class="auth-input !min-h-0 !rounded-none !border-0 !bg-transparent px-0 py-0 text-center text-[12px]" required>
                                        </td>
                                        <td class="border-0 border-b border-slate-400 px-1 py-1" style="width:17%;">
                                            <input name="contact_middle_name" value="{{ old('contact_middle_name') }}" class="auth-input !min-h-0 !rounded-none !border-0 !bg-transparent px-0 py-0 text-center text-[12px]">
                                        </td>
                                        <td class="border-0 border-b border-slate-400 px-1 py-1" style="width:17%;">
                                            <input type="text" name="contact_suffix_name" value="{{ old('contact_suffix_name') }}" class="auth-input !min-h-0 !rounded-none !border-0 !bg-transparent px-0 py-0 text-center text-[12px]">
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
                                <input id="office" list="hospital-office-options" name="office" value="{{ old('office') }}" autocomplete="off" class="auth-input !inline-block !min-h-0 !w-[450px] !rounded-none !border-0 !bg-transparent px-1 py-0 text-[12px]" required>
                                <p class="mt-1 text-[11px] text-slate-500">Type or pick from the regional hospital list.</p>
                            </td>
                        </tr>
                        <tr>
                            <td class="border border-slate-400 px-2 py-1">7) Address :
                                <input id="address" name="address" value="{{ old('address') }}" class="auth-input !inline-block !min-h-0 !w-[450px] !rounded-none !border-0 !bg-transparent px-1 py-0 text-[12px]" required>
                            </td>
                        </tr>
                        <tr>
                            <td class="border border-slate-400 p-0">
                                <table class="w-full border-collapse table-fixed text-[12px]">
                                    <tr>
                                        <td class="border-0 border-r border-slate-400 px-2 py-1" style="width:23%;">8) Landline :
                                            <input name="landline" value="{{ old('landline') }}" inputmode="numeric" pattern="[0-9]*" oninput="this.value=this.value.replace(/[^0-9]/g,'');" class="auth-input !min-h-0 !rounded-none !border-0 !bg-transparent px-0 py-0 text-[12px]">
                                        </td>
                                        <td class="border-0 border-r border-slate-400 px-2 py-1" style="width:23%;">9) Fax No :
                                            <input name="fax_no" value="{{ old('fax_no') }}" inputmode="numeric" pattern="[0-9]*" oninput="this.value=this.value.replace(/[^0-9]/g,'');" class="auth-input !min-h-0 !rounded-none !border-0 !bg-transparent px-0 py-0 text-[12px]">
                                        </td>
                                        <td class="border-0 border-r border-slate-400 px-2 py-1" style="width:23%;">10) Mobile No :
                                            <input name="mobile_no" value="{{ old('mobile_no') }}" inputmode="numeric" pattern="[0-9]*" oninput="this.value=this.value.replace(/[^0-9]/g,'');" class="auth-input !min-h-0 !rounded-none !border-0 !bg-transparent px-0 py-0 text-[12px]" required>
                                        </td>
                                        <td class="border-0 px-2 py-1" style="width:31%;">11) Email Address :
                                            <input type="text" name="email_address" value="{{ old('email_address') }}" class="auth-input !min-h-0 !rounded-none !border-0 !bg-transparent px-0 py-0 text-[12px]">
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
                        <textarea name="description_request" style="height: 240px; min-height: 240px;" class="auth-input !h-[240px] !min-h-[240px] !rounded-none !border-0 !bg-transparent px-0 py-0 text-[12px]" required>{{ old('description_request') }}</textarea>
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
                                        <input name="approved_by_name" value="{{ old('approved_by_name') }}" class="auth-input !min-h-0 !rounded-none !border-0 border-b border-slate-400 !bg-transparent px-0 py-0 text-[12px]" required>
                                        <p class="text-center">Name &amp; Signature of Head of Office</p>

                                        <input name="approved_by_position" value="{{ old('approved_by_position') }}" class="mt-2 auth-input !min-h-0 !rounded-none !border-0 border-b border-slate-400 !bg-transparent px-0 py-0 text-[12px]" required>
                                        <p class="text-center">Position</p>
                                    </div>
                                    <div class="col-span-4">
                                        <input name="approved_date" type="date" value="{{ old('approved_date', now()->toDateString()) }}" class="auth-input !min-h-0 !rounded-none !border-0 border-b border-slate-400 !bg-transparent px-0 py-0 text-[12px]" required>
                                        <p class="text-center">Date Signed</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="border border-slate-400 px-2 py-1 text-center font-semibold">(For Knowledge Management and Information Technology Service only)</td>
                        </tr>
                        <tr>
                            <td colspan="2" class="border border-slate-400 px-2 py-1 font-semibold">14) ACTION TAKEN <span class="font-normal italic">(Use separate sheet if necessary)</span></td>
                        </tr>
                    </table>

                    <table class="w-full border-collapse text-[12px] text-slate-900">
                        <thead>
                            <tr>
                                <th colspan="2" class="border border-slate-400 px-2 py-1 text-center font-normal">Received</th>
                                <th colspan="3" class="border border-slate-400 px-2 py-1 text-center font-normal">Action</th>
                                <th rowspan="2" class="border border-slate-400 px-2 py-1 text-center font-normal">Signature<br>(g)</th>
                            </tr>
                            <tr>
                                <th class="border border-slate-400 px-2 py-1 text-center font-normal">Date<br>(a)</th>
                                <th class="border border-slate-400 px-2 py-1 text-center font-normal">Time<br>(b)</th>
                                <th class="border border-slate-400 px-2 py-1 text-center font-normal">Date<br>(c)</th>
                                <th class="border border-slate-400 px-2 py-1 text-center font-normal">Time<br>(d)</th>
                                <th class="border border-slate-400 px-2 py-1 text-center font-normal">Taken (e) / Officer (f)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for ($i = 0; $i < 5; $i++)
                                <tr>
                                    <td class="border border-slate-300 px-1 py-1">
                                        <input name="action_log_date[]" type="date" value="{{ old('action_log_date.' . $i) }}" class="auth-input !min-h-0 !rounded-none !border-0 !bg-transparent px-0 py-0 text-[12px]">
                                    </td>
                                    <td class="border border-slate-300 px-1 py-1">
                                        <input name="action_log_time[]" type="time" value="{{ old('action_log_time.' . $i) }}" class="auth-input !min-h-0 !rounded-none !border-0 !bg-transparent px-0 py-0 text-[12px]">
                                    </td>
                                    <td class="border border-slate-300 px-1 py-1"></td>
                                    <td class="border border-slate-300 px-1 py-1"></td>
                                    <td class="border border-slate-300 px-1 py-1">
                                        <div class="grid grid-cols-2 gap-1">
                                            <input name="action_log_action_taken[]" type="text" value="{{ old('action_log_action_taken.' . $i) }}" class="auth-input !min-h-0 !rounded-none !border-0 !bg-transparent px-0 py-0 text-[12px]">
                                            <input name="action_log_action_officer[]" type="text" value="{{ old('action_log_action_officer.' . $i) }}" class="auth-input !min-h-0 !rounded-none !border-0 !bg-transparent px-0 py-0 text-[12px]">
                                        </div>
                                    </td>
                                    <td class="border border-slate-300 px-1 py-1"></td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>

                    <table class="w-full border-collapse text-[12px] text-slate-900">
                        <tr>
                            <td class="border border-slate-400 px-2 py-1" style="width:34%;">15) NOTED BY :</td>
                            <td class="border border-slate-400 px-2 py-1" style="width:33%;">16)</td>
                            <td class="border border-slate-400 px-2 py-1" style="width:33%;">17)</td>
                        </tr>
                        <tr>
                            <td class="border border-slate-400 px-2 py-1">
                                <input name="noted_by_name" value="{{ old('noted_by_name') }}" class="auth-input !min-h-0 !rounded-none !border-0 !bg-transparent px-0 py-0 text-[12px]">
                            </td>
                            <td class="border border-slate-400 px-2 py-1">
                                <input name="noted_by_position" value="{{ old('noted_by_position') }}" class="auth-input !min-h-0 !rounded-none !border-0 !bg-transparent px-0 py-0 text-[12px]">
                            </td>
                            <td class="border border-slate-400 px-2 py-1">
                                <input name="noted_by_date_signed" type="date" value="{{ old('noted_by_date_signed') }}" class="auth-input !min-h-0 !rounded-none !border-0 !bg-transparent px-0 py-0 text-[12px]">
                            </td>
                        </tr>
                        <tr>
                            <td class="border border-slate-400 px-2 py-1 text-center">Name and Signature of Supervisor</td>
                            <td class="border border-slate-400 px-2 py-1 text-center">Position</td>
                            <td class="border border-slate-400 px-2 py-1 text-center">Date Signed</td>
                        </tr>
                    </table>

                    <input type="hidden" name="kmits_date" value="{{ old('kmits_date', now()->toDateString()) }}">
                    <x-input-error :messages="$errors->get('kmits_date')" class="mt-1" />
                </div>

                <div class="flex items-center justify-between border-t border-slate-300 bg-slate-50 px-4 py-3">
                    <a href="{{ route('service-requests.index') }}" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:border-slate-500">Back</a>
                    <button type="submit" class="auth-button">Submit Service Request</button>
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
