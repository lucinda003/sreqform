<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Request {{ $serviceRequest->reference_code }}</title>
    <style>
        @page {
            size: A4;
            margin: 14mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            color: #111827;
            margin: 0;
            font-size: 12px;
            line-height: 1.4;
            background: #ffffff;
        }

        .sheet {
            width: 100%;
            max-width: 190mm;
            margin: 0 auto;
        }

        .header {
            border: 1px solid #111827;
            padding: 10px;
            margin-bottom: 10px;
        }

        .title {
            font-size: 18px;
            font-weight: 700;
            text-align: center;
            margin: 0;
        }

        .reference {
            margin-top: 8px;
            font-size: 12px;
        }

        .grid-two {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .field {
            border: 1px solid #374151;
            padding: 8px;
            min-height: 52px;
        }

        .label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #374151;
            margin-bottom: 4px;
            display: block;
        }

        .value {
            font-size: 12px;
            color: #111827;
            white-space: pre-line;
        }

        .section-title {
            margin: 12px 0 6px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            color: #111827;
        }

        .footer-note {
            margin-top: 10px;
            font-size: 10px;
            color: #4b5563;
        }

        .print-controls {
            margin: 12px 0;
            text-align: right;
        }

        .print-btn {
            border: 1px solid #111827;
            background: #111827;
            color: #ffffff;
            padding: 8px 12px;
            font-size: 12px;
            border-radius: 6px;
            cursor: pointer;
        }

        @media print {
            .print-controls {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="sheet">
        <div class="print-controls">
            <button class="print-btn" onclick="window.print()">Print</button>
        </div>

        <div class="header">
            <h1 class="title">Service Request Form</h1>
            <div class="reference">
                <strong>Reference Code:</strong> {{ $serviceRequest->reference_code }}<br>
                <strong>Date:</strong> {{ $serviceRequest->request_date->format('F d, Y') }}<br>
                <strong>Status:</strong> {{ strtoupper($serviceRequest->status) }}
            </div>
        </div>

        <div class="section-title">Contact Person</div>
        <div class="grid-two">
            <div class="field">
                <span class="label">Last Name</span>
                <div class="value">{{ $serviceRequest->contact_last_name }}</div>
            </div>
            <div class="field">
                <span class="label">First Name</span>
                <div class="value">{{ $serviceRequest->contact_first_name }}</div>
            </div>
            <div class="field">
                <span class="label">Middle Name</span>
                <div class="value">{{ $serviceRequest->contact_middle_name ?: '-' }}</div>
            </div>
            <div class="field">
                <span class="label">Office</span>
                <div class="value">{{ $serviceRequest->office }}</div>
            </div>
            <div class="field" style="grid-column: 1 / -1;">
                <span class="label">Address</span>
                <div class="value">{{ $serviceRequest->address }}</div>
            </div>
            <div class="field">
                <span class="label">Landline</span>
                <div class="value">{{ $serviceRequest->landline ?: '-' }}</div>
            </div>
            <div class="field">
                <span class="label">Fax No.</span>
                <div class="value">{{ $serviceRequest->fax_no ?: '-' }}</div>
            </div>
            <div class="field" style="grid-column: 1 / -1;">
                <span class="label">Mobile No.</span>
                <div class="value">{{ $serviceRequest->mobile_no }}</div>
            </div>
        </div>

        <div class="section-title">Description Request</div>
        <div class="field" style="min-height: 110px;">
            <span class="label">Please clarify and write down the details of the request.</span>
            <div class="value">{{ $serviceRequest->description_request }}</div>
        </div>

        <div class="section-title">Approved By</div>
        <div class="grid-two">
            <div class="field">
                <span class="label">Name of Head of Office</span>
                <div class="value">{{ $serviceRequest->approved_by_name }}</div>
            </div>
            <div class="field">
                <span class="label">Position</span>
                <div class="value">{{ $serviceRequest->approved_by_position }}</div>
            </div>
            <div class="field">
                <span class="label">Signature</span>
                <div class="value">__________________________</div>
            </div>
            <div class="field">
                <span class="label">Date Signed</span>
                <div class="value">{{ $serviceRequest->approved_date->format('F d, Y') }}</div>
            </div>
        </div>

        <div class="section-title">For knowledge management and information technology service only</div>
        <div class="grid-two">
            <div class="field">
                <span class="label">10. Date</span>
                <div class="value">{{ optional($serviceRequest->kmits_date)->format('F d, Y') ?: '-' }}</div>
            </div>
            <div class="field">
                <span class="label">11. Time Received</span>
                <div class="value">{{ $serviceRequest->time_received ?: '-' }}</div>
            </div>
        </div>

        <div class="field" style="margin-top: 10px; min-height: 70px;">
            <span class="label">12. Actions Taken (use separate if necessary)</span>
            <div class="value">{{ $serviceRequest->actions_taken ?: '-' }}</div>
        </div>

        <div class="section-title">Action Log</div>
        <table style="width:100%; border-collapse: collapse; font-size: 11px;">
            <thead>
                <tr>
                    <th style="border:1px solid #374151; padding:6px; text-align:left;">Date</th>
                    <th style="border:1px solid #374151; padding:6px; text-align:left;">Time</th>
                    <th style="border:1px solid #374151; padding:6px; text-align:left;">Action Taken</th>
                    <th style="border:1px solid #374151; padding:6px; text-align:left;">Action Officer</th>
                    <th style="border:1px solid #374151; padding:6px; text-align:left;">Signature</th>
                </tr>
            </thead>
            <tbody>
                @php $logs = $serviceRequest->action_logs ?? []; @endphp
                @for ($i = 0; $i < 5; $i++)
                    <tr>
                        <td style="border:1px solid #374151; padding:6px;">{{ data_get($logs, $i . '.date', '-') }}</td>
                        <td style="border:1px solid #374151; padding:6px;">{{ data_get($logs, $i . '.time', '-') }}</td>
                        <td style="border:1px solid #374151; padding:6px;">{{ data_get($logs, $i . '.action_taken', '-') }}</td>
                        <td style="border:1px solid #374151; padding:6px;">{{ data_get($logs, $i . '.action_officer', '-') }}</td>
                        <td style="border:1px solid #374151; padding:6px;">________________</td>
                    </tr>
                @endfor
            </tbody>
        </table>

        <div class="grid-two" style="margin-top: 10px;">
            <div class="field">
                <span class="label">13. Noted by (Name of Supervisor)</span>
                <div class="value">{{ $serviceRequest->noted_by_name ?: '-' }}</div>
            </div>
            <div class="field">
                <span class="label">14. Position</span>
                <div class="value">{{ $serviceRequest->noted_by_position ?: '-' }}</div>
            </div>
            <div class="field">
                <span class="label">Signature</span>
                <div class="value">__________________________</div>
            </div>
            <div class="field">
                <span class="label">15. Date Signed</span>
                <div class="value">{{ optional($serviceRequest->noted_by_date_signed)->format('F d, Y') ?: '-' }}</div>
            </div>
        </div>

        <div class="footer-note">
            Generated from DOH Service Request module.
        </div>
    </div>
</body>
</html>
