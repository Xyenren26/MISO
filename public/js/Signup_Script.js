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

// Check if privacy policy is agreed
function checkPrivacyPolicy(input) {
  if (!input.checked) {
    displayError(input, 'You must agree to the Privacy Policy');
    return false;
  }
  clearError(input);
  return true;
}

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

// Form submit event
form.addEventListener('submit', (e) => {
  e.preventDefault();

  const isFormValid =
    checkRequired([firstName, lastName, department, employeeId, email, password]) &&
    checkEmail(email) &&
    checkPassword(password) &&
    checkPrivacyPolicy(privacyPolicy);

  if (isFormValid) {
    // Submit the form data
    alert('Account created successfully!');
    form.reset();
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
