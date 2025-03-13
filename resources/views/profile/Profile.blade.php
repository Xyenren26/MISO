<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Profile Details</title>
    <link rel="icon" href="{{ asset('images/Systembrowserlogo.png') }}" type="image/png">
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
            zoom: 0.8; /* 80% zoom */
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
            position: relative; /* Allows absolute positioning inside */
            margin-bottom: 15px;
        }

        .info-item label {
            display: block;
            font-size: 14px;
            color: #555555;
            margin-bottom: 5px;
        }
        .department-select{
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #cccccc;
            border-radius: 5px;
            background-color: #f9f9f9;
            color: #333333;
            padding-right: 120px; /* Make space for the text inside */
        }
        .info-item input {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #cccccc;
            border-radius: 5px;
            background-color: #f9f9f9;
            color: #333333;
            padding-right: 120px; /* Make space for the text inside */
        }

        .info-item input:disabled {
            background-color: #e6e6e6;
        }

        /* Resend text inside input container */
        .resend-button {
            position: absolute;
            right: 10px; /* Position to the right inside the input */
            top: 50%;
            border:none;
            transform: translateY(-50%); /* Align vertically */
            color: blue;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            white-space: nowrap;
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

        .modal-verification {
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
        }

        .email-verification-modal {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 400px;
            margin: auto;
        }

        .email-verification-modal h4 {
            color: #333;
            font-size: 20px;
            margin-bottom: 10px;
        }

        .email-verification-modal p {
            color: #666;
            font-size: 16px;
            margin-bottom: 20px;
        }

        .email-verification-modal .close-btn {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .email-verification-modal .close-btn:hover {
            background: #0056b3;
        }

        .profile-image {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #003067; /* Stroke color */
            padding: 2px;
            transition: 0.3s ease-in-out;
        }

        .profile-image:hover {
            opacity: 0.8;
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
<!-- Vertical Navigation Menu -->
<div class="vertical-nav">
    <!-- Home Button -->
    @if(auth()->user()->account_type == 'end_user')
        <a href="{{ route('employee.home') }}" class="nav-button home-button">
            <i class="fas fa-home"></i> <span>Home</span>
        </a>
    @elseif(auth()->user()->account_type == 'technical_support')
        <a href="{{ route('home') }}" class="nav-button home-button">
            <i class="fas fa-home"></i> <span>Home</span>
        </a>
    @elseif(auth()->user()->account_type == 'technical_support_head')
        <a href="{{ route('home') }}" class="nav-button home-button">
            <i class="fas fa-home"></i> <span>Home</span>
        </a>
    @elseif(auth()->user()->account_type == 'administrator')
        <a href="{{ route('home') }}" class="nav-button home-button">
            <i class="fas fa-home"></i> <span>Home</span>
        </a>
    @endif


    <!-- Account Security Button -->
    <a href="{{ route('account.security') }}" class="nav-button security-button">
        <i class="fas fa-user-shield"></i> <span>Account Security</span>
    </a>
</div>

    <div class="profile-container">
        <!-- Header Section -->
        <div class="profile-header">
            <div class="profile-info">
                <!-- Clickable Profile Picture -->
                <label for="profile-upload" class="profile-container">
                    <img 
                        src="{{ Auth::user()->avatar ? asset('storage/users-avatar/'.Auth::user()->avatar) : asset('default-profile.png') }}" 
                        alt="Profile Picture" 
                        class="profile-image" 
                        id="profile-picture">
                    <input type="file" id="profile-upload" accept="image/*" style="display: none;">
                </label>

                <div class="name-section">
                    <h1 id="username">{{ strtoupper($user->first_name) }} {{ strtoupper($user->last_name) }}</h1>
                    <p id="employee-id">{{ str_pad($user->employee_id, 7, '0', STR_PAD_LEFT) }}</p>
                </div>
            </div>
        </div>


        <!-- Main Content -->
        <div class="profile-details">
            <h3>About</h3>
            <form id="profile-form" action="{{ route('profile.update') }}" method="POST">
                @csrf <!-- CSRF Token for protection -->
                <div class="info-item">
                    <label for="firstname">First Name:</label>
                    <input type="text" id="firstname" name="first_name" placeholder="Enter First Name" value="{{ $user->first_name }}" disabled>
                </div>
                <div class="info-item">
                    <label for="lastname">Last Name:</label>
                    <input type="text" id="lastname" name="last_name" placeholder="Enter Last Name" value="{{ $user->last_name }}" disabled>
                </div>
                <div class="info-item" id="department-container">
                    <label for="department">Department:</label>
                    <input type="text" id="current-department" name="department" value="{{ $user->department }}" disabled /> <!-- Non-editable input initially -->
                    <select id="department" name="department" class="department-select" style="display: none;"></select>
                </div>
                <div class="info-item">
                    <label for="role">Account Role:</label>
                    <input type="text" id="role" placeholder="Enter Account Role" value="{{ ucwords(str_replace('_', ' ', $role)) }}" disabled>
                </div>
                <div class="info-item">
                    <label for="phone">Phone Number:</label>
                    <input type="text" id="phone_number" name="phone_number" placeholder="Enter Phone Number" value="{{ $user->phone_number }}" disabled>
                </div>
                <div class="info-item">
                    <label for="email">Email:</label>
                    <input type="email" id="email" value="{{ $user->email }}" disabled>
                    @if(!$user->hasVerifiedEmail())
                        <p style="color: red;">Your email is not verified.</p>
                        <button type="button" class="resend-button" id="trigger-modal-btn">Resend Verification Email</button>
                    @else
                        <p style="color: green;">Email Verified</p>
                    @endif
                </div>
            </div>
            <button type="button" class="save-button" id="edit-save-btn">Edit</button>
            </form>
        </div>
    </div>
    <div id="verification-modal" class="modal" style="display: none;">
    <div class="modal-verification">
        <div class="modal-content email-verification-modal">
            <h4>Email Verification</h4>
            <p>A verification email has been sent to your email address. Please check your inbox.</p>
            <button id="close-modal" class="close-btn">Close</button>
        </div>
    </div>

    <form id="verification-form" method="POST" action="{{ route('verification.send') }}" style="display: none;">
        @csrf
    </form>
</body>
<script>
    window.updateAvatarUrl = "{{ route('profile.updateAvatar') }}";
    window.csrfToken = "{{ csrf_token() }}";
    window.userDepartment = "{{ Auth::user()->department }}";
</script>
<script src="{{ asset('js/profile.js') }}"></script>
</html>
