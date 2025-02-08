<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
    /* Reset */
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
        }
    .account-security-container {
    max-width: 600px;
    margin: auto;
    padding: 20px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

h2 {
    text-align: center;
    margin-bottom: 20px;
}
h3{
    margin-bottom:20px;
}

.security-section {
    margin-bottom: 30px;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background: #f9f9f9;
}

.form-group {
    margin-bottom: 15px;
}

label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
}

input {
    width: 100%;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.btn {
    display: inline-block;
    width: 100%;
    padding: 10px;
    background: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.btn:hover {
    background: #0056b3;
}

p {
    text-align: center;
    margin-top: 10px;
}

p a {
    color: #007bff;
    text-decoration: none;
}

p a:hover {
    text-decoration: underline;
}

.password-container {
    position: relative;
    display: flex;
    align-items: center;
}

.toggle-password {
    position: absolute;
    right: 10px;
    cursor: pointer;
    color: #666;
}

.toggle-password.active {
    color: #007bff;
}

.error-message {
    font-size: 14px;
    color: red;
    margin-top: 5px;
}

 /* Vertical Navigation Container */
 .vertical-nav {
            display: flex;
            flex-direction: column; /* Align buttons vertically */
            gap: 15px; /* Spacing between buttons */
            width: 200px; /* Set a fixed width for sidebar */
            padding: 20px;
            border-radius: 10px; /* Rounded corners */
            
            /* Fix Position */
            position: absolute; /* Keeps it from affecting the layout */
            left: 20px; /* Adjust distance from the left */
            top: 50px; /* Adjust distance from the top */
            
            /* Ensure it's above other elements */
            z-index: 10; /* Make sure it's on top */
        }

        /* General Button Styling */
        .nav-button {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 15px;
            font-size: 16px;
            font-weight: bold;
            text-decoration: none;
            color: white;
            border-radius: 8px;
            transition: all 0.3s ease-in-out;
        }

        /* Home Button Styling */
        .home-button {
            background-color: #007bff; /* Blue */
        }

        .home-button:hover {
            background-color: #0056b3;
        }

        /* Security Button Styling */
        .security-button {
            background-color: #28a745; /* Green */
        }

        .security-button:hover {
            background-color: #1e7e34;
        }

        /* Icon Styling */
        .nav-button i {
            font-size: 20px;
        }
</style>
<!-- Vertical Navigation Menu -->
<div class="vertical-nav">
    <!-- Home Button -->
    <a href="{{ route('home') }}" class="nav-button home-button">
        <i class="fas fa-home"></i> <span>Home</span>
    </a>

    <!-- Account Security Button -->
    <a href="{{ route('profile.index') }}" class="nav-button security-button">
        <i class="fas fa-user-circle"></i> <span>Profile Information</span>
    </a>
</div>

<div class="account-security-container">
    <h2>Account Security</h2>

    <!-- Change Password Section -->
    <div class="security-section">
        <h3>Change Password</h3>
        <form id="change-password-form" action="{{ route('account.changePassword') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="current-password">Current Password</label>
                <div class="password-container">
                    <input type="password" id="current-password" name="current_password" required>
                    <i class="fas fa-eye toggle-password" data-target="current-password"></i>
                </div>
            </div>
            <div class="form-group">
                <label for="new-password">New Password</label>
                <div class="password-container">
                    <input type="password" id="new-password" name="new_password" required>
                    <i class="fas fa-eye toggle-password" data-target="new-password"></i>
                </div>
            </div>
            <div class="form-group">
                <label for="confirm-password">Confirm New Password</label>
                <div class="password-container">
                <input type="password" id="confirm-password" name="new_password_confirmation" required>
                    <i class="fas fa-eye toggle-password" data-target="confirm-password"></i>
                </div>
                <p id="password-error" class="error-message"></p>
            </div>
            <button type="submit" class="btn">Update Password</button>
            @auth
                @if(auth()->user()->email_verified_at)
                    <p><a href="{{ route('password.request') }}">Forgot Password?</a></p>
                @else
                    <p>Please verify your email to reset your password.</p>
                @endif
            @endauth
        </form>
    </div>

    <!-- Change Email Section -->
    <div class="security-section">
        <h3>Change Email</h3>
        <form id="change-email-form" action="{{ route('account.changeEmail') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="new-email">New Email Address</label>
                <input type="email" id="new-email" name="email" required>
            </div>
            <button type="submit" class="btn">Update Email</button>
        </form>
    </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const passwordForm = document.getElementById("change-password-form");
    const emailForm = document.getElementById("change-email-form");
    const passwordError = document.getElementById("password-error");
    const currentPasswordInput = document.getElementById("current-password");
    const newPasswordInput = document.getElementById("new-password");
    const confirmPasswordInput = document.getElementById("confirm-password");
    const toggleIcons = document.querySelectorAll(".toggle-password");

    function showAlert(type, message) {
        // Remove existing alerts
        document.querySelectorAll(".alert-box").forEach(alert => alert.remove());

        const alertBox = document.createElement("div");
        alertBox.classList.add("alert-box", "px-4", "py-3", "rounded-lg", "relative", "mb-4");

        if (type === "success") {
            alertBox.classList.add("bg-green-100", "border", "border-green-400", "text-green-700");
        } else {
            alertBox.classList.add("bg-red-100", "border", "border-red-400", "text-red-700");
        }

        alertBox.innerHTML = `
            <strong class="font-bold">${type === "success" ? "Success!" : "Error!"}</strong>
            <span class="block sm:inline">${message}</span>
            <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.remove();">
                &times;
            </button>
        `;

        document.querySelector(".account-security-container").prepend(alertBox);
    }

    // 🔹 Real-time Password Matching Validation
    function validatePasswordMatch() {
        if (newPasswordInput.value !== confirmPasswordInput.value) {
            passwordError.textContent = "Passwords do not match!";
            passwordError.style.color = "red";
        } else {
            passwordError.textContent = "";
        }
    }

    newPasswordInput.addEventListener("input", validatePasswordMatch);
    confirmPasswordInput.addEventListener("input", validatePasswordMatch);

    // 🔹 Password Form Submission
    passwordForm.addEventListener("submit", function (event) {
        event.preventDefault();

        if (newPasswordInput.value !== confirmPasswordInput.value) {
            passwordError.textContent = "Passwords do not match!";
            passwordError.style.color = "red";
            return;
        }

        const formData = new FormData(passwordForm);

        fetch(passwordForm.action, {
            method: "POST",
            body: formData,
            headers: {
                "X-CSRF-TOKEN": document.querySelector("meta[name='csrf-token']").getAttribute("content"),
                "Accept": "application/json"
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert("success", "Password updated successfully!");
                passwordForm.reset();
            } else {
                showAlert("error", data.message);
            }
        })
        .catch(error => {
            console.error("Error:", error);
            showAlert("error", "An unexpected error occurred.");
        });
    });

    // 🔹 Email Form Submission
    emailForm.addEventListener("submit", function (event) {
        event.preventDefault();
        const formData = new FormData(emailForm);

        fetch(emailForm.action, {
            method: "POST",
            body: formData,
            headers: {
                "X-CSRF-TOKEN": document.querySelector("meta[name='csrf-token']").getAttribute("content"),
                "Accept": "application/json"
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert("success", data.message);
                emailForm.reset();
            } else {
                showAlert("error", data.message);
            }
        })
        .catch(error => {
            console.error("Error:", error);
            showAlert("error", "An unexpected error occurred.");
        });
    });

    // 🔹 Toggle Password Visibility
    toggleIcons.forEach(icon => {
        icon.addEventListener("click", function () {
            const targetId = this.getAttribute("data-target");
            const targetInput = document.getElementById(targetId);

            if (targetInput) { // Ensure target input exists before modifying
                if (targetInput.type === "password") {
                    targetInput.type = "text";
                    this.classList.add("active");
                } else {
                    targetInput.type = "password";
                    this.classList.remove("active");
                }
            }
        });
    });
});

</script>
<script src="https://cdn.tailwindcss.com"></script>
