<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset Modal</title>
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

        /* Form Styling */
        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        label {
            font-weight: bold;
        }

        input[type="email"] {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
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
            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <button type="submit">Send Reset Link</button>
            </form>
        </div>
    </div>
</body>
</html>