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

        body {
            background-image: url('../images/leftscreenbg2.png');
            background-size: auto;
            background-position: bottom center;
            background-attachment: fixed;
            position: relative;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .modal {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 300px;
            text-align: center;
            position: relative;
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

    <script>
        function showAlert(type, message) {
            document.querySelectorAll(".alert-box").forEach(alert => alert.remove());

            const alertBox = document.createElement("div");
            alertBox.classList.add("alert-box");

            if (type === "success" || type === "message") { 
                alertBox.classList.add("bg-green-100"); // Green background for success & message
            } else {
                alertBox.classList.add("bg-red-100"); // Red background for errors
            }

            alertBox.innerHTML = `
                <strong>${type === "success" ? "Success!" : type === "message" ? "Message!" : "Error!"}</strong> 
                <span>${message}</span>
                <button class="not" type="button" onclick="this.parentElement.remove();" style="float:right; background:none; border:none; font-weight:bold; cursor:pointer;">&times;</button>
            `;

            document.querySelector(".modal").prepend(alertBox);
        }

        // Ensure session messages are available after the page loads
        window.onload = function () {
            const successMessage = @json(session('success'));
            const unverifiedMessage = @json(session('unverified'));
            const errorMessage = @json($errors->first());

            if (successMessage) {
                showAlert('success', successMessage);
            }
            if (unverifiedMessage) {
                showAlert('message', unverifiedMessage); // Treat "unverified" as a message
            }
            if (errorMessage) {
                showAlert('error', errorMessage);
            }
        };

    </script>

</body>
</html>
