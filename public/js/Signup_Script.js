document.addEventListener("DOMContentLoaded", function () {
  const form = document.querySelector("form");
  const email = document.getElementById("email");
  const password = document.getElementById("password");
  const passwordConfirmation = document.getElementById("password_confirmation");

  if (!form || !email || !password || !passwordConfirmation) {
    console.error("Required form elements not found!");
    return;
  }

  // Live validation for email
  email.addEventListener("input", function () {
    checkEmail(email);
  });

  // Live validation for password
  password.addEventListener("input", () => {
    checkPassword(password);
    updatePasswordStrength(password);
  });

  passwordConfirmation.addEventListener("input", () => {
    if (password.value !== passwordConfirmation.value) {
      displayErrorPassword(passwordConfirmation, "Passwords do not match");
    } else {
      clearError(passwordConfirmation);
    }
  });

  // Form validation on submit
  form.addEventListener("submit", function (event) {
    let isValid = true;

    isValid = checkRequired([email, password, passwordConfirmation]) && isValid;
    isValid = checkEmail(email) && isValid;
    isValid = checkPassword(password) && isValid;

    // Check if passwords match
    if (password.value !== passwordConfirmation.value) {
      displayError(passwordConfirmation, "Passwords do not match");
      isValid = false;
    } else {
      clearError(passwordConfirmation);
    }

    if (!isValid) {
      event.preventDefault();
    }
  });

  // Function to check required fields
  function checkRequired(inputs) {
    let isValid = true;
    inputs.forEach((input) => {
      if (input.value.trim() === "") {
        displayError(input, `${input.placeholder} is required`);
        isValid = false;
      } else {
        clearError(input);
      }
    });
    return isValid;
  }

  // Function to validate email format
  function checkEmail(input) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(input.value.trim())) {
      displayError(input, "Invalid email address");
      return false;
    }
    clearError(input);
    return true;
  }

  // Function to validate password complexity
  function checkPassword(input) {
    const passwordRegex = /^(?=.*[a-zA-Z])(?=.*\d|.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,}$/;
    if (!passwordRegex.test(input.value)) {
      displayErrorPassword(
        input,
        "Password must be at least 6 characters and include at least one letter, one number, or one special character."
      );
      return false;
    }
    clearError(input);
    return true;
  }

  // Function to update password strength meter
  function updatePasswordStrength(input) {
    let strengthMeter = document.getElementById("password-strength");
    if (!strengthMeter) {
      strengthMeter = document.createElement("small");
      strengthMeter.id = "password-strength";
      strengthMeter.style.display = "block";
      strengthMeter.style.marginTop = "5px";
      strengthMeter.style.fontWeight = "bold";
      password.closest(".form-group-row").before(strengthMeter);
    }

    const passwordValue = input.value;
    const lengthCriteria = passwordValue.length >= 8;
    const hasUpperCase = /[A-Z]/.test(passwordValue);
    const hasLowerCase = /[a-z]/.test(passwordValue);
    const hasDigits = /\d/.test(passwordValue);
    const hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/.test(passwordValue);

    if (passwordValue.length < 6) {
      strengthMeter.textContent = "Weak";
      strengthMeter.style.color = "red";
    } else if (
      !lengthCriteria ||
      !hasUpperCase ||
      !hasLowerCase ||
      !hasDigits ||
      !hasSpecialChar
    ) {
      strengthMeter.textContent = "Medium";
      strengthMeter.style.color = "orange";
    } else {
      strengthMeter.textContent = "Strong";
      strengthMeter.style.color = "green";
    }
  }

  // Password visibility toggle
  window.togglePasswordVisibility = function (inputId) {
    const passwordInput = document.getElementById(inputId);
    const eyeIcon = document.querySelector(`#toggle-${inputId} i`);
    if (passwordInput.type === "password") {
      passwordInput.type = "text";
      eyeIcon.classList.remove("fa-eye");
      eyeIcon.classList.add("fa-eye-slash");
    } else {
      passwordInput.type = "password";
      eyeIcon.classList.remove("fa-eye-slash");
      eyeIcon.classList.add("fa-eye");
    }
  };

  // Modal elements
  const modal = document.getElementById("privacy-policy-modal");
  const openModal = document.getElementById("privacy-policy-link");
  const closeModal = document.querySelector(".close");

  if (modal && openModal && closeModal) {
    // Open modal
    openModal.addEventListener("click", function (event) {
      event.preventDefault();
      modal.style.display = "block";
    });

    // Close modal
    closeModal.addEventListener("click", function () {
      modal.style.display = "none";
    });

    // Close modal when clicking outside content
    window.addEventListener("click", function (event) {
      if (event.target === modal) {
        modal.style.display = "none";
      }
    });
  }

  // Function to display error messages OUTSIDE input field
  function displayError(input, message) {
    let errorElement = document.getElementById(`${input.id}-error`);
    if (!errorElement) {
      errorElement = document.createElement("small");
      errorElement.id = `${input.id}-error`;
      errorElement.style.color = "red";
      errorElement.style.display = "block";
      errorElement.style.marginBottom = "10px";
      input.closest(".form-group").after(errorElement);
    }
    errorElement.textContent = message;
  }

  // Function to display error messages OUTSIDE input field
  function displayErrorPassword(input, message) {
    let errorElement = document.getElementById(`${input.id}-error`);
    if (!errorElement) {
      errorElement = document.createElement("small");
      errorElement.id = `${input.id}-error`;
      errorElement.style.color = "red";
      errorElement.style.display = "block";
      input.closest(".input-wrapper").after(errorElement);
    }
    errorElement.textContent = message;
  }
  // Function to clear error messages
  function clearError(input) {
    let errorElement = document.getElementById(`${input.id}-error`);
    if (errorElement) {
      errorElement.remove();
    }
  }  
});
