<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Management Information System</title>
    
    <!-- Include your CSS -->
    <link rel="stylesheet" href="{{ asset('css/Home_Style.css') }}">
    
    <!-- Include Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<div class="container">
    <!-- Sidebar -->
    @include('components.sidebar')

    <!-- Main Content Area -->
    <div class="main-content">
        <!-- Navbar -->
        <div class="navbar">
            <h1 class="navbar-title">MANAGEMENT INFORMATION SYSTEM OFFICE</h1>
            <div class="navbar-icons">
                <i class="fas fa-bell navbar-icon"></i>
                <i class="fas fa-envelope navbar-icon"></i>
                <i class="fas fa-user navbar-icon"></i>
            </div>
        </div>

            <!-- Image Container -->
            <div class="content-wrapper">
                <div class="image-container">
                    <i class="fas fa-chevron-left nav-arrow left-arrow" onclick="showPreviousImage()"></i>
                    <img src="{{ asset('images/worksample1.jpg') }}" 
                        alt="Work Sample" 
                        id="slideshow-image" 
                        class="image" 
                        data-images="{{ asset('images/worksample1.jpg') }},{{ asset('images/worksample2.jpg') }},{{ asset('images/worksample3.jpg') }}">
                    <i class="fas fa-chevron-right nav-arrow right-arrow" onclick="showNextImage()"></i>
                </div>
                <div class="metrics-container">
                    <h3>Metrics</h3>
                    <p>Example Metric 1</p>
                    <p>Example Metric 2</p>
                    <p>Example Metric 3</p>
                </div>
            </div>
            <!-- Summary Container -->
            <div class="summary-container">
                <h3>Summary</h3>
                <p>Here is the summary content for this section...</p>
            </div>
    </div>
</div>

<!-- Include your JavaScript -->
<script src="{{ asset('js/Home_Script.js') }}"></script>
</body>
</html>
