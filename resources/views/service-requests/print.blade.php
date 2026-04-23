<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Request {{ $serviceRequest->reference_code }}</title>
    <style>
        @page {
            size: A4;
            margin: 9mm;
        }

        * {
            box-sizing: border-box;
        }

        .hidden {
            display: none !important;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            font-size: 13px;
            color: #111827;
            background:
                radial-gradient(circle at 15% 24%, rgba(182, 245, 196, 0.78), transparent 33%),
                radial-gradient(circle at 83% 19%, rgba(82, 195, 188, 0.82), transparent 30%),
                linear-gradient(120deg, #d9f3dd 0%, #6fd3b7 44%, #3bbec8 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }

        .screen-shell {
            position: relative;
            min-height: 100vh;
            padding: 24px 12px 32px;
            z-index: 1;
        }

        .screen-aurora,
        .screen-wave {
            position: fixed;
            pointer-events: none;
            z-index: 0;
        }

        .screen-aurora {
            width: 520px;
            height: 520px;
            top: -160px;
            right: -120px;
            border-radius: 999px;
            background: radial-gradient(circle at center, rgba(255, 255, 255, 0.46), rgba(255, 255, 255, 0) 70%);
        }

        .screen-wave {
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.56);
        }

        .screen-wave-one {
            width: 560px;
            height: 560px;
            left: -220px;
            bottom: -260px;
        }

        .screen-wave-two {
            width: 360px;
            height: 360px;
            left: -120px;
            top: 220px;
            background: rgba(255, 255, 255, 0.72);
        }

        .sheet {
            width: 100%;
            max-width: 200mm;
            margin: 0 auto 12px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 18px 44px rgba(7, 32, 66, 0.34);
            padding: 10px;
            position: relative;
        }

        .controls {
            text-align: right;
            margin-bottom: 4px;
        }

        .btn {
            border: 1px solid #111827;
            background: #111827;
            color: #fff;
            padding: 7px 16px;
            border-radius: 5px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
        }

        .btn.secondary {
            background: #fff;
            color: #111827;
            margin-right: 8px;
        }

        .floating-signature-layer {
            position: absolute;
            inset: 10px;
            z-index: 45;
            pointer-events: none;
        }

        .floating-signature {
            position: absolute;
            top: 0;
            left: 0;
            width: 220px;
            max-height: 90px;
            object-fit: contain;
            cursor: grab;
            touch-action: none;
            user-select: none;
            pointer-events: auto;
            border: 2px solid transparent;
            border-radius: 3px;
            transition: border-color 0.15s;
        }

        .floating-signature.active-sig {
            border-color: #0ea5e9;
        }

        .floating-signature.dragging {
            cursor: grabbing;
        }

        /* ── Inline resize toolbar ── */
        .sig-toolbar {
            position: absolute;
            display: none;
            align-items: center;
            gap: 2px;
            background: #0f172a;
            border-radius: 6px;
            padding: 3px 5px;
            pointer-events: auto;
            z-index: 60;
            white-space: nowrap;
            box-shadow: 0 4px 12px rgba(0,0,0,0.35);
        }

        .sig-toolbar.visible {
            display: flex;
        }

        .sig-toolbar-btn {
            width: 26px;
            height: 26px;
            border: none;
            border-radius: 4px;
            background: transparent;
            color: #f1f5f9;
            font-size: 16px;
            font-weight: 700;
            line-height: 1;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.12s;
        }

        .sig-toolbar-btn:hover {
            background: rgba(255,255,255,0.15);
        }

        .sig-toolbar-btn.delete-btn {
            color: #fca5a5;
            padding: 0 10px;
            width: auto;
        }

        .sig-toolbar-btn.delete-btn:hover {
            background: rgba(239, 68, 68, 0.45);
            color: #fff;
        }

        .sig-toolbar-divider {
            width: 1px;
            height: 18px;
            background: rgba(255,255,255,0.2);
            margin: 0 2px;
        }

        .sig-toolbar-label {
            font-size: 11px;
            color: #cbd5e1;
            padding: 0 2px;
            font-family: Arial, sans-serif;
            font-weight: 600;
            letter-spacing: 0.03em;
        }

        /* ── Signature pad modal ── */
        .signature-pad-modal {
            position: fixed;
            inset: 0;
            z-index: 120;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 12px;
            background: rgba(2, 6, 23, 0.7);
            backdrop-filter: blur(2px);
        }

        .signature-pad-modal.open {
            display: flex;
        }

        .signature-pad-card {
            width: min(900px, 100%);
            height: min(74vh, 620px);
            background: #fff;
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            box-shadow: 0 24px 52px rgba(15, 23, 42, 0.4);
            display: flex;
            flex-direction: column;
            gap: 10px;
            padding: 12px;
        }

        .signature-pad-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 8px;
        }

        .signature-pad-title {
            margin: 0;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: #0f172a;
        }

        .signature-pad-close {
            width: 32px;
            height: 32px;
            border-radius: 999px;
            border: 1px solid #cbd5e1;
            background: #fff;
            color: #0f172a;
            font-size: 18px;
            line-height: 1;
            cursor: pointer;
        }

        .signature-pad-close:hover {
            background: #f8fafc;
        }

        .signature-pad-canvas-wrap {
            flex: 1;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            overflow: hidden;
            background: #f8fafc;
        }

        .signature-pad-canvas {
            width: 100%;
            height: 100%;
            display: block;
            background: #fff;
            cursor: crosshair;
            touch-action: none;
        }

        .signature-pad-actions {
            display: flex;
            justify-content: flex-end;
            gap: 8px;
            flex-wrap: wrap;
        }

        .signature-pad-btn {
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            background: #fff;
            color: #0f172a;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            padding: 7px 10px;
            cursor: pointer;
        }

        .signature-pad-btn:hover {
            background: #f8fafc;
            border-color: #94a3b8;
        }

        .signature-pad-btn.primary {
            background: #0f766e;
            border-color: #0f766e;
            color: #fff;
        }

        .signature-pad-btn.primary:hover {
            background: #115e59;
            border-color: #115e59;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td, th {
            border: 1px solid #111827;
            padding: 5px 7px;
            vertical-align: top;
            font-size: 13px;
        }

        .noborder { border: 0 !important; }
        .center { text-align: center !important; }
        .bold { font-weight: 700; }
        .small { font-size: 12px; }
        .tiny { font-size: 11px; }

        .logo-cell { width: 100px; }
        .logo { margin-top: 4px; width: 50px; height: 50px; }

        .header-main {
            padding-top: 6px;
            font-size: 10px;
            font-weight: 1000;
            text-align: center;
        }

        .header-sub {
            font-size: 12px;
            font-weight: 700;
            text-align: center;
            border-top: 1px solid #111827;
            margin-top: 15px;
            padding-top: 4px;
            margin-left: -7px;
            margin-right: -7px;
        }

        .print-header td { vertical-align: middle; }

        .header-logo-cell {
            width: 84px;
            text-align: center;
            padding: 6px;
        }

        .header-logo {
            width: 54px;
            height: 54px;
            object-fit: contain;
        }

        .header-main-cell {
            font-size: 12px;
            font-weight: 700;
            text-align: left;
            line-height: 1.2;
            padding: 8px 10px;
        }

        .header-sub-cell {
            font-size: 14px;
            font-weight: 700;
            text-align: center;
            padding: 7px 10px;
        }

        .header-meta-label,
        .header-meta-value {
            width: 120px;
            font-size: 12px;
            font-weight: 700;
            text-align: center;
            white-space: nowrap;
        }

        .reference-wrap {
            width: 100%;
            margin-left: auto;
            margin-top: 7px;
            display: flex;
            align-items: center;
            gap: 5px;
            flex-wrap: nowrap;
        }

        .reference-label {
            font-size: 15px;
            font-weight: 700;
            margin-bottom: 1px;
        }

        .reference-code {
            display: inline-block;
            min-width: auto;
            border-bottom: 1px solid #111827;
            text-align: center;
            font-size: 13px;
            font-weight: 700;
            white-space: nowrap;
        }

        .status-label { margin-left: auto; font-size: 13px; font-weight: 700; }

        .status-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 999px;
            border: 1px solid #0f172a;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.04em;
        }

        .status-badge.status-approved  { background: #dcfce7; color: #166534; border-color: #16a34a; }
        .status-badge.status-checking  { background: #e0f2fe; color: #075985; border-color: #38bdf8; }
        .status-badge.status-pending   { background: #fef9c3; color: #854d0e; border-color: #eab308; }
        .status-badge.status-rejected  { background: #fee2e2; color: #991b1b; border-color: #ef4444; }

        .datetime-wrap {
            width: 100%;
            margin-left: auto;
            margin-top: 7px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .datetime-line { margin-top: 0; font-size: 14px; margin-bottom: 1px; }

        .datetime-value {
            width: auto;
            display: block;
            border-bottom: 1px solid #111827;
            text-align: center;
            font-weight: 700;
            font-size: 14px;
        }

        .desc-title {
            border-top: 1px solid #111827;
            border-right: 1px solid #111827;
            border-bottom: 4px solid #111827;
            border-left: 1px solid #111827;
            padding: 6px 7px;
            font-size: 13px;
        }

        .desc-body {
            min-height: 130px;
            max-height: 200px;
            border-right: 1px solid #111827;
            border-bottom: 4px solid #111827;
            border-left: 1px solid #111827;
            padding: 0 8px 10px;
            font-size: 13px;
            white-space: pre-line;
            word-break: break-word;
            overflow-wrap: anywhere;
            overflow: hidden;
            line-height: 1.35;
        }

        .line-value2 {
            min-height: 18px;
            border-bottom: 1px solid #111827;
            padding: 0 3px;
            margin-left: -6px;
            margin-right: -6px;
        }

        .line-value {
            min-height: 18px;
            border-bottom: 1px solid #111827;
            padding: 0 3px;
        }

        .line-caption { margin-top: 2px; text-align: center; font-size: 13px; }

        .action-row { }
        .action-row td { word-wrap: break-word; overflow-wrap: break-word; word-break: break-word; }

        .version { margin-top: 2px; text-align: right; font-size: 12px; font-weight: 700; }

        @media print {
            body { background: #fff; min-height: auto; overflow: visible; }
            .screen-shell { min-height: auto; padding: 0; }
            .screen-aurora, .screen-wave { display: none; }
            .controls { display: none; }
            .signature-placeholder, .signature-pad-modal { display: none !important; }
            .sig-toolbar { display: none !important; }
            .floating-signature { cursor: default; border-color: transparent !important; }
            .sheet {
                max-width: auto;
                width: 70%;
                transform: scale(1.3);
                margin-top: 200px;
                background: #fff;
                border-radius: 0;
                box-shadow: none;
                padding: 0;
            }
            td, th { padding: 7px 9px; font-size: 13px; }
            .small { font-size: 12px; }
            .tiny { font-size: 11px; }
            .header-main { font-size: 18px; }
            .logo { margin-top: 4px; width: 70px; height: 70px; }
            .header-sub { font-size: 15px; margin-top: 10px; margin-left: -7px; margin-right: -7px; padding-top: 10px; }
            .header-main-cell { font-size: 14px; }
            .header-logo { width: 62px; height: 62px; }
            .header-sub-cell { font-size: 16px; }
            .header-meta-label, .header-meta-value { font-size: 13px; }
            .reference-label { font-size: 20px; font-weight: 700; margin-bottom: 1px; }
            .reference-code { display: inline-block; min-width: auto; border-bottom: 1px solid #111827; text-align: center; font-size: 20px; font-weight: 700; }
            .reference-wrap { width: 100%; margin-left: 0; margin-top: 7px; display: flex; align-items: center; gap: 5px; justify-content: flex-end; flex-wrap: nowrap; }
            .datetime-line, .datetime-value { font-size: 15px; }
            .datetime-wrap { width: 100%; margin-left: 330px; margin-top: 7px; display: flex; align-items: center; gap: 5px; }
            .datetime-line { margin-top: 0; font-size: 12px; margin-bottom: 1px; }
            .datetime-value { width: auto; display: block; border-bottom: 1px solid #111827; text-align: center; font-weight: 700; }
            .desc-title { font-size: 13px; }
            .desc-body { min-height: 180px; max-height: 200px; font-size: 13px; line-height: 1.4; padding-top: 0; overflow: hidden; }
            .line-value2 { min-height: 22px; margin-left: -6px; margin-right: -6px; font-size: 13px; }
            .line-value { min-height: 22px; font-size: 13px; }
            .line-caption { font-size: 12px; }
            .action-row { height: 34px; }
            .version { font-size: 14px; }
        }

        @if (request()->boolean('embedded'))
            body { background: #fff; min-height: auto; overflow: visible; }
            .screen-shell { min-height: auto; padding: 0; }
            .screen-aurora, .screen-wave, .controls { display: none; }
            .sheet { max-width: auto; width: 70%; transform: scale(1.3); margin-top: 200px; background: #fff; border-radius: 0; box-shadow: none; padding: 0; }
            td, th { padding: 7px 9px; font-size: 13px; }
            .small { font-size: 12px; }
            .tiny { font-size: 11px; }
            .header-main { font-size: 18px; }
            .logo { margin-top: 4px; width: 70px; height: 70px; }
            .header-sub { font-size: 15px; margin-top: 10px; margin-left: -7px; margin-right: -7px; padding-top: 10px; }
            .header-main-cell { font-size: 14px; }
            .header-logo { width: 62px; height: 62px; }
            .header-sub-cell { font-size: 16px; }
            .header-meta-label, .header-meta-value { font-size: 13px; }
            .reference-label { font-size: 20px; font-weight: 700; margin-bottom: 1px; }
            .reference-code { display: inline-block; min-width: auto; border-bottom: 1px solid #111827; text-align: center; font-size: 20px; font-weight: 700; }
            .reference-wrap { width: 100%; margin-left: 0; margin-top: 7px; display: flex; align-items: center; gap: 5px; justify-content: flex-end; flex-wrap: nowrap; }
            .datetime-line, .datetime-value { font-size: 15px; }
            .datetime-wrap { width: 100%; margin-left: 330px; margin-top: 7px; display: flex; align-items: center; gap: 5px; }
            .datetime-line { margin-top: 0; font-size: 12px; margin-bottom: 1px; }
            .datetime-value { width: auto; display: block; border-bottom: 1px solid #111827; text-align: center; font-weight: 700; }
            .desc-title { font-size: 13px; }
            .desc-body { min-height: 180px; max-height: 200px; font-size: 13px; line-height: 1.4; padding-top: 0; overflow: hidden; }
            .line-value2 { min-height: 22px; margin-left: -6px; margin-right: -6px; font-size: 13px; }
            .line-value { min-height: 22px; font-size: 13px; }
            .line-caption { font-size: 12px; }
            .action-row { height: 34px; }
            .version { font-size: 14px; }
        @endif
    </style>
</head>
<body data-print-reference="{{ $serviceRequest->reference_code }}">
    <div class="screen-aurora"></div>
    <div class="screen-wave screen-wave-one"></div>
    <div class="screen-wave screen-wave-two"></div>

    <div class="screen-shell">
    <div class="sheet">
        @if (! request()->boolean('embedded'))
            <div class="controls">
                <button class="btn secondary" type="button" id="reset-signature-positions">Reset Signatures</button>
                <button class="btn secondary" type="button" id="open-floating-signature">Add Signature</button>
                <button class="btn secondary" type="button" id="ctrl-shrink">Signature −</button>
                <button class="btn secondary" type="button" id="ctrl-grow">Signature +</button>
                <button class="btn secondary" type="button" id="ctrl-delete" style="border-color:#dc2626; color:#b91c1c;">Delete Signature</button>
                <button class="btn" onclick="window.print()">Print</button>
            </div>
        @endif

        <div class="floating-signature-layer" id="floating-signature-layer" aria-hidden="true">
            <img src="" alt="Floating Signature" id="floating-signature-image" class="floating-signature hidden" draggable="false">
            <!-- Inline resize toolbar (single instance, repositioned per active sig) -->
            <div class="sig-toolbar" id="sig-toolbar">
                <button type="button" class="sig-toolbar-btn" id="sig-shrink" title="Shrink (make smaller)">−</button>
                <span class="sig-toolbar-label">Signature</span>
                <button type="button" class="sig-toolbar-btn" id="sig-grow" title="Grow (make bigger)">+</button>
                <div class="sig-toolbar-divider"></div>
                <button type="button" class="sig-toolbar-btn delete-btn" id="sig-delete" title="Delete signature">Delete</button>
            </div>
        </div>

        <table class="print-header">
            <tr>
                <td class="header-logo-cell" rowspan="3"><img src="{{ asset('images/dohlogo.svg') }}" alt="DOH" class="header-logo"></td>
                <td class="header-main-cell" rowspan="2">Knowledge Management and Information Technology Service</td>
                <td class="header-meta-label">Page No :</td>
                <td class="header-meta-value">1 of 1</td>
            </tr>
            <tr>
                <td class="header-meta-label">Revision No :</td>
                <td class="header-meta-value">1</td>
            </tr>
            <tr>
                <td class="header-sub-cell">Service Request Form</td>
                <td class="header-meta-label">Effectivity :</td>
                <td class="header-meta-value">May 02, 2014</td>
            </tr>
        </table>

        <div class="reference-wrap">
            <div class="reference-label">Reference Code :</div>
            <div class="reference-code">{{ $serviceRequest->reference_code }}</div>
        </div>

        @php
            $formatClock = function ($value): string {
                $value = trim((string) $value);
                if ($value === '') return '';
                foreach (['H:i:s', 'H:i', 'g:i A', 'g:i a'] as $format) {
                    try { return \Carbon\Carbon::createFromFormat($format, $value)->format('g:i:s A'); } catch (\Throwable $e) {}
                }
                return $value;
            };
        @endphp

        <div class="datetime-wrap">
            <div class="datetime-line">1) Date/Time of Request (mm/dd/yyyy h:m:s) :</div>
            <div class="datetime-value">{{ $serviceRequest->request_date->format('m/d/Y') }}{{ $serviceRequest->time_received ? ' - ' : '' }}{{ $formatClock($serviceRequest->time_received) }}</div>
        </div>

        <table style="margin-top:8px;">
            <tr><td>2) Request Category : {{ data_get($serviceRequest, 'request_category', '') }}</td></tr>
            <tr><td>3) Application System Name : {{ data_get($serviceRequest, 'application_system_name', '') }}</td></tr>
            <tr><td>4) Expected Date / Time of Completion : {{ optional($serviceRequest->expected_completion_date)->format('m/d/Y') }}{{ $serviceRequest->expected_completion_date && $serviceRequest->expected_completion_time ? ' - ' : '' }}{{ $formatClock($serviceRequest->expected_completion_time) }}</td></tr>
            <tr>
                <td style="padding:0;">
                    <table style="width:95%; border-collapse:collapse; table-layout:fixed; border:0;">
                        <tr>
                            <td class="noborder" style="width:31%; padding:4px 6px; vertical-align:top;">5) Name of Contact Person :</td>
                            <td class="noborder center" style="width:17%; border-bottom:1px solid #111827 !important;">{{ $serviceRequest->contact_last_name }}</td>
                            <td class="noborder center" style="width:17%; border-bottom:1px solid #111827 !important;">{{ $serviceRequest->contact_first_name }}</td>
                            <td class="noborder center" style="width:17%; border-bottom:1px solid #111827 !important;">{{ $serviceRequest->contact_middle_name ?: '' }}</td>
                            <td class="noborder center" style="width:18%; border-bottom:1px solid #111827 !important;">{{ data_get($serviceRequest, 'contact_suffix_name', '') }}</td>
                        </tr>
                        <tr>
                            <td class="noborder"></td>
                            <td class="noborder center">Last Name</td>
                            <td class="noborder center">First Name</td>
                            <td class="noborder center">Middle Name</td>
                            <td class="noborder center">Suffix Name</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr><td>6) Office : {{ $serviceRequest->office }}</td></tr>
            <tr><td>7) Address : {{ $serviceRequest->address }}</td></tr>
            <tr>
                <td style="padding:0;">
                    <table style="width:100%; border-collapse:collapse; table-layout:fixed; border:0;">
                        <tr>
                            <td class="noborder" style="width:25%; border-right:1px solid #111827 !important; padding:6px;">8) Landline : {{ $serviceRequest->landline ?: '' }}</td>
                            <td class="noborder" style="width:23%; border-right:1px solid #111827 !important; padding:8px;">9) Fax No : {{ $serviceRequest->fax_no ?: '' }}</td>
                            <td class="noborder" style="width:23%; border-right:1px solid #111827 !important; padding:8px;">10) Mobile No : {{ $serviceRequest->mobile_no ?: '' }}</td>
                            <td class="noborder" style="width:29%; padding:8px;">11) Email Address : {{ data_get($serviceRequest, 'email_address', '') }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <div class="desc-title">
            12) <span class="bold">DESCRIPTION OF REQUEST</span> : <span style="font-style:italic;">(Please clearly write down the details of the request.)</span>
        </div>
        @php
            $descriptionPreview = \Illuminate\Support\Str::limit((string) $serviceRequest->description_request, 1800, '...');
            $descriptionLength = \Illuminate\Support\Str::length($descriptionPreview);
            if ($descriptionLength <= 280)       { $descriptionFontSize = '16px'; $descriptionLineHeight = '1.45'; }
            elseif ($descriptionLength <= 900)   { $descriptionFontSize = '14px'; $descriptionLineHeight = '1.38'; }
            elseif ($descriptionLength <= 1500)  { $descriptionFontSize = '12px'; $descriptionLineHeight = '1.3'; }
            else                                  { $descriptionFontSize = '10px'; $descriptionLineHeight = '1.2'; }
        @endphp
        <div class="desc-body" style="font-size: {{ $descriptionFontSize }}; line-height: {{ $descriptionLineHeight }};">{{ $descriptionPreview }}</div>

        <table style="margin-top:0;">
            <tr>
                <td style="width:180px; border-right:0; font-size: 16px;" class="bold">13) APPROVED BY :</td>
                <td style="padding:0 10px 6px; border-left:0;">
                    <div style="display:flex; gap:16px; align-items:flex-end;">
                        <div style="flex:1;">
                            <div style="min-height:56px; margin-top:4px;">
                                @php
                                    $approvedSignatureUrl = trim((string) ($serviceRequest->approved_by_signature ?? '')) !== ''
                                        ? route('service-requests.signature.approved', [
                                            'serviceRequest' => $serviceRequest,
                                            'reference_code' => $serviceRequest->reference_code,
                                            'token' => (string) ($signatureViewToken ?? ''),
                                        ])
                                        : '';
                                @endphp
                                @if ($approvedSignatureUrl !== '')
                                    <img src="{{ $approvedSignatureUrl }}" alt="Signature" style="max-height:60px; max-width:220px; object-fit:contain; display:block; margin:0 auto; margin-bottom:-10px; user-select:none; -webkit-user-drag:none;">
                                @endif
                                <div class="line-value center" style="margin-top:0; min-height:16px; padding-left:0; padding-right:0;">{{ \Illuminate\Support\Str::limit((string) $serviceRequest->approved_by_name, 90, '...') }}</div>
                            </div>
                            <div class="line-caption">Name &amp; Signature of Head of Office</div>
                            <div style="margin-top:8px;" class="line-value center">{{ \Illuminate\Support\Str::limit((string) $serviceRequest->approved_by_position, 80, '...') }}</div>
                            <div class="line-caption">Position</div>
                        </div>
                        <div style="width:38%;">
                            <div class="line-value center" style="margin-top:36px;">{{ optional($serviceRequest->approved_date)->format('m/d/Y') }}</div>
                            <div class="line-caption">Date Signed</div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr><td colspan="2" class="center bold" style="padding:2px 6px;">(For Knowledge Management and Information Technology Service only)</td></tr>
            <tr><td colspan="2" class="bold">14) ACTION TAKEN <span style="font-style:italic; font-weight:400;">(Use separate sheet if necessary)</span></td></tr>
        </table>

        <table style="table-layout: fixed;">
            <tr class="center">
                <td colspan="2" style="width:190px;">Received</td>
                <td colspan="4">Action</td>
                <td rowspan="2" style="width:90px; vertical-align:middle;">Signature<br><span class="tiny">(g)</span></td>
            </tr>
            <tr class="center">
                <td style="width:95px;">Date<br><span class="tiny">(a)</span></td>
                <td style="width:95px;">Time<br><span class="tiny">(b)</span></td>
                <td style="width:95px;">Date<br><span class="tiny">(c)</span></td>
                <td style="width:95px;">Time<br><span class="tiny">(d)</span></td>
                <td style="width:120px;">Taken<br><span class="tiny">(e)</span></td>
                <td style="width:120px;">Officer<br><span class="tiny">(f)</span></td>
            </tr>
            @php
                $logs = $serviceRequest->action_logs ?? [];
                $formatLogTime = function ($value): string {
                    $value = trim((string) $value);
                    if ($value === '') return '';
                    foreach (['H:i:s', 'H:i', 'g:i A', 'g:i a'] as $format) {
                        try { return \Carbon\Carbon::createFromFormat($format, $value)->format('g:i A'); } catch (\Throwable $e) {}
                    }
                    return $value;
                };
            @endphp
            @for ($i = 0; $i < 5; $i++)
                <tr class="action-row">
                    <td>{{ data_get($logs, $i . '.date', '') }}</td>
                    <td>{{ $formatLogTime(data_get($logs, $i . '.time', '')) }}</td>
                    <td>{{ data_get($logs, $i . '.action_date', '') }}</td>
                    <td>{{ $formatLogTime(data_get($logs, $i . '.action_time', '')) }}</td>
                    <td>{{ (string) data_get($logs, $i . '.action_taken', '') }}</td>
                    <td>{{ \Illuminate\Support\Str::limit((string) data_get($logs, $i . '.action_officer', ''), 28, '...') }}</td>
                    <td class="center">&nbsp;</td>
                </tr>
            @endfor
        </table>

        <table>
            <tr>
                <td style="width:34%; border-bottom:0 !important;">15) NOTED BY :</td>
                <td style="width:33%; border-bottom:0 !important;">16)</td>
                <td style="width:33%; border-bottom:0 !important;">17)</td>
            </tr>
            <tr>
                <td style="padding:1px 6px; border-top:0 !important;">
                    <div class="line-value2 center">{{ \Illuminate\Support\Str::limit((string) ($serviceRequest->noted_by_name ?: ''), 70, '...') }}</div>
                    <div class="center" style="padding-top:1px;">Name of Supervisor</div>
                </td>
                <td style="padding:1px 6px; border-top:0 !important;">
                    <div class="line-value2 center">{{ \Illuminate\Support\Str::limit((string) ($serviceRequest->noted_by_position ?: ''), 60, '...') }}</div>
                    <div class="center" style="padding-top:1px;">Position</div>
                </td>
                <td style="padding:1px 6px; border-top:0 !important;">
                    <div class="line-value2 center">{{ optional($serviceRequest->noted_by_date_signed)->format('m/d/Y') ?: '' }}</div>
                    <div class="center" style="padding-top:1px;">Date Signed</div>
                </td>
            </tr>
        </table>

        <div class="version" style="margin-top: 20px;">DOH-KMITS-SRF Ver. 1</div>
    </div>
    </div>

    <!-- Signature pad modal -->
    <div class="signature-pad-modal" id="signature-pad-modal" aria-hidden="true">
        <div class="signature-pad-card" role="dialog" aria-modal="true" aria-labelledby="signature-pad-title">
            <div class="signature-pad-head">
                <h2 class="signature-pad-title" id="signature-pad-title">Draw Signature</h2>
                <button type="button" class="signature-pad-close" id="signature-pad-close" aria-label="Close signature modal">×</button>
            </div>
            <div class="signature-pad-canvas-wrap">
                <canvas id="signature-pad-canvas" class="signature-pad-canvas"></canvas>
            </div>
            <div class="signature-pad-actions">
                <div style="display:flex; align-items:center; gap:6px; margin-right:auto;">
                    <label for="stroke-thickness" style="font-size:11px; font-weight:700; color:#475569; white-space:nowrap;">Kapal:</label>
                    <button type="button" class="signature-pad-btn" id="stroke-thinner" title="Nipisan">−</button>
                    <input type="number" id="stroke-thickness-label" min="0.5" max="12" step="0.5" value="2.4"
                        style="width:52px; text-align:center; font-size:12px; font-weight:700; color:#0f172a;
                               border:1px solid #cbd5e1; border-radius:5px; padding:4px 4px;
                               outline:none; -moz-appearance:textfield;"
                        title="I-type ang kapal (0.5 - 12)">
                    <button type="button" class="signature-pad-btn" id="stroke-thicker" title="Kapalan">+</button>
                </div>
                <button type="button" class="signature-pad-btn" id="signature-pad-clear">Clear</button>
                <button type="button" class="signature-pad-btn" id="signature-pad-remove">Remove Signature</button>
                <button type="button" class="signature-pad-btn" id="signature-pad-cancel">Cancel</button>
                <button type="button" class="signature-pad-btn primary" id="signature-pad-apply">Use Signature</button>
            </div>
        </div>
    </div>

    @if (request()->boolean('autoprint'))
        <script>
            window.addEventListener('load', function () { setTimeout(function () { window.print(); }, 150); });
        </script>
    @endif

    <script>
        (function () {
            const referenceCode = document.body.getAttribute('data-print-reference') || 'default';
            const signaturesStorageKey = 'print-floating-signatures:' + referenceCode;
            const legacyDataStorageKey     = 'print-floating-signature-data:'     + referenceCode;
            const legacyPositionStorageKey = 'print-floating-signature-position:' + referenceCode;
            const legacySizeStorageKey     = 'print-floating-signature-size:'     + referenceCode;

            const resetButton      = document.getElementById('reset-signature-positions');
            const openButton       = document.getElementById('open-floating-signature');
            const floatingLayer    = document.getElementById('floating-signature-layer');
            const floatingTemplate = document.getElementById('floating-signature-image');
            const modal            = document.getElementById('signature-pad-modal');
            const canvas           = document.getElementById('signature-pad-canvas');
            const modalCloseButton  = document.getElementById('signature-pad-close');
            const modalClearButton  = document.getElementById('signature-pad-clear');
            const modalRemoveButton = document.getElementById('signature-pad-remove');
            const modalCancelButton = document.getElementById('signature-pad-cancel');
            const modalApplyButton  = document.getElementById('signature-pad-apply');

            // Inline toolbar elements
            const toolbar     = document.getElementById('sig-toolbar');
            const btnShrink   = document.getElementById('sig-shrink');
            const btnGrow     = document.getElementById('sig-grow');
            const btnDelete   = document.getElementById('sig-delete');

            if (!floatingLayer || !floatingTemplate || !modal || !canvas || !toolbar) return;

            const sanitizeValue = v => String(v || '').trim();
            const clamp = (v, mn, mx) => Math.min(mx, Math.max(mn, v));
            const getLayerBounds = () => floatingLayer.getBoundingClientRect();

            const baseSignatureWidth  = 220;
            const baseSignatureHeight = 90;
            const minSignatureScale   = 0.5;
            const maxSignatureScale   = 2.2;
            const scaleStep           = 0.12;

            const signatureNodes = new Map();
            let signatures       = [];
            let activeSignatureId = null;
            let padMode          = 'add';
            let padTargetId      = null;

            let activeNode       = null;
            let activePointerId  = null;
            let dragOrigin       = { x: 0, y: 0 };
            let dragStart        = { x: 0, y: 0 };
            let pointerMoved     = false;

            const ctx = canvas.getContext('2d');
            if (!ctx) return;

            let drawingOnPad = false;

            floatingTemplate.classList.add('hidden');

            // ── Toolbar helpers ─────────────────────────────────────────────

            const hideToolbar = () => {
                toolbar.classList.remove('visible');
            };

            const showToolbarForNode = (node, signature) => {
                // Make toolbar visible first so offsetHeight is correct
                toolbar.style.visibility = 'hidden';
                toolbar.classList.add('visible');

                const layerRect = getLayerBounds();
                const nodeRect  = node.getBoundingClientRect();
                const toolbarH  = toolbar.offsetHeight || 30;

                // Sit toolbar flush just above the signature (2px gap)
                const leftPx = nodeRect.left - layerRect.left;
                const topPx  = (nodeRect.top  - layerRect.top) - toolbarH - 2;

                toolbar.style.left       = leftPx + 'px';
                toolbar.style.top        = topPx  + 'px';
                toolbar.style.transform  = 'none';
                toolbar.style.visibility = '';
            };

            // ── Utility ─────────────────────────────────────────────────────

            const syncBodyOverflow = () => {
                document.body.style.overflow = modal.classList.contains('open') ? 'hidden' : '';
            };

            const generateSignatureId = () =>
                'sig-' + Date.now() + '-' + Math.floor(Math.random() * 100000);

            const normalizeSignature = (raw, fallbackIndex) => {
                const src = sanitizeValue(raw && raw.src);
                if (src === '') return null;
                const xRatio = Number(raw && raw.xRatio);
                const yRatio = Number(raw && raw.yRatio);
                const scale  = Number(raw && raw.scale);
                const offset = Math.min((fallbackIndex || 0) * 0.02, 0.2);
                return {
                    id:     sanitizeValue(raw && raw.id) || generateSignatureId(),
                    src,
                    xRatio: Number.isFinite(xRatio) ? clamp(xRatio, 0, 1) : clamp(0.24 + offset, 0, 1),
                    yRatio: Number.isFinite(yRatio) ? clamp(yRatio, 0, 1) : clamp(0.55 + offset, 0, 1),
                    scale:  Number.isFinite(scale)  ? clamp(scale, minSignatureScale, maxSignatureScale) : 1,
                };
            };

            const findSignatureById = id =>
                signatures.find(s => s.id === id) || null;

            const persistSignatures = () => {
                try { localStorage.setItem(signaturesStorageKey, JSON.stringify(signatures)); } catch (e) {}
            };

            const removeAllNodes = () => {
                signatureNodes.forEach(n => n.remove());
                signatureNodes.clear();
            };

            const setActiveSignature = id => {
                activeSignatureId = id;
                signatureNodes.forEach((node, nodeId) => {
                    node.classList.toggle('active-sig', nodeId === activeSignatureId);
                });
                if (!id) hideToolbar();
            };

            const applyNodeLayout = (signature, node) => {
                const bounds = getLayerBounds();
                node.style.left     = (signature.xRatio * bounds.width) + 'px';
                node.style.top      = (signature.yRatio * bounds.height) + 'px';
                node.style.width    = Math.round(baseSignatureWidth  * signature.scale) + 'px';
                node.style.maxHeight = Math.round(baseSignatureHeight * signature.scale) + 'px';
            };

            // ── Signature pad ────────────────────────────────────────────────

            let strokeWidth = 2.4;
            const minStroke = 0.5;
            const maxStroke = 12;
            const strokeStep = 0.5;

            const updateStrokeLabel = () => {
                const input = document.getElementById('stroke-thickness-label');
                if (input) input.value = strokeWidth.toFixed(1);
            };

            const configurePadCanvas = () => {
                const ratio = window.devicePixelRatio || 1;
                const rect  = canvas.getBoundingClientRect();
                canvas.width  = Math.max(1, Math.floor(rect.width  * ratio));
                canvas.height = Math.max(1, Math.floor(rect.height * ratio));
                ctx.setTransform(ratio, 0, 0, ratio, 0, 0);
                ctx.lineWidth   = strokeWidth;
                ctx.lineCap     = 'round';
                ctx.strokeStyle = '#0f172a';
            };

            const clearPadCanvas = () => ctx.clearRect(0, 0, canvas.width, canvas.height);

            const drawDataUrlOnPad = dataUrl => {
                clearPadCanvas();
                const src = sanitizeValue(dataUrl);
                if (src === '') return;
                const image  = new Image();
                image.onload = () => { clearPadCanvas(); ctx.drawImage(image, 0, 0, canvas.clientWidth, canvas.clientHeight); };
                image.src    = src;
            };

            const getCenteredPadSignature = () => {
                const width = canvas.width, height = canvas.height;
                let imageData;
                try { imageData = ctx.getImageData(0, 0, width, height); } catch (e) { return canvas.toDataURL('image/png'); }
                const data = imageData.data;
                let minX = width, minY = height, maxX = -1, maxY = -1;
                for (let y = 0; y < height; y++) {
                    for (let x = 0; x < width; x++) {
                        if (data[(y * width + x) * 4 + 3] > 0) {
                            if (x < minX) minX = x; if (y < minY) minY = y;
                            if (x > maxX) maxX = x; if (y > maxY) maxY = y;
                        }
                    }
                }
                if (maxX < minX || maxY < minY) return '';
                const cropW = maxX - minX + 1, cropH = maxY - minY + 1;
                const tc = document.createElement('canvas');
                tc.width = width; tc.height = height;
                const tCtx = tc.getContext('2d');
                if (!tCtx) return canvas.toDataURL('image/png');
                const scale  = Math.min((width * 0.9) / cropW, (height * 0.8) / cropH, 1);
                const drawW  = cropW * scale, drawH = cropH * scale;
                const drawX  = (width - drawW) / 2, drawY = (height - drawH) / 2;
                tCtx.clearRect(0, 0, width, height);
                tCtx.drawImage(canvas, minX, minY, cropW, cropH, drawX, drawY, drawW, drawH);
                return tc.toDataURL('image/png');
            };

            const getPadPoint = e => {
                const rect   = canvas.getBoundingClientRect();
                const source = e.touches ? e.touches[0] : e;
                return { x: source.clientX - rect.left, y: source.clientY - rect.top };
            };

            const startPadDraw = e => {
                if (!modal.classList.contains('open')) return;
                drawingOnPad = true;
                const p = getPadPoint(e);
                ctx.beginPath(); ctx.moveTo(p.x, p.y);
                e.preventDefault();
            };

            const movePadDraw = e => {
                if (!drawingOnPad) return;
                const p = getPadPoint(e);
                ctx.lineTo(p.x, p.y); ctx.stroke();
                e.preventDefault();
            };

            const endPadDraw = () => { drawingOnPad = false; };

            const closePadModal = () => {
                modal.classList.remove('open');
                modal.setAttribute('aria-hidden', 'true');
                drawingOnPad = false;
                padTargetId  = null;
                padMode      = 'add';
                syncBodyOverflow();
            };

            const openPadModal = (mode, signatureId) => {
                padMode    = mode || 'add';
                padTargetId = sanitizeValue(signatureId);
                modal.classList.add('open');
                modal.setAttribute('aria-hidden', 'false');
                syncBodyOverflow();
                window.requestAnimationFrame(() => {
                    configurePadCanvas();
                    if (padMode === 'edit' && padTargetId !== '') {
                        const target = findSignatureById(padTargetId);
                        drawDataUrlOnPad(target ? target.src : '');
                    } else {
                        clearPadCanvas();
                    }
                });
            };

            // ── Node events ──────────────────────────────────────────────────

            const getPixelPosition = signature => {
                const bounds = getLayerBounds();
                return { x: signature.xRatio * bounds.width, y: signature.yRatio * bounds.height };
            };

            const attachNodeEvents = node => {
                // Double-click → redraw
                node.addEventListener('dblclick', () => {
                    const id = sanitizeValue(node.getAttribute('data-signature-id'));
                    if (!id) return;
                    hideToolbar();
                    setActiveSignature(id);
                    openPadModal('edit', id);
                });

                node.addEventListener('pointerdown', e => {
                    if (e.button !== 0) return;
                    const id = sanitizeValue(node.getAttribute('data-signature-id'));
                    const sig = findSignatureById(id);
                    if (!sig) return;
                    setActiveSignature(id);
                    hideToolbar();
                    activeNode      = node;
                    activePointerId = e.pointerId;
                    dragOrigin      = getPixelPosition(sig);
                    dragStart       = { x: e.clientX, y: e.clientY };
                    pointerMoved    = false;
                    node.classList.add('dragging');
                    node.setPointerCapture(e.pointerId);
                    e.preventDefault();
                });

                node.addEventListener('pointermove', e => {
                    if (!activeNode || activePointerId !== e.pointerId) return;
                    const id  = sanitizeValue(activeNode.getAttribute('data-signature-id'));
                    const sig = findSignatureById(id);
                    if (!sig) return;
                    const next = {
                        x: dragOrigin.x + (e.clientX - dragStart.x),
                        y: dragOrigin.y + (e.clientY - dragStart.y),
                    };
                    if (Math.abs(e.clientX - dragStart.x) > 3 || Math.abs(e.clientY - dragStart.y) > 3)
                        pointerMoved = true;
                    const bounds = getLayerBounds();
                    sig.xRatio = bounds.width  > 0 ? clamp(next.x, 0, Math.max(0, bounds.width  - 20)) / bounds.width  : 0;
                    sig.yRatio = bounds.height > 0 ? clamp(next.y, 0, Math.max(0, bounds.height - 20)) / bounds.height : 0;
                    applyNodeLayout(sig, activeNode);
                    e.preventDefault();
                });

                const finishDrag = e => {
                    if (!activeNode || activePointerId !== e.pointerId) return;
                    const id = sanitizeValue(activeNode.getAttribute('data-signature-id'));
                    activeNode.classList.remove('dragging');
                    activeNode.releasePointerCapture(e.pointerId);
                    activeNode      = null;
                    activePointerId = null;
                    persistSignatures();

                    // Show inline toolbar only on a tap (no drag)
                    if (!pointerMoved) {
                        const sig = findSignatureById(id);
                        const nd  = signatureNodes.get(id);
                        if (sig && nd) showToolbarForNode(nd, sig);
                    }
                    pointerMoved = false;
                };

                node.addEventListener('pointerup',     finishDrag);
                node.addEventListener('pointercancel', finishDrag);
            };

            // ── Build / destroy nodes ────────────────────────────────────────

            const rebuildSignatureNodes = () => {
                removeAllNodes();
                signatures.forEach(sig => {
                    const node = document.createElement('img');
                    node.src         = sig.src;
                    node.alt         = 'Floating Signature';
                    node.draggable   = false;
                    node.className   = 'floating-signature';
                    node.setAttribute('data-signature-id', sig.id);
                    node.title       = 'Click to resize · Drag to move · Double-click to redraw';
                    applyNodeLayout(sig, node);
                    attachNodeEvents(node);
                    floatingLayer.appendChild(node);
                    signatureNodes.set(sig.id, node);
                });
                if (!findSignatureById(activeSignatureId) && signatures.length > 0)
                    activeSignatureId = signatures[signatures.length - 1].id;
                if (signatures.length === 0) activeSignatureId = null;
                setActiveSignature(activeSignatureId);

                // Re-append toolbar so it's always on top
                floatingLayer.appendChild(toolbar);
            };

            const removeSignatureById = id => {
                signatures = signatures.filter(s => s.id !== id);
                if (activeSignatureId === id)
                    activeSignatureId = signatures.length > 0 ? signatures[signatures.length - 1].id : null;
                hideToolbar();
                rebuildSignatureNodes();
                persistSignatures();
            };

            // ── Toolbar button actions ───────────────────────────────────────

            const resizeActive = delta => {
                const sig = findSignatureById(activeSignatureId);
                if (!sig) return;
                sig.scale = clamp(sig.scale + delta, minSignatureScale, maxSignatureScale);
                const node = signatureNodes.get(sig.id);
                if (node) { applyNodeLayout(sig, node); showToolbarForNode(node, sig); }
                persistSignatures();
            };

            btnShrink.addEventListener('click', e => { e.stopPropagation(); resizeActive(-scaleStep); });
            btnGrow.addEventListener('click',   e => { e.stopPropagation(); resizeActive(+scaleStep); });
            btnDelete.addEventListener('click', e => {
                e.stopPropagation();
                if (activeSignatureId) removeSignatureById(activeSignatureId);
            });

            // ── Control bar buttons (shrink / grow / delete) ─────────────────
            const ctrlShrink = document.getElementById('ctrl-shrink');
            const ctrlGrow   = document.getElementById('ctrl-grow');
            const ctrlDelete = document.getElementById('ctrl-delete');
            if (ctrlShrink) ctrlShrink.addEventListener('click', () => resizeActive(-scaleStep));
            if (ctrlGrow)   ctrlGrow.addEventListener('click',   () => resizeActive(+scaleStep));
            if (ctrlDelete) ctrlDelete.addEventListener('click', () => {
                if (activeSignatureId) removeSignatureById(activeSignatureId);
            });

            // ── Storage load ─────────────────────────────────────────────────

            const loadSignaturesFromStorage = () => {
                let loaded = [];
                try {
                    const parsed = JSON.parse(localStorage.getItem(signaturesStorageKey) || '[]');
                    if (Array.isArray(parsed)) loaded = parsed;
                } catch (e) { loaded = []; }

                const normalized = loaded.map((e, i) => normalizeSignature(e, i)).filter(Boolean);
                if (normalized.length > 0) return normalized;

                // Legacy single-sig migration
                let legacySrc = '', legacyPos = { xRatio: 0.24, yRatio: 0.55 }, legacyScale = 1;
                try { legacySrc = sanitizeValue(localStorage.getItem(legacyDataStorageKey) || ''); } catch (e) {}
                try {
                    const pp = JSON.parse(localStorage.getItem(legacyPositionStorageKey) || '{}');
                    if (pp && typeof pp === 'object') {
                        const xR = Number(pp.xRatio), yR = Number(pp.yRatio);
                        if (Number.isFinite(xR) && Number.isFinite(yR))
                            legacyPos = { xRatio: clamp(xR, 0, 1), yRatio: clamp(yR, 0, 1) };
                    }
                } catch (e) {}
                try {
                    const ps = Number(localStorage.getItem(legacySizeStorageKey) || '1');
                    if (Number.isFinite(ps)) legacyScale = clamp(ps, minSignatureScale, maxSignatureScale);
                } catch (e) {}

                if (legacySrc === '') return [];
                return [{ id: generateSignatureId(), src: legacySrc, ...legacyPos, scale: legacyScale }];
            };

            signatures = loadSignaturesFromStorage();
            if (signatures.length > 0) activeSignatureId = signatures[signatures.length - 1].id;
            rebuildSignatureNodes();

            // ── Pad canvas events ────────────────────────────────────────────

            canvas.addEventListener('mousedown', startPadDraw);
            canvas.addEventListener('mousemove', movePadDraw);
            window.addEventListener('mouseup',   endPadDraw);
            canvas.addEventListener('touchstart', startPadDraw, { passive: false });
            canvas.addEventListener('touchmove',  movePadDraw,  { passive: false });
            canvas.addEventListener('touchend',   endPadDraw);

            // ── Toolbar + pad wiring ─────────────────────────────────────────

            if (openButton) openButton.addEventListener('click', () => openPadModal('add', ''));

            modalClearButton.addEventListener('click', clearPadCanvas);

            document.getElementById('stroke-thinner').addEventListener('click', () => {
                strokeWidth = Math.max(minStroke, parseFloat((strokeWidth - strokeStep).toFixed(1)));
                ctx.lineWidth = strokeWidth;
                updateStrokeLabel();
            });

            document.getElementById('stroke-thicker').addEventListener('click', () => {
                strokeWidth = Math.min(maxStroke, parseFloat((strokeWidth + strokeStep).toFixed(1)));
                ctx.lineWidth = strokeWidth;
                updateStrokeLabel();
            });

            document.getElementById('stroke-thickness-label').addEventListener('input', (e) => {
                const val = parseFloat(e.target.value);
                if (!Number.isFinite(val)) return;
                strokeWidth = Math.min(maxStroke, Math.max(minStroke, val));
                ctx.lineWidth = strokeWidth;
            });

            document.getElementById('stroke-thickness-label').addEventListener('change', (e) => {
                const val = parseFloat(e.target.value);
                if (!Number.isFinite(val)) { updateStrokeLabel(); return; }
                strokeWidth = Math.min(maxStroke, Math.max(minStroke, parseFloat(val.toFixed(1))));
                ctx.lineWidth = strokeWidth;
                updateStrokeLabel();
            });
            modalApplyButton.addEventListener('click', () => {
                const sigData = getCenteredPadSignature();
                if (sigData === '') { closePadModal(); return; }
                if (padMode === 'edit' && padTargetId !== '') {
                    const t = findSignatureById(padTargetId);
                    if (t) { t.src = sigData; setActiveSignature(t.id); }
                } else {
                    const offset  = Math.min(signatures.length * 0.02, 0.2);
                    const created = { id: generateSignatureId(), src: sigData, xRatio: clamp(0.24 + offset, 0, 1), yRatio: clamp(0.55 + offset, 0, 1), scale: 1 };
                    signatures.push(created);
                    setActiveSignature(created.id);
                }
                rebuildSignatureNodes();
                persistSignatures();
                closePadModal();
            });
            modalRemoveButton.addEventListener('click', () => {
                if (padMode === 'edit' && padTargetId !== '') removeSignatureById(padTargetId);
                closePadModal();
            });
            modalCloseButton.addEventListener('click',  closePadModal);
            modalCancelButton.addEventListener('click', closePadModal);
            modal.addEventListener('click', e => { if (e.target === modal) closePadModal(); });

            // Click outside signatures & toolbar → hide toolbar
            document.addEventListener('pointerdown', e => {
                if (toolbar.contains(e.target)) return;
                const clickedSig = e.target.closest && e.target.closest('[data-signature-id]');
                if (!clickedSig) hideToolbar();
            }, true);

            document.addEventListener('keydown', e => {
                if (e.key !== 'Escape') return;
                if (modal.classList.contains('open')) closePadModal();
                hideToolbar();
            });

            // ── postMessage API (kept for external resize messages) ──────────

            window.addEventListener('message', e => {
                const payload = e && e.data;
                if (!payload || typeof payload !== 'object') return;
                if (payload.type === 'open-print-signature-pad') { openPadModal('add', ''); return; }
                if (payload.type === 'resize-print-signature') {
                    const delta = Number(payload.delta);
                    if (!Number.isFinite(delta) || delta === 0) return;
                    if (!activeSignatureId && signatures.length > 0)
                        setActiveSignature(signatures[signatures.length - 1].id);
                    resizeActive(delta);
                }
            });

            // ── Misc ─────────────────────────────────────────────────────────

            window.addEventListener('resize', () => {
                rebuildSignatureNodes();
                hideToolbar();
                if (!modal.classList.contains('open')) return;
                configurePadCanvas();
                if (padMode === 'edit' && padTargetId !== '') {
                    const t = findSignatureById(padTargetId);
                    drawDataUrlOnPad(t ? t.src : '');
                }
            });

            if (resetButton) {
                resetButton.addEventListener('click', () => {
                    signatures = []; activeSignatureId = null;
                    hideToolbar();
                    rebuildSignatureNodes();
                    try {
                        localStorage.removeItem(signaturesStorageKey);
                        localStorage.removeItem(legacyDataStorageKey);
                        localStorage.removeItem(legacyPositionStorageKey);
                        localStorage.removeItem(legacySizeStorageKey);
                    } catch (e) {}
                    closePadModal();
                });
            }

            window.addEventListener('beforeprint', () => { hideToolbar(); rebuildSignatureNodes(); });
            window.addEventListener('afterprint',  rebuildSignatureNodes);
        })();
    </script>
</body>
</html>