@php View::share('pageTitle', 'Edit Service Request'); @endphp
<x-guest-layout>
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
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            padding: 0;
            display: flex;
            align-items: stretch;
            min-height: 64px;
        }

        .srf-topbar-accent {
            width: 6px;
            background: #f1f5f9;
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
        }

        .srf-topbar-title {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: #f1f5f9;
            margin: 0;
            line-height: 1;
        }

        .srf-topbar-sub {
            font-size: 11px;
            color: #94a3b8;
            margin: 3px 0 0;
            letter-spacing: 0.04em;
        }

        /* ── Card ── */
        .srf-card {
            background: #fff;
            border-radius: 12px;
            border: 1.5px solid #cbd5e1;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        /* ── Form header bar ── */
        .srf-form-header {
            background: #1e293b;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .srf-header-back {
            display: inline-flex;
            align-items: center;
            justify-content: flex-start;
            font-size: 18px;
            font-weight: 700;
            color: #fff;
            text-decoration: none;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            padding: 4px 10px;
            transition: all 0.2s;
        }

        .srf-header-back:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateX(-2px);
        }

        .srf-form-header-text {
            font-size: 18px;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: #fff;
            margin: 0;
        }

        .srf-ref-code {
            font-family: 'DM Mono', monospace;
            background: #f1f5f9;
            color: #0f172a;
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            margin-left: auto;
        }

        /* ── Section label ── */
        .srf-section {
            padding: 20px 24px 0;
        }

        .srf-section-label {
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: #1e293b;
            margin: 0 0 12px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .srf-section-label::after {
            content: '';
            flex: 1;
            height: 1.5px;
            background: #e2e8f0;
        }

        /* ── Field rows ── */
        .srf-field-grid {
            display: grid;
            gap: 16px;
            margin-bottom: 20px;
        }

        .srf-field-grid-2 {
            grid-template-columns: 1fr 1fr;
        }

        .srf-field-grid-3 {
            grid-template-columns: 1fr 1fr 1fr;
        }

        .srf-field-grid-name {
            grid-template-columns: 1.2fr 1fr 1fr 1fr 0.6fr;
        }

        .srf-field {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .srf-label {
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.03em;
            text-transform: uppercase;
            color: #64748b;
        }

        .srf-required {
            color: #ef4444;
            margin-left: 2px;
        }

        /* ── Inputs ── */
        .srf-input,
        .srf-select,
        .srf-textarea {
            font-family: 'DM Sans', sans-serif;
            font-size: 15px;
            color: #0f172a;
            font-weight: 500;
            background: #fff;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px 12px;
            outline: none;
            width: 100%;
            box-sizing: border-box;
            transition: all 0.2s;
        }

        .srf-input:focus,
        .srf-select:focus,
        .srf-textarea:focus {
            border-color: #64748b;
            box-shadow: 0 0 0 4px rgba(100, 116, 139, 0.1);
        }

        .srf-textarea {
            resize: vertical;
            min-height: 120px;
        }

        /* ── Signature area ── */
        .srf-sig-wrap {
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            background: #f8fafc;
            padding: 12px;
        }

        .srf-sig-modes {
            display: flex;
            gap: 20px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .srf-sig-canvas {
            width: 100%;
            height: 200px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background: #fff;
            cursor: crosshair;
        }

        /* ── Actions ── */
        .srf-actions {
            margin-top: 24px;
            padding: 16px 24px;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .srf-btn {
            font-family: 'DM Sans', sans-serif;
            font-weight: 700;
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-size: 13px;
            border: none;
        }

        .srf-btn-submit {
            background: #0f172a;
            color: #fff;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.2);
        }

        .srf-btn-submit:hover {
            background: #1e293b;
            transform: translateY(-1px);
        }

        .srf-btn-cancel {
            background: #fff;
            color: #64748b;
            border: 1.5px solid #e2e8f0;
        }

        .srf-btn-cancel:hover {
            border-color: #cbd5e1;
            color: #475569;
        }
    </style>

    <div class="srf-root" style="max-width: 900px; margin: 2rem auto; padding: 0 1rem;">
        <div class="srf-card">
            <!-- Top brand bar -->
            <div class="srf-topbar">
                <div class="srf-topbar-accent"></div>
                <div class="srf-topbar-inner">
                    <div class="srf-topbar-brand">
                        <img src="{{ asset('images/dohlogo.svg') }}" alt="DOH" class="srf-topbar-logo">
                        <div>
                            <h2 class="srf-topbar-title">Department of Health</h2>
                            <p class="srf-topbar-sub">Knowledge Management and Information Technology Service</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Header with Back & Reference -->
            <div class="srf-form-header">
                <a href="{{ route('service-requests.track', ['reference_code' => $serviceRequest->reference_code]) }}"
                    class="srf-header-back" title="Go Back">←</a>
                <h1 class="srf-form-header-text">Edit Service Request</h1>
                <div class="srf-ref-code">#{{ $serviceRequest->reference_code }}</div>
            </div>

            <form method="POST" action="{{ $signedUpdateUrl }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- 1. Date/Time -->
                <div class="srf-section">
                    <h3 class="srf-section-label">1. Request Timeline</h3>
                    <div class="srf-field-grid srf-field-grid-2">
                        <div class="srf-field">
                            <label class="srf-label">Date of Request<span class="srf-required">*</span></label>
                            <input type="date" name="request_date"
                                value="{{ old('request_date', optional($serviceRequest->request_date)->toDateString() ?? now()->toDateString()) }}"
                                class="srf-input" required>
                        </div>
                        <div class="srf-field">
                            <label class="srf-label">Time of Request</label>
                            <input type="time" name="time_received"
                                value="{{ old('time_received', $serviceRequest->time_received) }}" class="srf-input">
                        </div>
                    </div>
                </div>

                <!-- 2-4. Category & Completion -->
                <div class="srf-section">
                    <h3 class="srf-section-label">2-4. Service Details</h3>
                    <div class="srf-field-grid">
                        <div class="srf-field">
                            <label class="srf-label">2. Request Category</label>
                            <select name="request_category" class="srf-select">
                                <option value="Technical Assistance" @selected(old('request_category', $serviceRequest->request_category) === 'Technical Assistance')>Technical Assistance
                                </option>
                                <option value="System Access" @selected(old('request_category', $serviceRequest->request_category) === 'System Access')>System Access</option>
                                <option value="Network/Internet" @selected(old('request_category', $serviceRequest->request_category) === 'Network/Internet')>Network/Internet</option>
                                <option value="Hardware Support" @selected(old('request_category', $serviceRequest->request_category) === 'Hardware Support')>Hardware Support</option>
                                <option value="Software Installation" @selected(old('request_category', $serviceRequest->request_category) === 'Software Installation')>Software Installation
                                </option>
                                <option value="Data Request" @selected(old('request_category', $serviceRequest->request_category) === 'Data Request')>Data Request</option>
                                <option value="Others" @selected(old('request_category', $serviceRequest->request_category) === 'Others')>Others</option>
                            </select>
                        </div>
                    </div>
                    <div class="srf-field-grid">
                        <div class="srf-field">
                            <label class="srf-label">3. Application System Name<span
                                    class="srf-required">*</span></label>
                            <input type="text" name="application_system_name"
                                value="{{ old('application_system_name', $serviceRequest->application_system_name) }}"
                                class="srf-input" required>
                        </div>
                    </div>
                    <div class="srf-field-grid srf-field-grid-2">
                        <div class="srf-field">
                            <label class="srf-label">4. Expected Completion Date</label>
                            <input type="date" name="expected_completion_date"
                                value="{{ old('expected_completion_date', optional($serviceRequest->expected_completion_date)->toDateString()) }}"
                                class="srf-input">
                        </div>
                        <div class="srf-field">
                            <label class="srf-label">Expected Completion Time</label>
                            <input type="time" name="expected_completion_time"
                                value="{{ old('expected_completion_time', $serviceRequest->expected_completion_time) }}"
                                class="srf-input">
                        </div>
                    </div>
                </div>

                <!-- 5. Name -->
                <div class="srf-section">
                    <h3 class="srf-section-label">5. Contact Person</h3>
                    <div class="srf-field-grid srf-field-grid-name">
                        <div class="srf-field">
                            <label class="srf-label">Last Name<span class="srf-required">*</span></label>
                            <input type="text" name="contact_last_name"
                                value="{{ old('contact_last_name', $serviceRequest->contact_last_name) }}"
                                class="srf-input text-center" required>
                        </div>
                        <div class="srf-field">
                            <label class="srf-label">First Name<span class="srf-required">*</span></label>
                            <input type="text" name="contact_first_name"
                                value="{{ old('contact_first_name', $serviceRequest->contact_first_name) }}"
                                class="srf-input text-center" required>
                        </div>
                        <div class="srf-field">
                            <label class="srf-label">Middle Name</label>
                            <input type="text" name="contact_middle_name"
                                value="{{ old('contact_middle_name', $serviceRequest->contact_middle_name) }}"
                                class="srf-input text-center">
                        </div>
                        <div class="srf-field">
                            <label class="srf-label">Suffix</label>
                            <input type="text" name="contact_suffix_name"
                                value="{{ old('contact_suffix_name', $serviceRequest->contact_suffix_name) }}"
                                class="srf-input text-center">
                        </div>
                    </div>
                </div>

                <!-- 6-7. Office & Address -->
                <div class="srf-section">
                    <h3 class="srf-section-label">6-7. Location Information</h3>
                    <div class="srf-field-grid">
                        <div class="srf-field">
                            <label class="srf-label">6. Office<span class="srf-required">*</span></label>
                            <div data-office-picker style="position:relative;">
                                <input type="hidden" id="office" name="office"
                                    value="{{ old('office', $serviceRequest->office) }}">
                                <div id="office_picker_root"
                                    style="display:flex; align-items:center; border:1.5px solid #e2e8f0; border-radius:8px; padding:6px 12px; background:#fff; min-height:45px;">
                                    <div id="office_chips" style="display:flex; flex-wrap:wrap; gap:6px; flex:1;"></div>
                                    <input type="search" id="office_search" placeholder="Search office..."
                                        autocomplete="off"
                                        style="border:none; outline:none; padding:4px 0; flex:1; min-width:150px; font-size:15px; background:transparent;">
                                </div>
                                <div id="office_results"
                                    style="display:none; position:absolute; top:100%; left:0; right:0; z-index:50; border:1.5px solid #cbd5e1; border-radius:8px; background:#fff; margin-top:5px; max-height:220px; overflow-y:auto; box-shadow:0 10px 25px rgba(0,0,0,0.1);">
                                </div>
                            </div>
                            <p id="office-regcode-display" style="font-size:12px; color:#64748b; margin-top:4px;"></p>
                        </div>
                    </div>
                    <div class="srf-field-grid">
                        <div class="srf-field">
                            <label class="srf-label">7. Address<span class="srf-required">*</span></label>
                            <input id="address" name="address" value="{{ old('address', $serviceRequest->address) }}"
                                class="srf-input" required>
                        </div>
                    </div>
                </div>

                <!-- 8-11. Contact Info -->
                <div class="srf-section">
                    <h3 class="srf-section-label">8-11. Communications</h3>
                    <div class="srf-field-grid srf-field-grid-3">
                        <div class="srf-field">
                            <label class="srf-label">8. Landline</label>
                            <input name="landline" value="{{ old('landline', $serviceRequest->landline) }}"
                                class="srf-input">
                        </div>
                        <div class="srf-field">
                            <label class="srf-label">9. Mobile No</label>
                            <input name="mobile_no" value="{{ old('mobile_no', $serviceRequest->mobile_no) }}"
                                class="srf-input">
                        </div>
                    </div>
                    <div class="srf-field-grid">
                        <div class="srf-field">
                            <label class="srf-label">10. Email Address<span class="srf-required">*</span></label>
                            <input type="email" name="email_address"
                                value="{{ old('email_address', $serviceRequest->email_address) }}" class="srf-input"
                                required>
                        </div>
                    </div>
                </div>

                <!-- 12. Description -->
                <div class="srf-section">
                    <h3 class="srf-section-label">12. Description of Request</h3>
                    <div class="srf-field">
                        <label class="srf-label">Details of your request<span class="srf-required">*</span></label>
                        <textarea name="description_request" class="srf-textarea"
                            placeholder="Please clearly write down the details..."
                            required>{{ old('description_request', $serviceRequest->description_request) }}</textarea>
                    </div>
                    <div class="srf-field" style="margin-top:16px; padding-top:16px; border-top:1px solid #f1f5f9;">
                        <label class="srf-label">Attach Photos (Up to 3)</label>
                        <input type="file" name="description_photos[]" accept="image/*,application/pdf" multiple class="srf-input"
                            id="track-edit-description-photos" style="padding:8px;">
                        <p style="font-size:12px; color:#64748b; margin-top:4px;">You can upload up to 3 files (images or PDF). Max 5MB
                            each.</p>
                    </div>
                </div>

                <!-- 13. Approval -->
                <div class="srf-section" style="padding-bottom: 24px;">
                    <h3 class="srf-section-label">13. Head of Office Approval</h3>
                    <p style="font-size:12px; color:#64748b; margin-bottom:12px;">Update head of office details and
                        signature if necessary.</p>

                    <div class="srf-sig-wrap">
                        <div class="srf-sig-modes" style="margin-bottom:15px;">
                            <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                                <input type="radio" name="approved_by_signature_mode" value="draw"
                                    @checked(old('approved_by_signature_mode', 'draw') === 'draw')>
                                <span style="display:flex; align-items:center; gap:4px;"><svg width="14" height="14"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M12 20h9M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                                    </svg> Draw Signature</span>
                            </label>
                            <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                                <input type="radio" name="approved_by_signature_mode" value="upload"
                                    @checked(old('approved_by_signature_mode') === 'upload')>
                                <span style="display:flex; align-items:center; gap:4px;"><svg width="14" height="14"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M17 8l-5-5-5 5M12 3v12"></path>
                                    </svg> Upload File</span>
                            </label>
                        </div>

                        <div id="draw-wrap">
                            <canvas id="signature-canvas" class="srf-sig-canvas"></canvas>
                            <input type="hidden" name="approved_by_signature_drawn" id="signature-drawn"
                                value="{{ old('approved_by_signature_drawn') }}">
                            <div
                                style="margin-top:10px; display:flex; justify-content:space-between; align-items:center;">
                                <button type="button" id="signature-clear" class="srf-btn srf-btn-cancel"
                                    style="padding:6px 16px; font-size:11px; text-transform:none;">Clear
                                    Drawing</button>
                                <span style="font-size:11px; color:#94a3b8; font-style:italic;">Please sign your name
                                    clearly in the box</span>
                            </div>
                        </div>

                        <div id="upload-wrap" class="hidden">
                            <div
                                style="border:2px dashed #e2e8f0; border-radius:8px; padding:20px; text-align:center; background:#fff;">
                                <input type="file" name="approved_by_signature_upload" accept="image/*"
                                    class="srf-input" style="border:none; background:transparent;">
                                <p style="font-size:11px; color:#64748b; margin-top:8px;">PNG or JPEG with transparent
                                    background preferred.</p>
                            </div>
                        </div>
                    </div>

                    <div class="srf-field-grid srf-field-grid-2" style="margin-top:20px;">
                        <div class="srf-field">
                            <label class="srf-label">Head of Office Name<span class="srf-required">*</span></label>
                            <input type="text" name="approved_by_name"
                                value="{{ old('approved_by_name', $serviceRequest->approved_by_name) }}"
                                class="srf-input" placeholder="FULL NAME" required>
                        </div>
                        <div class="srf-field">
                            <label class="srf-label">Position / Designation<span class="srf-required">*</span></label>
                            <input type="text" name="approved_by_position"
                                value="{{ old('approved_by_position', $serviceRequest->approved_by_position) }}"
                                class="srf-input" placeholder="OFFICIAL POSITION" required>
                        </div>
                    </div>
                    <div class="srf-field-grid" style="grid-template-columns: 200px 1fr;">
                        <div class="srf-field">
                            <label class="srf-label">Date Signed</label>
                            <input type="date" name="approved_date"
                                value="{{ old('approved_date', optional($serviceRequest->approved_date)->toDateString()) }}"
                                class="srf-input">
                        </div>
                    </div>
                </div>

                <div class="srf-actions">
                    <a href="{{ route('service-requests.track', ['reference_code' => $serviceRequest->reference_code]) }}"
                        class="srf-btn srf-btn-cancel">Cancel Changes</a>
                    <button type="submit" class="srf-btn srf-btn-submit">Update Request Form</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Office Picker Logic
            const initOfficePicker = function () {
                const hiddenInput = document.getElementById('office');
                const searchInput = document.getElementById('office_search');
                const chipsContainer = document.getElementById('office_chips');
                const resultsContainer = document.getElementById('office_results');
                const addressInput = document.getElementById('address');
                const regcodeDisplay = document.getElementById('office-regcode-display');

                let selected = hiddenInput.value ? [hiddenInput.value] : [];
                let officeDataMap = {};

                const renderChips = () => {
                    chipsContainer.innerHTML = selected.map(val => `
                        <div style="background:#f1f5f9; border:1px solid #cbd5e1; border-radius:4px; padding:4px 10px; display:flex; align-items:center; gap:8px; font-size:14px; font-weight:700; color:#0f172a;">
                            ${val}
                            <button type="button" data-remove="${val}" style="border:none; background:transparent; color:#94a3b8; cursor:pointer; font-size:18px; line-height:1;">&times;</button>
                        </div>
                    `).join('');
                    searchInput.placeholder = selected.length ? '' : 'Search for your office...';
                    searchInput.style.display = selected.length ? 'none' : 'block';
                };

                const fetchOffices = async (query) => {
                    try {
                        const response = await fetch(\`{{ route('offices.search') }}?q=\${encodeURIComponent(query)}\`);
                        const data = await response.json();
                        return data.offices || [];
                    } catch (err) { return []; }
                };

                searchInput.addEventListener('input', async (e) => {
                    const query = e.target.value.trim();
                    if (query.length < 2) { resultsContainer.style.display = 'none'; return; }
                    
                    const offices = await fetchOffices(query);
                    resultsContainer.innerHTML = offices.map(o => {
                        officeDataMap[o.name] = o;
                        return \`<div data-office="\${o.name}" style="padding:12px; cursor:pointer; border-bottom:1px solid #f1f5f9; font-size:14px; transition:background 0.2s;">
                            <div style="font-weight:700; color:#1e293b;">\${o.name}</div>
                            <div style="font-size:12px; color:#64748b; margin-top:2px;">\${o.address || 'No address listed'}</div>
                        </div>\`;
                    }).join('');
                    
                    resultsContainer.querySelectorAll('div[data-office]').forEach(div => {
                        div.addEventListener('mouseenter', () => div.style.background = '#f8fafc');
                        div.addEventListener('mouseleave', () => div.style.background = '#fff');
                    });
                    
                    resultsContainer.style.display = offices.length ? 'block' : 'none';
                });

                resultsContainer.addEventListener('click', (e) => {
                    const row = e.target.closest('[data-office]');
                    if (!row) return;
                    const name = row.dataset.office;
                    const data = officeDataMap[name];
                    
                    selected = [name];
                    hiddenInput.value = name;
                    if (data && data.address) addressInput.value = data.address;
                    if (data && data.regcode) regcodeDisplay.textContent = 'Regional / Facility Code: ' + data.regcode;
                    
                    searchInput.value = '';
                    resultsContainer.style.display = 'none';
                    renderChips();
                });

                chipsContainer.addEventListener('click', (e) => {
                    const btn = e.target.closest('[data-remove]');
                    if (!btn) return;
                    selected = [];
                    hiddenInput.value = '';
                    renderChips();
                    searchInput.focus();
                });

                document.addEventListener('click', (e) => {
                    if (!e.target.closest('[data-office-picker]')) resultsContainer.style.display = 'none';
                });

                if (selected.length) renderChips();
            };

            // Signature Logic
            const initSignature = function() {
                const canvas = document.getElementById('signature-canvas');
                const hidden = document.getElementById('signature-drawn');
                const modeInputs = document.querySelectorAll('input[name="approved_by_signature_mode"]');
                const drawWrap = document.getElementById('draw-wrap');
                const uploadWrap = document.getElementById('upload-wrap');
                const clearBtn = document.getElementById('signature-clear');
                
                if (!canvas) return;
                const ctx = canvas.getContext('2d');
                let drawing = false;

                const resize = () => {
                    const ratio = window.devicePixelRatio || 1;
                    const w = canvas.offsetWidth;
                    const h = canvas.offsetHeight;
                    canvas.width = w * ratio;
                    canvas.height = h * ratio;
                    ctx.scale(ratio, ratio);
                    ctx.lineWidth = 2.5;
                    ctx.lineCap = 'round';
                    ctx.strokeStyle = '#0f172a';
                };

                const getPoint = (e) => {
                    const rect = canvas.getBoundingClientRect();
                    const s = e.touches ? e.touches[0] : e;
                    return { x: s.clientX - rect.left, y: s.clientY - rect.top };
                };

                const start = (e) => { drawing = true; ctx.beginPath(); const p = getPoint(e); ctx.moveTo(p.x, p.y); e.preventDefault(); };
                const move = (e) => { if (!drawing) return; const p = getPoint(e); ctx.lineTo(p.x, p.y); ctx.stroke(); e.preventDefault(); };
                const end = () => { if (drawing) hidden.value = canvas.toDataURL(); drawing = false; };

                resize();
                window.addEventListener('resize', resize);

                canvas.addEventListener('mousedown', start);
                canvas.addEventListener('mousemove', move);
                window.addEventListener('mouseup', end);
                canvas.addEventListener('touchstart', start, {passive:false});
                canvas.addEventListener('touchmove', move, {passive:false});
                canvas.addEventListener('touchend', end);

                clearBtn.addEventListener('click', () => { ctx.clearRect(0,0,canvas.width,canvas.height); hidden.value = ''; });

                modeInputs.forEach(input => input.addEventListener('change', (e) => {
                    const isDraw = e.target.value === 'draw';
                    drawWrap.classList.toggle('hidden', !isDraw);
                    uploadWrap.classList.toggle('hidden', isDraw);
                }));

                // Load existing signature if any
                if (hidden.value) {
                    const img = new Image();
                    img.onload = () => ctx.drawImage(img, 0, 0, canvas.width / (window.devicePixelRatio || 1), canvas.height / (window.devicePixelRatio || 1));
                    img.src = hidden.value;
                }
            };

            initOfficePicker();
            initSignature();

            // File size validation for description photos
            const photoInput = document.getElementById('track-edit-description-photos');
            if (photoInput) {
                photoInput.addEventListener('change', function(e) {
                    const maxFileSize = 5 * 1024 * 1024; // 5MB in bytes
                    const maxFiles = 3;
                    const files = Array.from(e.target.files || []);
                    const rejectedFiles = [];
                    const validFiles = [];

                    files.forEach(function(file) {
                        if (file.size > maxFileSize) {
                            rejectedFiles.push(file.name + ' (' + (file.size / (1024 * 1024)).toFixed(2) + ' MB)');
                        } else if (validFiles.length < maxFiles) {
                            validFiles.push(file);
                        }
                    });

                    if (rejectedFiles.length > 0) {
                        alert('The following file(s) exceed the 5MB limit and were not added:\n\n' + rejectedFiles.join('\n') + '\n\nPlease choose files that are 5MB or smaller.');
                        
                        // Reset input to only valid files
                        const dt = new DataTransfer();
                        validFiles.forEach(f => dt.items.add(f));
                        e.target.files = dt.files;
                    }
                });
            }
        });
    </script>
</x-guest-layout>