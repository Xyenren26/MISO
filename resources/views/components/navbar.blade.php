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
            <div class="nav-item dropdown">
                
                    <i class="fas fa-bell navbar-icon"  id="notificationDropdown" onclick="toggleNotifications()"></i>
                    <span class="badge" id="notificationBadge" style="display: none;">0</span>
         

                <div class="dropdown-menu notification-dropdown" id="notificationMenu">
                    <div class="notification-header">
                        <h3>Notifications</h3>
                        <span class="mark-all" onclick="markAllAsRead()">Mark all as read</span>
                    </div>
                    <div id="notificationList">
                        <p class="text-muted text-center">No new notifications</p>
                    </div>
                </div>
            </div>


            <i class="fas fa-envelope navbar-icon"></i>
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

        function toggleNotifications() {
            document.getElementById("notificationMenu").classList.toggle("show");
        }

        document.addEventListener("DOMContentLoaded", function () {
            fetchNotifications();
        });

        function fetchNotifications() {
            fetch('/notifications')
                .then(response => response.json())
                .then(data => {
                    const notificationList = document.getElementById("notificationList");
                    const badge = document.getElementById("notificationBadge");

                    if (data.length > 0) {
                        badge.style.display = "inline-block";
                        badge.innerText = data.length;

                        notificationList.innerHTML = data.map(notification => `
                            <div class="notification-item" onclick="markAsRead('${notification.id}')">
                                <img src="${notification.data.image || '/default-avatar.png'}" alt="User">
                                <div class="notification-text">
                                    <a href="${notification.data.link || '#'}">${notification.data.message}</a>
                                    <div class="notification-time">${timeAgo(notification.created_at)}</div>
                                </div>
                            </div>
                        `).join("");
                    } else {
                        badge.style.display = "none";
                        notificationList.innerHTML = `<p class="text-muted text-center">No new notifications</p>`;
                    }
                })
                .catch(error => console.error("Error fetching notifications:", error));
        }

        function markAsRead(id) {
            fetch(`/notifications/mark-as-read/${id}`, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                    "Content-Type": "application/json"
                }
            }).then(() => fetchNotifications());
        }

        function markAllAsRead() {
            fetch('/notifications/mark-all-as-read', {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                    "Content-Type": "application/json"
                }
            }).then(() => fetchNotifications());
        }

        // Convert timestamps to "X minutes/hours ago"
        function timeAgo(timestamp) {
            const date = new Date(timestamp);
            const now = new Date();
            const diff = Math.floor((now - date) / 1000); // Difference in seconds

            if (diff < 60) return `${diff} seconds ago`;
            if (diff < 3600) return `${Math.floor(diff / 60)} minutes ago`;
            if (diff < 86400) return `${Math.floor(diff / 3600)} hours ago`;
            return `${Math.floor(diff / 86400)} days ago`;
        }

    </script>
</body>
</html>
