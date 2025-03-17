
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechTrack</title>
    <link rel="icon" href="{{ asset('images/Systembrowserlogo.png') }}" type="image/png">

    <link rel="stylesheet" href="{{ asset('css/faq_style.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.faq-question').click(function() {
                $(this).next('.faq-answer').slideToggle();
                $(this).parent().siblings().find('.faq-answer').slideUp();
                $(this).toggleClass('active');
                $(this).parent().siblings().find('.faq-question').removeClass('active');
            });
        });
    </script>
</head>
<body>
        
<div class="container">
    <!-- Sidebar -->
    @include('components.sidebar')

    <!-- Main Content Area -->
    <div class="main-content">
        <!-- Navbar -->
        @include('components.navbar')

        <h1 class="headerfaq">Frequently Asked Questions - TechTrack</h1>
        <div class="faq-item">
            <h3 class="faq-question">What is TechTrack?</h3>
            <p class="faq-answer">TechTrack: An Electronic Service Monitoring
and Management System for MISO Technical Division developed for Pasig City Hall's Management Information Systems Office (MISO). It is designed to track and manage IT assets, technical support requests, and resource deployment in a more efficient and responsive manner.</p>
        </div>
        <div class="faq-item">
            <h3 class="faq-question">How does TechTrack improve operation?</h3>
            <p class="faq-answer">TechTrack automates the tracking and management of ticket request for technical issue related to computers, printers, and employee equipment. With real-time asset tracking, automated reports, and integrated service request management, it minimizes manual errors and optimizes the use of resources.</p>
        </div>
        <div class="faq-item">
            <h3 class="faq-question">How can I submit a service request using TechTrack?</h3>
            <p class="faq-answer">To submit a service request, simply log in to the TechTrack system and navigate to the 'Request Support' section. You can create a new request by providing details about the issue, and it will be assigned to the appropriate support personnel for resolution.</p>
        </div>
        <div class="faq-item">
            <h3 class="faq-question">Is TechTrack available for other departments in Pasig City Hall?</h3>
            <p class="faq-answer">Currently, TechTrack is exclusively designed for the MISO Technical Support Division at Pasig City Hall. However, there are plans for future expansions to support other departments as well.</p>
        </div>
        <div class="faq-item">
            <h3 class="faq-question">What features does TechTrack offer?</h3>
            <p class="faq-answer">TechTrack includes features such as real-time ticket tracking, automated technical performance, and an integrated ticketing system for managing internal technical support requests. These features improve operational efficiency and accountability across Pasig City Hall.</p>
        </div>
        <div class="faq-item">
            <h3 class="faq-question">How do I access my account in TechTrack?</h3>
            <p class="faq-answer">To access your TechTrack account, visit the login page and enter your credentials. If you don’t have an account, you can request access from your department's MISO coordinator.</p>
        </div>
        <div class="faq-item">
            <h3 class="faq-question">How can I update my TechTrack account information?</h3>
            <p class="faq-answer">To update your account information, go to the 'Profile' section in your TechTrack dashboard and select 'Edit Profile'. You can update your contact details, department information, and other personal settings.</p>
        </div>
        <div class="faq-item">
            <h3 class="faq-question">What should I do if I experience issues with TechTrack?</h3>
            <p class="faq-answer">If you encounter any issues while using TechTrack, please report them through the integrated ticketing system or email the support team at support@techtrack.com. Our team will assist you in resolving the issue.</p>
        </div>
        <div class="faq-item">
            <h3 class="faq-question">How is TechTrack different from traditional manual inventory systems?</h3>
            <p class="faq-answer">Unlike manual systems, TechTrack automates many processes, such as asset tracking, maintenance scheduling, and ticketing. This results in higher accuracy, faster response times, and better resource utilization, leading to more efficient operations in Pasig City Hall.</p>
        </div>
    </div>
</div>
</body>
</html>
