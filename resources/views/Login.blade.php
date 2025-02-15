<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>TechTrack</title>
    <link rel="icon" href="{{ asset('images/Systembrowserlogo.png') }}" type="image/png">
  <link rel="stylesheet" href="{{ asset('css/Login_Style.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
</head>
<body>
  <div class="split-screen">
    <!-- Left Screen -->
    <div class="left-screen">
      <!-- Logo Container -->
      <div class="logo-container">
        <img src="{{ asset('images/systemLogo.png') }}" alt="Logo" class="logo">
        <div class="pasig-logo">
          <img src="{{ asset('images/pasiglogo.png') }}" alt="Pasig Logo">
        </div>
      </div>
      <div class="login-container">
        <h2>Login</h2>

        <form action="{{ route('login.authenticate') }}" method="POST">
          @csrf <!-- CSRF token for protection -->
          <label for="username">Username</label>
          <input type="text" name="username" id="username" placeholder="Enter your Username" required>

          <label for="password">Password</label>
          <div class="password-container">
              <input type="password" name="password" id="password" placeholder="Enter your password" required>
              <i class="fas fa-eye toggle-password" id="togglePassword"></i>
          </div>

          <button type="submit" class="login-btn">Login</button>
          
      </form>
      <a href="{{ route('password.request') }}" class="forgot-password">Forgot Password?</a>
        <div class="create-account">
          <span>Don't have an account?</span>
          <a href="/signup">Create an account</a> <!-- Link to Sign Up page -->
        </div>
      </div>
    </div>
    
    <!-- Right Screen (Slideshow Gallery) -->
    <div class="right-screen">
      <div class="slideshow-container">
        <img src="{{ asset('images/slide1.jpg') }}" class="slide" alt="Slide 1">
        <img src="{{ asset('images/slide2.png') }}" class="slide" alt="Slide 2">
        <img src="{{ asset('images/technicalsupport.png') }}" class="slide" alt="Slide 3">
        <img src="{{ asset('images/PasigMunicipal.jpg') }}" class="slide" alt="Slide 4">

        <!-- Navigation buttons -->
        <a class="prev">&#10094;</a>
        <a class="next">&#10095;</a>

        <!-- Dots -->
        <div class="dot-container">
          <span class="dot"></span>
          <span class="dot"></span>
          <span class="dot"></span>
          <span class="dot"></span>
        </div>
      </div>
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

<script src="{{ asset('js/Login_Script.js') }}"> </script>
<script>
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

    // Append to the login container
    document.querySelector(".login-container").prepend(alertBox);
}

</script>
</body>
</html>
