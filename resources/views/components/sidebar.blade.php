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
                    @if(is_null(auth()->user()->email_verified_at))
                        <p style="color: red; margin-bottom: 10px;">Please validate your email first</p>
                        <button class="employeetickets" disabled style="background-color: gray; cursor: not-allowed;">
                            <span class="employeeticketsicon">➕</span> 
                            <span class="employeeticketstext">Request Support</span>
                        </button>
                    @else
                        <button class="employeetickets" id="requestSupportButton" onclick="openTicketFormModal()">
                            <span class="employeeticketsicon">➕</span> 
                            <span class="employeeticketstext">Request Support</span>
                        </button>
                    @endif
                    <!-- Message placeholder -->
                    <p id="ticketMessage" style="display: none; color: red; margin-top: 10px;"></p>
                </div>

                <li class="{{ Request::is('employee/home') ? 'active' : '' }}">
                    <a href="{{ route('employee.home') }}"><i class="fas fa-home"></i><span class="menu-label">Dashboard</span></a>
                </li>
                <li class="{{ request()->routeIs('employee.tickets') ? 'active' : '' }}">
                    <a href="{{ route('employee.tickets') }}"><i class="fas fa-ticket-alt"></i><span class="menu-label">Ticket Management</span></a>
                </li>
            @elseif(in_array(auth()->user()->account_type, ['technical_support']))
                <!-- Only show 'Home' for technical_support but not for administrator or technical_support_head -->
                <li class="{{ Request::is('home') ? 'active' : '' }}">
                    <a href="{{ route('home') }}"><i class="fas fa-home"></i><span class="menu-label">Dashboard</span></a>
                </li>
            @endif

            @if(in_array(auth()->user()->account_type, ['administrator', 'technical_support', 'technical_support_head']))
                <li class="{{ Request::is('ticket') ? 'active' : '' }}">
                    <a href="{{ route('ticket') }}"><i class="fas fa-ticket-alt"></i><span class="menu-label">Ticket Management</span></a>
                </li>
                <li class="{{ Request::is('audit_logs') ? 'active' : '' }}">
                    <a href="{{ route('audit_logs') }}"><i class="fas fa-file-alt"></i><span class="menu-label"> Audit Logs</span></a>
                </li>
            @endif
        </ul>

        @if(in_array(auth()->user()->account_type, ['administrator', 'technical_support', 'technical_support_head']))
            <!-- Administrative Section -->
            <ul class="menu">
                <!-- Toggle Button for Administrative Section -->
                <li class="administrative-label collapsed" onclick="toggleAdministrativeMenu()">
                    <div style="display: flex; align-items: center;">
                        <i class="fas fa-caret-down"></i>
                        <span>Administrative</span>
                    </div>
                </li>

                <!-- Collapsible Administrative Menu -->
                <div id="administrative-menu" class="collapsible-menu collapsed">
                    <li class="{{ Request::is('user_management') ? 'active' : '' }}">
                        <a href="{{ route('user_management') }}"><i class="fas fa-users"></i><span class="menu-label">User Management</span></a>
                    </li>
                    <li class="{{ Request::is('report') ? 'active' : '' }}">
                        <a href="{{ route('report') }}"><i class="fas fa-chart-line"></i><span class="menu-label">Reports and Analytics</span></a>
                    </li>
                    <li class="{{ Request::is('department') ? 'active' : '' }}">
                        <a href="{{ route('department.index') }}"><i class="fas fa-building"></i><span class="menu-label">Departments</span></a>
                    </li>
                    <li class="{{ Request::is('archive') ? 'active' : '' }}">
                        <a href="{{ route('archive.index') }}"><i class="fas fa-archive"></i><span class="menu-label">Archived Tickets</span></a>
                    </li>
                    <li class="{{ Request::is('announcements*') ? 'active' : '' }}">
                        <a href="{{ route('announcements.index') }}"><i class="fas fa-bullhorn"></i><span class="menu-label">Announcements</span></a>
                    </li>
                    <li class="{{ Request::is('concerns*') ? 'active' : '' }}">
                        <a href="{{ route('concerns.index') }}"><i class="fas fa-bullhorn"></i><span class="menu-label">Concerns</span></a>
                    </li>
                </div>
            </ul>
        @endif
    </div>

    <script src="{{ asset('js/Sidebar_Script.js') }}"></script>
    <script>
       // Toggle Administrative Menu
        function toggleAdministrativeMenu() {
            const administrativeMenu = document.getElementById('administrative-menu');
            const label = document.querySelector('.administrative-label');

            if (administrativeMenu && label) {
                if (administrativeMenu.classList.contains('expanded')) {
                    // If expanded, collapse it
                    administrativeMenu.classList.remove('expanded');
                    administrativeMenu.classList.add('collapsed');
                } else {
                    // If collapsed, expand it
                    administrativeMenu.classList.remove('collapsed');
                    administrativeMenu.classList.add('expanded');
                }

                // Toggle caret rotation
                label.classList.toggle('collapsed');
            } else {
                console.error("Elements not found!");
            }
        }

        </script>
</body>
</html>