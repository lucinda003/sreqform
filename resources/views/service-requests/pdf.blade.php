<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Service Request {{ $serviceRequest->reference_code }}</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 12px;
            color: #111827;
            margin: 0;
            padding: 20px;
        }
        .header {
            border: 1px solid #111827;
            padding: 10px;
            margin-bottom: 10px;
        }
        .title {
            margin: 0;
            text-align: center;
            font-size: 18px;
        }
        .ref {
            margin-top: 8px;
            line-height: 1.5;
        }
        .section-title {
            margin-top: 14px;
            margin-bottom: 6px;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 11px;
            color: #374151;
        }
        .grid {
            width: 100%;
            border-collapse: collapse;
        }
        .grid td {
            border: 1px solid #9ca3af;
            vertical-align: top;
            padding: 8px;
        }
        .label {
            display: block;
            font-size: 10px;
            text-transform: uppercase;
            color: #6b7280;
            margin-bottom: 3px;
        }
        .value {
            white-space: pre-line;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="title">Service Request Form</h1>
        <div class="ref">
            <strong>Reference Code:</strong> {{ $serviceRequest->reference_code }}<br>
            <strong>Date:</strong> {{ $serviceRequest->request_date->format('F d, Y') }}<br>
            <strong>Status:</strong> {{ strtoupper($serviceRequest->status) }}
        </div>
    </div>

    <div class="section-title">Contact Person</div>
    <table class="grid">
        <tr>
            <td>
                <span class="label">Last Name</span>
                <div class="value">{{ $serviceRequest->contact_last_name }}</div>
            </td>
            <td>
                <span class="label">First Name</span>
                <div class="value">{{ $serviceRequest->contact_first_name }}</div>
            </td>
            <td>
                <span class="label">Middle Name</span>
                <div class="value">{{ $serviceRequest->contact_middle_name ?: '-' }}</div>
            </td>
        </tr>
        <tr>
            <td>
                <span class="label">Office</span>
                <div class="value">{{ $serviceRequest->office }}</div>
            </td>
            <td colspan="2">
                <span class="label">Address</span>
                <div class="value">{{ $serviceRequest->address }}</div>
            </td>
        </tr>
        <tr>
            <td>
                <span class="label">Landline</span>
                <div class="value">{{ $serviceRequest->landline ?: '-' }}</div>
            </td>
            <td>
                <span class="label">Fax No.</span>
                <div class="value">{{ $serviceRequest->fax_no ?: '-' }}</div>
            </td>
            <td>
                <span class="label">Mobile No.</span>
                <div class="value">{{ $serviceRequest->mobile_no }}</div>
            </td>
        </tr>
    </table>

    <div class="section-title">Description Request</div>
    <table class="grid">
        <tr>
            <td>
                <span class="label">Please clarify and write down the details of the request.</span>
                <div class="value">{{ $serviceRequest->description_request }}</div>
            </td>
        </tr>
    </table>

    <div class="section-title">Approved By</div>
    <table class="grid">
        <tr>
            <td>
                <span class="label">Name of Head of Office</span>
                <div class="value">{{ $serviceRequest->approved_by_name }}</div>
            </td>
            <td>
                <span class="label">Position</span>
                <div class="value">{{ $serviceRequest->approved_by_position }}</div>
            </td>
        </tr>
        <tr>
            <td>
                <span class="label">Signature</span>
                <div class="value">__________________________</div>
            </td>
            <td>
                <span class="label">Date Signed</span>
                <div class="value">{{ $serviceRequest->approved_date->format('F d, Y') }}</div>
            </td>
        </tr>
    </table>

    <div class="section-title">For knowledge management and information technology service only</div>
    <table class="grid">
        <tr>
            <td>
                <span class="label">10. Date</span>
                <div class="value">{{ optional($serviceRequest->kmits_date)->format('F d, Y') ?: '-' }}</div>
            </td>
            <td>
                <span class="label">11. Time Received</span>
                <div class="value">{{ $serviceRequest->time_received ?: '-' }}</div>
            </td>
        </tr>
    </table>

    <table class="grid" style="margin-top: 8px;">
        <tr>
            <td>
                <span class="label">12. Actions Taken (use separate if necessary)</span>
                <div class="value">{{ $serviceRequest->actions_taken ?: '-' }}</div>
            </td>
        </tr>
    </table>

    <div class="section-title">Action Log</div>
    <table class="grid">
        <tr>
            <td><span class="label">Date</span></td>
            <td><span class="label">Time</span></td>
            <td><span class="label">Action Taken</span></td>
            <td><span class="label">Action Officer</span></td>
            <td><span class="label">Signature</span></td>
        </tr>
        @php $logs = $serviceRequest->action_logs ?? []; @endphp
        @for ($i = 0; $i < 5; $i++)
            <tr>
                <td><div class="value">{{ data_get($logs, $i . '.date', '-') }}</div></td>
                <td><div class="value">{{ data_get($logs, $i . '.time', '-') }}</div></td>
                <td><div class="value">{{ data_get($logs, $i . '.action_taken', '-') }}</div></td>
                <td><div class="value">{{ data_get($logs, $i . '.action_officer', '-') }}</div></td>
                <td><div class="value">________________</div></td>
            </tr>
        @endfor
    </table>

    <table class="grid" style="margin-top: 8px;">
        <tr>
            <td>
                <span class="label">13. Noted by (Name of Supervisor)</span>
                <div class="value">{{ $serviceRequest->noted_by_name ?: '-' }}</div>
            </td>
            <td>
                <span class="label">14. Position</span>
                <div class="value">{{ $serviceRequest->noted_by_position ?: '-' }}</div>
            </td>
        </tr>
        <tr>
            <td>
                <span class="label">Signature</span>
                <div class="value">__________________________</div>
            </td>
            <td>
                <span class="label">15. Date Signed</span>
                <div class="value">{{ optional($serviceRequest->noted_by_date_signed)->format('F d, Y') ?: '-' }}</div>
            </td>
        </tr>
    </table>
</body>
</html>
