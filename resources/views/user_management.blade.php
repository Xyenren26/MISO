<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link rel="stylesheet" href="{{ asset('css/User_Manage_Style.css') }}">
</head>
<body>
<div class="container">
    <!-- Sidebar -->
    @include('components.sidebar')

    <!-- Main Content Area -->
    <div class="main-content">
        <!-- Navbar -->
        @include('components.navbar')

        <!-- Title and Controls -->
        <div class="title-bar">
            <h1 class="title">User Management</h1>
            <div class="controls">
                <!-- Search Container -->
                <div class="search-container">
                    <input type="text" class="search-input" placeholder="Search...">
                    <button class="search-button">
                        <span class="material-icons" style="color: white;">search</span>
                    </button>
                </div>

                <!-- Filter Dropdown -->
                <div class="dropdown">
                    <button class="dropdown-button">
                        <span class="material-icons">filter_list</span> Filter <span class="arrow">&#x25BC;</span>
                    </button>
                    <div class="dropdown-content">
                        <a href="?filter=end-user">End User</a>
                        <a href="?filter=technical-support">Technical Support</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="table-container">
            <p class="results-info">Showing 1 to 0 of 0 results</p>
            <table class="user-table">
                <thead>
                    <tr>
                        <th>Employee ID</th>
                        <th>Name</th>
                        <th>Department</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>{{ $user->employee_id }}</td>
                            <td>{{ $user->first_name . ', ' . $user->last_name }}</td>
                            <td>{{ $user->department }}</td>
                            <td>{{ $user->account_type }}</td>
                            <td>{{ $user->status }}</td>
                            <td>
                                <button class="action-button settings">
                                    <span class="material-icons">settings</span>
                                </button>
                                <button class="action-button delete">
                                    <span class="material-icons">delete</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="no-record">NO RECORD FOUND</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Include custom scripts -->
<script src="{{ asset('js/User_Manage_Script.js') }}"></script>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</body>
</html>
