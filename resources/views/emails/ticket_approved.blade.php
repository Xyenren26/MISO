<!DOCTYPE html>
<html>
<head>
    <title>Ticket Approval Notification</title>
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
    <p>Dear User,</p>
    <p>Your ticket with Control No: <strong>{{ $ticketId }}</strong> has been approved.</p>
    <p><strong>Status:</strong> {{ $status }}</p>
    <p>Approved by: <strong>{{ $approvedBy }}</strong></p>
    <p>Date Approved: <strong>{{ $approveDate }}</strong></p>
    <p>Thank you!</p>
    <p class="footer">Regards, <br> <strong>{{ config('app.name') }}</strong></p>
</div>
</body>
</html>
