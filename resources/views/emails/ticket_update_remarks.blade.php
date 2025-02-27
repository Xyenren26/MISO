<!DOCTYPE html>
<html>
<head>
    <title>Service Request Status Updated</title>
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
        <h2>Ticket Request Update</h2>
        <p>Hello, </p>
        <p>Your technical service request with Form No: <strong>{{ $control_no }}</strong> has been updated to <strong>{{ $status }}</strong>.</p>
        <p>Thank you.</p>

        <p class="footer">Regards, <br> <strong>{{ config('app.name') }}</strong></p>
    </div>
</body>
</html>
