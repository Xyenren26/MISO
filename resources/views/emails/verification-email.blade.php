<!DOCTYPE html>
<html>
<head>
    <title>Email Verification</title>
</head>
<body>
    <h2>Hello, {{ $user->first_name }}!</h2>
    <p>Thank you for registering. Please verify your email by clicking the link below:</p>

    <p>
        <a href="{{ $verificationUrl }}" style="background: #0073e6; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
            Verify Email
        </a>
    </p>

    <p>If you did not create an account, no further action is required.</p>

    <p>Regards,<br> {{ config('app.name') }}</p>
</body>
</html>
