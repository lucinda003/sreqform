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

    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap');

        .srf-root {
            position: relative;
            z-index: 5;
            font-family: 'DM Sans', sans-serif;
            font-size: 15px;
            color: #000;
        }

        /* ── Header ── */
        .srf-topbar {
            background: linear-gradient(135deg, #134e4a 0%, #0f766e 60%, #14b8a6 100%);
            padding: 0;
            display: flex;
            align-items: stretch;
            min-height: 64px;
        }
        .srf-topbar-accent {
            width: 6px;
            background: #f0fdf4;
            flex-shrink: 0;
        }
        .srf-topbar-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
            flex: 1;
        }
        .srf-topbar-brand {
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .srf-topbar-logo {
            width: 40px;
            height: 40px;
            object-fit: contain;
            filter: brightness(0) invert(1);
        }
        .srf-topbar-title {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: #ccfbf1;
            margin: 0;
            line-height: 1;
        }
        .srf-topbar-sub {
            font-size: 11px;
            color: #5eead4;
            margin: 3px 0 0;
            letter-spacing: 0.04em;
        }
        .srf-track-btn {
            font-size: 12px;
            font-weight: 500;
            color: #134e4a;
            background: #ccfbf1;
            border: none;
            padding: 7px 18px;
            border-radius: 20px;
            text-decoration: none;
            letter-spacing: 0.03em;
            transition: background 0.18s, color 0.18s;
        }
        .srf-track-btn:hover {
            background: #f0fdf4;
            color: #0f766e;
        }

        /* ── Card ── */
        .srf-card {
            background: #fff;
            border-radius: 12px;
            border: 1.5px solid #cbd5e1;
            overflow: hidden;
            box-shadow: 0 2px 16px 0 rgba(15,118,110,0.07);
        }

        /* ── Form header bar ── */
        .srf-form-header {
            background: #0f766e;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .srf-form-header-text {
            font-size: 15px;
            font-weight: 600;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #fff;
            margin: 0;
        }
        .srf-form-header-line {
            flex: 1;
            height: 1px;
            background: rgba(255,255,255,0.2);
        }

        /* ── Section label ── */
        .srf-section {
            padding: 16px 20px 0;
        }
        .srf-section-label {
            font-size: 17px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #000;
            margin: 0 0 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .srf-section-label::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        /* ── Field rows ── */
        .srf-field-grid {
            display: grid;
            gap: 12px;
            margin-bottom: 12px;
        }
        .srf-field-grid-2 { grid-template-columns: 1fr 1fr; }
        .srf-field-grid-3 { grid-template-columns: 1fr 1fr 1fr; }
        .srf-field-grid-4 { grid-template-columns: 1fr 1fr 1fr 1fr; }
        .srf-field-grid-54 { grid-template-columns: 5fr 4fr; }
        .srf-field-grid-name { grid-template-columns: 220px 1fr 1fr 1fr 100px; }

        .srf-field {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        .srf-label {
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: #000;
        }
        .srf-required {
            color: #ef4444;
            margin-left: 2px;
        }

        /* ── Inputs ── */
        .srf-input, .srf-select, .srf-textarea {
            font-family: 'DM Sans', sans-serif;
            font-size: 16px;
            color: #000;
            font-weight: 600;
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 6px;
            padding: 7px 10px;
            outline: none;
            width: 100%;
            box-sizing: border-box;
            transition: border-color 0.18s, background 0.18s, box-shadow 0.18s;
        }
        .srf-input:hover, .srf-select:hover {
            border-color: #94a3b8;
            background: #fff;
        }
        .srf-input:focus, .srf-select:focus, .srf-textarea:focus {
            border-color: #0f766e;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(15,118,110,0.1);
        }
        .srf-textarea {
            resize: vertical;
            min-height: 160px;
            line-height: 1.6;
        }
        .srf-hint {
            font-size: 13px;
            color: #000;
            font-weight: 600;
            margin: 0;
        }

        /* ── Divider ── */
        .srf-divider {
            height: 1px;
            background: #f1f5f9;
            margin: 4px 0 16px;
        }

        /* ── Description box ── */
        .srf-desc-box {
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
        }
        .srf-desc-header {
            background: #f8fafc;
            border-bottom: 1.5px solid #e2e8f0;
            padding: 8px 14px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #475569;
        }
        .srf-desc-body {
            padding: 12px 14px;
        }

        /* ── Photo upload ── */
        .srf-upload-wrap {
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid #f1f5f9;
        }
        .srf-upload-label {
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #000;
            display: block;
            margin-bottom: 6px;
        }
        .srf-file-input {
            font-size: 15px;
            color: #475569;
        }
        .srf-file-input::file-selector-button {
            font-size: 15px;
            font-weight: 700;
            color: #fff;
            background: #0f766e;
            border: none;
            padding: 10px 18px;
            border-radius: 5px;
            margin-right: 10px;
            cursor: pointer;
            transition: background 0.18s;
        }
        .srf-file-input::file-selector-button:hover {
            background: #134e4a;
        }

        /* ── Signature area ── */
        .srf-sig-wrap {
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            background: #fafafa;
            padding: 10px;
        }
        .srf-sig-modes {
            display: flex;
            gap: 16px;
            font-size: 14px;
            color: #000;
            font-weight: 600;
            margin-bottom: 8px;
        }
        .srf-sig-modes label {
            display: flex;
            align-items: center;
            gap: 5px;
            cursor: pointer;
        }
        .srf-sig-canvas {
            width: 100%;
            height: 150px;
            max-width: 100%;
            border: 1px solid #e2e8f0;
            border-radius: 5px;
            background: #fff;
            display: block;
        }
        .srf-sig-clear {
            margin-top: 6px;
            font-size: 11px;
            font-weight: 500;
            color: #64748b;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 5px;
            padding: 4px 12px;
            cursor: pointer;
            transition: border-color 0.18s, color 0.18s;
        }
        .srf-sig-clear:hover {
            border-color: #94a3b8;
            color: #1e293b;
        }

        /* ── Approved section ── */
        .srf-approved-grid {
            
            display: grid;
            grid-template-columns: auto 1fr;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
        }
        .srf-approved-label-cell {
            background: #f8fafc;
            border-right: 1.5px solid #e2e8f0;
            padding: 14px 16px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #475569;
            writing-mode: vertical-rl;
            text-orientation: mixed;
            transform: rotate(180deg);
            white-space: nowrap;
        }
        .srf-approved-body {
            padding: 14px 16px;
        }
        .srf-approved-inner {
            display: grid;
            grid-template-columns: 3fr 2fr;
            gap: 20px;
        }
        .srf-field-underline {
            display: flex;
            flex-direction: column;
            gap: 4px;
            margin-top: 12px;
        }
        .srf-input-underline {
            font-family: 'DM Sans', sans-serif;
            font-size: 16px;
            color: #000;
            font-weight: 600;
            background: transparent;
            border: none;
            border-bottom: 1.5px solid #cbd5e1;
            border-radius: 0;
            padding: 4px 2px;
            outline: none;
            width: 100%;
            transition: border-color 0.18s;
        }
        .srf-input-underline:focus {
            border-bottom-color: #0f766e;
        }
        .srf-input-underline:hover {
            border-bottom-color: #94a3b8;
        }
        .srf-sublabel {
            font-size: 13px;
            color: #000;
            font-weight: 600;
            text-align: center;
        }

        /* ── Footer ── */
        .srf-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 20px;
            background: #f8fafc;
            border-top: 1.5px solid #e2e8f0;
        }
        .srf-btn-back {
            font-family: 'DM Sans', sans-serif;
            font-size: 16px;
            font-weight: 700;
            color: #475569;
            background: #fff;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px 28px;
            text-decoration: none;
            transition: border-color 0.18s, color 0.18s;
        }
        .srf-btn-back:hover {
            border-color: #94a3b8;
            color: #1e293b;
        }
        .srf-btn-submit {
            font-family: 'DM Sans', sans-serif;
            font-size: 16px;
            font-weight: 700;
            color: #fff;
            background: #0f766e;
            border: none;
            border-radius: 8px;
            padding: 13px 34px;
            cursor: pointer;
            letter-spacing: 0.03em;
            transition: background 0.18s, box-shadow 0.18s;
        }
        .srf-btn-submit:hover {
            background: #134e4a;
            box-shadow: 0 4px 12px rgba(15,118,110,0.25);
        }

        .srf-number-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #ccfbf1;
            color: #0f766e;
            font-size: 14px;
            font-weight: 700;
            flex-shrink: 0;
            margin-right: 6px;
        }
    </style>

    <div class="srf-root">

        {{-- Top bar --}}
        <header class="auth-login-topbar">
        <div class="auth-login-brand">
            <img src="{{ asset('images/dohlogo.svg') }}" alt="DOH Logo" class="auth-login-brand-logo">
            <div>
                <h1 class="auth-login-brand-title">DEPARTMENT OF HEALTH</h1>
                <p class="auth-login-brand-subtitle">Secure Access Portal</p>
            </div>
        </div>

        <div class="auth-login-top-actions">
            <a href="{{ route('service-requests.track') }}" class="auth-login-register">Track Request</a>
        </div>
    </header>

        {{-- Main card --}}
        <section style="max-width: 1300px; margin: 1.5rem auto; padding: 0 1rem 2rem;">
            <div class="srf-card">

                {{-- Form header --}}
                <div class="srf-form-header">
                    <span class="srf-form-header-text">Service Request Form</span>
                    <div class="srf-form-header-line"></div>
                </div>

                <form method="POST" action="{{ route('service-requests.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input id="request_date" name="request_date" type="hidden" value="{{ old('request_date', now()->toDateString()) }}">
                    <input name="time_received" type="hidden" value="{{ old('time_received', now()->format('H:i')) }}">

                    {{-- Section: Request Info --}}
                    <div class="srf-section" style="padding-bottom: 4px;">
                        <p class="srf-section-label">Request Information</p>

                        <div class="srf-field-grid srf-field-grid-3" style="margin-bottom: 16px;">
                            <div class="srf-field">
                                @php
                                    $requestCategoryOptions = [
                                        'Technical Assistance',
                                        'System Access',
                                        'Network/Internet',
                                        'Hardware Support',
                                        'Software Installation',
                                        'Data Request',
                                        'Others',
                                    ];
                                    $oldRequestCategory = (string) old('request_category', '');
                                    $hasCustomRequestCategory = $oldRequestCategory !== ''
                                        && ! in_array($oldRequestCategory, $requestCategoryOptions, true);
                                @endphp
                                <label class="srf-label">
                                    <span class="srf-number-badge">1</span> Request Category <span class="srf-required">*</span>
                                </label>
                                <select name="request_category" id="request_category" class="srf-select">
                                    <option value="Technical Assistance" @selected(old('request_category') === 'Technical Assistance')>Technical Assistance</option>
                                    <option value="System Access" @selected(old('request_category') === 'System Access')>System Access</option>
                                    <option value="Network/Internet" @selected(old('request_category') === 'Network/Internet')>Network/Internet</option>
                                    <option value="Hardware Support" @selected(old('request_category') === 'Hardware Support')>Hardware Support</option>
                                    <option value="Software Installation" @selected(old('request_category') === 'Software Installation')>Software Installation</option>
                                    <option value="Data Request" @selected(old('request_category') === 'Data Request')>Data Request</option>
                                    @if ($hasCustomRequestCategory)
                                        <option value="{{ $oldRequestCategory }}" selected data-custom="1">{{ $oldRequestCategory }}</option>
                                    @endif
                                    <option value="Others" @selected(old('request_category') === 'Others' || $hasCustomRequestCategory)>Others</option>
                                </select>
                                <input
                                    type="text"
                                    id="request_category_other"
                                    class="srf-input mt-2 {{ old('request_category') === 'Others' || $hasCustomRequestCategory ? '' : 'hidden' }}"
                                    placeholder="Type request category"
                                    value="{{ $hasCustomRequestCategory ? $oldRequestCategory : '' }}"
                                >
                            </div>
                            <div class="srf-field">
                                <label class="srf-label">
                                    <span class="srf-number-badge">2</span> Application System Name <span class="srf-required">*</span>
                                </label>
                                <input type="text" name="application_system_name" value="{{ old('application_system_name') }}" class="srf-input" required>
                            </div>
                            <div class="srf-field">
                                <label class="srf-label">
                                    <span class="srf-number-badge">3</span> Expected Date / Time of Completion
                                </label>
                                <div style="display: flex; gap: 8px;">
                                    <input type="date" name="expected_completion_date" value="{{ old('expected_completion_date') }}" class="srf-input" style="width: 180px;">
                                    <input type="time" name="expected_completion_time" value="{{ old('expected_completion_time') }}" class="srf-input" style="width: 140px;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="srf-divider"></div>

                    {{-- Section: Contact Person --}}
                    <div class="srf-section" style="padding-bottom: 4px;">
                        <p class="srf-section-label">
                            <span class="srf-number-badge">4</span> Contact Person
                        </p>
                        <div class="srf-field-grid srf-field-grid-4" style="margin-bottom: 16px;">
                            <div class="srf-field">
                                <label class="srf-label">Last Name <span class="srf-required">*</span></label>
                                <input name="contact_last_name" value="{{ old('contact_last_name') }}" class="srf-input" required>
                            </div>
                            <div class="srf-field">
                                <label class="srf-label">First Name <span class="srf-required">*</span></label>
                                <input name="contact_first_name" value="{{ old('contact_first_name') }}" class="srf-input" required>
                            </div>
                            <div class="srf-field">
                                <label class="srf-label">Middle Name</label>
                                <input name="contact_middle_name" value="{{ old('contact_middle_name') }}" class="srf-input">
                            </div>
                            <div class="srf-field">
                                <label class="srf-label">Suffix</label>
                                <input name="contact_suffix_name" value="{{ old('contact_suffix_name') }}" class="srf-input">
                            </div>
                        </div>
                    </div>

                    <div class="srf-divider"></div>

                    {{-- Section: Office & Contact --}}
                    <div class="srf-section" style="padding-bottom: 4px;">
                        <p class="srf-section-label">Office & Contact Details</p>

                        <div class="srf-field-grid srf-field-grid-2" style="margin-bottom: 12px;">
                            <div class="srf-field">
                                <label class="srf-label">
                                    <span class="srf-number-badge">5</span> Office <span class="srf-required">*</span>
                                </label>
                                <input id="office" list="hospital-office-options" name="office"
                                    value="{{ old('office') }}" autocomplete="off"
                                    class="srf-input" required>
                                <p class="srf-hint">Type or pick from the regional hospital list.</p>
                            </div>
                            <div class="srf-field">
                                <label class="srf-label">
                                    <span class="srf-number-badge">6</span> Address <span class="srf-required">*</span>
                                </label>
                                <input id="address" name="address" value="{{ old('address') }}" class="srf-input" required>
                            </div>
                        </div>

                        <div class="srf-field-grid srf-field-grid-4" style="margin-bottom: 16px;">
                            <div class="srf-field">
                                <label class="srf-label"><span class="srf-number-badge">7</span> Landline</label>
                                <input name="landline" value="{{ old('landline') }}" inputmode="numeric"
                                    oninput="this.value=this.value.replace(/[^0-9]/g,'');" class="srf-input">
                            </div>
                            <div class="srf-field">
                                <label class="srf-label"><span class="srf-number-badge">8</span> Fax No</label>
                                <input name="fax_no" value="{{ old('fax_no') }}" inputmode="numeric"
                                    oninput="this.value=this.value.replace(/[^0-9]/g,'');" class="srf-input">
                            </div>
                            <div class="srf-field">
                                <label class="srf-label"><span class="srf-number-badge">9</span> Mobile No <span class="srf-required">*</span></label>
                                <input name="mobile_no" value="{{ old('mobile_no') }}" inputmode="numeric"
                                    oninput="this.value=this.value.replace(/[^0-9]/g,'');" class="srf-input" required>
                            </div>
                            <div class="srf-field">
                                <label class="srf-label"><span class="srf-number-badge">10</span> Email Address</label>
                                <input type="text" name="email_address" value="{{ old('email_address') }}" class="srf-input">
                            </div>
                        </div>
                    </div>

                    <div class="srf-divider"></div>

                    {{-- Section: Description --}}
                    <div class="srf-section" style="padding-bottom: 16px;">
                        <p class="srf-section-label">
                            <span class="srf-number-badge">11</span> Description of Request <span class="srf-required">*</span>
                        </p>
                        <div class="srf-desc-box">
                            <div class="srf-desc-header">
                                Please clearly write down the details of the request.
                            </div>
                            <div class="srf-desc-body">
                                <textarea name="description_request" class="srf-textarea" required>{{ old('description_request') }}</textarea>
                                <x-input-error :messages="$errors->get('description_request')" class="mt-1" />

                                <div class="srf-upload-wrap">
                                    <span class="srf-upload-label">Attach Photos (1 to 3)</span>
                                    <input id="description_photos" name="description_photos[]" type="file"
                                        accept="image/*" multiple class="srf-file-input">
                                    <p style="font-size:11px; color:#94a3b8; margin: 4px 0 0;">Max 3 images, 5MB each.</p>
                                    <x-input-error :messages="$errors->get('description_photos')" class="mt-1" />
                                    <x-input-error :messages="$errors->get('description_photos.*')" class="mt-1" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="srf-divider"></div>

                    {{-- Section: Approved By --}}
                    <div class="srf-section" style="padding-bottom: 16px;">
                        <p class="srf-section-label">
                            <span class="srf-number-badge">12</span> Approved By
                        </p>
                        <div class="srf-approved-grid">
                            <div class="srf-approved-label-cell">Approved By</div>
                            <div class="srf-approved-body">
                                <div class="srf-approved-inner">
                                    <div>
                                        <div class="srf-sig-wrap">
                                            <div class="srf-sig-modes">
                                                <label>
                                                    <input type="radio" name="approved_by_signature_mode" value="draw"
                                                        @checked(old('approved_by_signature_mode', 'draw') === 'draw')>
                                                    Draw Signature
                                                </label>
                                                <label>
                                                    <input type="radio" name="approved_by_signature_mode" value="upload"
                                                        @checked(old('approved_by_signature_mode') === 'upload')>
                                                    Upload Signature
                                                </label>
                                            </div>
                                            <div id="create-signature-draw-wrap">
                                                <canvas id="create-signature-canvas" class="srf-sig-canvas"
                                                    style="height:150px; width:100%;"></canvas>
                                                <input type="hidden" name="approved_by_signature_drawn"
                                                    id="create-signature-drawn" value="{{ old('approved_by_signature_drawn') }}">
                                                <button type="button" id="create-signature-clear" class="srf-sig-clear">Clear</button>
                                            </div>
                                            <div id="create-signature-upload-wrap" class="hidden">
                                                <input type="file" name="approved_by_signature_upload" accept="image/*"
                                                    id="create-signature-upload" class="srf-file-input">
                                            </div>
                                            <x-input-error :messages="$errors->get('approved_by_signature_upload')" class="mt-1" />
                                            <x-input-error :messages="$errors->get('approved_by_signature_drawn')" class="mt-1" />
                                        </div>

                                        <div class="srf-field-underline">
                                            <input name="approved_by_name" value="{{ old('approved_by_name') }}"
                                                class="srf-input-underline" required>
                                            <p class="srf-sublabel">Name &amp; Signature of Head of Office</p>
                                        </div>

                                        <div class="srf-field-underline">
                                            <input name="approved_by_position" value="{{ old('approved_by_position') }}"
                                                class="srf-input-underline" required>
                                            <p class="srf-sublabel">Position</p>
                                        </div>
                                    </div>

                                    <div>
                                        <div class="srf-field-underline">
                                            <input name="approved_date" type="date"
                                                value="{{ old('approved_date', now()->toDateString()) }}"
                                                id="create-approved-date" class="srf-input-underline" required>
                                            <p class="srf-sublabel">Date Signed</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="kmits_date" value="{{ old('kmits_date', now()->toDateString()) }}">
                        <x-input-error :messages="$errors->get('kmits_date')" class="mt-1" />
                    </div>

                    {{-- Footer --}}
                    <div class="srf-footer">
                        <a href="{{ route('service-requests.index') }}" class="srf-btn-back">← Back</a>
                        <button type="submit" class="srf-btn-submit">Submit Service Request</button>
                    </div>
                </form>
            </div>
        </section>
    </div>

    <datalist id="hospital-office-options">
        @foreach (array_keys($hospitalOfficeMap) as $hospitalOfficeOption)
            <option value="{{ $hospitalOfficeOption }}"></option>
        @endforeach
    </datalist>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const createForm = document.querySelector('form[action="{{ route('service-requests.store') }}"]');
            const requestCategorySelect = document.getElementById('request_category');
            const requestCategoryOther = document.getElementById('request_category_other');

            const syncRequestCategoryOther = function () {
                if (!requestCategorySelect || !requestCategoryOther) return;

                const useOther = requestCategorySelect.value === 'Others';
                requestCategoryOther.classList.toggle('hidden', !useOther);
                requestCategoryOther.required = useOther;

                if (!useOther) {
                    requestCategoryOther.value = '';
                }
            };

            if (requestCategorySelect && requestCategoryOther) {
                requestCategorySelect.addEventListener('change', syncRequestCategoryOther);
                syncRequestCategoryOther();

                if (createForm) {
                    createForm.addEventListener('submit', function () {
                        if (requestCategorySelect.value !== 'Others') {
                            return;
                        }

                        const customValue = requestCategoryOther.value.trim();
                        if (customValue === '') {
                            return;
                        }

                        let customOption = requestCategorySelect.querySelector('option[data-custom="1"]');
                        if (!customOption) {
                            customOption = document.createElement('option');
                            customOption.setAttribute('data-custom', '1');
                            requestCategorySelect.appendChild(customOption);
                        }

                        customOption.value = customValue;
                        customOption.textContent = customValue;
                        customOption.selected = true;
                    });
                }
            }

            const officeInput = document.getElementById('office');
            const addressInput = document.getElementById('address');
            const optionsList = document.getElementById('hospital-office-options');
            const officeAddressMap = @json($hospitalOfficeMap);

            if (!officeInput || !optionsList) return;

            const staticOptions = Object.keys(officeAddressMap);

            const setOptions = function (items) {
                optionsList.innerHTML = '';
                items.forEach(function (item) {
                    const option = document.createElement('option');
                    option.value = item;
                    optionsList.appendChild(option);
                });
            };

            officeInput.addEventListener('input', function () {
                const termLower = officeInput.value.trim().toLowerCase();
                if (termLower === '') { setOptions(staticOptions.slice(0, 50)); return; }
                const starts = staticOptions.filter(i => i.toLowerCase().startsWith(termLower));
                const contains = staticOptions.filter(i => i.toLowerCase().includes(termLower));
                setOptions((starts.length > 0 ? starts : contains).slice(0, 50));
            });

            const syncAddress = function () {
                if (!addressInput) return;
                const mapped = officeAddressMap[officeInput.value.trim()] || '';
                if (mapped) addressInput.value = mapped;
            };

            officeInput.addEventListener('change', syncAddress);
            officeInput.addEventListener('blur', syncAddress);

            const initSignatureInput = function () {
                const modeInputs = document.querySelectorAll('input[name="approved_by_signature_mode"]');
                const drawWrap = document.getElementById('create-signature-draw-wrap');
                const uploadWrap = document.getElementById('create-signature-upload-wrap');
                const canvas = document.getElementById('create-signature-canvas');
                const hiddenDrawn = document.getElementById('create-signature-drawn');
                const clearBtn = document.getElementById('create-signature-clear');
                const uploadInput = document.getElementById('create-signature-upload');
                const approvedDateInput = document.getElementById('create-approved-date');

                if (!drawWrap || !uploadWrap || !canvas || !hiddenDrawn) return;

                const fillSignedDateIfEmpty = function () {
                    if (!approvedDateInput || approvedDateInput.value !== '') {
                        return;
                    }

                    const now = new Date();
                    const year = now.getFullYear();
                    const month = String(now.getMonth() + 1).padStart(2, '0');
                    const day = String(now.getDate()).padStart(2, '0');
                    approvedDateInput.value = `${year}-${month}-${day}`;
                };

                const ctx = canvas.getContext('2d');
                if (!ctx) return;

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
                    return { x: source.clientX - rect.left, y: source.clientY - rect.top };
                };

                canvas.addEventListener('mousedown', function (e) {
                    drawing = true;
                    const p = pointFromEvent(e);
                    ctx.beginPath(); ctx.moveTo(p.x, p.y);
                    e.preventDefault();
                });
                canvas.addEventListener('mousemove', function (e) {
                    if (!drawing) return;
                    const p = pointFromEvent(e);
                    ctx.lineTo(p.x, p.y); ctx.stroke();
                    hiddenDrawn.value = canvas.toDataURL('image/png');
                    fillSignedDateIfEmpty();
                    e.preventDefault();
                });
                window.addEventListener('mouseup', function () { drawing = false; });
                canvas.addEventListener('touchstart', function (e) {
                    drawing = true;
                    const p = pointFromEvent(e);
                    ctx.beginPath(); ctx.moveTo(p.x, p.y);
                    e.preventDefault();
                }, { passive: false });
                canvas.addEventListener('touchmove', function (e) {
                    if (!drawing) return;
                    const p = pointFromEvent(e);
                    ctx.lineTo(p.x, p.y); ctx.stroke();
                    hiddenDrawn.value = canvas.toDataURL('image/png');
                    fillSignedDateIfEmpty();
                    e.preventDefault();
                }, { passive: false });
                canvas.addEventListener('touchend', function () { drawing = false; });

                if (clearBtn) {
                    clearBtn.addEventListener('click', function () {
                        ctx.clearRect(0, 0, canvas.width, canvas.height);
                        hiddenDrawn.value = '';
                    });
                }

                if (uploadInput) {
                    uploadInput.addEventListener('change', function () {
                        if (uploadInput.files && uploadInput.files.length > 0) {
                            fillSignedDateIfEmpty();
                        }
                    });
                }

                const syncMode = function () {
                    const mode = document.querySelector('input[name="approved_by_signature_mode"]:checked')?.value || 'draw';
                    drawWrap.classList.toggle('hidden', mode !== 'draw');
                    uploadWrap.classList.toggle('hidden', mode !== 'upload');
                };

                modeInputs.forEach(i => i.addEventListener('change', syncMode));
                syncMode();
            };

            initSignatureInput();
        });
    </script>
</x-guest-layout>