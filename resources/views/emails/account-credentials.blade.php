<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            margin-bottom: 30px;
            border-bottom: 2px solid #1e293b;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #1e293b;
            font-size: 24px;
        }
        .credentials {
            background-color: #f0f9ff;
            border-left: 4px solid #0369a1;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .credential-item {
            margin: 15px 0;
        }
        .credential-label {
            font-size: 12px;
            color: #666;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .credential-value {
            font-size: 16px;
            color: #1e293b;
            font-weight: 600;
            font-family: 'Courier New', monospace;
            margin-top: 8px;
            word-break: break-all;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            font-size: 12px;
            color: #666;
        }
        .warning {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
            font-size: 13px;
            color: #92400e;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Welcome to the Platform</h1>
        </div>

        <p>Hi {{ $user->name }},</p>

        <p>Your account has been successfully created. Please use the credentials below to log in:</p>

        <div class="credentials">
            <div class="credential-item">
                <div class="credential-label">Username</div>
                <div class="credential-value">{{ $username }}</div>
            </div>
            <div class="credential-item">
                <div class="credential-label">Password</div>
                <div class="credential-value">{{ $password }}</div>
            </div>
        </div>

        <div class="warning">
            <strong>⚠️ Important:</strong> Please change your password immediately after logging in. Do not share your credentials with anyone.
        </div>

        <p>If you have any questions or need assistance, please contact the administrator.</p>

        <div class="footer">
            <p>This is an automated message, please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
