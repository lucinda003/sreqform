<x-guest-layout>
    <header class="auth-login-topbar">
        <div class="auth-login-brand">
            <img src="{{ asset('images/dohlogo.svg') }}" alt="DOH Logo" class="auth-login-brand-logo">
            <div>
                <h1 class="auth-login-brand-title">DEPARTMENT OF HEALTH</h1>
                <p class="auth-login-brand-subtitle">Secure Access Portal</p>
            </div>
        </div>
    </header>

    <section class="auth-login-card-wrap" style="max-width: 1280px; margin-top: 1.4rem;">
        <div class="mx-auto w-full py-2">
        <div class="overflow-x-auto rounded-2xl border border-slate-300 bg-white shadow-lg">
            <form method="POST" action="{{ $signedUpdateUrl }}" enctype="multipart/form-data" class="min-w-[1040px] space-y-0">
                @csrf
                @method('PUT')

                <div class="px-4 pb-3">
                    <table class="w-full border-collapse text-[12px] text-slate-900">
                        <tr>
                            <td class="border border-slate-400 px-2 py-1 font-semibold">Reference Code :
                                <span class="inline-block min-w-64 border-b border-slate-400 px-1 py-0.5 text-center">{{ $serviceRequest->reference_code }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="border border-slate-400 px-2 py-1">1) Date/Time of Request (mm/dd/yyyy h:m:s) :
                                <div class="ms-2 inline-flex items-center gap-2 align-middle">
                                    <input id="request_date" name="request_date" type="date" class="inline-block min-h-0 w-[170px] rounded-none border-0 border-b border-slate-400 bg-transparent px-0 py-0 text-[12px] align-middle focus:outline-none focus:ring-0" value="{{ old('request_date', optional($serviceRequest->request_date)->toDateString() ?? now()->toDateString()) }}" required>
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
                                <select id="request_category" name="request_category" class="inline-block align-middle min-h-0 w-[260px] rounded-none border-0 border-b border-slate-200 bg-transparent px-0 py-0 text-[12px] focus:outline-none focus:ring-0">
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
                            <td class="border border-slate-400 px-2 py-1">3) Application System Name * : <input type="text" name="application_system_name" value="{{ old('application_system_name', $serviceRequest->application_system_name) }}" class="auth-input !inline-block !min-h-0 !w-[320px] !rounded-none !border-0 !bg-transparent px-1 py-0 text-[12px]" required></td>
                        </tr>
                        <tr>
                            <td class="border border-slate-400 px-2 py-1">4) Expected Date / Time of Completion * :
                                <input type="date" name="expected_completion_date" value="{{ old('expected_completion_date', optional($serviceRequest->expected_completion_date)->toDateString()) }}" class="inline-block min-h-0 w-[170px] rounded-none border-0 border-b border-slate-200 bg-transparent px-0 py-0 text-[12px] align-middle focus:outline-none focus:ring-0" required>
                                <input type="time" name="expected_completion_time" value="{{ old('expected_completion_time', $serviceRequest->expected_completion_time) }}" class="ms-2 inline-block min-h-0 w-[130px] rounded-none border-0 border-b border-slate-200 bg-transparent px-0 py-0 text-[12px] align-middle focus:outline-none focus:ring-0" required>
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
                                <div data-office-picker style="display: inline-block; vertical-align: top; width: calc(100% - 85px);">
                                    <input type="hidden" id="office" name="office" value="{{ old('office', $serviceRequest->office) }}">
                                    <div style="display: flex; align-items: center; border: 0; border-bottom: 1px solid #e2e8f0; border-radius: 0; padding: 0 2px; background: transparent; min-height: 22px;">
                                        <div id="office_chips" style="display: flex; flex-wrap: wrap; gap: 4px; flex: 1; min-width: 0;"></div>
                                        <input type="search" id="office_search" placeholder="Office..." autocomplete="off" style="border: none; outline: none; padding: 0 4px; flex: 1; min-width: 140px; font-size: 12px; background: transparent;">
                                    </div>
                                    <div id="office_results" style="display: none; border: 1px solid #ccc; max-height: 200px; overflow-y: auto; background: white; margin-top: 2px; font-size: 12px;"></div>
                                </div>
                                <p id="office-regcode-display" class="mt-1 text-[11px] text-slate-500"></p>
                            </td>
                        </tr>
                        <tr>
                            <td class="border border-slate-400 px-2 py-1">7) Address :
                                <input id="address" name="address" value="{{ old('address', $serviceRequest->address) }}" class="auth-input !inline-block !min-h-0 !rounded-none !border-0 !border-b !border-slate-200 !bg-transparent px-1 py-0 text-[12px]" style="width: calc(100% - 85px);" required>
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
                                            <input name="mobile_no" value="{{ old('mobile_no', $serviceRequest->mobile_no) }}" inputmode="tel" oninput="this.value=this.value.replace(/[^0-9+() -]/g,'');" class="auth-input !min-h-0 !rounded-none !border-0 !bg-transparent px-0 py-0 text-[12px]" autocomplete="tel-national" maxlength="20">
                                        </td>
                                        <td class="border-0 px-2 py-1" style="width:31%;">11) Email Address * :
                                            <input type="email" name="email_address" value="{{ old('email_address', $serviceRequest->email_address) }}" class="auth-input !min-h-0 !rounded-none !border-0 !bg-transparent px-0 py-0 text-[12px]" autocomplete="email" required>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="px-4 pb-3">
                    <div class="border border-slate-400 border-b-4 px-2 py-1 text-[12px] font-semibold">12) DESCRIPTION OF REQUEST * : <span class="font-normal italic">(Please clearly write down the details of the request.)</span></div>
                    <div class="border border-t-0 border-slate-400 border-b-4 px-2 py-1">
                        <textarea name="description_request" style="height: 240px; min-height: 240px;" class="auth-input !h-[240px] !min-h-[240px] !rounded-none !border-0 !bg-transparent px-0 py-0 text-[12px]" required>{{ old('description_request', $serviceRequest->description_request) }}</textarea>

                        <div class="mt-3 border-t border-slate-300 pt-2">
                            <label for="description_photos" class="block text-[12px] font-semibold text-slate-700">Attach Photos (1 to 3)</label>
                            <input id="description_photos" name="description_photos[]" type="file" accept="image/*" multiple class="mt-1 block w-full text-[12px] text-slate-700 file:mr-3 file:rounded-md file:border-0 file:bg-slate-800 file:px-3 file:py-1.5 file:text-[12px] file:font-medium file:text-white hover:file:bg-slate-700">
                            <p class="mt-1 text-[11px] text-slate-500">You can upload up to 3 images. Max 5MB each.</p>
                            <x-input-error :messages="$errors->get('description_photos')" class="mt-1" />
                            <x-input-error :messages="$errors->get('description_photos.*')" class="mt-1" />
                        </div>
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
                                        <div class="mb-0 rounded-md border border-slate-300 bg-slate-50 p-1 pb-0">
                                            <div class="mb-1 flex flex-wrap items-center gap-3 text-[11px] text-slate-700">
                                                <label class="inline-flex items-center gap-1">
                                                    <input type="radio" name="approved_by_signature_mode" value="draw" @checked(old('approved_by_signature_mode', 'draw') === 'draw')>
                                                    Draw Signature
                                                </label>
                                                <label class="inline-flex items-center gap-1">
                                                    <input type="radio" name="approved_by_signature_mode" value="upload" @checked(old('approved_by_signature_mode') === 'upload')>
                                                    Upload Signature
                                                </label>
                                            </div>

                                            <div id="create-signature-draw-wrap" class="space-y-1">
                                                <canvas id="create-signature-canvas" class="h-56 w-full rounded border border-slate-500 bg-white"></canvas>
                                                <input type="hidden" name="approved_by_signature_drawn" id="create-signature-drawn" value="{{ old('approved_by_signature_drawn') }}">
                                                <input type="hidden" name="approved_by_signature_clear" id="create-signature-clear-flag" value="0">
                                                <button type="button" id="create-signature-clear" class="rounded border border-slate-300 bg-white px-2 py-1 text-[11px] font-semibold text-slate-700">Clear</button>
                                            </div>

                                            <div id="create-signature-upload-wrap" class="hidden">
                                                <input type="file" name="approved_by_signature_upload" accept="image/*" class="block w-full text-[11px] text-slate-700 file:mr-2 file:rounded-md file:border-0 file:bg-slate-800 file:px-2 file:py-1 file:text-[11px] file:font-medium file:text-white">
                                            </div>

                                            <x-input-error :messages="$errors->get('approved_by_signature_upload')" class="mt-1" />
                                            <x-input-error :messages="$errors->get('approved_by_signature_drawn')" class="mt-1" />
                                        </div>

                                        <input name="approved_by_name" value="{{ old('approved_by_name', $serviceRequest->approved_by_name) }}" class="auth-input !-mt-2 !min-h-0 !rounded-none !border-0 border-b border-slate-400 !bg-transparent px-0 py-0 text-[12px]" required>
                                        <p class="text-center">Name &amp; Signature of Head of Office</p>

                                        <input name="approved_by_position" value="{{ old('approved_by_position', $serviceRequest->approved_by_position) }}" class="mt-2 auth-input !min-h-0 !rounded-none !border-0 border-b border-slate-400 !bg-transparent px-0 py-0 text-[12px]" required>
                                        <p class="text-center">Position</p>
                                    </div>
                                    <div class="col-span-4">
                                        <input name="approved_date" type="date" value="{{ old('approved_date', optional($serviceRequest->approved_date)->toDateString() ?? now()->toDateString()) }}" class="auth-input !min-h-0 !rounded-none !border-0 border-b border-slate-400 !bg-transparent px-0 py-0 text-[12px]" required>
                                        <p class="text-center">Date Signed</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>

                    <input type="hidden" name="kmits_date" value="{{ old('kmits_date', optional($serviceRequest->kmits_date)->toDateString() ?? now()->toDateString()) }}">
                    <x-input-error :messages="$errors->get('kmits_date')" class="mt-1" />
                </div>

                <div class="flex items-center justify-between border-t border-slate-300 bg-slate-50 px-4 py-3">
                    <a href="{{ route('service-requests.track', ['reference_code' => $serviceRequest->reference_code]) }}" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:border-slate-500">Back</a>
                    <button type="submit" class="auth-button">Update Service Request</button>
                </div>
            </form>
        </div>
    </div>
    </section>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const escapePickerHtml = function (value) {
                return String(value || '')
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            };

            const initChipSearchPicker = function (config) {
                const hiddenInput = document.getElementById(config.hiddenId);
                const searchInput = document.getElementById(config.searchId);
                const chipsContainer = document.getElementById(config.chipsId);
                const results = document.getElementById(config.resultsId);
                let options = Array.isArray(config.options) ? config.options : [];
                const maxSelections = Number(config.maxSelections || 0);

                if (!hiddenInput || !searchInput || !chipsContainer || !results) {
                    return null;
                }

                let selected = [];

                const normalize = function (value) {
                    return String(value || '').trim();
                };

                const selectedKey = function (value) {
                    return normalize(value).toLowerCase();
                };

                const syncHiddenInput = function () {
                    hiddenInput.value = selected.join(', ');
                    hiddenInput.dispatchEvent(new Event('input', { bubbles: true }));
                    hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
                    searchInput.setCustomValidity('');
                };

                const renderChips = function () {
                    chipsContainer.innerHTML = selected.map(function (value, index) {
                        return '<span style="display:inline-block;max-width:100%;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;padding:0 4px;font-size:12px;font-weight:600;color:#0f172a;">' +
                            escapePickerHtml(value) +
                            '</span>';
                    }).join('');

                    searchInput.placeholder = selected.length > 0 ? '' : config.placeholder;
                    searchInput.style.flex = selected.length > 0 ? '0 0 24px' : '1';
                    searchInput.classList.remove('hidden');
                };

                const loadRemoteOptions = async function (query) {
                    if (!config.searchEndpoint) {
                        return;
                    }

                    const url = new URL(config.searchEndpoint, window.location.origin);
                    url.searchParams.set('q', query);

                    try {
                        const response = await fetch(url.toString(), {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                        });

                        if (!response.ok) {
                            return;
                        }

                        const payload = await response.json();
                        const offices = Array.isArray(payload.offices) ? payload.offices : [];

                        offices.forEach(function (office) {
                            const name = normalize(office.name || '');
                            if (name === '') {
                                return;
                            }

                            officeAddressMap[name] = String(office.address || '');
                            officeRegcodeMap[name] = String(office.regcode || '');
                        });

                        options = offices
                            .map(function (office) {
                                return normalize(office.name || '');
                            })
                            .filter(function (name) {
                                return name !== '';
                            });
                    } catch (error) {
                        // Keep current options on transient failures.
                    }
                };

                const renderResults = async function () {
                    const query = normalize(searchInput.value);
                    await loadRemoteOptions(query);

                    const selectedKeys = selected.map(selectedKey);
                    const matches = options
                        .filter(function (option) {
                            return selectedKeys.indexOf(selectedKey(option)) === -1;
                        })
                        .filter(function (option) {
                            return query === '' || selectedKey(option).includes(selectedKey(query));
                        })
                        .slice(0, 20);

                    const rows = matches.map(function (option) {
                        return '<button type="button" data-chip-picker-option="' + escapePickerHtml(option) + '" style="display:block;width:100%;border:0;background:#fff;padding:6px 8px;text-align:left;cursor:pointer;">' +
                            escapePickerHtml(option) +
                            '</button>';
                    });

                    const exactMatch = options.some(function (option) {
                        return selectedKey(option) === selectedKey(query);
                    });
                    const alreadySelected = selectedKeys.indexOf(selectedKey(query)) !== -1;

                    if (query !== '' && !exactMatch && !alreadySelected) {
                        rows.unshift('<button type="button" data-chip-picker-option="' + escapePickerHtml(query) + '" style="display:block;width:100%;border:0;background:#fff;padding:6px 8px;text-align:left;cursor:pointer;">Add "' + escapePickerHtml(query) + '"</button>');
                    }

                    results.innerHTML = rows.length > 0
                        ? rows.join('')
                        : '<div style="padding:6px 8px;color:#64748b;">No matching records.</div>';
                    results.style.display = 'block';
                };

                const addSelection = function (value) {
                    const normalized = normalize(value);
                    if (normalized === '') {
                        return;
                    }

                    if (maxSelections > 0 && selected.length >= maxSelections) {
                        selected = [];
                    }

                    const exists = selected.some(function (item) {
                        return selectedKey(item) === selectedKey(normalized);
                    });

                    if (!exists) {
                        selected.push(normalized);
                    }

                    searchInput.value = '';
                    results.style.display = 'none';
                    syncHiddenInput();
                    renderChips();
                };

                const removeSelection = function (index) {
                    selected.splice(index, 1);
                    syncHiddenInput();
                    renderChips();
                    renderResults();
                    searchInput.focus();
                };

                const setFromHiddenInput = function () {
                    selected = hiddenInput.value
                        .split(',')
                        .map(normalize)
                        .filter(function (value, index, items) {
                            return value !== '' && items.findIndex(function (item) {
                                return selectedKey(item) === selectedKey(value);
                            }) === index;
                        });

                    syncHiddenInput();
                    renderChips();
                };

                searchInput.addEventListener('input', renderResults);
                searchInput.addEventListener('focus', renderResults);
                searchInput.addEventListener('keydown', function (event) {
                    if (event.key !== 'Enter') {
                        return;
                    }

                    event.preventDefault();
                    const firstOption = results.querySelector('[data-chip-picker-option]');
                    addSelection(firstOption ? firstOption.getAttribute('data-chip-picker-option') : searchInput.value);
                });

                results.addEventListener('mousedown', function (event) {
                    event.preventDefault();
                });

                results.addEventListener('click', function (event) {
                    const option = event.target.closest('[data-chip-picker-option]');
                    if (!option) {
                        return;
                    }

                    addSelection(option.getAttribute('data-chip-picker-option'));
                });

                chipsContainer.addEventListener('click', function (event) {
                    const removeButton = event.target.closest('[data-chip-picker-remove]');
                    if (!removeButton) {
                        return;
                    }

                    removeSelection(Number(removeButton.getAttribute('data-chip-picker-remove')));
                });

                document.addEventListener('click', function (event) {
                    if (event.target.closest(config.rootSelector)) {
                        return;
                    }

                    results.style.display = 'none';
                });

                setFromHiddenInput();

                return {
                    setFromHiddenInput: setFromHiddenInput,
                    setOptions: function (nextOptions) {
                        options = Array.isArray(nextOptions) ? nextOptions : [];
                        renderResults();
                    },
                };
            };

            // Initialize office chip picker
            const officeOptions = [];
            const officeAddressMap = {};
            const officeRegcodeMap = {};
            const officeSearchEndpoint = @json(route('offices.search'));
            const currentOffice = @json(old('office', $serviceRequest->office ?? ''));
            
            const officePicker = initChipSearchPicker({
                hiddenId: 'office',
                searchId: 'office_search',
                chipsId: 'office_chips',
                resultsId: 'office_results',
                rootSelector: '[data-office-picker]',
                options: officeOptions,
                searchEndpoint: officeSearchEndpoint,
                placeholder: 'Office',
                requiredMessage: 'Please select an office.',
                maxSelections: 1,
            });

            // Sync address and regcode when office changes
            const officeInput = document.getElementById('office');
            const addressInput = document.getElementById('address');
            
            const syncOfficeDetails = function () {
                const selectedOffice = officeInput.value.trim();
                
                if (selectedOffice && officeAddressMap[selectedOffice]) {
                    addressInput.value = officeAddressMap[selectedOffice];
                }
                
                const regcodeDisplay = document.getElementById('office-regcode-display');
                if (regcodeDisplay && selectedOffice && officeRegcodeMap[selectedOffice]) {
                    regcodeDisplay.textContent = 'Regional Hospital / Health Facility Code (for reference): ' + officeRegcodeMap[selectedOffice];
                }
            };
            
            // Observe changes to office input
            if (officeInput) {
                const observer = new MutationObserver(syncOfficeDetails);
                observer.observe(officeInput, { attributes: true, attributeFilter: ['value'] });
                
                officeInput.addEventListener('change', syncOfficeDetails);
            }
            
            // Initial sync if there's an existing office
            if (currentOffice) {
                syncOfficeDetails();
            }

            const initSignatureInput = function () {
                const modeInputs = document.querySelectorAll('input[name="approved_by_signature_mode"]');
                const drawWrap = document.getElementById('create-signature-draw-wrap');
                const uploadWrap = document.getElementById('create-signature-upload-wrap');
                const canvas = document.getElementById('create-signature-canvas');
                const hiddenDrawn = document.getElementById('create-signature-drawn');
                const clearFlag = document.getElementById('create-signature-clear-flag');
                const clearBtn = document.getElementById('create-signature-clear');

                if (!drawWrap || !uploadWrap || !canvas || !hiddenDrawn) {
                    return;
                }

                const ctx = canvas.getContext('2d');
                if (!ctx) {
                    return;
                }

                const getCenteredSignatureDataUrl = function () {
                    const width = canvas.width;
                    const height = canvas.height;
                    const imageData = ctx.getImageData(0, 0, width, height);
                    const data = imageData.data;

                    let minX = width;
                    let minY = height;
                    let maxX = -1;
                    let maxY = -1;

                    for (let y = 0; y < height; y++) {
                        for (let x = 0; x < width; x++) {
                            const alpha = data[(y * width + x) * 4 + 3];
                            if (alpha > 0) {
                                if (x < minX) minX = x;
                                if (y < minY) minY = y;
                                if (x > maxX) maxX = x;
                                if (y > maxY) maxY = y;
                            }
                        }
                    }

                    if (maxX < minX || maxY < minY) {
                        return '';
                    }

                    const cropWidth = maxX - minX + 1;
                    const cropHeight = maxY - minY + 1;

                    const targetCanvas = document.createElement('canvas');
                    targetCanvas.width = width;
                    targetCanvas.height = height;

                    const targetCtx = targetCanvas.getContext('2d');
                    if (!targetCtx) {
                        return canvas.toDataURL('image/png');
                    }

                    const scale = Math.min((width * 0.9) / cropWidth, (height * 0.8) / cropHeight, 1);
                    const drawWidth = cropWidth * scale;
                    const drawHeight = cropHeight * scale;
                    const drawX = (width - drawWidth) / 2;
                    const drawY = (height - drawHeight) / 2;

                    targetCtx.clearRect(0, 0, width, height);
                    targetCtx.drawImage(
                        canvas,
                        minX,
                        minY,
                        cropWidth,
                        cropHeight,
                        drawX,
                        drawY,
                        drawWidth,
                        drawHeight
                    );

                    return targetCanvas.toDataURL('image/png');
                };

                const syncHiddenSignature = function () {
                    const centeredSignature = getCenteredSignatureDataUrl();
                    hiddenDrawn.value = centeredSignature;

                    if (clearFlag && centeredSignature !== '') {
                        clearFlag.value = '0';
                    }
                };

                const resizeCanvas = function () {
                    const ratio = window.devicePixelRatio || 1;
                    const rect = canvas.getBoundingClientRect();
                    canvas.width = Math.max(1, Math.floor(rect.width * ratio));
                    canvas.height = Math.max(1, Math.floor(rect.height * ratio));
                    ctx.setTransform(ratio, 0, 0, ratio, 0, 0);
                    ctx.lineWidth = 2;
                    ctx.lineCap = 'round';
                    ctx.strokeStyle = '#0f172a';
                };

                resizeCanvas();
                window.addEventListener('resize', resizeCanvas);

                if (hiddenDrawn.value) {
                    const img = new Image();
                    img.onload = function () {
                        ctx.clearRect(0, 0, canvas.width, canvas.height);
                        ctx.drawImage(img, 0, 0, canvas.clientWidth, canvas.clientHeight);
                    };
                    img.src = hiddenDrawn.value;
                }

                let drawing = false;

                const pointFromEvent = function (event) {
                    const rect = canvas.getBoundingClientRect();
                    const source = event.touches ? event.touches[0] : event;
                    return {
                        x: source.clientX - rect.left,
                        y: source.clientY - rect.top,
                    };
                };

                const start = function (event) {
                    drawing = true;
                    const point = pointFromEvent(event);
                    ctx.beginPath();
                    ctx.moveTo(point.x, point.y);
                    event.preventDefault();
                };

                const move = function (event) {
                    if (!drawing) {
                        return;
                    }

                    const point = pointFromEvent(event);
                    ctx.lineTo(point.x, point.y);
                    ctx.stroke();
                    event.preventDefault();
                };

                const end = function () {
                    if (drawing) {
                        syncHiddenSignature();
                    }
                    drawing = false;
                };

                canvas.addEventListener('mousedown', start);
                canvas.addEventListener('mousemove', move);
                window.addEventListener('mouseup', end);
                canvas.addEventListener('touchstart', start, { passive: false });
                canvas.addEventListener('touchmove', move, { passive: false });
                canvas.addEventListener('touchend', end);

                if (clearBtn) {
                    clearBtn.addEventListener('click', function () {
                        ctx.clearRect(0, 0, canvas.width, canvas.height);
                        hiddenDrawn.value = '';
                        if (clearFlag) {
                            clearFlag.value = '1';
                        }
                    });
                }

                const syncMode = function () {
                    const selected = document.querySelector('input[name="approved_by_signature_mode"]:checked');
                    const mode = selected ? selected.value : 'draw';
                    drawWrap.classList.toggle('hidden', mode !== 'draw');
                    uploadWrap.classList.toggle('hidden', mode !== 'upload');

                    if (mode === 'upload' && clearFlag) {
                        clearFlag.value = '0';
                    }
                };

                modeInputs.forEach(function (input) {
                    input.addEventListener('change', syncMode);
                });
                syncMode();

                const form = canvas.closest('form');
                if (form) {
                    form.addEventListener('submit', function () {
                        const selected = document.querySelector('input[name="approved_by_signature_mode"]:checked');
                        const mode = selected ? selected.value : 'draw';
                        if (mode === 'draw') {
                            syncHiddenSignature();
                        }
                    });
                }
            };

            initSignatureInput();
        });
    </script>
</x-guest-layout>
