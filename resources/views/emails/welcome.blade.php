<!DOCTYPE html>
<html>
<head>
    <title>Welcome to Our System</title>
</head>
<body>
    <h1>Hello, {{ $name }}!</h1>
    <p>Welcome to our system! We are thrilled to have you on board.</p>
    <p>If you have any questions, feel free to reach out to our support team.</p>
    <p>Thanks for joining us!</p>

    <!-- Verification Button -->
    <p>To verify your email, please click the button below:</p>
    <a href="{{ $verification_url }}" style="display:inline-block; padding: 10px 20px; background-color: #4CAF50; color: white; text-align: center; text-decoration: none; border-radius: 5px;">
        Verify Email
    </a>
</body>
</html>
