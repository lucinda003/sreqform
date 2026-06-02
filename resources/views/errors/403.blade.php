<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 Unauthorized access</title>
    <style>
        :root {
            color-scheme: light;
        }

        body {
            margin: 0;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: #f8fafc;
            color: #0f172a;
        }

        .page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .card {
            max-width: 520px;
            width: 100%;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 28px 32px;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
            text-align: center;
        }

        .status {
            font-size: 64px;
            font-weight: 700;
            letter-spacing: 2px;
            color: #b91c1c;
            margin: 0 0 12px;
        }

        .message {
            font-size: 18px;
            color: #b91c1c;
            margin: 0;
        }
    </style>
</head>
<body>
    <main class="page">
        <section class="card">
            <h1 class="status">403</h1>
            <p class="message">{{ trim($exception->getMessage() ?? '') !== '' ? $exception->getMessage() : 'Unauthorized access' }}</p>
        </section>
    </main>
</body>
</html>
