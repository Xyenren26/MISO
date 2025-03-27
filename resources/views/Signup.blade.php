<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TechTrack Signup</title>
  <link rel="icon" href="{{ asset('images/Systembrowserlogo.png') }}" type="image/png">
  <link rel="stylesheet" href="{{ asset('css/Signup_Style.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
</head>
<body>

  <div class="logo-container">
    <img src="images/SystemLogo.png" alt="Logo" class="logo">
    <div class="pasig-logo">
      <img src="images/pasiglogo.png" alt="Pasig Logo">
    </div>
  </div>

  <div class="container">
    <div class="sign-up-container">
      <h2>Create Account</h2>
      <form action="{{ route('signup.store') }}" method="POST">
        @csrf

        <div class="form-group">
          <label for="employee-id">Employee ID</label>
          <input type="text" id="employee-id" name="employee-id" placeholder="Employee ID" value="{{ old('employee-id') }}" maxlength="7" required>      
          <span id="error-message" style="color: red; display: none;">Please enter a valid 7-digit Employee ID. Only numbers are allowed.</span>
        </div>

        <!-- Email -->
        <div class="form-group">
          <label for="email">Email Address</label>
          <input type="email" id="email" name="email" placeholder="Email Address" value="{{ old('email') }}" required>
        </div>

        <!-- Password and Confirm Password in a Row -->
        <div class="form-group-row">
            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    <input type="password" id="password" name="password" placeholder="Password" required>
                    <!-- FontAwesome Eye Icon for visibility toggle -->
                    <i id="toggle-password" class="fa-solid fa-eye toggle-password" onclick="togglePasswordVisibility('password', 'toggle-password')"></i>
                </div>
            </div>
            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <div class="input-wrapper">
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" required>
                    <!-- FontAwesome Eye Icon for visibility toggle -->
                    <i id="toggle-password-confirm" class="fa-solid fa-eye toggle-password" onclick="togglePasswordVisibility('password_confirmation', 'toggle-password-confirm')"></i>
                </div>
            </div>
        </div>


         <!-- Privacy Policy -->
         <div class="form-group checkbox-group">
          <label for="privacy-policy">
            By clicking Sign Up, you agree to our <a href="#" id="privacy-policy-link">Privacy Policy</a>.
          </label>
        </div>

        <!-- Sign Up Button -->
        <button type="submit" id="submit-btn" class="sign-up-btn">Sign Up</button>

        <!-- Existing Account -->
        <div class="existing-account">
          <span>Already have an account?</span> <a href="/login">Sign In</a>
        </div>
      </form>
    </div>
  </div>
  
<!-- Footer -->
<footer class="footer">
    <div class="footer-content">
      <p>&copy; TechTrack: An Electronic Service Monitoring and Management
      System.</p>
      
      <div class="contact-info">
        <p><i class="fas fa-phone-alt"></i> Phone: +63 912 345 6789</p>
        <p><i class="fas fa-envelope"></i> Email: <a href="mailto:support@esqms.ph">support@techtrack.com</a></p>
        <p><i class="fas fa-globe"></i> Website: <a href="https://www.techtrack.com" target="_blank">www.techtrack.com</a></p>
      </div>

      <div class="social-icons">
        <a href="https://www.facebook.com/PasigPIO" target="_blank"><i class="fab fa-facebook"></i></a>
        <a href="https://twitter.com/PasigInfo" target="_blank"><i class="fab fa-twitter"></i></a>
        <a href="https://www.instagram.com/pasigpio" target="_blank"><i class="fab fa-instagram"></i></a>
      </div>
    </div>
  </footer>

  <div id="privacy-policy-modal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h2>Privacy Policy</h2>
      <p>
        This Privacy Policy describes how Pasig City Hall's Management Information Systems Office (MISO) collects, uses, discloses, and protects your personal information when you use the TechTrack: An Electronic Service Monitoring and Management System and related services. We are devoted to protecting your privacy and handling your personal information responsibly.
      </p>

      <h3>1. Data Collection Methods</h3>
      <p>
        MISO gathers personal information that you submit while using our services, as well as data about how you access and interact with the platform. This data is collected through forms, user activities, and automated techniques such as cookies and server logs. The information gathered is utilized to improve platform functionality, user experience, and ensure effective service delivery and support.
      </p>

      <h3>2. Personal Data Gathering</h3>
      <p>
        The collection of personal information occurs when you register or use our services. This information may include your name, email address, phone number, employee ID, and address. The information we gather allows us to verify your identity, manage your account, and provide personalized features and support, ensuring a secure and efficient experience on our platform.
      </p>

      <h3>3. Service Usage Analytics</h3>
      <p>
        We collect usage data to better understand how you interact with our services. This includes information on your device's Internet Protocol (IP) address, browser type, operating system, the pages you view, and the date and time of your visit. This information allows us to analyze trends, optimize system operation, and provide a more personalized user experience.
      </p>

      <h3>4. Cookies and Monitoring Technologies</h3>
      <p>
        We use cookies and other tracking technologies to enhance your user experience on our platform. Cookies are small data files stored on your device that help us remember your preferences, understand how you use our site, and improve the performance of our services. You can manage or deactivate cookies in your browser settings, but this may limit certain functionalities.
      </p>

      <h3>5. Information Usage Practices</h3>
      <p>
        The data we collect is used to provide, maintain, and enhance our services. This includes addressing user requests, managing accounts, and improving system functionality. Additionally, we may use your information to communicate with you, respond to inquiries, provide customer support, and send important service updates or notifications.
      </p>

      <h3>6. Disclosure of Personal Data</h3>
      <p>
        We prioritize your privacy and are committed to safeguarding your information. We may disclose your data in specific situations, including:
        <ul>
          <li>With service providers who assist in system operations.</li>
          <li>To comply with legal obligations.</li>
          <li>During business transfers such as mergers or acquisitions.</li>
          <li>With your explicit consent.</li>
        </ul>
      </p>

      <h3>7. Data Privacy Protection</h3>
      <p>
        We implement security measures such as encryption, firewalls, and regular security audits to protect your personal information. However, despite our efforts, we cannot guarantee absolute security due to inherent risks associated with data transmission and storage.
      </p>

      <h3>8. Personal Data Rights</h3>
      <p>
        As a user, you have certain rights regarding your personal information, including the right to:
        <ul>
          <li>Access, modify, or delete your personal data.</li>
          <li>Restrict or object to data processing.</li>
          <li>File a complaint with the appropriate data protection authorities if you believe your rights have been violated.</li>
        </ul>
      </p>

      <h3>9. Changes to This Privacy Policy</h3>
      <p>
        We may update our Privacy Policy from time to time. We encourage you to review this Privacy Policy periodically for any changes.
      </p>

      <h3>10. Contact Us</h3>
      <p>
        If you have any questions about this Privacy Policy, please contact us:
        <ul>
          <li>Email: <a href="mailto:pasigmiso@pasig.city.com">pasigmiso@pasig.city.com</a></li>
          <li>Phone: (Tel) 8343-11 / Local 1313</li>
          <li>Main City Hall: Caruncho Avenue, Pasig City</li>
          <li>Temporary City Hall (for some offices): Eulogio Amang Rodriguez Avenue in Barangay Rosario
          Pasig City Hall Annex: 3341 Kaginhawaan, Pasig</li>
        </ul>
      </p>
    </div>
</div>

<script src="{{ asset('js/Signup_Script.js') }}"></script>
</body>
</html>
<script>
    function showAlert(type, message) {
        // Remove existing alerts
        document.querySelectorAll(".alert-box").forEach(alert => alert.remove());

        const alertBox = document.createElement("div");
        alertBox.classList.add("alert-box", "px-4", "py-3", "rounded-lg", "relative", "mb-4");

        if (type === "success") {
            alertBox.classList.add("success");
        } else {
            alertBox.classList.add("error");
        }


        alertBox.innerHTML = `
            <strong class="font-bold">${type === "success" ? "Success!" : "Error!"}</strong>
            <span class="block sm:inline">${message}</span>
            <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.remove();">
                &times;
            </button>
        `;

        // Append to a valid container
        document.querySelector(".sign-up-container").prepend(alertBox);

    }

    // Run showAlert for Laravel validation errors
    document.addEventListener("DOMContentLoaded", function () {
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                showAlert("error", "{{ $error }}");
            @endforeach
        @endif
    });
</script>

