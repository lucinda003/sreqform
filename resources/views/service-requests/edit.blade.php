<x-guest-layout>
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

    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&display=swap');

        .srf-root {
            position: relative;
            z-index: 5;
            font-family: 'DM Sans', sans-serif;
            font-size: 15px;
            color: #000;
        }

        .srf-card {
            background: #fff;
            border-radius: 12px;
            border: 1.5px solid #cbd5e1;
            overflow: hidden;
            box-shadow: 0 2px 16px 0 rgba(15, 118, 110, 0.07);
        }

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
            background: rgba(255, 255, 255, 0.2);
        }

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

        .srf-status-block {
            padding-top: 14px;
            padding-bottom: 14px;
            border-bottom: 1px solid #e2e8f0;
            background: #f8fafc;
        }

        .srf-table {
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
        }

        .srf-table td {
            border-color: #e2e8f0 !important;
            vertical-align: top;
        }

        .srf-table input[type="text"],
        .srf-table input[type="date"],
        .srf-table input[type="time"],
        .srf-table select,
        .srf-table textarea {
            font-family: 'DM Sans', sans-serif !important;
            font-size: 13px !important;
            color: #0f172a !important;
            font-weight: 600 !important;
            background: #f8fafc !important;
            border: 1.5px solid #e2e8f0 !important;
            border-radius: 6px !important;
            padding: 6px 10px !important;
            outline: none !important;
            transition: border-color 0.18s, background 0.18s, box-shadow 0.18s !important;
        }

        .srf-table input[type="text"]:focus,
        .srf-table input[type="date"]:focus,
        .srf-table input[type="time"]:focus,
        .srf-table select:focus,
        .srf-table textarea:focus {
            border-color: #0f766e !important;
            background: #fff !important;
            box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.1) !important;
        }

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
            font-size: 15px;
            font-weight: 700;
            color: #475569;
            background: #fff;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px 22px;
            text-decoration: none;
            transition: border-color 0.18s, color 0.18s;
        }

        .srf-btn-back:hover {
            border-color: #94a3b8;
            color: #1e293b;
        }

        .srf-btn-submit {
            font-family: 'DM Sans', sans-serif;
            font-size: 15px;
            font-weight: 700;
            color: #fff;
            background: #0f766e;
            border: none;
            border-radius: 8px;
            padding: 11px 26px;
            cursor: pointer;
            letter-spacing: 0.03em;
            transition: background 0.18s, box-shadow 0.18s;
        }

        .srf-btn-submit:hover {
            background: #134e4a;
            box-shadow: 0 4px 12px rgba(15, 118, 110, 0.25);
        }

        .srf-chat-list {
            display: flex;
            flex-direction: column;
            gap: 0.55rem;
            max-height: 280px;
            overflow-y: auto;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            background: #f8fafc;
            padding: 0.8rem;
        }

        .srf-chat-item {
            display: flex;
        }

        .srf-chat-item.admin {
            justify-content: flex-end;
        }

        .srf-chat-item.requestor {
            justify-content: flex-start;
        }

        .srf-chat-bubble {
            max-width: min(680px, 92%);
            border-radius: 10px;
            border: 1px solid #cbd5e1;
            padding: 0.5rem 0.72rem;
            background: #fff;
        }

        .srf-chat-bubble.admin {
            background: #ecfeff;
            border-color: #99f6e4;
        }

        .srf-chat-bubble.requestor {
            background: #fff;
            border-color: #cbd5e1;
        }

        .srf-chat-meta {
            margin: 0 0 0.2rem;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: #64748b;
        }

        .srf-chat-text {
            margin: 0;
            font-size: 13px;
            color: #0f172a;
            white-space: pre-wrap;
            word-break: break-word;
        }

        .srf-chat-locked {
            margin-top: 0.7rem;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            background: #f8fafc;
            padding: 0.55rem 0.72rem;
            font-size: 12px;
            color: #334155;
        }

        .srf-notif-wrap {
            position: relative;
        }

        .srf-notif-btn {
            width: 38px;
            height: 38px;
            border-radius: 999px;
            border: 1px solid #cbd5e1;
            background: #fff;
            color: #0f172a;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .srf-notif-btn:hover {
            border-color: #94a3b8;
            background: #f8fafc;
        }

        .srf-notif-btn svg {
            width: 18px;
            height: 18px;
        }

        .srf-notif-count {
            position: absolute;
            top: -4px;
            right: -4px;
            min-width: 18px;
            height: 18px;
            border-radius: 999px;
            background: #dc2626;
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            line-height: 18px;
            text-align: center;
            padding: 0 5px;
            border: 2px solid #fff;
        }

        .srf-notif-panel {
            position: absolute;
            top: 46px;
            right: 0;
            width: min(360px, calc(100vw - 24px));
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 18px 36px rgba(15,23,42,0.25);
            overflow: hidden;
            z-index: 85;
        }

        .srf-notif-panel-head {
            padding: 10px 12px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #0f172a;
            border-bottom: 1px solid #e2e8f0;
            background: #f8fafc;
        }

        .srf-notif-list {
            max-height: 300px;
            overflow-y: auto;
            padding: 8px;
            display: flex;
            flex-direction: column;
            gap: 7px;
        }

        .srf-notif-empty {
            margin: 0;
            font-size: 13px;
            color: #64748b;
            padding: 6px 4px;
        }

        .srf-notif-item {
            border: 1px solid #bfdbfe;
            border-radius: 9px;
            background: #eff6ff;
            padding: 8px 10px;
        }

        .srf-notif-item-title {
            margin: 0;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #1d4ed8;
        }

        .srf-notif-item-text {
            margin: 3px 0 0;
            font-size: 13px;
            font-weight: 600;
            color: #0f172a;
            word-break: break-word;
        }

        .srf-notif-item-time {
            margin: 4px 0 0;
            font-size: 11px;
            color: #64748b;
        }

        .srf-toast-stack {
            position: fixed;
            top: 18px;
            right: 18px;
            z-index: 80;
            display: flex;
            flex-direction: column;
            gap: 8px;
            pointer-events: none;
        }

        .srf-toast {
            min-width: 260px;
            max-width: min(420px, calc(100vw - 24px));
            border-radius: 10px;
            border: 1px solid #99f6e4;
            background: #ecfeff;
            color: #0f172a;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.2);
            padding: 10px 12px;
            pointer-events: auto;
            animation: srf-toast-in 180ms ease-out;
        }

        .srf-toast-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #0f766e;
            margin: 0 0 3px;
        }

        .srf-toast-text {
            margin: 0;
            font-size: 13px;
            font-weight: 600;
            color: #0f172a;
        }

        @keyframes srf-toast-in {
            from {
                transform: translateY(-8px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>

    <div class="srf-root">

    <header class="auth-login-topbar">
        <div class="auth-login-brand">
            <img src="{{ asset('images/dohlogo.svg') }}" alt="DOH Logo" class="auth-login-brand-logo">
            <div>
                <h1 class="auth-login-brand-title">DEPARTMENT OF HEALTH</h1>
                <p class="auth-login-brand-subtitle">Secure Access Portal</p>
            </div>
        </div>

        <div class="auth-login-top-actions">
            @if ($isAdmin)
                <div class="srf-notif-wrap" id="admin-chat-notif-wrap">
                    <button type="button" class="srf-notif-btn" id="admin-chat-notif-toggle" aria-label="View notifications">
                        <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path d="M10 2a5.5 5.5 0 00-5.5 5.5v2.8L3 12.5h14l-1.5-2.2V7.5A5.5 5.5 0 0010 2z"></path>
                            <path d="M8.5 15.8a1.5 1.5 0 003 0"></path>
                        </svg>
                        <span class="srf-notif-count hidden" id="admin-chat-notif-count">0</span>
                    </button>

                    <div class="srf-notif-panel hidden" id="admin-chat-notif-panel">
                        <div class="srf-notif-panel-head">Notifications</div>
                        <div class="srf-notif-list" id="admin-chat-notif-list">
                            <p class="srf-notif-empty" id="admin-chat-notif-empty">No notifications yet.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </header>

    <section style="max-width: 1300px; margin: 1.5rem auto; padding: 0 1rem 2rem;">
    @if (session('status'))
        <div class="mb-3 rounded-xl border border-emerald-300 bg-emerald-50 px-3 py-2 text-sm font-semibold text-emerald-700">
            {{ session('status') }}
        </div>
    @endif

    <div class="mb-3 hidden rounded-xl border border-sky-300 bg-sky-50 px-3 py-2 text-sm font-semibold text-sky-800" data-admin-live-notice></div>

    <div class="srf-card">
        <div class="srf-form-header">
            <span class="srf-form-header-text">Service Request Form</span>
            <div class="srf-form-header-line"></div>
        </div>

        <div class="srf-section srf-status-block">
            <div class="flex flex-wrap items-center gap-3">
                <p class="text-sm font-semibold text-slate-700">Status :</p>
                @php
                    $statusClasses = match ($serviceRequest->status) {
                        'checking' => 'border-sky-300 bg-sky-100 text-sky-800',
                        'approved' => 'border-emerald-300 bg-emerald-100 text-emerald-800',
                        'completed', 'closed' => 'border-teal-300 bg-teal-100 text-teal-800',
                        'rejected' => 'border-rose-300 bg-rose-100 text-rose-800',
                        default => 'border-amber-300 bg-amber-100 text-amber-800',
                    };
                @endphp
                <span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold uppercase {{ $statusClasses }}">
                    {{ $serviceRequest->status }}
                </span>

                @if ($isAdmin)
                    <div class="ms-auto flex flex-wrap items-center gap-2">
                        <a id="admin-print-button" href="{{ route('service-requests.print', $serviceRequest) }}" class="rounded-xl border border-slate-300 bg-slate-50 px-5 py-2.5 text-sm font-bold uppercase tracking-[0.06em] text-slate-800 transition hover:bg-slate-100">Print</a>
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

        <div class="overflow-x-auto bg-white">
            <form method="POST" action="{{ route('service-requests.update', $serviceRequest) }}" enctype="multipart/form-data" class="min-w-[1040px] space-y-0">
                @csrf
                @method('PUT')

                <fieldset @if ($isAdmin) disabled @endif>

                <div class="px-4 pb-3">
                    <p class="srf-section-label">Request Information</p>
                    <table class="srf-table w-full border-collapse text-[12px] text-slate-900">
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
                    <p class="srf-section-label">Requester Details</p>
                    <table class="srf-table w-full border-collapse text-[12px] text-slate-900">
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
                    <p class="srf-section-label">Description of Request</p>
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
                    <p class="srf-section-label">Approved By</p>
                    <table class="srf-table w-full border-collapse text-[12px] text-slate-900">
                        <tr>
                            <td class="w-48 border border-slate-400 px-2 py-1 font-semibold">13) APPROVED BY :</td>
                            <td class="border border-slate-400 px-2 py-1">
                                <div class="grid grid-cols-10 gap-3">
                                    <div class="col-span-6">
                                        @if (! $isAdmin)
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

                                                @if (!empty($serviceRequest->approved_by_signature) && old('approved_by_signature_drawn') === null)
                                                    <div class="mb-2">
                                                        <p class="mb-1 text-[11px] text-slate-600">Current Signature</p>
                                                        <img src="{{ \Illuminate\Support\Facades\Storage::url($serviceRequest->approved_by_signature) }}" alt="Current Signature" class="h-16 rounded border border-slate-300 bg-white px-2 py-1">
                                                    </div>
                                                @endif

                                                <div id="edit-signature-draw-wrap" class="space-y-1">
                                                    <canvas id="edit-signature-canvas" class="h-24 w-full rounded border border-slate-300 bg-white"></canvas>
                                                    <input type="hidden" name="approved_by_signature_drawn" id="edit-signature-drawn" value="{{ old('approved_by_signature_drawn') }}">
                                                    <input type="hidden" name="approved_by_signature_clear" id="edit-signature-clear-flag" value="0">
                                                    <button type="button" id="edit-signature-clear" class="rounded border border-slate-300 bg-white px-2 py-1 text-[11px] font-semibold text-slate-700">Clear</button>
                                                </div>

                                                <div id="edit-signature-upload-wrap" class="hidden">
                                                    <input type="file" name="approved_by_signature_upload" accept="image/*" class="block w-full text-[11px] text-slate-700 file:mr-2 file:rounded-md file:border-0 file:bg-slate-800 file:px-2 file:py-1 file:text-[11px] file:font-medium file:text-white">
                                                </div>

                                                <x-input-error :messages="$errors->get('approved_by_signature_upload')" class="mt-1" />
                                                <x-input-error :messages="$errors->get('approved_by_signature_drawn')" class="mt-1" />
                                            </div>
                                        @else
                                            <div class="mb-1 rounded-md border border-slate-300 bg-slate-50 p-2">
                                                <p class="text-[11px] font-semibold text-slate-700">Requester Signature (Read Only)</p>
                                                @if (!empty($serviceRequest->approved_by_signature))
                                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($serviceRequest->approved_by_signature) }}" alt="Requester Signature" class="mt-1 h-14 rounded border border-slate-300 bg-white px-2 py-1">
                                                @else
                                                    <p class="mt-1 text-[11px] text-slate-500">No signature provided.</p>
                                                @endif
                                            </div>
                                        @endif

                                        <input name="approved_by_name" value="{{ old('approved_by_name', $serviceRequest->approved_by_name) }}" class="auth-input !-mt-2 !min-h-0 !rounded-none !border-0 border-b border-slate-400 !bg-transparent px-0 py-0 text-[12px]" required>
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

                <div class="srf-footer">
                    <a href="{{ route('service-requests.index') }}" class="srf-btn-back">Cancel</a>
                    <button type="submit" class="srf-btn-submit">Update Service Request</button>
                </div>
            </form>
        </div>
    </div>
    </section>

    @if ($isAdmin)
        <section style="max-width: 1300px; margin: -0.7rem auto 1.8rem; padding: 0 1rem;">
            <div class="srf-card">
                <div class="srf-form-header">
                    <span class="srf-form-header-text">Requestor and Admin Chat</span>
                    <div class="srf-form-header-line"></div>
                </div>

                @php
                    $adminChatStatus = strtolower((string) ($serviceRequest->contact_chat_status ?? ''));
                    $isChatPending = $adminChatStatus === 'pending';
                    $isChatAccepted = $adminChatStatus === 'accepted';
                @endphp

                <div class="p-4" data-admin-chat-panel data-admin-chat-status="{{ $adminChatStatus !== '' ? $adminChatStatus : 'none' }}" data-admin-chat-poll-endpoint="{{ route('service-requests.messages.index', $serviceRequest) }}" data-admin-reference-code="{{ $serviceRequest->reference_code }}">
                    <div class="rounded-lg border border-amber-200 bg-amber-50 px-3 py-3 text-sm text-amber-900 {{ $isChatPending ? '' : 'hidden' }}" data-admin-chat-state="pending">
                            <p class="font-semibold">Pending chat request from requestor.</p>
                            <p class="mt-1 text-xs">Accept to unlock messaging, or decline to keep chat hidden.</p>

                            <form method="POST" action="{{ route('service-requests.chat-request.decision', $serviceRequest) }}" class="mt-3 flex flex-wrap gap-2">
                                @csrf
                                <button type="submit" name="decision" value="accepted" class="rounded-lg border border-emerald-300 bg-emerald-600 px-3 py-1.5 text-xs font-bold uppercase tracking-[0.05em] text-white transition hover:bg-emerald-700">Accept Request</button>
                                <button type="submit" name="decision" value="rejected" class="rounded-lg border border-rose-300 bg-rose-600 px-3 py-1.5 text-xs font-bold uppercase tracking-[0.05em] text-white transition hover:bg-rose-700">Decline</button>
                            </form>
                    </div>

                    <div class="rounded-lg border border-rose-200 bg-rose-50 px-3 py-3 text-sm text-rose-800 {{ $adminChatStatus === 'rejected' ? '' : 'hidden' }}" data-admin-chat-state="rejected">
                        Last chat request was declined. Waiting for a new chat request from the track page.
                    </div>

                    <div class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-3 text-sm text-slate-700 {{ (! $isChatAccepted && $adminChatStatus !== 'rejected' && ! $isChatPending) ? '' : 'hidden' }}" data-admin-chat-state="none">
                        No chat request yet. Chat stays hidden until requestor clicks Contact Admin Personnel and admin accepts.
                    </div>

                    <div data-admin-chat-state="accepted" class="{{ $isChatAccepted ? '' : 'hidden' }}">
                        <div class="mb-3 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs font-semibold uppercase tracking-[0.04em] text-emerald-700" data-admin-chat-accepted-banner>
                            Chat request accepted
                        </div>

                        <div class="srf-chat-list" data-chat-list data-chat-endpoint="{{ route('service-requests.messages.index', $serviceRequest) }}">
                            @forelse ($chatMessages as $chatMessage)
                                @php
                                    $isAdminMessage = strtolower((string) $chatMessage->sender_type) === 'admin';
                                    $senderLabel = $isAdminMessage
                                        ? ('Admin' . (filled($chatMessage->senderUser?->name) ? ' - ' . $chatMessage->senderUser->name : ''))
                                        : 'Requestor';
                                @endphp

                                <div class="srf-chat-item {{ $isAdminMessage ? 'admin' : 'requestor' }}">
                                    <div class="srf-chat-bubble {{ $isAdminMessage ? 'admin' : 'requestor' }}">
                                        <p class="srf-chat-meta">{{ $senderLabel }} • {{ $chatMessage->created_at?->format('M j, Y g:i A') }}</p>
                                        <p class="srf-chat-text">{{ $chatMessage->message }}</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-slate-500">No chat messages yet.</p>
                            @endforelse
                        </div>

                        <form method="POST" action="{{ route('service-requests.messages.store', $serviceRequest) }}" class="mt-3" data-chat-enter-form>
                            @csrf
                            <label for="admin_chat_message" class="block text-xs font-semibold uppercase tracking-[0.06em] text-slate-600">Reply as Admin</label>
                            <textarea id="admin_chat_message" name="message" class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-teal-600 focus:ring-teal-600" rows="3" maxlength="1000" required>{{ old('message') }}</textarea>
                            <x-input-error :messages="$errors->get('message')" class="mt-1" />
                            <p class="mt-1 hidden text-xs text-rose-600" data-chat-error></p>
                            <p class="mt-1 text-[11px] text-slate-500">Press Enter to send. Use Shift+Enter for a new line.</p>
                            <div class="mt-2 flex justify-end">
                                <button type="submit" class="rounded-lg bg-teal-700 px-4 py-2 text-xs font-bold uppercase tracking-[0.06em] text-white transition hover:bg-teal-800">Send Message</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    @endif

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

            const initSignatureInput = function () {
                const modeInputs = document.querySelectorAll('input[name="approved_by_signature_mode"]');
                const drawWrap = document.getElementById('edit-signature-draw-wrap');
                const uploadWrap = document.getElementById('edit-signature-upload-wrap');
                const canvas = document.getElementById('edit-signature-canvas');
                const hiddenDrawn = document.getElementById('edit-signature-drawn');
                const clearFlag = document.getElementById('edit-signature-clear-flag');
                const clearBtn = document.getElementById('edit-signature-clear');

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

            const initDirectPrint = function () {
                const printButton = document.getElementById('admin-print-button');
                if (!printButton) {
                    return;
                }

                const frameId = 'service-request-print-frame';
                let printFrame = document.getElementById(frameId);

                if (!printFrame) {
                    printFrame = document.createElement('iframe');
                    printFrame.id = frameId;
                    printFrame.style.display = 'none';
                    document.body.appendChild(printFrame);
                }

                printButton.addEventListener('click', function (event) {
                    event.preventDefault();

                    const baseUrl = printButton.getAttribute('href') || '';
                    if (baseUrl === '') {
                        return;
                    }

                    const ts = Date.now();
                    const iframeUrl = baseUrl + (baseUrl.includes('?') ? '&' : '?') + 'print_ts=' + ts;

                    printFrame.onload = function () {
                        setTimeout(function () {
                            try {
                                printFrame.contentWindow.focus();
                                printFrame.contentWindow.print();
                            } catch (error) {
                                window.open(baseUrl + (baseUrl.includes('?') ? '&' : '?') + 'autoprint=1', '_blank');
                            }
                        }, 120);
                    };

                    printFrame.src = iframeUrl;
                });
            };

            const initChatEnterSubmit = function () {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                const adminChatPanel = document.querySelector('[data-admin-chat-panel]');
                const adminLiveNotice = document.querySelector('[data-admin-live-notice]');
                const notifWrap = document.getElementById('admin-chat-notif-wrap');
                const notifToggle = document.getElementById('admin-chat-notif-toggle');
                const notifPanel = document.getElementById('admin-chat-notif-panel');
                const notifList = document.getElementById('admin-chat-notif-list');
                const notifEmpty = document.getElementById('admin-chat-notif-empty');
                const notifCount = document.getElementById('admin-chat-notif-count');
                const chatForms = document.querySelectorAll('[data-chat-enter-form]');
                let unreadNotifications = 0;

                const escapeHtml = function (value) {
                    return String(value)
                        .replace(/&/g, '&amp;')
                        .replace(/</g, '&lt;')
                        .replace(/>/g, '&gt;')
                        .replace(/"/g, '&quot;')
                        .replace(/'/g, '&#039;');
                };

                const normalizeChatState = function (state) {
                    const normalized = String(state || '').toLowerCase();
                    return ['none', 'pending', 'accepted', 'rejected'].includes(normalized) ? normalized : 'none';
                };

                const getCurrentChatState = function () {
                    if (!adminChatPanel) {
                        return 'none';
                    }

                    return normalizeChatState(adminChatPanel.dataset.adminChatStatus || 'none');
                };

                const setCurrentChatState = function (state) {
                    if (!adminChatPanel) {
                        return;
                    }

                    adminChatPanel.dataset.adminChatStatus = normalizeChatState(state);
                };

                const showChatRequestToast = function (message) {
                    let stack = document.getElementById('admin-chat-toast-stack');

                    if (!stack) {
                        stack = document.createElement('div');
                        stack.id = 'admin-chat-toast-stack';
                        stack.className = 'srf-toast-stack';
                        document.body.appendChild(stack);
                    }

                    const toast = document.createElement('div');
                    toast.className = 'srf-toast';
                    toast.innerHTML = '<p class="srf-toast-title">New Chat Request</p><p class="srf-toast-text"></p>';
                    const textNode = toast.querySelector('.srf-toast-text');
                    if (textNode) {
                        textNode.textContent = message;
                    }

                    stack.appendChild(toast);

                    window.setTimeout(function () {
                        toast.remove();
                    }, 4500);
                };

                const updateNotifBadge = function () {
                    if (!notifCount) {
                        return;
                    }

                    if (unreadNotifications > 0) {
                        notifCount.textContent = String(unreadNotifications);
                        notifCount.classList.remove('hidden');
                        return;
                    }

                    notifCount.classList.add('hidden');
                };

                const addNotificationItem = function (message) {
                    if (!notifList) {
                        return;
                    }

                    if (notifEmpty) {
                        notifEmpty.classList.add('hidden');
                    }

                    const notifItem = document.createElement('div');
                    notifItem.className = 'srf-notif-item';
                    notifItem.innerHTML = '<p class="srf-notif-item-title">Chat Request</p><p class="srf-notif-item-text"></p><p class="srf-notif-item-time"></p>';

                    const textNode = notifItem.querySelector('.srf-notif-item-text');
                    const timeNode = notifItem.querySelector('.srf-notif-item-time');

                    if (textNode) {
                        textNode.textContent = message;
                    }

                    if (timeNode) {
                        const now = new Date();
                        timeNode.textContent = now.toLocaleString();
                    }

                    notifList.prepend(notifItem);

                    while (notifList.querySelectorAll('.srf-notif-item').length > 12) {
                        const items = notifList.querySelectorAll('.srf-notif-item');
                        if (items.length <= 12) {
                            break;
                        }
                        items[items.length - 1].remove();
                    }

                    unreadNotifications += 1;
                    updateNotifBadge();
                };

                if (notifToggle && notifPanel) {
                    notifToggle.addEventListener('click', function () {
                        notifPanel.classList.toggle('hidden');
                        if (!notifPanel.classList.contains('hidden')) {
                            unreadNotifications = 0;
                            updateNotifBadge();
                        }
                    });

                    document.addEventListener('click', function (event) {
                        if (!notifWrap || notifPanel.classList.contains('hidden')) {
                            return;
                        }

                        if (!notifWrap.contains(event.target)) {
                            notifPanel.classList.add('hidden');
                        }
                    });
                }

                const showTopLiveNotice = function (message) {
                    if (!adminLiveNotice) {
                        return;
                    }

                    adminLiveNotice.textContent = message;
                    adminLiveNotice.classList.remove('hidden');

                    window.setTimeout(function () {
                        if (adminLiveNotice.textContent === message) {
                            adminLiveNotice.classList.add('hidden');
                        }
                    }, 8000);
                };

                const syncChatStateUi = function (state) {
                    if (!adminChatPanel) {
                        return;
                    }

                    const normalizedState = normalizeChatState(state);
                    const stateBlocks = adminChatPanel.querySelectorAll('[data-admin-chat-state]');

                    stateBlocks.forEach(function (block) {
                        const blockState = normalizeChatState(block.getAttribute('data-admin-chat-state') || 'none');
                        block.classList.toggle('hidden', blockState !== normalizedState);
                    });

                    setCurrentChatState(normalizedState);
                };

                syncChatStateUi(getCurrentChatState());

                chatForms.forEach(function (form) {
                    const textarea = form.querySelector('textarea[name="message"]');
                    const chatSection = form.closest('.p-4');
                    const chatList = chatSection ? chatSection.querySelector('[data-chat-list]') : null;
                    const chatEndpoint = chatList ? chatList.dataset.chatEndpoint : '';
                    const errorBox = form.querySelector('[data-chat-error]');

                    if (!textarea || !chatList || chatEndpoint === '') {
                        return;
                    }

                    const renderMessages = function (messages, scrollToBottom) {
                        if (!Array.isArray(messages) || messages.length === 0) {
                            chatList.innerHTML = '<p class="text-sm text-slate-500">No chat messages yet.</p>';
                            return;
                        }

                        chatList.innerHTML = messages.map(function (message) {
                            const senderType = String(message.sender_type || '').toLowerCase() === 'admin' ? 'admin' : 'requestor';
                            const senderLabel = escapeHtml(message.sender_label || '');
                            const createdAt = escapeHtml(message.created_at_label || '');
                            const text = escapeHtml(message.message || '').replace(/\n/g, '<br>');

                            return '<div class="srf-chat-item ' + senderType + '">' +
                                '<div class="srf-chat-bubble ' + senderType + '">' +
                                '<p class="srf-chat-meta">' + senderLabel + ' • ' + createdAt + '</p>' +
                                '<p class="srf-chat-text">' + text + '</p>' +
                                '</div>' +
                                '</div>';
                        }).join('');

                        if (scrollToBottom) {
                            chatList.scrollTop = chatList.scrollHeight;
                        }
                    };

                    const loadMessages = async function (scrollToBottom) {
                        try {
                            const response = await fetch(chatEndpoint, {
                                headers: {
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                },
                            });

                            if (!response.ok) {
                                return;
                            }

                            const payload = await response.json();
                            const nextState = payload.chat_accepted
                                ? 'accepted'
                                : normalizeChatState(payload.chat_status || 'none');

                            const currentState = getCurrentChatState();
                            if (currentState !== nextState) {
                                if (currentState !== 'pending' && nextState === 'pending' && adminChatPanel) {
                                    const referenceCode = adminChatPanel.dataset.adminReferenceCode || 'Unknown reference';
                                    const notifyMessage = referenceCode + ' send a request chat';
                                    showChatRequestToast(notifyMessage);
                                    showTopLiveNotice(notifyMessage);
                                    addNotificationItem(notifyMessage);
                                }

                                syncChatStateUi(nextState);
                                if (nextState !== 'accepted') {
                                    return;
                                }
                            }

                            if (nextState !== 'accepted') {
                                return;
                            }

                            renderMessages(payload.messages || [], scrollToBottom);
                        } catch (error) {
                            // Keep previous chat state if refresh fails.
                        }
                    };

                    const sendMessage = async function () {
                        const messageValue = textarea.value.trim();
                        if (messageValue === '') {
                            return;
                        }

                        if (errorBox) {
                            errorBox.classList.add('hidden');
                            errorBox.textContent = '';
                        }

                        const body = new URLSearchParams();
                        body.set('_token', csrfToken);
                        body.set('message', messageValue);

                        try {
                            const response = await fetch(form.action, {
                                method: 'POST',
                                headers: {
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                                    'X-Requested-With': 'XMLHttpRequest',
                                },
                                body: body.toString(),
                            });

                            if (response.ok) {
                                textarea.value = '';
                                await loadMessages(true);
                                return;
                            }

                            if (response.status === 422) {
                                const payload = await response.json();
                                const messageError = payload?.errors?.message?.[0] || 'Unable to send message.';

                                if (errorBox) {
                                    errorBox.textContent = messageError;
                                    errorBox.classList.remove('hidden');
                                    return;
                                }
                            }

                            form.submit();
                        } catch (error) {
                            form.submit();
                        }
                    };

                    form.addEventListener('submit', function (event) {
                        event.preventDefault();
                        sendMessage();
                    });

                    textarea.addEventListener('keydown', function (event) {
                        if (event.key === 'Enter' && !event.shiftKey) {
                            event.preventDefault();
                            sendMessage();
                        }
                    });

                    loadMessages(true);
                    window.setInterval(function () {
                        loadMessages(false);
                    }, 4000);
                });

                if (adminChatPanel && chatForms.length === 0) {
                    const pollEndpoint = adminChatPanel.dataset.adminChatPollEndpoint || '';

                    if (pollEndpoint !== '') {
                        const pollStatus = async function () {
                            try {
                                const response = await fetch(pollEndpoint, {
                                    headers: {
                                        'Accept': 'application/json',
                                        'X-Requested-With': 'XMLHttpRequest',
                                    },
                                });

                                if (!response.ok) {
                                    return;
                                }

                                const payload = await response.json();
                                const nextState = payload.chat_accepted
                                    ? 'accepted'
                                    : normalizeChatState(payload.chat_status || 'none');

                                const currentState = getCurrentChatState();
                                if (currentState !== nextState) {
                                    if (currentState !== 'pending' && nextState === 'pending' && adminChatPanel) {
                                        const referenceCode = adminChatPanel.dataset.adminReferenceCode || 'Unknown reference';
                                        const notifyMessage = referenceCode + ' send a request chat';
                                        showChatRequestToast(notifyMessage);
                                        showTopLiveNotice(notifyMessage);
                                        addNotificationItem(notifyMessage);
                                    }

                                    syncChatStateUi(nextState);

                                    // Show message panel right away once request becomes accepted.
                                    if (nextState === 'accepted') {
                                        window.location.reload();
                                    }
                                }
                            } catch (error) {
                                // Keep current UI on transient poll failures.
                            }
                        };

                        window.setInterval(pollStatus, 4000);
                    }
                }
            };

            initSignatureInput();
            initDirectPrint();
            initChatEnterSubmit();
        });

    </script>
</x-guest-layout>
