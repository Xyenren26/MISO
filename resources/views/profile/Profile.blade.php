<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Details</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
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

        /* Home Button */
        .home-button {
            position: absolute;
            top: 20px;
            left: 20px;
            padding: 10px 20px;
            background-color: #0073e6;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
        }

        .home-button:hover {
            background-color: #005bb5;
        }

        /* Profile Container */
        .profile-container {
            max-width: 900px;
            margin: 20px auto;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        /* Header Section */
        .profile-header {
            background-image: url('../images/background-image.jpg'); /* Replace with your desired image path */
            background-size: cover; /* Ensure the image covers the entire header */
            background-position: center; /* Center the image */
            padding: 20px; /* Optional: Add padding for spacing */
            align-items: center;
            justify-content: space-between;
            color: white; /* Ensure text is visible against the background image */
        }

        /* Profile Info Section */
        .profile-info {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Remove Profile Picture and Add System Logo */
        .system-logo {
            width: 200px;
            height: auto;
            margin-bottom: 15px;
        }

        .name-section {
            margin-bottom: 10px;
            text-align: center; /* Center-aligns the text */
        }

        .name-section h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }

        .name-section p {
            font-size: 16px;
            color: white;
        }

        /* Main Content Section */
        .profile-details {
            padding: 20px;
        }

        /* Form Styles */
        .info-item {
            margin-bottom: 15px;
        }

        .info-item label {
            display: block;
            font-size: 14px;
            color: #555555;
            margin-bottom: 5px;
        }

        .info-item input {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #cccccc;
            border-radius: 5px;
            background-color: #f9f9f9;
            color: #333333;
        }

        .info-item input:disabled {
            background-color: #e6e6e6;
        }

        /* Save Button */
        .save-button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #0073e6;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        .save-button:hover {
            background-color: #005bb5;
        }

        .modal {
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0,0,0); /* Fallback color */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto; /* 15% from the top and centered */
            padding: 20px;
            border: 1px solid #888;
            width: 80%; /* Could be more or less, depending on screen size */
        }

        .close-btn {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close-btn:hover,
        .close-btn:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        /* Responsive Design */
        @media (max-width: 768px) {
            .profile-header {
                height: 300px;
            }

            .profile-info {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>
<body>
    <!-- Home Button -->
    <a href="{{ route('home') }}" class="home-button"><i class="fas fa-home"></i></a>

    <div class="profile-container">
        <!-- Header Section -->
        <div class="profile-header">
            <div class="profile-info">
                <!-- Replace Profile Picture with System Logo -->
                <img 
                    src="{{ asset('images/systemLogo.png') }}" 
                    alt="System Logo" 
                    class="system-logo" 
                    id="system-logo">
                <div class="name-section">
                    <h1 id="username">{{ strtoupper($user->first_name) }} {{ strtoupper($user->last_name) }}</h1>
                    <p id="employee-id">{{ $user->employee_id }}</p>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="profile-details">
            <h3>About</h3>
            <form id="profile-form">
                <div class="info-item">
                    <label for="firstname">First Name:</label>
                    <input type="text" id="firstname" placeholder="Enter First Name" value="{{ $user->first_name }}" disabled>
                </div>
                <div class="info-item">
                    <label for="lastname">Last Name:</label>
                    <input type="text" id="lastname" placeholder="Enter Last Name" value="{{ $user->last_name }}" disabled>
                </div>
                <div class="info-item">
                    <label for="department">Department:</label>
                    <input type="text" id="department" placeholder="Enter Department" value="{{ $user->department }}" disabled>
                </div>
                <div class="info-item">
                    <label for="role">Account Role:</label>
                    <input type="text" id="role" placeholder="Enter Account Role" value="{{ ucwords(str_replace('_', ' ', $role)) }}" disabled>
                </div>
                <div class="info-item">
                    <label for="phone">Phone Number:</label>
                    <input type="text" id="phone" placeholder="Enter Phone Number" value="{{ $user->phone_number }}" disabled>
                </div>
                <div class="info-item">
                    <label for="email">Email:</label>
                    <input type="email" id="email" value="{{ $user->email }}" disabled>
                    @if(!$user->hasVerifiedEmail())
                        <p style="color: red;">Your email is not verified.</p>
                        <button type="button" class="save-button" id="trigger-modal-btn">Resend Verification Email</button>
                    @else
                        <p style="color: green;">Email Verified</p>
                    @endif
                </div>
            </form>
            
            <form id="verification-form" method="POST" action="{{ route('verification.send') }}" style="display: none;">
                @csrf
            </form>

            <div id="verification-modal" class="modal" style="display: none;">
                <div class="modal-content">
                    <h4>Email Verification</h4>
                    <p>A verification email has been sent to your email address. Please check your inbox.</p>
                    <button id="close-modal" class="close-btn">Close</button>
                </div>
            </div>
            
            <button type="button" class="save-button" id="edit-save-btn">Edit</button>
        </div>
    </div>
</body>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const triggerBtn = document.getElementById("trigger-modal-btn");
    const modal = document.getElementById("verification-modal");
    const closeModal = document.getElementById("close-modal");
    const verificationForm = document.getElementById("verification-form");

    if (triggerBtn && verificationForm) {
        triggerBtn.addEventListener("click", function() {
            modal.style.display = "block";
            verificationForm.submit();
        });
    }

    if (closeModal) {
        closeModal.addEventListener("click", function() {
            modal.style.display = "none";
        });
    }

    window.addEventListener("click", function(event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });
});
</script>
<script src="{{ asset('js/profile.js') }}"></script>

</html>
