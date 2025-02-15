// Slideshow and Button Functionality
let slideIndex = 0;
const slides = document.getElementsByClassName("slide");
const dots = document.getElementsByClassName("dot");
const prev = document.querySelector(".prev");
const next = document.querySelector(".next");
let slideInterval;  // To store the interval
let isPaused = false;  // To check if slideshow is paused

function showSlides(n) {
  if (n >= slides.length) slideIndex = 0;
  if (n < 0) slideIndex = slides.length - 1;

  for (let i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
  }

  for (let i = 0; i < dots.length; i++) {
    dots[i].className = dots[i].className.replace(" active", "");
  }

  slides[slideIndex].style.display = "block";
  dots[slideIndex].className += " active";
}

function nextSlide() {
  slideIndex++;
  showSlides(slideIndex);
}

function prevSlide() {
  slideIndex--;
  showSlides(slideIndex);
}

function currentSlide(n) {
  slideIndex = n;
  showSlides(slideIndex);
}

// Start the auto slide
function startAutoSlide() {
  slideInterval = setInterval(nextSlide, 4000); // Change slide every 5 seconds
}

// Stop the auto slide
function stopAutoSlide() {
  clearInterval(slideInterval);
}

// Pause for 1 minute after manual navigation
function pauseAutoSlide() {
  if (!isPaused) {
    stopAutoSlide();
    isPaused = true;
    setTimeout(() => {
      isPaused = false;
      startAutoSlide();
    }, 60000); // Pause for 60 seconds
  }
}

// Event Listeners for buttons with pause
next.addEventListener("click", () => {
  nextSlide();
  pauseAutoSlide();
});

prev.addEventListener("click", () => {
  prevSlide();
  pauseAutoSlide();
});

// Dots functionality
for (let i = 0; i < dots.length; i++) {
  dots[i].addEventListener("click", function () {
    currentSlide(i);
    pauseAutoSlide();
  });
}

// Initialize the first slide and start auto-slide
showSlides(slideIndex);
startAutoSlide();

// Open and Hide Eye Function
const togglePassword = document.getElementById('togglePassword');
const passwordField = document.getElementById('password');

if (togglePassword && passwordField) {
  togglePassword.addEventListener('click', function () {
    const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordField.setAttribute('type', type);

    // Toggle eye and eye-slash icons
    this.classList.toggle('fa-eye');
    this.classList.toggle('fa-eye-slash');
  });
}
