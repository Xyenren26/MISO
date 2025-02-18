<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link rel="stylesheet" href="{{ asset('css/User_Manage_Style.css') }}">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        /* Status Colors */
        .status-active {
            color: green;
            font-weight: bold;
        }
        .status-inactive {
            color: red;
            font-weight: bold;
        }
    </style>
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

        <!-- Session Message -->
        @if(session('message'))
            <div class="alert alert-info" style="padding: 10px; background-color: #f1f1f1; border: 1px solid #ddd; border-radius: 5px; color: #333;">
                {{ session('message') }}
            </div>
        @endif

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
                            
                            <!-- Format Role Properly -->
                            <td>
                                @if ($user->account_type === 'technical_support')
                                    Technical Support
                                @elseif ($user->account_type === 'administrator')
                                    Administrator
                                @elseif ($user->account_type === 'employee')
                                    Employee
                                @else
                                    {{ ucfirst(str_replace('_', ' ', $user->account_type)) }}
                                @endif
                            </td>

                            <!-- Status with Color -->
                            <td class="{{ $user->status === 'active' ? 'status-active' : 'status-inactive' }}">
                                {{ ucfirst($user->status) }}
                            </td>

                            <td>
                                <!-- Edit Button -->
                                <button class="action-button settings" 
                                    title="Edit User" 
                                    onclick="openEditModal('{{ $user->employee_id }}', '{{ $user->first_name }}', '{{ $user->last_name }}', '{{ $user->account_type }}')">
                                    <span class="material-icons">settings</span>
                                </button>


                                <!-- Delete Button with Confirmation Modal Trigger -->
                                <form id="deleteForm{{ $user->employee_id }}" action="{{ route('user.delete', ['employee_id' => $user->employee_id]) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="action-button delete" 
                                            title="Delete User" 
                                            onclick="showDeleteModal(event, 'deleteForm{{ $user->employee_id }}')">
                                        <span class="material-icons">delete</span>
                                    </button>
                                </form>

                                <!-- Disable/Enable Button -->
                                <form id="disableForm{{ $user->employee_id }}" action="{{ route('user.toggleStatus', ['employee_id' => $user->employee_id]) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="button" class="action-button disable" 
                                            title="{{ $user->status === 'active' ? 'Disable User' : 'Enable User' }}" 
                                            onclick="toggleStatus(event, '{{ $user->employee_id }}', '{{ $user->status }}')">
                                        <span class="material-icons">{{ $user->status === 'active' ? 'do_not_disturb_alt' : 'check_circle' }}</span>
                                        {{ $user->status === 'active' ? 'Disable User' : 'Enable User' }}
                                    </button>
                                </form>
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

<!-- Modals -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeEditModal()">&times;</span>
        <h2>Edit User Information</h2>

        <!-- User Info Display -->
        <div id="userInfo" class="user-info-row">
            <!-- User details will be injected here -->
        </div>

        <form id="editForm" action="" method="POST">
            @csrf
            <div>
                <label for="editOption">Select what to edit:</label>
                <select id="editOption" name="editOption" onchange="toggleEditFields(this.value)">
                    <option value="employee_id">Employee ID</option>
                    <option value="password">Password</option>
                </select>
            </div>

            <!-- Employee ID Input Field with Validation -->
            <div id="employeeIdField" style="display: block;">
                <label for="newEmployeeId">New Employee ID:</label>
                <input type="text" id="newEmployeeId" name="newEmployeeId" maxlength="7" pattern="\d{7}" 
                       title="Employee ID must be exactly 7 digits" required>
            </div>

            <!-- Password Input Field -->
            <div id="passwordField" style="display: none;">
                <label for="newPassword">New Password:</label>
                <input type="password" id="newPassword" name="newPassword" minlength="8" 
                       title="Password must be at least 8 characters long" required>
            </div>

            <button type="submit" class="modal-button confirm-button">Confirm</button>
            <button type="button" class="modal-button cancel-button" onclick="closeEditModal()">Cancel</button>
        </form>
    </div>
</div>


<div id="deleteModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Are you sure you want to delete this user?</h2>
        <button class="modal-button cancel-button" onclick="closeModal()">Cancel</button>
        <button class="modal-button confirm-button" id="confirmDeleteButton" onclick="confirmDelete()">Confirm</button>
    </div>
</div>

<!-- Include custom scripts -->
<script src="{{ asset('js/User_Manage_Script.js') }}"></script>
</body>
</html>