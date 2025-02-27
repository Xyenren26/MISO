<!DOCTYPE html>
<html>
<head>
    <title>Welcome to Our System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #003067;
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
            box-shadow: 0 0 10px #003067;
        }
        .logo {
            width: 300px;
            margin: 20px auto;
            display: block;
        }
        h1 {
            color: #333;
        }
        p {
            color: #666;
            font-size: 16px;
            line-height: 1.5;
        }
        .button-container {
            text-align: center;
            margin-top: 20px;
        }
        .button {
            display: inline-block;
            padding: 12px 18px;
            background-color: #003067;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            transition: all 0.3s ease-in-out;
        }
        .button:hover {
            background-color: rgb(232, 230, 233);
            color: #003067;
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
            color: #999;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="https://i.imgur.com/aQrIRgy.png" alt="System Logo" class="logo">
        <h1>Hello, {{ $name }}!</h1>
        <p>Welcome to our system! We are thrilled to have you on board.</p>
        <p>If you have any questions, feel free to reach out to our support team.</p>
        <p>Thanks for joining us!</p>

        <!-- Verification Button -->
        <div class="button-container">
            @php
                $isExpired = now()->timestamp > $expiresAt->timestamp;
            @endphp

            @if ($isExpired)
                <p style="color: red;"><strong>This verification link has expired.</strong></p>
                <a class="button expired">Expired</a>
            @else
                <a href="{{ $verification_url }}" class="button">Verify Email</a>
                <p style="font-size: 14px; color: gray;">This link will expire in 60 minutes.</p>
            @endif
        </div>

        <p class="footer">If you did not sign up for this account, please ignore this email.</p>
    </div>
</body>
</html>
