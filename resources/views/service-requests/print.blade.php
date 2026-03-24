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
            font-size: 11px;
            color: #111827;
            background: #fff;
        }

        .sheet {
            width: 100%;
            max-width: 200mm;
            margin: 0 auto 12px;
        }

        .controls {
            text-align: right;
            margin-bottom: 4px;
        }

        .btn {
            border: 1px solid #111827;
            background: #111827;
            color: #fff;
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 11px;
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
            font-size: 11px;
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
            font-size: 10px;
        }

        .tiny {
            font-size: 9px;
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
        }

        .reference-wrap {
            width: 100%;
            margin-left: 500px;
            margin-top: 7px;
            display: flex;
            align-items: center;
            gap: 5px;
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
        }

        .datetime-wrap {
            width: 100%;
            margin-left: 420px;
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
            width:auto;
            display: block;
            border-bottom: 1px solid #111827;
            text-align: center;
            font-weight: 700;
        }

        .desc-title {
            border-top: 1px solid #111827;
            border-bottom: 4px solid #111827;
            padding: 6px 7px;
            font-size: 11px;
        }

        .desc-body {
            min-height: 130px;
            border-bottom: 4px solid #111827;
            padding: 10px 8px;
            white-space: pre-line;
            word-break: break-word;
            overflow-wrap: anywhere;
            line-height: 1.35;
        }

        .line-value {
            min-height: 18px;
            border-bottom: 1px solid #111827;
            padding: 0 3px;
        }

        .line-caption {
            margin-top: 2px;
            text-align: center;
            font-size: 11px;
        }

        .action-row {
            height: 26px;
        }

        .version {
            margin-top: 2px;
            text-align: right;
            font-size: 12px;
            font-weight: 700;
        }

        @media print {
            .controls {
                display: none;
            }

            .sheet {
                max-width: auto;
                width: 70%;
                transform: scale(1.3);
                margin-top: 200px;
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
                font-size: 16px;
                margin-top: 8px;
            }
        
            .reference-label {
                font-size: 20px;
            }

            .reference-code {
                font-size: 22px;
            }

            .reference-wrap {
                width: 100%;
                margin-left: 500px;
                margin-top: 7px;
                display: flex;
                align-items: center;
                gap: 5px;
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
            }

            .datetime-line,
            .datetime-value {
                font-size: 14px;
            }

            .datetime-wrap {
                width: 100%;
                margin-left: 400px;
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
                font-size: 13px;
                line-height: 1.4;
            }

            .line-value {
                min-height: 22px;
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
    <div class="sheet">
        <div class="controls">
            <button class="btn" onclick="window.print()">Print</button>
        </div>

        <table>
            <tr>
                <td class="center logo-cell"><img src="{{ asset('images/dohlogo.svg') }}" alt="DOH" class="logo"></td>
                <td>
                    <div class="header-main">Knowledge Management and Information Technology Service</div>
                    <div class="header-sub">Service Request Form</div>
                </td>
            </tr>
        </table>

        <div class="reference-wrap">
            <div class="reference-label">Reference Code :</div>
            <div class="reference-code">{{ $serviceRequest->reference_code }}</div>
        </div>

        <div class="datetime-wrap">
            <div class="datetime-line">1) Date/Time of Request (mm/dd/yyyy h:m:s) :</div>
            <div class="datetime-value">{{ $serviceRequest->request_date->format('m/d/Y') }} {{ $serviceRequest->time_received ?: '' }}</div>
        </div>

        <table style="margin-top:8px;">
            <tr>
                <td>2) Request Category : {{ data_get($serviceRequest, 'request_category', '') }}</td>
            </tr>
            <tr>
                <td>3) Application System Name : {{ data_get($serviceRequest, 'application_system_name', '') }}</td>
            </tr>
            <tr>
                <td>4) Expected Date / Time of Completion : {{ optional($serviceRequest->expected_completion_date)->format('m/d/Y') }}{{ $serviceRequest->expected_completion_date && $serviceRequest->expected_completion_time ? ' - ' : '' }}{{ $serviceRequest->expected_completion_time ?: '' }}</td>
            </tr>
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
        <div class="desc-body">{{ $serviceRequest->description_request }}</div>

        <table style="margin-top:0;">
            <tr>
                <td style="width:180px;" class="bold">13) APPROVED BY :</td>
                <td style="padding:6px 10px;">
                    <div style="display:flex; gap:16px; align-items:flex-start;">
                        <div style="flex:1;">
                            <div class="line-value">{{ $serviceRequest->approved_by_name }}</div>
                            <div class="line-caption">Name &amp; Signature of Head of Office</div>

                            <div style="margin-top:8px;" class="line-value">{{ $serviceRequest->approved_by_position }}</div>
                            <div class="line-caption">Position</div>
                        </div>

                        <div style="width:38%;">
                            <div class="line-value">{{ optional($serviceRequest->approved_date)->format('m/d/Y') }}</div>
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

        <table>
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
            @php $logs = $serviceRequest->action_logs ?? []; @endphp
            @for ($i = 0; $i < 5; $i++)
                <tr class="action-row">
                    <td>{{ data_get($logs, $i . '.date', '') }}</td>
                    <td>{{ data_get($logs, $i . '.time', '') }}</td>
                    <td></td>
                    <td></td>
                    <td>{{ data_get($logs, $i . '.action_taken', '') }}</td>
                    <td>{{ data_get($logs, $i . '.action_officer', '') }}</td>
                    <td></td>
                </tr>
            @endfor
        </table>

        <table>
            <tr>
                <td style="width:34%;">15) NOTED BY :</td>
                <td style="width:33%;">16)</td>
                <td style="width:33%;">17)</td>
            </tr>
            <tr>
                <td class="center" style="padding:2px 6px;">Name and Signature of Supervisor</td>
                <td class="center" style="padding:2px 6px;">Position</td>
                <td class="center" style="padding:2px 6px;">Date Signed</td>
            </tr>
        </table>

        <div class="version">DOH-KMITS-SRF Ver. 1</div>
    </div>
</body>
</html>
