<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/Sidebar_Style.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="sidebar" id="sidebar">
        <!-- Logo Section -->
        <div class="logo-container">
            <img src="{{ asset('images/pasiglogo.png') }}" alt="Logo" class="sidebar-logo">
        </div>

        <!-- Minimize Button (Left Arrow to Minimize) -->
        <button class="sidebar-toggle-btn" onclick="toggleSidebar()">
            <i class="fas fa-arrow-left" id="sidebar-toggle-icon"></i>
        </button>

        <!-- Menu Items -->
        <ul class="menu">
            <li><i class="fas fa-home"></i><span class="menu-label">Home</span></li>
            <li><i class="fas fa-cogs"></i><span class="menu-label">Device Management</span></li>
            <li><i class="fas fa-users"></i><span class="menu-label">User Management</span></li>
        </ul>

        <!-- Social Media Icons (Facebook, Instagram, Website) -->
        <div class="social-icons">
            <a href="https://facebook.com" target="_blank" class="social-icon"><i class="fab fa-facebook-f"></i></a>
            <a href="https://instagram.com" target="_blank" class="social-icon"><i class="fab fa-instagram"></i></a>
            <a href="https://yourwebsite.com" target="_blank" class="social-icon"><i class="fas fa-globe"></i></a>
        </div>
    </div>

    <script src="{{ asset('js/Sidebar_Script.js') }}"></script>
</body>
</html>
