<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up</title>
  <link rel="stylesheet" href="{{ asset('css/Signup_Style.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
</head>
<body>

  <div class="logo-container">
    <img src="images/ELECTRONIC SERVICE QUEUING MANAGEMENT SYSTEM logo2.png" alt="Logo" class="logo">
    <div class="pasig-logo">
      <img src="images/pasiglogo.png" alt="Pasig Logo">
    </div>
  </div>

  <div class="container">
    <div class="sign-up-container">
      <h2>Create Account</h2>

      <!-- Display Validation Errors -->
      @if ($errors->any())
        <div class="error-messages">
          <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('signup.store') }}" method="POST">
        @csrf

        <div class="form-group">
          <label for="username">Username</label>
          <input type="text" id="username" name="username" placeholder="Username" value="{{ old('username') }}" required>
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
              <span id="toggle-password" class="eye-icon" onclick="togglePasswordVisibility('password')">
                <i class="fas fa-eye"></i> <!-- Eye icon -->
              </span>
            </div>
          </div>
          <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <div class="input-wrapper">
              <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" required>
              <!-- FontAwesome Eye Icon for visibility toggle -->
              <span id="toggle-password-confirm" class="eye-icon" onclick="togglePasswordVisibility('password_confirmation')">
                <i class="fas fa-eye"></i> <!-- Eye icon -->
              </span>
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
        <button type="submit" class="sign-up-btn">Sign Up</button>

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
    <p>&copy; 2025 Electronic Service Queuing Management System. All Rights Reserved.</p>
    
    <div class="contact-info">
      <p><i class="fas fa-phone-alt"></i> Phone: +63 912 345 6789</p>
      <p><i class="fas fa-envelope"></i> Email: <a href="mailto:support@esqms.ph">support@esqms.ph</a></p>
      <p><i class="fas fa-globe"></i> Website: <a href="https://www.esqms.ph" target="_blank">www.esqms.ph</a></p>
    </div>

    <div class="social-icons">
      <a href="https://www.facebook.com" target="_blank"><i class="fab fa-facebook"></i></a>
      <a href="https://www.twitter.com" target="_blank"><i class="fab fa-twitter"></i></a>
      <a href="https://www.instagram.com" target="_blank"><i class="fab fa-instagram"></i></a>
      <a href="https://www.linkedin.com" target="_blank"><i class="fab fa-linkedin"></i></a>
    </div>
  </div>
</footer>

<div id="privacy-policy-modal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h2>Privacy Policy</h2>
    <p>This Privacy Policy describes how Pasig City Hall's Management Information Systems Office (MISO) collects, uses, discloses, and protects your personal information when you use the E-PIS (Enhanced Pasig Inventory System) and related services...</p>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script src="{{ asset('js/Signup_Script.js') }}"></script>
</body>
</html>
