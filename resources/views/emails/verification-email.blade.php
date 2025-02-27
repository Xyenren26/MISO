<!DOCTYPE html>
<html>
<head>
    <title>Email Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            text-align: center;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .logo {
            width: 300px;
            margin: 20px auto;
            display: block;
        }
        h2 {
            color: #333;
        }
        p {
            color: #555;
            font-size: 16px;
            line-height: 1.5;
        }
        .button-container {
            margin-top: 20px;
        }
        .button {
            display: inline-block;
            padding: 12px 20px;
            background-color: #0073e6;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            transition: all 0.3s ease-in-out;
        }
        .button:hover {
            background-color: #005bb5;
            transform: scale(1.05);
        }
        .expired {
            background-color: #ccc;
            pointer-events: none;
            cursor: not-allowed;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
    <img src="https://i.imgur.com/aQrIRgy.png" alt="System Logo" class="logo">
        <h2>Hello, {{ $user->first_name }}!</h2>
        <p>Thank you for registering. Please verify your email by clicking the button below:</p>

        <div class="button-container">
            @php
                $isExpired = now()->timestamp > $expiresAt->timestamp;
            @endphp

            @if ($isExpired)
                <p style="color: red;"><strong>This verification link has expired.</strong></p>
                <a class="button expired">Expired</a>
            @else
                <a href="{{ $verificationUrl }}" class="button">Verify Email</a>
                <p style="font-size: 14px; color: gray;">This link will expire in 60 minutes.</p>
            @endif
        </div>

        <p>If you did not create an account, no further action is required.</p>

        <p class="footer">Regards, <br> <strong>{{ config('app.name') }}</strong></p>
    </div>
</body>
</html>
