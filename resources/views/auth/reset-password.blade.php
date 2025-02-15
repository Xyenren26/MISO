<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password Modal</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        /* Apply Background Image to the Body */
        body {
            background-image: url('../images/leftscreenbg2.png'); /* Your global background image */
            background-size: auto; /* Keep the image in its original size */
            background-position: bottom center; /* Position the image at the bottom */
            background-attachment: fixed; /* Keep the background fixed while scrolling */
            position: relative;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Modal Overlay */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent black background */
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Modal Box */
        .modal {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 300px;
            text-align: center;
        }

        .alert-box {
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            font-weight: bold;
            position: absolute;
            top: -100px;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            text-align: center;
        }

        .bg-green-100 { background-color: #d4edda; border: 1px solid #155724; color: #155724; }
        .bg-red-100 { background-color: #f8d7da; border: 1px solid #721c24; color: #721c24; }
        .not{
            margin-top: -37px;
            margin-right: -14px;
            color:black;
        }

        /* Form Styling */
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-weight: bold;
            text-align: left;
        }

        input[type="email"],
        input[type="password"] {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            width: 100%;
        }

        button {
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <!-- Modal Structure -->
    <div class="modal-overlay">
        <div class="modal">
            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="{{ request()->email ?? old('email', $email) }}" required readonly>
                
                <label for="password">New Password:</label>
                <input type="password" id="password" name="password" required>
                
                <label for="password_confirmation">Confirm Password:</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
                
                <button type="submit">Reset Password</button>
            </form>
        </div>
    </div>
</body>
</html>