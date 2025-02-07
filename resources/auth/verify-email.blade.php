<!-- resources/views/auth/verify-email.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
</head>
<body>
    <h2>Email Verification Required</h2>
    <p>Please check your email and click the verification link to continue.</p>
    
    @if (session('message'))
        <p style="color: green;">{{ session('message') }}</p>
    @endif

    <form method="POST" action="{{ route('verification.resend') }}">
        @csrf
        <button type="submit">Resend Verification Email</button>
    </form>
</body>
</html>
