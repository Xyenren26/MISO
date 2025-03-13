<!-- resources/views/components/greeting-popup.blade.php -->
@props(['accountType'])

@php
    // Format account_type (capitalize and replace underscores with spaces)
    $formattedAccountType = ucwords(str_replace('_', ' ', $accountType));

    // Get greeting based on time of day
    $time = now()->format('H');
    if ($time < 12) {
        $greeting = 'Good Morning';
    } elseif ($time < 18) {
        $greeting = 'Good Afternoon';
    } else {
        $greeting = 'Good Evening';
    }

    // Initialize slideshow data for technical support and end_user
    $slides = [];
    if ($accountType === 'technical_support' || $accountType === 'end_user') {
        $slides = [
            [
                'image' => asset('images/tutorial/slide1.jpg'), // Replace with your image path
                'text' => 'Welcome to our system! Let us guide you through the basics.',
            ],
            [
                'image' => asset('images/tutorial/slide2.jpg'), // Replace with your image path
                'text' => 'Here’s how you can navigate through the dashboard.',
            ],
            [
                'image' => asset('images/tutorial/slide3.jpg'), // Replace with your image path
                'text' => 'Feel free to explore all the features available to you.',
            ],
        ];
    }
@endphp
<style>
    /* Greetings Popup Styling */
    .greetings-popup {
        position: fixed; /* Fixed position to stay in place */
        bottom: 20px; /* Stick to the bottom */
        right: 20px; /* Stick to the right */
        z-index: 1000; /* Ensure it's above other content */
        background-color: #003067; /* Background color */
        color: white; /* Text color */
        padding: 25px; /* Padding */
        border-radius: 15px; /* Rounded corners */
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2); /* Shadow */
        font-family: 'Roboto', sans-serif; /* Font */
        max-width: 350px; /* Maximum width */
        width: 90%; /* Responsive width */
        animation: slideIn 0.5s ease-out; /* Slide-in animation */
        transform: translateX(0); /* Ensure it stays in place */
        transition: all 0.3s ease; /* Smooth transitions */
    }

    /* Slide-in Animation */
    @keyframes slideIn {
        from {
            transform: translateY(100%); /* Start from below */
            opacity: 0; /* Fade in */
        }
        to {
            transform: translateY(0); /* Slide up to position */
            opacity: 1; /* Fully visible */
        }
    }

    /* Responsive Design for Smaller Screens */
    @media screen and (max-width: 768px) {
        .greetings-popup {
            max-width: 300px; /* Slightly smaller width for smaller screens */
            padding: 20px; /* Slightly less padding */
            bottom: 10px; /* Move closer to the bottom */
            right: 10px; /* Move closer to the right */
        }
    }

    @media screen and (max-width: 480px) {
        .greetings-popup {
            max-width: 250px; /* Even smaller width for mobile */
            padding: 15px; /* Less padding */
            bottom: 5px; /* Very close to the bottom */
            right: 5px; /* Very close to the right */
        }
    }
</style>

<div id="greetingPopup" class="greetings-popup">
    <!-- Greeting Section -->
    <div class="greetings-text" style="text-align: center;">
        <h3 style="margin: 0; font-size: 1.5rem; font-weight: bold; color: #ffffff;">{{ $greeting }}, {{ auth()->user()->name }}!</h3>
        <p style="margin: 10px 0 0; font-size: 1rem; color: #e0e0e0;">Welcome to our system as a <strong>{{ $formattedAccountType }}</strong>.</p>
    </div>

    <!-- Slideshow Section (Only for Technical Support and End User) -->
    @if ($accountType === 'technical_support' || $accountType === 'end_user')
        <div id="greetingsSlideshow" class="greetings-slideshow" style="margin-top: 20px;">
            @foreach ($slides as $index => $slide)
                <div class="greetings-slide" style="display: {{ $index === 0 ? 'block' : 'none' }};">
                    <img src="{{ $slide['image'] }}" alt="Slide {{ $index + 1 }}" style="width: 100%; border-radius: 10px; margin-bottom: 10px;">
                    <p style="margin: 10px 0; font-size: 0.9rem; text-align: center; color: #e0e0e0;">{{ $slide['text'] }}</p>
                </div>
            @endforeach
        </div>

        <!-- Buttons (Only for Technical Support and End User) -->
        <div class="greetings-buttons" style="display: flex; justify-content: space-between; margin-top: 20px;">
            <button id="prevGreetingsBtn" class="greetings-prev" style="display: none; background-color: #00509e; color: white; border: none; padding: 8px 16px; border-radius: 5px; cursor: pointer; font-size: 0.9rem; transition: background-color 0.3s ease;">Previous</button>
            <button id="nextGreetingsBtn" class="greetings-next" style="background-color: #00509e; color: white; border: none; padding: 8px 16px; border-radius: 5px; cursor: pointer; font-size: 0.9rem; transition: background-color 0.3s ease;">Next</button>
            <button id="closeGreetingsBtn" class="greetings-close" style="background-color: #ff4d4d; color: white; border: none; padding: 8px 16px; border-radius: 5px; cursor: pointer; font-size: 0.9rem; transition: background-color 0.3s ease;">Close</button>
        </div>
    @endif
</div>

<style>
    /* Animation for the popup */
    @keyframes slideIn {
        from {
            transform: translateY(100%);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    /* Hover effects for buttons */
    .greetings-prev:hover, .greetings-next:hover {
        background-color: #003f7f !important;
    }

    .greetings-close:hover {
        background-color: #e60000 !important;
    }
</style>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        let currentSlide = 0;
        const slides = document.querySelectorAll('.greetings-slide');
        const prevBtn = document.getElementById('prevGreetingsBtn');
        const nextBtn = document.getElementById('nextGreetingsBtn');
        const closeBtn = document.getElementById('closeGreetingsBtn');

        function showSlide(index) {
            slides.forEach((slide, i) => {
                slide.style.display = i === index ? 'block' : 'none';
            });
            prevBtn.style.display = index === 0 ? 'none' : 'block';
            nextBtn.style.display = index === slides.length - 1 ? 'none' : 'block';
        }

        prevBtn.addEventListener('click', function () {
            if (currentSlide > 0) {
                currentSlide--;
                showSlide(currentSlide);
            }
        });

        nextBtn.addEventListener('click', function () {
            if (currentSlide < slides.length - 1) {
                currentSlide++;
                showSlide(currentSlide);
            }
        });

        closeBtn.addEventListener('click', function () {
            document.getElementById('greetingPopup').style.display = 'none';
        });

        // Ensure the first slide is displayed
        showSlide(currentSlide);
    });
</script>