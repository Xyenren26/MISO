<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/Navbar_Style.css') }}">
</head>
<body>
    <div class="navbar">
        <h1 class="navbar-title">
            Welcome, 
            @if(Auth::check())
                {{ strtoupper(Auth::user()->first_name) }} {{ strtoupper(Auth::user()->last_name) }}
            @else
                GUEST
            @endif
        </h1>
        <div class="navbar-icons">
            <i class="fas fa-bell navbar-icon"></i>
            <a href="{{ url('/chat') }}" title="View Messages">
    <i class="fas fa-envelope navbar-icon"></i>
</a>
            <div class="user-dropdown">
                <i class="fas fa-user navbar-icon" onclick="toggleDropdown()"></i>
                <div class="dropdown-menu" id="dropdownMenu">
                    <div class="last-name">
                        @if(Auth::check())
                            {{ strtoupper(Auth::user()->last_name) }}
                        @else
                            GUEST
                        @endif
                    </div>
                    <a class="dropdown-item" href="{{ route('profile.index') }}">
                        <i class="fas fa-user-circle"></i> My Account
                    </a>
                    <a class="dropdown-item" href="/settings">
                        <i class="fas fa-cog"></i> Settings
                    </a>
                    <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Function to toggle dropdown visibility
        function toggleDropdown() {
            const dropdownMenu = document.getElementById('dropdownMenu');
            dropdownMenu.classList.toggle('active');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function (event) {
            const dropdownMenu = document.getElementById('dropdownMenu');
            if (!event.target.closest('.user-dropdown')) {
                dropdownMenu.classList.remove('active');
            }
        });
    </script>
</body>
</html>
