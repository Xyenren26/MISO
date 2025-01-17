// Sign-Up Form Validation
const form = document.querySelector('form');
const firstName = document.getElementById('first-name');
const lastName = document.getElementById('last-name');
const department = document.getElementById('department');
const employeeId = document.getElementById('employee-id');
const email = document.getElementById('email');
const password = document.getElementById('password');
const privacyPolicy = document.getElementById('privacy-policy');

// Error message display function
function displayError(input, message) {
  const formGroup = input.parentElement;
  formGroup.classList.add('error');
  const errorElement = formGroup.querySelector('small');
  if (errorElement) {
    errorElement.textContent = message;
  } else {
    const small = document.createElement('small');
    small.textContent = message;
    formGroup.appendChild(small);
  }
}

// Clear error messages
function clearError(input) {
  const formGroup = input.parentElement;
  formGroup.classList.remove('error');
  const errorElement = formGroup.querySelector('small');
  if (errorElement) {
    errorElement.textContent = '';
  }
}

// Play beep sound using Web Audio API
function playSound() {
  const audioContext = new (window.AudioContext || window.webkitAudioContext)();
  const oscillator = audioContext.createOscillator();
  oscillator.type = 'sine'; // Sound type: sine wave
  oscillator.frequency.setValueAtTime(440, audioContext.currentTime); // Frequency in Hz (440Hz = A4)
  oscillator.connect(audioContext.destination);
  oscillator.start();
  setTimeout(() => oscillator.stop(), 200); // Play sound for 200ms
}

// Check if input is empty
function checkRequired(inputArray) {
  let isValid = true;
  inputArray.forEach((input) => {
    if (input.value.trim() === '') {
      displayError(input, `${input.placeholder} is required`);
      isValid = false;
    } else {
      clearError(input);
    }
  });
  return isValid;
}

// Check email format
function checkEmail(input) {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailRegex.test(input.value.trim())) {
    displayError(input, 'Invalid email address');
    return false;
  }
  clearError(input);
  return true;
}

// Check password complexity
function checkPassword(input) {
  const passwordRegex = /^(?=.*[a-zA-Z])(?=.*\d|.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,}$/; 
  // Password must be at least 6 characters long, and include at least one letter, one number or one special character.
  if (!passwordRegex.test(input.value)) {
    displayError(
      input,
      'Password must be at least 6 characters long and include at least one letter, one number or one special character (@$!%*?&).'
    );
    return false;
  }
  clearError(input);
  return true;
}

document.addEventListener('DOMContentLoaded', function() {
  const employeeIDInput = document.getElementById('employee-id');
  const errorMessage = document.getElementById('error-message');

  employeeIDInput.addEventListener('input', function() {
      // Remove any non-numeric characters as the user types
      employeeIDInput.value = employeeIDInput.value.replace(/[^0-9]/g, '');

      const employeeIDValue = employeeIDInput.value;

      // Check if the value is exactly a 7-digit number
      const isValid = /^\d{7}$/.test(employeeIDValue);

      if (!isValid) {
          errorMessage.style.display = 'inline';  // Show error message
          employeeIDInput.style.borderColor = 'red';  // Optional: change border color to red
      } else {
          errorMessage.style.display = 'none';  // Hide error message
          employeeIDInput.style.borderColor = 'green';  // Optional: change border color to green
      }
  });

  // Optional: Add a submit handler for the form to ensure validation before submitting
  document.querySelector('form').addEventListener('submit', function(e) {
      const employeeIDValue = employeeIDInput.value;
      if (!/^\d{7}$/.test(employeeIDValue)) {
          e.preventDefault();  // Prevent form submission
          errorMessage.style.display = 'inline';  // Show error message if not valid
      }
  });
});


// Suggest password strength dynamically
password.addEventListener('input', () => {
  const passwordStrength = document.getElementById('password-strength');
  if (!passwordStrength) {
    const strengthMeter = document.createElement('small');
    strengthMeter.id = 'password-strength';
    password.parentElement.appendChild(strengthMeter);
  }
  const strengthMeter = document.getElementById('password-strength');
  if (password.value.length < 6) {
    strengthMeter.textContent = 'Weak';
    strengthMeter.style.color = 'red';
  } else if (password.value.length < 8) {
    strengthMeter.textContent = 'Medium';
    strengthMeter.style.color = 'orange';
  } else {
    strengthMeter.textContent = 'Strong';
    strengthMeter.style.color = 'green';
  }
});


// Get modal elements
const modal = document.getElementById("privacy-policy-modal");
const openModal = document.getElementById("privacy-policy-link");
const closeModal = document.querySelector(".close");

// Open the modal when clicking the link
openModal.addEventListener("click", function (event) {
  event.preventDefault(); // Prevent default link behavior
  modal.style.display = "block";
});

// Close the modal when clicking the "x"
closeModal.addEventListener("click", function () {
  modal.style.display = "none";
});

// Close the modal when clicking outside the modal content
window.addEventListener("click", function (event) {
  if (event.target === modal) {
    modal.style.display = "none";
  }
});
