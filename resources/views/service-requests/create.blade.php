@php View::share('pageTitle', 'Service Request Form'); @endphp
<x-guest-layout>
    @php
        $hospitalOfficeMap = is_array($hospitalOfficeMap ?? null) ? $hospitalOfficeMap : [];
        $officeRegcodeMap = is_array($officeRegcodeMap ?? null) ? $officeRegcodeMap : [];
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

        .srf-root .auth-login-topbar {
            gap: 1rem;
            padding: 0.55rem 0.95rem;
        }

        .srf-root .auth-login-brand {
            gap: 0.65rem;
        }

        .srf-root .auth-login-brand-logo {
            width: 48px;
            height: 48px;
        }

        .srf-root .auth-login-brand-title {
            font-size: 1.18rem;
            line-height: 1.04;
            letter-spacing: 0.01em;
        }

        .srf-root .auth-login-brand-subtitle {
            display: block;
            font-size: 0.78rem;
            margin-top: 0;
            line-height: 1.12;
            color: #334155;
            max-width: 420px;
            white-space: normal;
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

        .srf-header-back {
            display: inline-flex;
            align-items: center;
            justify-content: flex-start;
            font-size: 18px;
            font-weight: 700;
            letter-spacing: 0.02em;
            text-transform: none;
            color: #fff;
            text-decoration: none;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.42);
            border-radius: 8px;
            padding: 3px 9px;
            transition: background 0.15s ease, border-color 0.15s ease, transform 0.15s ease;
            flex-shrink: 0;
        }

        .srf-header-back:hover {
            background: rgba(255, 255, 255, 0.22);
            border-color: rgba(255, 255, 255, 0.7);
            transform: translateY(-1px);
        }
        .srf-form-header-text {
            font-size: 21px;
            font-weight: 700;
            letter-spacing: 0.08em;
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
        .srf-office-label-row {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }
        .srf-input.srf-office-region-filter {
            width: auto;
            min-width: 150px;
            max-width: 220px;
            flex: 0 1 220px;
            padding-top: 4px;
            padding-bottom: 4px;
            font-size: 13px;
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
        .srf-system-picker {
            position: relative;
        }
        .srf-system-picker-box {
            display: flex;
            min-height: 39px;
            width: 100%;
            align-items: center;
            gap: 6px;
            border: 1.5px solid #e2e8f0;
            border-radius: 6px;
            background: #f8fafc;
            padding: 4px 8px;
            box-sizing: border-box;
            transition: border-color 0.18s, background 0.18s, box-shadow 0.18s;
        }
        .srf-system-picker-box:hover {
            border-color: #94a3b8;
            background: #fff;
        }
        .srf-system-picker-box:focus-within {
            border-color: #0f766e;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(15,118,110,0.1);
        }
        .srf-system-picker-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }
        .srf-system-picker-chip {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            max-width: 100%;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            background: #fff;
            padding: 3px 7px;
            color: #000;
            font-size: 14px;
            font-weight: 600;
            line-height: 1.2;
        }
        .srf-system-picker-remove {
            border: 0;
            background: transparent;
            color: #475569;
            cursor: pointer;
            font-size: 15px;
            font-weight: 700;
            line-height: 1;
            padding: 0 1px;
        }
        .srf-system-picker-remove:hover {
            color: #dc2626;
        }
        .srf-system-picker-input {
            flex: 1;
            min-width: 160px;
            border: 0;
            outline: none;
            background: transparent;
            color: #000;
            font-family: 'DM Sans', sans-serif;
            font-size: 16px;
            font-weight: 600;
            padding: 3px 2px;
        }
        .srf-system-picker-input::placeholder {
            color: #64748b;
            font-weight: 500;
        }
        .srf-system-picker-results {
            position: absolute;
            left: 0;
            right: 0;
            z-index: 30;
            margin-top: 4px;
            max-height: 240px;
            overflow-y: auto;
            border: 1.5px solid #cbd5e1;
            border-radius: 6px;
            background: #fff;
            box-shadow: 0 12px 24px rgba(15, 23, 42, 0.18);
        }
        .srf-system-picker-option {
            display: block;
            width: 100%;
            border: 0;
            background: transparent;
            padding: 8px 10px;
            text-align: left;
            color: #000;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
        }
        .srf-system-picker-option:hover {
            background: #f1f5f9;
        }
        .srf-system-picker-empty {
            padding: 8px 10px;
            color: #64748b;
            font-size: 14px;
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
            height: 300px;
            max-width: 100%;
            border: 1px solid #e2e8f0;
            border-radius: 5px;
            background: #fff;
            display: block;
        }
        .srf-sig-upload-area {
            min-height: 300px;
            border: 1px dashed #cbd5e1;
            border-radius: 6px;
            background: #fff;
            display: flex;
            align-items: center;
            padding: 12px;
        }
        .hidden.srf-sig-upload-area {
            display: none !important;
        }
        .srf-sig-upload-area .srf-file-input {
            width: 100%;
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
            grid-template-columns: 1fr;
            gap: 0;
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
        .srf-btn-clear {
            font-family: 'DM Sans', sans-serif;
            font-size: 11px;
            font-weight: 700;
            color: #475569;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 4px 10px;
            cursor: pointer;
            transition: border-color 0.18s, color 0.18s, background 0.18s;
        }
        .srf-btn-clear:hover {
            border-color: #cbd5e1;
            color: #1e293b;
            background: #f8fafc;
        }
        .srf-scroll-top {
            position: fixed;
            bottom: 28px;
            right: 24px;
            z-index: 88;
            width: 44px;
            height: 44px;
            border-radius: 999px;
            border: 1.5px solid #cbd5e1;
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(8px);
            color: #0f172a;
            box-shadow: 0 6px 20px rgba(15, 23, 42, 0.15);
            cursor: pointer;
            display: none;
            align-items: center;
            justify-content: center;
            transition: background 0.15s ease, border-color 0.15s ease, transform 0.15s ease;
        }
        .srf-scroll-top.visible {
            display: inline-flex;
        }
        .srf-scroll-top:hover {
            background: #f0fdfa;
            border-color: #0f766e;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(15, 118, 110, 0.2);
        }
        .srf-scroll-top svg {
            width: 20px;
            height: 20px;
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

        <header class="auth-login-topbar">
            <div class="auth-login-brand">
                <img src="{{ asset('images/dohlogo.svg') }}" alt="DOH Logo" class="auth-login-brand-logo">
                <div>
                    <h1 class="auth-login-brand-title">KMITS</h1>
                    <p class="auth-login-brand-subtitle">Knowledge Management and Information Technology Service</p>
                </div>
            </div>

            <div class="auth-login-top-actions"></div>
        </header>

        {{-- Main card --}}
        <section style="max-width: 1300px; margin: 1.5rem auto; padding: 0 1rem 2rem;">
            <div class="srf-card">

                {{-- Form header --}}
                <div class="srf-form-header">
                    <a href="{{ route('service-requests.track') }}" class="srf-header-back" aria-label="Back to tracking page">&larr; Back</a>
                    <span class="srf-form-header-text">Service Request Form</span>
                    <div class="srf-form-header-line"></div>
                </div>

                @if ($errors->has('form'))
                    <div class="mx-5 mt-4 rounded-md border border-amber-200 bg-amber-50 px-3 py-2 text-sm font-medium text-amber-800">
                        {{ $errors->first('form') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('service-requests.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="submission_token" value="{{ $submissionToken ?? '' }}">
                    <input id="request_date" name="request_date" type="hidden" value="{{ old('request_date', now()->toDateString()) }}">
                    <input name="time_received" type="hidden" value="{{ old('time_received', now()->format('H:i')) }}">

                    {{-- Section: Request Info --}}
                    <div class="srf-section" style="padding-bottom: 4px;">
                        <p class="srf-section-label">Request Information</p>

                        <div class="srf-field-grid srf-field-grid-2" style="margin-bottom: 12px;">
                            <div class="srf-field">
                                <label class="srf-label" for="department_code">
                                    <span class="srf-number-badge">1</span> Send to Department <span class="srf-required">*</span>
                                </label>
                                <select id="department_code" name="department_code" class="srf-select" required>
                                    <option value="">Select department</option>
                                    @foreach ($departmentOptions as $department)
                                        <option value="{{ $department }}" @selected((string) old('department_code') === (string) $department)>
                                            {{ $department }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('department_code')" class="mt-1" />
                            </div>
                        </div>

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
                                <label class="srf-label" for="request_category">
                                    <span class="srf-number-badge">2</span> Request Category <span class="srf-required">*</span>
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
                                <label class="srf-label" for="application_system_name">
                                    <span class="srf-number-badge">3</span> Application System Name <span class="srf-required">*</span>
                                </label>
                                @php
                                    $applicationSystemOptions = collect($applicationSystemOptions ?? []);
                                    $oldApplicationSystemName = (string) old('application_system_name', '');
                                @endphp
                                <div class="srf-system-picker" data-application-system-picker>
                                    <input
                                        id="application_system_name"
                                        type="hidden"
                                        name="application_system_name"
                                        value="{{ $oldApplicationSystemName }}"
                                    >
                                    <div class="srf-system-picker-box">
                                        <div id="application_system_name_chips" class="srf-system-picker-chips"></div>
                                        <input
                                            id="application_system_name_search"
                                            type="text"
                                            class="srf-system-picker-input"
                                            autocomplete="off"
                                            placeholder="Application / System Name"
                                        >
                                    </div>
                                    <div
                                        id="application_system_name_results"
                                        class="srf-system-picker-results hidden"
                                    ></div>
                                </div>
                            </div>
                            <div class="srf-field">
                                <label class="srf-label" for="expected_completion_date">
                                    <span class="srf-number-badge">4</span> Expected Date / Time of Completion <span class="srf-required">*</span>
                                </label>
                                <div style="display: flex; gap: 8px;">
                                    <input id="expected_completion_date" type="date" name="expected_completion_date" value="{{ old('expected_completion_date') }}" class="srf-input" style="width: 180px;" required>
                                    <input id="expected_completion_time" type="time" name="expected_completion_time" value="{{ old('expected_completion_time') }}" class="srf-input" style="width: 140px;" required>
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
                                <label class="srf-label" for="contact_last_name">Last Name <span class="srf-required">*</span></label>
                                <input id="contact_last_name" name="contact_last_name" value="{{ old('contact_last_name') }}" class="srf-input" autocomplete="family-name" required maxlength="100">
                            </div>
                            <div class="srf-field">
                                <label class="srf-label" for="contact_first_name">First Name <span class="srf-required">*</span></label>
                                <input id="contact_first_name" name="contact_first_name" value="{{ old('contact_first_name') }}" class="srf-input" autocomplete="given-name" required maxlength="100">
                            </div>
                            <div class="srf-field">
                                <label class="srf-label" for="contact_middle_name">Middle Name</label>
                                <input id="contact_middle_name" name="contact_middle_name" value="{{ old('contact_middle_name') }}" class="srf-input" autocomplete="additional-name" maxlength="100">
                            </div>
                            <div class="srf-field">
                                <label class="srf-label" for="contact_suffix_name">Suffix</label>
                                <select id="contact_suffix_name" name="contact_suffix_name" class="srf-input">
                                    <option value="">Select</option>
                                    <option value="II" {{ old('contact_suffix_name') === 'II' ? 'selected' : '' }}>II</option>
                                    <option value="III" {{ old('contact_suffix_name') === 'III' ? 'selected' : '' }}>III</option>
                                    <option value="IV" {{ old('contact_suffix_name') === 'IV' ? 'selected' : '' }}>IV</option>
                                    <option value="Jr." {{ old('contact_suffix_name') === 'Jr.' ? 'selected' : '' }}>Jr.</option>
                                    <option value="N/A" {{ old('contact_suffix_name') === 'N/A' ? 'selected' : '' }}>N/A</option>
                                    <option value="Sr." {{ old('contact_suffix_name') === 'Sr.' ? 'selected' : '' }}>Sr.</option>
                                    <option value="V" {{ old('contact_suffix_name') === 'V' ? 'selected' : '' }}>V</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="srf-divider"></div>

                    {{-- Section: Office & Contact --}}
                    <div class="srf-section" style="padding-bottom: 4px;">
                        <p class="srf-section-label">Office & Contact Details</p>

                        <div class="srf-field-grid srf-field-grid-2" style="margin-bottom: 12px;">
                            <div class="srf-field">
                                <div class="srf-office-label-row">
                                    <label class="srf-label" for="office">
                                        <span class="srf-number-badge">5</span> Office <span class="srf-required">*</span>
                                    </label>
                                    <select id="office_region_filter" name="office_region_filter" class="srf-input srf-office-region-filter">
                                        <option value="">All regions</option>
                                        @foreach (($parentOfficeOptions ?? []) as $parentOfficeOption)
                                            <option value="{{ $parentOfficeOption }}" {{ old('office_region_filter') === $parentOfficeOption ? 'selected' : '' }}>
                                                {{ $parentOfficeOption }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @php
                                    $regions = $regions ?? [];
                                    $hospitalsByRegion = $hospitalsByRegion ?? [];
                                    $parentOfficeOptions = $parentOfficeOptions ?? [];
                                    $officesByParent = $officesByParent ?? [];
                                    $officeParentMap = $officeParentMap ?? [];
                                    $oldOffice = (string) old('office', '');
                                @endphp
                                <div class="srf-system-picker" data-office-picker>
                                    <input type="hidden" id="office" name="office" value="{{ old('office', '') }}">
                                    <div class="srf-system-picker-box">
                                        <div id="office_chips" class="srf-system-picker-chips"></div>
                                        <input
                                            id="office_search"
                                            type="text"
                                            class="srf-system-picker-input"
                                            placeholder="Search and select an office..."
                                            autocomplete="off"
                                        >
                                    </div>
                                    <div id="office_results" class="srf-system-picker-results hidden"></div>
                                </div>
                                <p id="office-regcode-label" class="mt-1 text-[11px] text-slate-500"></p>
                            </div>
                            <div class="srf-field">
                                <label class="srf-label" for="address">
                                    <span class="srf-number-badge">6</span> Address <span class="srf-required">*</span>
                                </label>
                                <input id="address" name="address" value="{{ old('address') }}" class="srf-input" autocomplete="street-address" required>
                            </div>
                        </div>

                        <div class="srf-field-grid srf-field-grid-4" style="margin-bottom: 16px;">
                            <div class="srf-field">
                                <label class="srf-label" for="landline"><span class="srf-number-badge">7</span> Landline</label>
                                <input id="landline" name="landline" value="{{ old('landline') }}" inputmode="tel"
                                    oninput="this.value=this.value.replace(/[^0-9+() -]/g,'');" class="srf-input" autocomplete="tel" maxlength="20">
                            </div>
                            <div class="srf-field">
                                <label class="srf-label" for="fax_no"><span class="srf-number-badge">8</span> Fax No</label>
                                <input id="fax_no" name="fax_no" value="{{ old('fax_no') }}" inputmode="tel"
                                    oninput="this.value=this.value.replace(/[^0-9+() -]/g,'');" class="srf-input" maxlength="20">
                            </div>
                            <div class="srf-field">
                                <label class="srf-label" for="mobile_no"><span class="srf-number-badge">9</span> Mobile No</label>
                                <input id="mobile_no" name="mobile_no" value="{{ old('mobile_no') }}" inputmode="tel"
                                    oninput="this.value=this.value.replace(/[^0-9+() -]/g,'');" class="srf-input" autocomplete="tel-national" maxlength="20">
                            </div>
                            <div class="srf-field">
                                <label class="srf-label" for="email_address"><span class="srf-number-badge">10</span> Email Address <span class="srf-required">*</span></label>
                                <input id="email_address" type="email" name="email_address" value="{{ old('email_address') }}" class="srf-input" autocomplete="email" required>
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
                                <textarea name="description_request" class="srf-textarea" maxlength="5000" required>{{ old('description_request') }}</textarea>
                                <p class="mt-1 text-[11px] text-slate-500" data-description-char-count>0/5000 characters</p>
                                <x-input-error :messages="$errors->get('description_request')" class="mt-1" />

                                <div class="srf-upload-wrap">
                                    <span class="srf-upload-label">Attach Photos (1 to 3)</span>
                                    <input id="description_photos" name="description_photos[]" type="file"
                                        accept="image/*" multiple class="srf-file-input">
                                    <p style="font-size:11px; color:#94a3b8; margin: 4px 0 0;">Max 3 images, 5MB each. You can choose files multiple times.</p>
                                    <p id="description-photos-selected" style="font-size:11px; color:#0f766e; margin: 4px 0 0;"></p>
                                    <button type="button" id="description-photos-clear" class="srf-btn-clear" style="margin-top:6px;" hidden>Clear photos</button>
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
                                            </div>
                                            <div id="create-signature-draw-wrap">
                                                <canvas id="create-signature-canvas" class="srf-sig-canvas"
                                                    style="width:100%;"></canvas>
                                                <input type="hidden" name="approved_by_signature_drawn"
                                                    id="create-signature-drawn" value="{{ old('approved_by_signature_drawn') }}">
                                                <button type="button" id="create-signature-clear" class="srf-sig-clear">Clear</button>
                                            </div>
                                            <x-input-error :messages="$errors->get('approved_by_signature_drawn')" class="mt-1" />
                                        </div>

                                        <div class="srf-field-underline">
                                            <input name="approved_by_name" value="{{ old('approved_by_name') }}"
                                                class="srf-input-underline">
                                            <p class="srf-sublabel">Name &amp; Signature of Head of Office</p>
                                        </div>

                                        <div class="srf-field-underline">
                                            <input name="approved_by_position" value="{{ old('approved_by_position') }}"
                                                class="srf-input-underline">
                                            <p class="srf-sublabel">Position</p>
                                        </div>

                                        <div class="srf-field-underline">
                                            <input name="approved_date" type="date"
                                                value="{{ old('approved_date') }}"
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
        <button type="button" class="srf-scroll-top" id="srf-scroll-top" aria-label="Scroll to top">
            <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M10 15V5"></path><path d="M5 9l5-5 5 5"></path></svg>
        </button>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('input[type="file"]').forEach(function(input) {
                input.addEventListener('change', function() {
                    if (this.files && this.files[0] && this.files[0].size > 5 * 1024 * 1024) {
                        alert('File size must be 5MB or less.');
                        this.value = '';
                    }
                });
            });

            const createForm = document.querySelector('form[action="{{ route('service-requests.store') }}"]');
            const requestCategorySelect = document.getElementById('request_category');
            const requestCategoryOther = document.getElementById('request_category_other');
            const draftStorageKey = 'service-request-create-draft-v1';
            const applicationSystemOptions = @json($applicationSystemOptions->values());
            const officeOptions = [];
            const officeParentMap = {};
            const hospitalOfficeMap = {};
            const officeRegcodeMap = {};
            const officeSearchEndpoint = @json(route('offices.search'));

            const escapeHtml = function (value) {
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

                if (!hiddenInput || !searchInput || !chipsContainer || !results) return null;

                let selected = [];

                const normalize = function (value) {
                    return String(value || '').trim();
                };

                const selectedKey = function (value) {
                    return normalize(value).toLowerCase();
                };

                const optionValue = function (option) {
                    return typeof option === 'object' && option !== null
                        ? normalize(option.value || option.name || '')
                        : normalize(option);
                };

                const optionLabel = function (option) {
                    if (typeof option === 'object' && option !== null) {
                        return normalize(option.label || option.display_name || option.value || option.name || '');
                    }

                    return normalize(option);
                };

                const optionSearchText = function (option) {
                    if (typeof option === 'object' && option !== null) {
                        return [
                            option.label,
                            option.display_name,
                            option.value,
                            option.name,
                            option.facility_type,
                            option.classification,
                            option.code,
                            option.regcode,
                            option.address,
                            option.phone,
                        ].map(normalize).filter(Boolean).join(' ');
                    }

                    return normalize(option);
                };

                const selectedLabels = {};
                let renderedOptions = [];
                let remoteAbortController = null;
                let remoteRequestSequence = 0;
                let remoteSearchTimer = null;
                const remoteOptionsCache = {};

                const syncHiddenInput = function () {
                    hiddenInput.value = selected.join(', ');
                    hiddenInput.dispatchEvent(new Event('input', { bubbles: true }));
                    hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
                    searchInput.setCustomValidity('');
                };

                const renderChips = function () {
                    chipsContainer.innerHTML = selected.map(function (value, index) {
                        const label = selectedLabels[value] || value;
                        return '<span class="srf-system-picker-chip">' +
                            escapeHtml(label) +
                            '<button type="button" class="srf-system-picker-remove" data-chip-picker-remove="' + index + '" aria-label="Remove ' + escapeHtml(label) + '">x</button>' +
                            '</span>';
                    }).join('');

                    searchInput.placeholder = selected.length > 0 ? '' : config.placeholder;
                    searchInput.classList.toggle('hidden', maxSelections > 0 && selected.length >= maxSelections);
                };

                const addSelection = function (option) {
                    const normalized = optionValue(option);
                    if (normalized === '') return;

                    if (maxSelections > 0 && selected.length >= maxSelections) {
                        selected = [];
                    }

                    const exists = selected.some(function (item) {
                        return selectedKey(item) === selectedKey(normalized);
                    });

                    if (!exists) {
                        selected.push(normalized);
                    }

                    const label = optionLabel(option);
                    if (label !== '') {
                        selectedLabels[normalized] = label;
                    }

                    if (typeof config.onSelect === 'function') {
                        config.onSelect(option, normalized);
                    }

                    searchInput.value = '';
                    results.classList.add('hidden');
                    syncHiddenInput();
                    renderChips();
                    if (!(maxSelections > 0 && selected.length >= maxSelections)) {
                        searchInput.focus();
                    }
                };

                const removeSelection = function (index) {
                    delete selectedLabels[selected[index]];
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

                const optionMatches = function (option, query) {
                    return selectedKey(optionSearchText(option)).includes(selectedKey(query));
                };

                const remoteSearchUrl = function (query) {
                    const url = new URL(config.searchEndpoint, window.location.origin);
                    url.searchParams.set('q', query);

                    if (config.parentFilterId) {
                        const parentFilter = document.getElementById(config.parentFilterId);
                        const parentValue = parentFilter ? parentFilter.value.trim() : '';
                        if (parentValue !== '') {
                            url.searchParams.set('parent_name', parentValue);
                        }
                    }

                    return url.toString();
                };

                const loadRemoteOptions = async function (query, requestSequence) {
                    if (!config.searchEndpoint) {
                        return false;
                    }

                    const url = remoteSearchUrl(query);
                    if (remoteOptionsCache[url]) {
                        options = remoteOptionsCache[url];
                        return true;
                    }

                    if (remoteAbortController) {
                        remoteAbortController.abort();
                    }

                    remoteAbortController = typeof AbortController !== 'undefined'
                        ? new AbortController()
                        : null;

                    try {
                        const response = await fetch(url, {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                            signal: remoteAbortController ? remoteAbortController.signal : undefined,
                        });

                        if (!response.ok || requestSequence !== remoteRequestSequence) {
                            return false;
                        }

                        const payload = await response.json();
                        if (requestSequence !== remoteRequestSequence) {
                            return false;
                        }

                        const offices = Array.isArray(payload.offices) ? payload.offices : [];

                        offices.forEach(function (office) {
                            const name = normalize(office.name || '');
                            if (name === '') {
                                return;
                            }

                            hospitalOfficeMap[name] = String(office.address || '');
                            officeRegcodeMap[name] = String(office.regcode || '');
                            officeParentMap[name] = String(office.parent_name || '');
                        });

                        options = offices
                            .map(function (office) {
                                const name = normalize(office.name || '');
                                const facilityType = normalize(office.facility_type || '');
                                if (name === '') {
                                    return null;
                                }

                                return {
                                    id: office.id || '',
                                    value: name,
                                    label: normalize(office.display_name || (facilityType !== '' ? name + ' • ' + facilityType : name)),
                                    address: String(office.address || ''),
                                    regcode: String(office.regcode || office.code || ''),
                                    parent_name: String(office.parent_name || ''),
                                    facility_type: facilityType,
                                    classification: normalize(office.classification || ''),
                                    code: normalize(office.code || office.regcode || ''),
                                    phone: String(office.phone || ''),
                                };
                            })
                            .filter(function (office) {
                                return office !== null;
                            });
                        remoteOptionsCache[url] = options;
                        return true;
                    } catch (error) {
                        if (error && error.name === 'AbortError') {
                            return false;
                        }

                        // Keep the current option list when search fails.
                        return false;
                    }
                };

                const renderResults = function (message) {
                    const query = normalize(searchInput.value);
                    const isFocused = document.activeElement === searchInput;

                    if (!isFocused && query === '') {
                        results.classList.add('hidden');
                        return;
                    }

                    const selectedKeys = selected.map(selectedKey);
                    const matches = options
                        .filter(function (option) {
                            return selectedKeys.indexOf(selectedKey(optionValue(option))) === -1;
                        })
                        .filter(function (option) {
                            return query === '' || optionMatches(option, query);
                        })
                        .slice(0, 20);

                    renderedOptions = matches;

                    const rows = matches.map(function (option, index) {
                        return '<button type="button" class="srf-system-picker-option" data-chip-picker-index="' + index + '">' +
                            escapeHtml(optionLabel(option)) +
                            '</button>';
                    });

                    const exactMatch = options.some(function (option) {
                        return selectedKey(optionValue(option)) === selectedKey(query);
                    });
                    const alreadySelected = selectedKeys.indexOf(selectedKey(query)) !== -1;

                    if (query !== '' && matches.length === 0 && !exactMatch && !alreadySelected) {
                        rows.unshift('<button type="button" class="srf-system-picker-option" data-chip-picker-value="' + escapeHtml(query) + '">Add "' + escapeHtml(query) + '"</button>');
                    }

                    if (message) {
                        rows.unshift('<div class="srf-system-picker-empty">' + escapeHtml(message) + '</div>');
                    }

                    results.innerHTML = rows.length > 0
                        ? rows.join('')
                        : '<div class="srf-system-picker-empty">No matching records.</div>';
                    results.classList.remove('hidden');
                };

                const refreshResults = function (delay) {
                    if (!config.searchEndpoint) {
                        renderResults();
                        return;
                    }

                    clearTimeout(remoteSearchTimer);
                    remoteSearchTimer = setTimeout(async function () {
                        const query = normalize(searchInput.value);
                        const requestSequence = remoteRequestSequence + 1;
                        remoteRequestSequence = requestSequence;

                        renderResults(query !== '' ? 'Searching...' : '');
                        const loaded = await loadRemoteOptions(query, requestSequence);

                        if (loaded && requestSequence === remoteRequestSequence) {
                            renderResults();
                        }
                    }, delay);
                };

                searchInput.addEventListener('input', function () {
                    refreshResults(180);
                });
                searchInput.addEventListener('focus', function () {
                    refreshResults(0);
                });
                searchInput.addEventListener('keydown', function (event) {
                    if (event.key !== 'Enter') return;

                    event.preventDefault();
                    const firstOption = results.querySelector('[data-chip-picker-index], [data-chip-picker-value]');
                    if (firstOption && firstOption.hasAttribute('data-chip-picker-index')) {
                        addSelection(renderedOptions[Number(firstOption.getAttribute('data-chip-picker-index'))]);
                        return;
                    }

                    addSelection(firstOption ? firstOption.getAttribute('data-chip-picker-value') : searchInput.value);
                });

                results.addEventListener('mousedown', function (event) {
                    event.preventDefault();
                });

                results.addEventListener('click', function (event) {
                    const option = event.target.closest('[data-chip-picker-index], [data-chip-picker-value]');
                    if (!option) return;

                    if (option.hasAttribute('data-chip-picker-index')) {
                        addSelection(renderedOptions[Number(option.getAttribute('data-chip-picker-index'))]);
                        return;
                    }

                    addSelection(option.getAttribute('data-chip-picker-value'));
                });

                chipsContainer.addEventListener('click', function (event) {
                    const removeButton = event.target.closest('[data-chip-picker-remove]');
                    if (!removeButton) return;

                    removeSelection(Number(removeButton.getAttribute('data-chip-picker-remove')));
                });

                document.addEventListener('click', function (event) {
                    if (event.target.closest(config.rootSelector)) return;
                    results.classList.add('hidden');
                });

                if (createForm) {
                    createForm.addEventListener('submit', function (event) {
                        if (selected.length === 0 && normalize(searchInput.value) !== '') {
                            addSelection(searchInput.value);
                        }

                        if (selected.length > 0) return;

                        event.preventDefault();
                        searchInput.setCustomValidity(config.requiredMessage);
                        searchInput.reportValidity();
                    });
                }

                const setOptions = function (nextOptions) {
                    options = Array.isArray(nextOptions) ? nextOptions : [];
                    refreshResults(0);
                };

                setFromHiddenInput();

                return {
                    setFromHiddenInput: setFromHiddenInput,
                    setOptions: setOptions,
                };
            };

            const initLockedSearchInput = function (inputId, clearButtonId) {
                const input = document.getElementById(inputId);
                const clearButton = document.getElementById(clearButtonId);

                if (!input || !clearButton) return;

                const lockIfFilled = function () {
                    if (input.value.trim() === '') return;

                    input.readOnly = true;
                    input.removeAttribute('list');
                    clearButton.classList.remove('hidden');
                };

                const unlockAndClear = function () {
                    input.value = '';
                    input.readOnly = false;
                    input.setAttribute('list', input.dataset.optionsList || '');
                    clearButton.classList.add('hidden');
                    input.focus();
                    input.dispatchEvent(new Event('input', { bubbles: true }));
                };

                input.dataset.optionsList = input.getAttribute('list') || '';
                input.addEventListener('change', lockIfFilled);
                input.addEventListener('blur', lockIfFilled);
                clearButton.addEventListener('click', unlockAndClear);

                lockIfFilled();
                return lockIfFilled;
            };

            const applicationSystemPicker = initChipSearchPicker({
                hiddenId: 'application_system_name',
                searchId: 'application_system_name_search',
                chipsId: 'application_system_name_chips',
                resultsId: 'application_system_name_results',
                rootSelector: '[data-application-system-picker]',
                options: applicationSystemOptions,
                placeholder: 'Application / System Name',
                requiredMessage: 'Please select or type at least one application system.',
            });
            const officePicker = initChipSearchPicker({
                hiddenId: 'office',
                searchId: 'office_search',
                chipsId: 'office_chips',
                resultsId: 'office_results',
                rootSelector: '[data-office-picker]',
                options: officeOptions,
                searchEndpoint: officeSearchEndpoint,
                parentFilterId: 'office_region_filter',
                placeholder: 'Office',
                requiredMessage: 'Please select an office.',
                maxSelections: 1,
                onSelect: function (option, selectedOffice) {
                    if (typeof option !== 'object' || option === null) {
                        return;
                    }

                    hospitalOfficeMap[selectedOffice] = String(option.address || '');
                    officeRegcodeMap[selectedOffice] = String(option.regcode || option.code || '');
                    officeParentMap[selectedOffice] = String(option.parent_name || '');

                    const landlineInput = document.getElementById('landline');
                    const mobileInput = document.getElementById('mobile_no');
                    const phoneValue = String(option.phone || '').trim();

                    if (!landlineInput || !mobileInput) {
                        return;
                    }

                    const parts = phoneValue
                        .split(/\s*(?:,|;|\/|\bor\b|\band\b)\s*/i)
                        .map(function (value) {
                            return value.replace(/[^0-9+() -]/g, '').trim();
                        })
                        .filter(Boolean);

                    let landline = '';
                    let mobile = '';

                    parts.forEach(function (part) {
                        const digits = part.replace(/\D/g, '');
                        const isMobile = /^09\d{9}$/.test(digits)
                            || /^639\d{9}$/.test(digits)
                            || /^0639\d{9}$/.test(digits);

                        if (isMobile && mobile === '') {
                            mobile = part;
                            return;
                        }

                        if (!isMobile && landline === '') {
                            landline = part;
                        }
                    });

                    if (landline === '' && mobile === '' && parts.length > 0) {
                        landline = parts[0];
                    }

                    landlineInput.value = landline;
                    mobileInput.value = mobile;
                    landlineInput.dispatchEvent(new Event('input', { bubbles: true }));
                    mobileInput.dispatchEvent(new Event('input', { bubbles: true }));
                },
            });

            const officeRegionFilter = document.getElementById('office_region_filter');
            const officeHiddenInput = document.getElementById('office');
            const applyOfficeRegionFilter = function () {
                if (!officeRegionFilter || !officePicker) return;

                const selectedRegion = officeRegionFilter.value.trim();
                officePicker.setOptions([]);

                if (!officeHiddenInput) return;
                const currentOffice = officeHiddenInput.value.trim();
                const currentOfficeRegion = String(officeParentMap[currentOffice] || '').trim();
                if (currentOffice !== '' && currentOfficeRegion !== '' && selectedRegion !== '' && currentOfficeRegion !== selectedRegion) {
                    officeHiddenInput.value = '';
                    officePicker.setFromHiddenInput();
                }
            };

            if (officeRegionFilter && officeHiddenInput && officeHiddenInput.value.trim() !== '' && officeParentMap[officeHiddenInput.value.trim()]) {
                officeRegionFilter.value = officeParentMap[officeHiddenInput.value.trim()];
            }

            if (officeRegionFilter) {
                officeRegionFilter.addEventListener('change', applyOfficeRegionFilter);
                applyOfficeRegionFilter();
            }

            const saveDraftToStorage = function () {
                if (!createForm) return;

                const draft = {};
                const fields = createForm.querySelectorAll('input[name], select[name], textarea[name]');

                fields.forEach(function (field) {
                    const name = field.name;
                    if (!name || name === '_token' || name === '_method') return;
                    if (field.type === 'file') return;

                    if (field.type === 'radio') {
                        if (field.checked) {
                            draft[name] = field.value;
                        }
                        return;
                    }

                    if (field.type === 'checkbox') {
                        if (!Array.isArray(draft[name])) {
                            draft[name] = [];
                        }
                        if (field.checked) {
                            draft[name].push(field.value || '1');
                        }
                        return;
                    }

                    draft[name] = field.value;
                });

                try {
                    localStorage.setItem(draftStorageKey, JSON.stringify(draft));
                } catch (error) {
                    // Ignore storage failures (private mode/quota).
                }
            };

            const clearDraftStorage = function () {
                try {
                    localStorage.removeItem(draftStorageKey);
                } catch (error) {
                    // Ignore storage failures (private mode/quota).
                }
            };

            const restoreDraftFromStorage = function () {
                if (!createForm) return;

                let rawDraft = null;
                try {
                    rawDraft = localStorage.getItem(draftStorageKey);
                } catch (error) {
                    return;
                }

                if (!rawDraft) return;

                let draft = null;
                try {
                    draft = JSON.parse(rawDraft);
                } catch (error) {
                    return;
                }

                if (!draft || typeof draft !== 'object') return;

                Object.keys(draft).forEach(function (name) {
                    const selector = '[name="' + name.replace(/"/g, '\\"') + '"]';
                    const fields = createForm.querySelectorAll(selector);
                    if (!fields.length) return;

                    const firstField = fields[0];
                    const storedValue = draft[name];

                    if (firstField.type === 'radio') {
                        fields.forEach(function (field) {
                            field.checked = String(field.value) === String(storedValue);
                        });
                        return;
                    }

                    if (firstField.type === 'checkbox') {
                        const values = Array.isArray(storedValue) ? storedValue.map(String) : [String(storedValue)];
                        fields.forEach(function (field) {
                            field.checked = values.includes(String(field.value || '1'));
                        });
                        return;
                    }

                    if (firstField.type === 'file') return;

                    firstField.value = storedValue == null ? '' : String(storedValue);
                });
            };

            if (createForm) {
                let formSubmitting = false;
                const submitButton = createForm.querySelector('button[type="submit"]');

                const navigationEntry = (window.performance && window.performance.getEntriesByType)
                    ? window.performance.getEntriesByType('navigation')[0]
                    : null;
                const navigationType = navigationEntry && navigationEntry.type
                    ? navigationEntry.type
                    : (window.performance && window.performance.navigation && window.performance.navigation.type === 1 ? 'reload' : 'navigate');

                if (navigationType === 'reload') {
                    restoreDraftFromStorage();
                    if (applicationSystemPicker) applicationSystemPicker.setFromHiddenInput();
                    if (officePicker) officePicker.setFromHiddenInput();
                } else {
                    clearDraftStorage();
                }

                let draftSaveTimer = null;
                const queueDraftSave = function () {
                    if (draftSaveTimer) {
                        window.clearTimeout(draftSaveTimer);
                    }
                    draftSaveTimer = window.setTimeout(saveDraftToStorage, 120);
                };

                createForm.addEventListener('input', queueDraftSave);
                createForm.addEventListener('change', queueDraftSave);
                createForm.addEventListener('submit', clearDraftStorage);
                createForm.addEventListener('submit', function (event) {
                    if (event.defaultPrevented) {
                        return;
                    }

                    if (formSubmitting) {
                        event.preventDefault();
                        return;
                    }

                    formSubmitting = true;

                    if (submitButton) {
                        submitButton.disabled = true;
                        submitButton.classList.add('opacity-60', 'cursor-not-allowed');
                        submitButton.textContent = 'Submitting...';
                    }
                });
            }

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
            const officeRegcodeLabel = document.getElementById('office-regcode-label');

            const syncAddress = function () {
                if (!addressInput) return;
                const selectedOffice = officeInput.value.trim();
                const mapped = hospitalOfficeMap[selectedOffice] || '';
                if (mapped) addressInput.value = mapped;

                if (officeRegcodeLabel) {
                    const regcode = officeRegcodeMap[selectedOffice] || '';
                    officeRegcodeLabel.textContent = regcode !== ''
                        ? 'Regional Hospital / Health Facility Code (for reference): ' + regcode
                        : '';
                }
            };

            if (officeInput) {
                officeInput.addEventListener('change', syncAddress);
                officeInput.addEventListener('blur', syncAddress);
            }

            const initAdaptiveDescriptionFont = function () {
                const descriptionTextarea = document.querySelector('textarea[name="description_request"]');
                const counter = document.querySelector('[data-description-char-count]');
                const maxChars = 5000;
                if (!descriptionTextarea) return;

                descriptionTextarea.setAttribute('maxlength', String(maxChars));

                const getFontSize = function (valueLength) {
                    if (valueLength <= 280) return 20;
                    if (valueLength <= 900) return 16;
                    if (valueLength <= 1500) return 15;
                    return 14;
                };

                const applyAdaptiveSize = function () {
                    const length = descriptionTextarea.value.length;
                    const size = getFontSize(length);
                    descriptionTextarea.style.fontSize = size + 'px';
                    descriptionTextarea.style.lineHeight = size >= 14 ? '1.45' : '1.35';
                    if (counter) {
                        counter.textContent = length + '/' + maxChars + ' characters';
                    }
                };

                descriptionTextarea.addEventListener('input', applyAdaptiveSize);
                applyAdaptiveSize();
            };

            initAdaptiveDescriptionFont();

            const initDescriptionPhotoSelection = function () {
                const photoInput = document.getElementById('description_photos');
                const selectedLabel = document.getElementById('description-photos-selected');
                const clearButton = document.getElementById('description-photos-clear');

                if (!photoInput || typeof DataTransfer === 'undefined') {
                    return;
                }

                const maxFiles = 3;
                let selectedFiles = [];
                const hasIndexedDb = typeof indexedDB !== 'undefined';
                const photoDbName = 'service-request-create';
                const photoStoreName = 'description_photos';

                const fileKey = function (file) {
                    return [file.name, file.size, file.lastModified, file.type].join('::');
                };

                const openPhotoDb = function () {
                    return new Promise(function (resolve, reject) {
                        const request = indexedDB.open(photoDbName, 1);

                        request.onupgradeneeded = function () {
                            const db = request.result;
                            if (!db.objectStoreNames.contains(photoStoreName)) {
                                db.createObjectStore(photoStoreName, { keyPath: 'key' });
                            }
                        };

                        request.onsuccess = function () {
                            resolve(request.result);
                        };

                        request.onerror = function () {
                            reject(request.error);
                        };
                    });
                };

                const writeStoredPhotos = function (files) {
                    if (!hasIndexedDb) return Promise.resolve();

                    return openPhotoDb().then(function (db) {
                        return new Promise(function (resolve, reject) {
                            const tx = db.transaction(photoStoreName, 'readwrite');
                            const store = tx.objectStore(photoStoreName);
                            store.clear();

                            files.forEach(function (file) {
                                store.put({
                                    key: fileKey(file),
                                    name: file.name,
                                    type: file.type,
                                    lastModified: file.lastModified,
                                    size: file.size,
                                    blob: file,
                                });
                            });

                            tx.oncomplete = function () {
                                resolve();
                            };
                            tx.onerror = function () {
                                reject(tx.error);
                            };
                        });
                    });
                };

                const readStoredPhotos = function () {
                    if (!hasIndexedDb) return Promise.resolve([]);

                    return openPhotoDb().then(function (db) {
                        return new Promise(function (resolve, reject) {
                            const tx = db.transaction(photoStoreName, 'readonly');
                            const store = tx.objectStore(photoStoreName);
                            const request = store.getAll();

                            request.onsuccess = function () {
                                resolve(request.result || []);
                            };
                            request.onerror = function () {
                                reject(request.error);
                            };
                        });
                    });
                };

                const clearStoredPhotos = function () {
                    if (!hasIndexedDb) return;

                    openPhotoDb()
                        .then(function (db) {
                            const tx = db.transaction(photoStoreName, 'readwrite');
                            tx.objectStore(photoStoreName).clear();
                        })
                        .catch(function () {
                            // Ignore storage failures.
                        });
                };

                const syncInputFiles = function () {
                    const transfer = new DataTransfer();
                    selectedFiles.forEach(function (file) {
                        transfer.items.add(file);
                    });
                    photoInput.files = transfer.files;
                };

                const syncSelectedLabel = function () {
                    if (!selectedLabel) {
                        return;
                    }

                    if (selectedFiles.length === 0) {
                        selectedLabel.textContent = '';
                        if (clearButton) {
                            clearButton.hidden = true;
                        }
                        return;
                    }

                    selectedLabel.textContent = selectedFiles.length + ' file(s) selected: ' + selectedFiles.map(function (file) {
                        return file.name;
                    }).join(', ');
                    if (clearButton) {
                        clearButton.hidden = false;
                    }
                };

                const persistSelectedFiles = function () {
                    writeStoredPhotos(selectedFiles).catch(function () {
                        // Ignore storage failures.
                    });
                };

                const restoreStoredFiles = function () {
                    readStoredPhotos()
                        .then(function (entries) {
                            if (!Array.isArray(entries) || entries.length === 0) {
                                return;
                            }

                            selectedFiles = entries
                                .map(function (entry) {
                                    const blob = entry.blob || entry;
                                    if (blob instanceof File) {
                                        return blob;
                                    }

                                    return new File([blob], entry.name || 'photo', {
                                        type: entry.type || (blob && blob.type) || 'image/png',
                                        lastModified: entry.lastModified || Date.now(),
                                    });
                                })
                                .slice(0, maxFiles);

                            syncInputFiles();
                            syncSelectedLabel();
                        })
                        .catch(function () {
                            // Ignore storage failures.
                        });
                };

                if (clearButton) {
                    clearButton.addEventListener('click', function () {
                        selectedFiles = [];
                        syncInputFiles();
                        syncSelectedLabel();
                        persistSelectedFiles();
                        saveDraftToStorage();
                    });
                }

                photoInput.addEventListener('change', function () {
                    const incoming = Array.from(photoInput.files || []);
                    if (incoming.length === 0) {
                        return;
                    }

                    const known = new Set(selectedFiles.map(fileKey));

                    incoming.forEach(function (file) {
                        const key = fileKey(file);
                        if (known.has(key) || selectedFiles.length >= maxFiles) {
                            return;
                        }

                        known.add(key);
                        selectedFiles.push(file);
                    });

                    syncInputFiles();
                    syncSelectedLabel();
                    persistSelectedFiles();
                    saveDraftToStorage();
                });

                selectedFiles = Array.from(photoInput.files || []).slice(0, maxFiles);
                syncInputFiles();
                syncSelectedLabel();
                restoreStoredFiles();

                if (createForm) {
                    createForm.addEventListener('submit', function () {
                        clearStoredPhotos();
                    });
                }
            };

            initDescriptionPhotoSelection();

            const initSignatureInput = function () {
                const drawWrap = document.getElementById('create-signature-draw-wrap');
                const canvas = document.getElementById('create-signature-canvas');
                const hiddenDrawn = document.getElementById('create-signature-drawn');
                const clearBtn = document.getElementById('create-signature-clear');
                const approvedDateInput = document.getElementById('create-approved-date');

                if (!drawWrap || !canvas || !hiddenDrawn) return;

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

                    saveDraftToStorage();

                    if (centeredSignature !== '') {
                        fillSignedDateIfEmpty();
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
                    e.preventDefault();
                });

                const endDrawing = function () {
                    if (drawing) {
                        syncHiddenSignature();
                    }
                    drawing = false;
                };

                window.addEventListener('mouseup', endDrawing);
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
                    e.preventDefault();
                }, { passive: false });
                canvas.addEventListener('touchend', endDrawing);

                if (clearBtn) {
                    clearBtn.addEventListener('click', function () {
                        ctx.clearRect(0, 0, canvas.width, canvas.height);
                        hiddenDrawn.value = '';
                        saveDraftToStorage();
                    });
                }

                const form = canvas.closest('form');
                if (form) {
                    form.addEventListener('submit', function () {
                        syncHiddenSignature();
                    });
                }
            };

            const initScrollTopButton = function () {
                const scrollTopBtn = document.getElementById('srf-scroll-top');
                if (!scrollTopBtn) return;

                scrollTopBtn.addEventListener('click', function () {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });

                let ticking = false;
                const handleScroll = function () {
                    if (window.scrollY > 200) {
                        scrollTopBtn.classList.add('visible');
                    } else {
                        scrollTopBtn.classList.remove('visible');
                    }
                    ticking = false;
                };

                window.addEventListener('scroll', function () {
                    if (!ticking) {
                        window.requestAnimationFrame(handleScroll);
                        ticking = true;
                    }
                }, { passive: true });

                handleScroll();
            };

            initSignatureInput();
            initScrollTopButton();
        });
    </script>
</x-guest-layout>
