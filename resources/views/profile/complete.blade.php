<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Your Profile</title>
    <link rel="icon" href="{{ asset('images/Systembrowserlogo.png') }}" type="image/png">
    <style>
        body {
            display: flex;
            flex-direction: column; /* Stack items vertically */
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-image: url('../images/leftscreenbg2.png'); /* Your global background image */
            background-size: auto; /* Keep the image in its original size */
            background-position: bottom center; /* Position the image at the bottom */
            background-attachment: fixed; /* Keep the background fixed while scrolling */
            font-family: Arial, sans-serif;
            color: #003067;
            text-align: center;
        }

        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px; /* Space between logos */
            margin-bottom: 20px; /* Add spacing below logo */
        }

        .logo {
            width: 200px; /* Adjust logo size */
            height: auto;
            object-fit: contain;
        }

        .pasig-logo img {
            width: 140px;
            height: auto;
            object-fit: contain;
        }

        .login-container {
            width: 100%;
            max-width: 350px;
            background-color: #fff;
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            border: 2px solid transparent;
            background-image: linear-gradient(#fff, #fff),
            linear-gradient(to right, #118df1, #2575fc);
            background-origin: border-box;
            background-clip: padding-box, border-box;
        }

        .login-container:hover {
            transform: scale(1.05);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
            background-image: linear-gradient(#f9f9f9, #f9f9f9),
            linear-gradient(to right, #0e2f66, #2575fc);
        }

        h2 {
            margin-bottom: 15px;
            font-size: 24px;
            font-weight: 700;
            color: #333;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left; /* Align labels to the left */
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input, select {
            width: 95%;
            padding: 10px;
            border: 1px solid #003067;
            border-radius: 5px;
        }

        .department-select{
            width: 100%;
            padding: 10px;
            border: 1px solid #003067;
            border-radius: 5px;
        }

        button {
            background-color: rgb(255, 255, 255);
            color: #003067;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
        }

        button:hover {
            background-color: #0055a4;
            color: white;
            transform: scale(1.05);
        }
          
        .alert-box {
          padding: 10px;
          border-radius: 5px;
          margin-bottom: 10px;
          position: relative;
        }

        .alert-box.error {
          background-color: #f8d7da;
          border: 1px solid #f5c6cb;
          color: #721c24;
          font-size: 12px;
        }

        .alert-box.success {
          background-color: #d4edda;
          border: 1px solid #c3e6cb;
          color: #155724;
          font-size: 12px;
        }
        .alert-box.message {
          background-color: #d4edda;
          border: 1px solid #c3e6cb;
          color: #155724;
          font-size: 12px;
        }

        .alert-box button {
          background: none;
          border: none;
          font-size: 16px;
          position: absolute;
          right: 10px;
          top: 0px;
          cursor: pointer;
        }
    </style>
</head>
<body>

<!-- Logo Positioned Outside of Login Container -->
<div class="logo-container">
    <img src="{{ asset('images/systemLogo.png') }}" alt="Logo" class="logo">
    <div class="pasig-logo">
        <img src="{{ asset('images/pasiglogo.png') }}" alt="Pasig Logo">
    </div>
</div>

<!-- Login Container -->
<div class="login-container">
    <h2>Complete Your Profile</h2>
    <form action="{{ route('profile.complete') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label for="first-name">First Name</label>
            <input type="text" id="first-name" name="first-name" placeholder="First Name" value="{{ old('first-name') }}" required oninput="this.value = this.value.replace(/\b\w/g, function(char) { return char.toUpperCase(); });">
        </div>

        <div class="form-group">
            <label for="last-name">Last Name</label>
            <input type="text" id="last-name" name="last-name" placeholder="Last Name" value="{{ old('last-name') }}" required oninput="this.value = this.value.replace(/\b\w/g, function(char) { return char.toUpperCase(); });">
        </div>

        <div class="form-group">
            <label for="department">Department</label>
            <select id="department" name="department" class="department-select" required></select>
        </div>

        <div class="form-group">
            <label for="phone-number">Phone Number</label>
            <input type="text" id="phone-number" name="phone-number" placeholder="Enter phone number" 
                value="{{ old('phone-number') }}" required oninput="validatePhoneNumber(this)">
            <small id="phone-error" style="color: red; display: none;">Invalid phone number</small>
        </div>
        

        <button type="submit" id="submit-btn">Complete Profile</button>
    </form>
</div>

<script>
// Function to fetch departments and populate the dropdown
async function populateDepartments() {
    try {
        // Fetch departments from the API
        const response = await fetch('/departments');
        if (!response.ok) throw new Error('Failed to fetch departments');
        const departments = await response.json();

        // Get all select elements with the class 'department-select'
        const selectElements = document.querySelectorAll('.department-select');

        // Loop through each select element
        selectElements.forEach(select => {
            // Clear any existing options
            select.innerHTML = '';

            // Add the default option
            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.text = 'Select Department';
            select.appendChild(defaultOption);

            // Loop through the grouped departments
            for (const [groupName, groupDepartments] of Object.entries(departments)) {
                // Create an optgroup element
                const optgroup = document.createElement('optgroup');
                optgroup.label = groupName;

                // Loop through the departments in the group
                groupDepartments.forEach(department => {
                    // Create an option element
                    const option = document.createElement('option');
                    option.value = department.name;
                    option.text = department.name;

                    // Preselect the user's department (if applicable)
                    if (department.name === "{{ Auth::user()->department }}") {
                        option.selected = true;
                    }

                    // Append the option to the optgroup
                    optgroup.appendChild(option);
                });

                // Append the optgroup to the select element
                select.appendChild(optgroup);
            }
        });
    } catch (error) {
        console.error('Error fetching departments:', error);
        // Display a user-friendly error message in all select elements
        document.querySelectorAll('.department-select').forEach(select => {
            select.innerHTML = '<option value="">Failed to load departments. Please try again later.</option>';
        });
    }
}

// Call the function to populate the dropdown when the page loads
document.addEventListener('DOMContentLoaded', populateDepartments);
function validatePhoneNumber(input) {
    let phoneError = document.getElementById('phone-error');
    let phoneRegex = /^[0-9\s\-()]{10,15}$/; // Allows digits, spaces, dashes, and parentheses

    if (phoneRegex.test(input.value)) {
        phoneError.style.display = 'none';
        input.setCustomValidity('');
    } else {
        phoneError.style.display = 'block';
        input.setCustomValidity('Invalid phone number');
    }
}

    document.addEventListener("DOMContentLoaded", function () {
        // Show Laravel validation errors
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                showAlert("error", "{{ $error }}");
            @endforeach
        @endif

        // Show success message for email verification or account creation
        @if (session('success'))
            showAlert("success", "{{ session('success') }}");
        @endif

        // Show any other general messages
        @if (session('message'))
            showAlert("success", "{{ session('message') }}");
        @endif
    });

    function showAlert(type, message) {
        document.querySelectorAll(".alert-box").forEach(alert => alert.remove());

        const alertBox = document.createElement("div");
        alertBox.classList.add("alert-box", type === "success" ? "success" : "error");
        alertBox.innerHTML = `
            <strong>${type === "success" ? "Success!" : "Error!"}</strong>
            <span>${message}</span>
            <button type="button" onclick="this.parentElement.remove();">&times;</button>
        `;

        document.querySelector(".login-container").prepend(alertBox);
    }
</script>

</body>
</html>
