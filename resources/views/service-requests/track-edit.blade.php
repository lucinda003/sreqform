<x-guest-layout>
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
                    hiddenDrawn.value = canvas.toDataURL('image/png');
                    if (clearFlag) {
                        clearFlag.value = '0';
                    }
                    event.preventDefault();
                };

                const end = function () {
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
            };

            initSignatureInput();
        });
    </script>
</x-guest-layout>
