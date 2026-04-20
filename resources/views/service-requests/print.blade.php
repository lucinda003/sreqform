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

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td,
        th {
            border: 1px solid #111827;
            padding: 5px 7px;
            vertical-align: top;
            font-size: 13px;
        }

        .noborder {
            border: 0 !important;
        }

        .center {
            text-align: center !important;
        }

        .bold {
            font-weight: 700;
        }

        .small {
            font-size: 12px;
        }

        .tiny {
            font-size: 11px;
        }

        .logo-cell {
            width: 100px;
        }

        .logo {
            margin-top: 4px;
            width: 50px;
            height: 50px;
            
        }

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

        .print-header td {
            vertical-align: middle;
        }

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

        .status-label {
            margin-left: auto;
            font-size: 13px;
            font-weight: 700;
        }

        .status-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 999px;
            border: 1px solid #0f172a;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.04em;
        }

        .status-badge.status-approved {
            background: #dcfce7;
            color: #166534;
            border-color: #16a34a;
        }

        .status-badge.status-checking {
            background: #e0f2fe;
            color: #075985;
            border-color: #38bdf8;
        }

        .status-badge.status-pending {
            background: #fef9c3;
            color: #854d0e;
            border-color: #eab308;
        }

        .status-badge.status-rejected {
            background: #fee2e2;
            color: #991b1b;
            border-color: #ef4444;
        }

        .datetime-wrap {
            width: 100%;
            margin-left: auto;
            margin-top: 7px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .datetime-line {
            margin-top: 0;
            font-size: 14px;
            margin-bottom: 1px;
        }

        .datetime-value {
            width:auto;
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

        .line-caption {
            margin-top: 2px;
            text-align: center;
            font-size: 13px;
        }

        .action-row {
            height: 26px;
        }

        .action-row td {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .version {
            margin-top: 2px;
            text-align: right;
            font-size: 12px;
            font-weight: 700;
        }


        @media print {
            body {
                background: #fff;
                min-height: auto;
                overflow: visible;
            }

            .screen-shell {
                min-height: auto;
                padding: 0;
            }

            .screen-aurora,
            .screen-wave {
                display: none;
            }

            .controls {
                display: none;
            }

            .sheet {
                max-width: autout;
                width: 70%;
                transform: scale(1.3);
                margin-top: 200px;
                background: #fff;
                border-radius: 0;
                box-shadow: none;
                padding: 0;
            }

            td,
            th {
                padding: 7px 9px;
                font-size: 13px;
            }

            .small {
                font-size: 12px;
            }

            .tiny {
                font-size: 11px;
            }

            .header-main {
                font-size: 18px;
            }

            .logo {
                margin-top: 4px;
                width: 70px;
                height: 70px;
            }

            .header-sub {
                font-size: 15px;
                margin-top: 10px;
                margin-left: -7px;
                margin-right: -7px;
                padding-top: 10px;
            }

            .header-main-cell {
                font-size: 14px;
            }

            .header-logo {
                width: 62px;
                height: 62px;
            }

            .header-sub-cell {
                font-size: 16px;
            }

            .header-meta-label,
            .header-meta-value {
                font-size: 13px;
            }

            .reference-label {
                font-size: 10px;
            }

            .reference-code {
                font-size: 12px;
            }

            .reference-wrap {
                width: 100%;
                margin-left: 0;
                margin-top: 7px;
                display: flex;
                align-items: center;
                gap: 5px;
                justify-content: flex-end;
                flex-wrap: nowrap;
            }

            .reference-label {
                font-size: 20px;
                font-weight: 700;
                margin-bottom: 1px;
            }

            .reference-code {
                display: inline-block;
                min-width: auto;
                border-bottom: 1px solid #111827;
                text-align: center;
                font-size: 20px;
                font-weight: 700;
            }

            .datetime-line,
            .datetime-value {
                font-size: 15px;
            }

            .datetime-wrap {
                width: 100%;
                margin-left: 330px;
                margin-top: 7px;
                display: flex;
                align-items: center;
                gap: 5px;
            }

            .datetime-line {
                margin-top: 0;
                font-size: 12px;
                margin-bottom: 1px;
            }

            .datetime-value {
                width: auto;
                display: block;
                border-bottom: 1px solid #111827;
                text-align: center;
                font-weight: 700;
            }

            .desc-title {
                font-size: 13px;
            }

            .desc-body {
                min-height: 180px;
                max-height: 200px;
                font-size: 13px;
                line-height: 1.4;
                padding-top: 0;
                overflow: hidden;
            }
            .line-value2 {
                min-height: 22px;
                margin-left: -6px;
                margin-right: -6px;
                font-size: 13px;
            }
            .line-value {
                min-height: 22px;
                font-size: 13px;
            }

            .line-caption {
                font-size: 12px;
            }

            .action-row {
                height: 34px;
            }

            .version {
                font-size: 14px;
            }
        }

    </style>
</head>
<body>
    <div class="screen-aurora"></div>
    <div class="screen-wave screen-wave-one"></div>
    <div class="screen-wave screen-wave-two"></div>

    <div class="screen-shell">
    <div class="sheet">
        @if (! request()->boolean('embedded'))
            <div class="controls">
                <button class="btn" onclick="window.print()">Print</button>
            </div>
        @endif

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
                if ($value === '') {
                    return '';
                }

                foreach (['H:i:s', 'H:i', 'g:i A', 'g:i a'] as $format) {
                    try {
                        return \Carbon\Carbon::createFromFormat($format, $value)->format('g:i:s A');
                    } catch (\Throwable $exception) {
                        // Keep trying formats until one matches.
                    }
                }

                return $value;
            };
        @endphp

        <div class="datetime-wrap">
            <div class="datetime-line">1) Date/Time of Request (mm/dd/yyyy h:m:s) :</div>
            <div class="datetime-value">{{ $serviceRequest->request_date->format('m/d/Y') }}{{ $serviceRequest->time_received ? ' - ' : '' }}{{ $formatClock($serviceRequest->time_received) }}</div>
        </div>

        <table style="margin-top:8px;">
            <tr>
                <td>2) Request Category : {{ data_get($serviceRequest, 'request_category', '') }}</td>
            </tr>
            <tr>
                <td>3) Application System Name : {{ data_get($serviceRequest, 'application_system_name', '') }}</td>
            </tr>
            <tr>
                <td>4) Expected Date / Time of Completion : {{ optional($serviceRequest->expected_completion_date)->format('m/d/Y') }}{{ $serviceRequest->expected_completion_date && $serviceRequest->expected_completion_time ? ' - ' : '' }}{{ $formatClock($serviceRequest->expected_completion_time) }}</td>
            </tr>
            <tr>
                <td style="padding:0;">
                    <table style="width:95%; border-collapse:collapse; table-layout:fixed; border:0;">
                        <tr>
                            <td class="noborder" style="width:31%; padding:4    px 6px; vertical-align:top;">5) Name of Contact Person :</td>
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
            <tr>
                <td>6) Office : {{ $serviceRequest->office }}</td>
            </tr>
            <tr>
                <td>7) Address : {{ $serviceRequest->address }}</td>
            </tr>
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
            if ($descriptionLength <= 280) {
                $descriptionFontSize = '16px';
                $descriptionLineHeight = '1.45';
            } elseif ($descriptionLength <= 900) {
                $descriptionFontSize = '14px';
                $descriptionLineHeight = '1.38';
            } elseif ($descriptionLength <= 1500) {
                $descriptionFontSize = '12px';
                $descriptionLineHeight = '1.3';
            } else {
                $descriptionFontSize = '10px';
                $descriptionLineHeight = '1.2';
            }
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
                                    <div style="width:100%; min-height:34px; margin-bottom:0; display:flex; align-items:flex-end; justify-content:center;">
                                        <img src="{{ $approvedSignatureUrl }}" alt="Signature" draggable="false" style="max-height:60px; max-width:220px; object-fit:contain; display:block; margin:0 auto; margin-bottom:-10px; user-select:none; -webkit-user-drag:none;">
                                    </div>
                                @else
                                    <div style="min-height:34px;"></div>
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
            <tr>
                <td colspan="2" class="center bold" style="padding:2px 6px;">(For Knowledge Management and Information Technology Service only)</td>
            </tr>
            <tr>
                <td colspan="2" class="bold">14) ACTION TAKEN <span style="font-style:italic; font-weight:400;">(Use separate sheet if necessary)</span></td>
            </tr>
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
                    if ($value === '') {
                        return '';
                    }

                    foreach (['H:i:s', 'H:i', 'g:i A', 'g:i a'] as $format) {
                        try {
                            return \Carbon\Carbon::createFromFormat($format, $value)->format('g:i A');
                        } catch (\Throwable $exception) {
                            // Keep trying the next format.
                        }
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
                    <td>{{ \Illuminate\Support\Str::limit((string) data_get($logs, $i . '.action_taken', ''), 40, '...') }}</td>
                    <td>{{ \Illuminate\Support\Str::limit((string) data_get($logs, $i . '.action_officer', ''), 28, '...') }}</td>
                    <td></td>
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

    @if (request()->boolean('autoprint'))
        <script>
            window.addEventListener('load', function () {
                setTimeout(function () {
                    window.print();
                }, 150);
            });
        </script>
    @endif
</body>
</html>
