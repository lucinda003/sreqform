<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Request {{ $serviceRequest->reference_code }}</title>
    <style>
        @page {
            size: A4;
            margin: 7mm;
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #111827;
            background: #fff;
        }

        .sheet {
            width: 100%;
            max-width: 196mm;
            margin: 0 auto;
        }

        .controls {
            text-align: right;
            margin-bottom: 4px;
        }

        .btn {
            border: 1px solid #0f172a;
            background: #111827;
            color: #fff;
            padding: 6px 10px;
            border-radius: 4px;
            font-size: 12px;
            cursor: pointer;
        }

        table { width: 100%; border-collapse: collapse; }
        td, th { border: 1px solid #111827; padding: 4px 6px; vertical-align: top; }

        .noborder { border: 0 !important; }
        .center { text-align: center !important; }
        .right { text-align: right; }
        .bold { font-weight: 700; }
        .small { font-size: 11px; }
        .tiny { font-size: 10px; }
        .action-row { height: 28px; }
        .logo { width: 46px; height: 46px; object-fit: contain; }
        .line-label { text-align: center; font-size: 11px; margin-top: 2px; }

        .desc-title {
            border-top: 1px solid #111827;
            border-bottom: 4px solid #111827;
            padding: 6px 2px;
        }

        .desc-body {
            min-height: 150px;
            border-bottom: 4px solid #111827;
            padding: 10px 8px;
            white-space: pre-line;
            word-break: break-word;
            overflow-wrap: anywhere;
            line-height: 1.45;
        }

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
                <td style="width:62px" class="center"><img src="{{ asset('images/dohlogo.svg') }}" alt="DOH" class="logo"></td>
                <td>
                    <div class="center bold" style="font-size:17px;">Knowledge Management and Information Technology Service</div>
                    <div class="center bold" style="margin-top:6px; font-size:15px; border-top:1px solid #111827; padding-top:4px;">Service Request Form</div>
                </td>
            </tr>
        </table>

        <table style="margin-left:120px; margin-top:4px;">
            <tr>
                <td class="noborder"></td>
                <td class="noborder center bold" style="font-size:14px;">Reference Code : <span style="font-size:16px;">{{ $serviceRequest->reference_code }}</span></td>
            </tr>
            <tr>
                <td class="noborder"></td>
                <td class="noborder center">1) Date/Time of Request (mm/dd/yyyy h:m:s) :
                    <span style="display:inline-block; min-width:210px; border-bottom:1px solid #111827; text-align:center;">
                        {{ $serviceRequest->request_date->format('m/d/Y') }} {{ $serviceRequest->time_received ?: '' }}
                    </span>
                </td>
            </tr>
        </table>

        <table style="margin-top:4px;">
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
                            <div style="min-height:18px; border-bottom:1px solid #111827; padding:0 4px;">{{ $serviceRequest->approved_by_name }}</div>
                            <div class="line-label">Name &amp; Signature of Head of Office</div>

                            <div style="margin-top:8px; min-height:18px; border-bottom:1px solid #111827; padding:0 4px;">{{ $serviceRequest->approved_by_position }}</div>
                            <div class="line-label">Position</div>
                        </div>

                        <div style="width:38%;">
                            <div style="min-height:18px; border-bottom:1px solid #111827; padding:0 4px;">{{ optional($serviceRequest->approved_date)->format('m/d/Y') }}</div>
                            <div class="line-label">Date Signed</div>
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
                <td colspan="2" style="width:180px;">Received</td>
                <td colspan="3">Action</td>
                <td rowspan="2" style="width:110px; vertical-align:middle;">Signature<br><span class="tiny">(g)</span></td>
            </tr>
            <tr class="center">
                <td style="width:90px;">Date<br><span class="tiny">(a)</span></td>
                <td style="width:90px;">Time<br><span class="tiny">(b)</span></td>
                <td style="width:90px;">Date<br><span class="tiny">(c)</span></td>
                <td style="width:90px;">Time<br><span class="tiny">(d)</span></td>
                <td>Taken<br><span class="tiny">(e)</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Officer<br><span class="tiny">(f)</span></td>
            </tr>
            @php $logs = $serviceRequest->action_logs ?? []; @endphp
            @for ($i = 0; $i < 5; $i++)
                <tr class="action-row">
                    <td>{{ data_get($logs, $i . '.date', '') }}</td>
                    <td>{{ data_get($logs, $i . '.time', '') }}</td>
                    <td></td>
                    <td></td>
                    <td>
                        <table style="width:100%; border-collapse:collapse; border:0;">
                            <tr>
                                <td class="noborder" style="width:60%; border-right:1px solid #111827 !important; padding:0 4px;">{{ data_get($logs, $i . '.action_taken', '') }}</td>
                                <td class="noborder" style="padding:0 4px;">{{ data_get($logs, $i . '.action_officer', '') }}</td>
                            </tr>
                        </table>
                    </td>
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

        <div class="bold" style="text-align:right; margin-top:2px; font-size:14px;">DOH-KMITS-SRF Ver. 1</div>
    </div>
</body>
</html>
