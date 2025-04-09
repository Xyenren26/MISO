<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- Add CSRF Token for AJAX requests -->
    <link rel="stylesheet" href="{{ asset('css/Navbar_Style.css') }}">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script>

    var pusher = new Pusher('4b1c31f00282eebeb356', {
    cluster: 'mt1'
    });

    // Subscribe to the notifications channel
    var channel = pusher.subscribe("notifications");

    // Listen for new notifications
    channel.bind("new-notification", function (data) {
        console.log("New notification received:", data); // Debugging log
        
        fetchNotifications(); // Update notifications in real-time
    });
    </script>
</head>
<body>
    <div class="navbar">
        <h1 class="navbar-title">
            WELCOME, 
            @if(Auth::check())
                {{ strtoupper(Auth::user()->first_name) }} {{ strtoupper(Auth::user()->last_name) }}
            @else
                GUEST
            @endif
        </h1>
        <div class="navbar-icons">
            <div class="nav-item dropdown">
                <i class="fas fa-bell navbar-icon" id="notificationDropdown" onclick="toggleNotifications()"></i>
                <span class="notifbadge" id="notificationBadge" style="display: none;">0</span>
                <div class="dropdown-menu notification-dropdown" id="notificationMenu">
                    <div class="notification-header">
                        <h3>Notifications</h3>
                        <span class="mark-all" onclick="markAllAsRead()">Delete All Notifications</span>
                    </div>
                    <div id="notificationList">
                        <p class="text-muted text-center">No new notifications</p>
                    </div>
                </div>
            </div>

            <a href="/message" title="View Messages">
            <span class="message-badge" id="unseen-count" style="display: none;">0</span>
                <i class="fas fa-envelope navbar-icon"></i>
            </a>
            <div class="user-dropdown">
                <i class="fas fa-user navbar-icon" onclick="toggleDropdown()"></i>
                <div class="dropdown-menu" id="dropdownMenu">
                    @if(Auth::check())
                        <div class="profile-container">
                            <!-- Profile Picture -->
                            <img src="{{ Auth::user()->avatar && Storage::disk('public')->exists('users-avatar/'.Auth::user()->avatar) ? asset('storage/users-avatar/'.Auth::user()->avatar) : asset('avatar.png') }}" 
                                alt="Profile Picture" 
                                class="profile-image">
                        </div>
                        <!-- Last Name -->
                        <div class="last-name">
                            {{ strtoupper(Auth::user()->last_name) }}
                        </div>
                    @else
                        <div class="last-name">GUEST</div>
                    @endif
                    <a class="dropdown-item" href="{{ route('profile.index') }}">
                        <i class="fas fa-user-circle"></i> My Account
                    </a>
                    <a class="dropdown-item" href="{{ url('/faq') }}">
                        <i class="fas fa-question-circle"></i> FAQ
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
    @include('components.chatbot')
<script>
    // Function to toggle user dropdown
    function toggleDropdown() {
        const dropdownMenu = document.getElementById('dropdownMenu');
        dropdownMenu.classList.toggle('active');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function (event) {
        const dropdownMenu = document.getElementById('dropdownMenu');
        const dropdownButton = document.getElementById('dropdownButton'); // Button that toggles dropdown
        if (!event.target.closest('.user-dropdown') && event.target !== dropdownButton) {
            dropdownMenu.classList.remove('active');
        }
    });

    // Toggle Notifications
    function toggleNotifications() {
        document.getElementById("notificationMenu").classList.toggle("show");
    }

    // Fetch notifications on page load
    document.addEventListener("DOMContentLoaded", function () {
        fetchNotifications();
        fetchMessageNotifications();
    });

    // Fetch Notifications
    function fetchNotifications() {
        fetch('/notifications')
            .then(response => response.json())
            .then(data => {
                console.log("Fetched notifications:", data); // Debugging log
                const notificationList = document.getElementById("notificationList");
                const badge = document.getElementById("notificationBadge");

                if (data.length > 0) {
                    badge.style.display = "inline-block";
                    badge.innerText = data.length;

                    // Use backticks for template literals
                    notificationList.innerHTML = data.map(notification => `
                        <div class="notification-item" onclick="markAsRead('${notification.id}')">

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

    // Fetch Unseen Messages Count
    function fetchMessageNotifications() {
        fetch('/unseen-messages')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Update the unseen message count in the UI
                const unseenCount = data.unseenCount;
                const unseenCountElement = document.getElementById('unseen-count');

                if (unseenCount > 0) {
                    unseenCountElement.textContent = unseenCount;
                    unseenCountElement.style.display = 'inline'; // Show the badge
                } else {
                    unseenCountElement.style.display = 'none'; // Hide the badge if no unseen messages
                }
            })
            .catch(error => {
                console.error('Error fetching unseen messages:', error);
            });
    }

    setInterval(fetchMessageNotifications, 5000);

    // Mark Notification as Read
    function markAsRead(id) {
        fetch(`/notifications/mark-as-read/${id}`, { // Use backticks for string interpolation
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

    // Mark All Notifications as Read
    function markAllAsRead() {
        fetch("{{ route('notifications.markAllRead') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                "Content-Type": "application/json"
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById("notificationList").innerHTML = '<p class="text-muted text-center">No new notifications</p>';
                document.getElementById("notificationBadge").style.display = "none";
            } else {
                alert("Error: " + data.message);
            }
        })
        .catch(error => console.error("Error marking notifications:", error));
    }
</script>
</body>
</html>