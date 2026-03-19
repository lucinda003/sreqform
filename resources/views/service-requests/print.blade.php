<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Request {{ $serviceRequest->reference_code }}</title>
    <style>
        @page {
            size: A4;
            margin: 8mm;
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #111;
            background: #fff;
        }

        .sheet {
            width: 100%;
            max-width: 194mm;
            margin: 0 auto;
        }

        .controls {
            text-align: right;
            margin-bottom: 6px;
        }

        .btn {
            border: 1px solid #111827;
            background: #111827;
            color: #fff;
            padding: 6px 10px;
            border-radius: 4px;
            font-size: 11px;
            cursor: pointer;
        }

        table { width: 100%; border-collapse: collapse; }
        td, th { border: 1px solid #4b5563; padding: 4px 6px; vertical-align: top; }

        .noborder { border: 0 !important; }
        .center { text-align: center; }
        .right { text-align: right; }
        .bold { font-weight: 700; }
        .small { font-size: 11px; }
        .tiny { font-size: 10px; }
        .gray { background: #e5e7eb; }
        .desc-box { min-height: 420px; }
        .action-row { height: 34px; }
        .logo { width: 38px; height: 38px; object-fit: contain; }

        @media print {
            .controls { display: none; }
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
                <td style="width:32px" class="center"><img src="{{ asset('images/dohlogo.svg') }}" alt="DOH" class="logo"></td>
                <td>
                    <div class="center bold" style="font-size:18px;">Knowledge Management and Information Technology Service</div>
                    <div class="center bold=" style="margin-top:8px; font-size:15px;">Service Request Form</div>
                </td>
            </tr>
        </table>

        <table>
            <tr>
                <td class="noborder"></td>
                <td class="noborder right bold">Reference Code: {{ $serviceRequest->reference_code }}</td>
            </tr>
            <tr>
                <td class="noborder"></td>
                <td class="noborder right">1.) Date of Request (mm/dd/yyyy): {{ $serviceRequest->request_date->format('m/d/Y') }}</td>
            </tr>
        </table>

        <table>
            <tr>
                <td style="width:24px;">2)</td>
                <td colspan="4">
                    Name of Contact Person :
                    <table style="display:inline-table; width75%; margin-left:px; vertical-align:top; border-collapse:collapse; table-layout:fixed;">
                        <tr>
                            <td class="noborder center" style="white-space:nowrap; padding:0 10px;">{{ $serviceRequest->contact_last_name ?: 'dqwdwq' }}</td>
                            <td class="noborder center" style="white-space:nowrap; padding:0 10px;">{{ $serviceRequest->contact_first_name ?: 'dqwdq' }}</td>
                            <td class="noborder center" style="white-space:nowrap; padding:0 10px;">{{ $serviceRequest->contact_middle_name ?: 'dqdqw' }}</td>
                        </tr>
                        <tr>
                            <td class="noborder center tiny" style="border-top:1px solid #4b5563 !important; padding-top:2px;">Last Name</td>
                            <td class="noborder center tiny" style="border-top:1px solid #4b5563 !important; padding-top:2px;">First Name</td>
                            <td class="noborder center tiny" style="border-top:1px solid #4b5563 !important; padding-top:2px;">Middle Name</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>3)</td>
                <td>Office:</td>
                <td colspan="3">{{ $serviceRequest->office }}</td>
            </tr>
            <tr>
                <td>4)</td>
                <td>Address:</td>
                <td colspan="3">{{ $serviceRequest->address }}</td>
            </tr>
            <tr>
                <td>5)</td>
                <td>Landline:</td>
                <td>{{ $serviceRequest->landline ?: '-' }}</td>
                <td>6.) Fax No.</td>
                <td>
                    {{ $serviceRequest->fax_no ?: '-' }}
                    <span style="margin-left:14px;">7) Mobile No.: {{ $serviceRequest->mobile_no }}</span>
                </td>
            </tr>
            <tr>
                <td colspan="5" class="bold" style="padding:0;">
                    <div style="padding:1px 4px;">8.) <span style="text-decoration: underline;">DESCRIPTION OF REQUEST:</span> <span style="font-style:italic; font-weight:400;">(Please clearly write down the details of the request.)</span></div>
                    <div style="height:5px; border-top:1px solid #1e3a8a; border-bottom:1px solid #1e3a8a; background:#d1d5db;"></div>
                </td>
            </tr>
            <tr>
                <td colspan="5" class="desc-box">
                    <div style="height:300px; white-space:pre-wrap; font-size:12px; line-height:1.6;">{{ $serviceRequest->description_request }}</div>
                </td>
            </tr>
        </table>

        <table>
            <tr>
                <td style="width:120px; border-right:0;" class="bold">9. APPROVED BY:</td>
                <td colspan="2" style="padding:6px 10px; border-left:0;">
                    <div style="display:flex; gap:16px; align-items:flex-start;">
                        <div style="flex:1;">
                            <div style="min-height:18px; border-bottom:1px solid #4b5563; padding:0 4px;">{{ $serviceRequest->approved_by_name }}</div>
                            <div class="small" style="padding-top:2px;">Name &amp; Signature of Head of Office</div>

                            <div style="margin-top:8px; min-height:18px; border-bottom:1px solid #4b5563; padding:0 4px;">{{ $serviceRequest->approved_by_position }}</div>
                            <div class="small" style="padding-top:2px;">Position</div>
                        </div>

                        <div style="width:38%;">
                            <div style="min-height:18px; border-bottom:1px solid #4b5563; padding:0 4px;">{{ optional($serviceRequest->approved_date)->format('m/d/Y') }}</div>
                            <div class="small" style="padding-top:2px;">Date Signed</div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="3" class="center gray bold">(For Knowledge Management and Information Technology Service only)</td>
            </tr>
            <tr>
                <td colspan="2">10. Date Received (mm/dd/yyyy): {{ optional($serviceRequest->kmits_date)->format('m/d/Y') ?: '-' }}</td>
                <td>11. Time Received (hh:mm): {{ $serviceRequest->time_received ?: '-' }}</td>
            </tr>
            <tr>
                <td colspan="3" class="bold">12. ACTIONS TAKEN: <span style="font-style:italic; font-weight:400;">(Use separate sheet if necessary)</span></td>
            </tr>
        </table>

        <table>
            <tr class="gray center bold">
                <td style="width:80px;">DATE<br><span class="tiny">(a)</span></td>
                <td style="width:80px;">TIME<br><span class="tiny">(b)</span></td>
                <td>ACTION TAKEN<br><span class="tiny">(c)</span></td>
                <td style="width:200px;">ACTION OFFICER<br><span class="tiny">(d)</span></td>
                <td style="width:85px;">SIGNATURE<br><span class="tiny">(e)</span></td>
            </tr>
            @php $logs = $serviceRequest->action_logs ?? []; @endphp
            @for ($i = 0; $i < 5; $i++)
                <tr class="action-row">
                    <td>{{ data_get($logs, $i . '.date', '') }}</td>
                    <td>{{ data_get($logs, $i . '.time', '') }}</td>
                    <td>{{ data_get($logs, $i . '.action_taken', '') }}</td>
                    <td>{{ data_get($logs, $i . '.action_officer', '') }}</td>
                    <td></td>
                </tr>
            @endfor
        </table>

        <table>
            <tr>
                <td style="width:92px;">13. NOTED BY:</td>
                <td>{{ $serviceRequest->noted_by_name ?: '' }}</td>
                <td style="width:95px;" class="center">14.</td>
                <td style="width:95px;" class="center">15.</td>
            </tr>
            <tr>
                <td></td>
                <td class="center small">Name and Signature of Supervisor</td>
                <td class="center small">Position</td>
                <td class="center small">Date Signed</td>
            </tr>
        </table>

        <div class="tiny" style="text-align:right; margin-top:2px;">DOH-KMITS-SRF</div>
    </div>
</body>
</html>
