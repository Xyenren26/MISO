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
            <img src="{{ asset('images/SystemLogo.png') }}" alt="Logo" class="sidebar-logo">
        </div>

        <!-- Minimize Button -->
        <button class="sidebar-toggle-btn" onclick="toggleSidebar()">
            <i class="fas fa-arrow-left" id="sidebar-toggle-icon"></i>
        </button>

        <!-- General Section -->
        <ul class="menu">
            <li class="label">General</li>
            
            @if(auth()->user()->account_type == 'end_user')
                <div class="employeeticketssection">
                    <button class="employeetickets" onclick="openTicketFormModal()">
                        <span class="employeeticketsicon">➕</span> 
                        <span class="employeeticketstext">Request Support</span>
                    </button>
                </div>
                <li class="{{ Request::is('employee/home') ? 'active' : '' }}">
                    <a href="{{ route('employee.home') }}"><i class="fas fa-home"></i><span class="menu-label">Home</span></a>
                </li>
                <li class="{{ request()->routeIs('employee.tickets') ? 'active' : '' }}">
                    <a href="{{ route('employee.tickets') }}"><i class="fas fa-ticket-alt"></i><span class="menu-label">Ticket Management</span></a>
                </li>
            @elseif(in_array(auth()->user()->account_type, ['administrator', 'technical_support']))
                <li class="{{ Request::is('home') ? 'active' : '' }}">
                    <a href="{{ route('home') }}"><i class="fas fa-home"></i><span class="menu-label">Home</span></a>
                </li>
                <li class="{{ Request::is('ticket') ? 'active' : '' }}">
                    <a href="{{ route('ticket') }}"><i class="fas fa-ticket-alt"></i><span class="menu-label">Ticket Management</span></a>
                </li>
                <li class="{{ Request::is('device_management') ? 'active' : '' }}">
                    <a href="{{ route('device_management') }}">
                        <i class="fas fa-cogs"></i><span class="menu-label">Device Management</span>
                    </a>
                </li>
            @endif
        </ul>

        @if(in_array(auth()->user()->account_type, ['administrator', 'technical_support']))
            <!-- Administrative Section -->
            <ul class="menu">
                <li class="label">Administrative</li>
                <li class="{{ Request::is('user_management') ? 'active' : '' }}">
                    <a href="{{ route('user_management') }}">
                        <i class="fas fa-users"></i><span class="menu-label">User Management</span>
                    </a>
                </li>
                <li class="{{ Request::is('report') ? 'active' : '' }}">
                    <a href="{{ route('report') }}">
                        <i class="fas fa-chart-line"></i><span class="menu-label">Reports and Analytics</span>
                    </a>
                </li>
                <li class="{{ Request::is('audit_logs') ? 'active' : '' }}">
                    <a href="{{ route('audit_logs') }}">
                        <i class="fas fa-file-alt"></i><span class="menu-label">Audit Logs</span>
                    </a>
                </li>
            </ul>
        @endif
    </div>

    <script src="{{ asset('js/Sidebar_Script.js') }}"></script>
</body>
</html>
