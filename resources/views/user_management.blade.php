<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechTrack User Management</title>
    <link rel="icon" href="{{ asset('images/Systembrowserlogo.png') }}" type="image/png">
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
                <form id="searchFilterForm">
                    <div class="search-container">
                        <input type="text" name="search" id="searchInput" class="search-input" placeholder="Search..." value="{{ request('search') }}">
                        <button type="submit" class="search-button">
                            <span class="material-icons" style="color: white;">search</span>
                        </button>
                    </div>
                    <div class="dropdown">
                        <select name="filter" id="filterSelect" class="dropdown-button">
                            <option value="">All Users</option>
                            <option value="end_user">End User</option>
                            <option value="technical_support">Technical Support</option>
                            <option value="administrator">Administrator</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>

        

        <!-- Table -->
        <div class="table-container">
        <p class="results-info">Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} results</p>
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
                <tbody id="userTableBody">
                    @forelse ($users as $user)
                        <tr>
                            <td>{{ str_pad($user->employee_id, 7, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $user->first_name . ', ' . $user->last_name }}</td>
                            <td>{{ $user->department }}</td>
                            
                            <!-- Format Role Properly -->
                            <td>
                                @if ($user->account_type === 'technical_support')
                                    Technical Support
                                @elseif ($user->account_type === 'administrator')
                                    Administrator
                                @elseif ($user->account_type === 'end_user')
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
                                <div class="button-container">

                                    <!-- Change Role Button -->
                                    <button class="action-button role" 
                                            title="Change Role" 
                                            onclick="openRoleModal('{{ $user->employee_id }}', '{{ $user->account_type }}')">
                                        <span class="material-icons">admin_panel_settings</span>
                                    </button>

                                    <!-- Disable/Enable Button -->
                                    <form id="disableForm{{ $user->employee_id }}" action="{{ route('user.toggleStatus', ['employee_id' => $user->employee_id]) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="button" class="action-button disable" 
                                                title="{{ $user->status === 'active' ? 'Disable User' : 'Enable User' }}" 
                                                onclick="toggleStatus(event, '{{ $user->employee_id }}', '{{ $user->status }}')">
                                            <span class="material-icons">{{ $user->status === 'active' ? 'do_not_disturb_alt' : 'check_circle' }}</span>
                                            {{ $user->status === 'active' ? '' : '' }}
                                        </button>
                                    </form>
                                </div>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="no-record">NO RECORD FOUND</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="pagination-container">
            <div class="results-count">
                @if ($users->count() > 0)
                    Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} results
                @else
                    Showing 1 to 0 of 0 results
                @endif
            </div>

            @if ($users->hasPages()) 
                <div class="pagination-buttons">
                    <ul class="flex space-x-2">
                        {{-- Previous Page Link --}}
                        <li class="{{ $users->onFirstPage() ? 'disabled opacity-50' : '' }}">
                            @if ($users->onFirstPage())
                                <span class="px-3 py-1">&lsaquo;</span>
                            @else
                                <a href="{{ $users->previousPageUrl() }}" data-page="{{ $users->currentPage() - 1 }}" class="px-3 py-1 border rounded">&lsaquo;</a>
                            @endif
                        </li>

                        {{-- Page Numbers (show current page, one before, one after) --}}
                        @for ($i = max(1, $users->currentPage() - 1); $i <= min($users->lastPage(), $users->currentPage() + 1); $i++)
                            <li class="{{ $i == $users->currentPage() ? 'active font-bold' : '' }}">
                                @if ($i == $users->currentPage())
                                    <span class="px-3 py-1 border rounded bg-gray-200">{{ $i }}</span>
                                @else
                                    <a href="{{ $users->url($i) }}" data-page="{{ $i }}" class="px-3 py-1 border rounded">{{ $i }}</a>
                                @endif
                            </li>
                        @endfor

                        {{-- Ellipsis for large page numbers --}}
                        @if ($users->currentPage() < $users->lastPage() - 2)
                            <li><span>...</span></li>
                            <li><a href="{{ $users->url($users->lastPage()) }}" data-page="{{ $users->lastPage() }}" class="px-3 py-1 border rounded">{{ $users->lastPage() }}</a></li>
                        @endif

                        {{-- Next Page Link --}}
                        <li class="{{ $users->hasMorePages() ? '' : 'disabled opacity-50' }}">
                            @if ($users->hasMorePages())
                                <a href="{{ $users->nextPageUrl() }}" data-page="{{ $users->currentPage() + 1 }}" class="px-3 py-1 border rounded">&rsaquo;</a>
                            @else
                                <span class="px-3 py-1">&rsaquo;</span>
                            @endif
                        </li>
                    </ul>
                </div>
            @endif
        </div>

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
                    title="Employee ID must be exactly 7 digits">
                <p id="warningMessage" style="color: red; display: none;">Employee ID must be 7 digits.</p>
            </div>

            <!-- Password Input Field -->
            <div id="passwordField" style="display: none;">
                <label for="newPassword">New Password:</label>
                <input type="password" id="newPassword" name="newPassword" minlength="8" 
                    title="Password must be at least 8 characters long">
            </div>

            <button type="submit" class="modal-button confirm-button">Confirm</button>
            <button type="button" class="modal-button cancel-button" onclick="closeEditModal()">Cancel</button>
        </form>
    </div>
</div>

<div id="roleModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeRoleModal()">&times;</span>
        <h2>Change User Role</h2>
        <form id="roleForm" method="POST">
            @csrf
            <label for="newRole">Select New Role:</label>
            <select id="newRole" name="newRole" required>
                <option value="end_user">End User</option>
                <option value="technical_support">Technical Support</option>
                <option value="administrator">Administrator</option>
            </select>
            <button type="submit" class="modal-button confirm-button">Confirm</button>
            <button type="button" class="modal-button cancel-button" onclick="closeRoleModal()">Cancel</button>
        </form>
    </div>
</div>
<!-- Include custom scripts -->
<script src="{{ asset('js/User_Manage_Script.js') }}"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("searchInput");
    const filterSelect = document.getElementById("filterSelect");

    function fetchUsers(url = "{{ route('user.management') }}") {
        const params = new URLSearchParams({
            search: searchInput.value,
            filter: filterSelect.value
        });

        // Append pagination parameter if fetching via pagination
        if (url.includes("?page=")) {
            url += `&${params.toString()}`;
        } else {
            url += `?${params.toString()}`;
        }

        fetch(url, {
            headers: {
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-TOKEN": "{{ csrf_token() }}" // Include CSRF token
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.html && data.pagination) {
                document.getElementById("userTableBody").innerHTML = data.html;
                document.getElementById("paginationLinks").innerHTML = data.pagination;
            } else {
                console.error("No HTML data returned from the server");
            }
        })
        .catch(error => console.error("Error fetching users:", error));
    }

    // Trigger AJAX search & filter
    searchInput.addEventListener("keyup", () => fetchUsers());
    filterSelect.addEventListener("change", () => fetchUsers());

    // Handle pagination dynamically (Event Delegation)
    document.addEventListener("click", function (event) {
        if (event.target.classList.contains("page-link")) {
            event.preventDefault();
            fetchUsers(event.target.href); // Fetch users for the clicked page
        }
    });
});


document.addEventListener("DOMContentLoaded", function () {
        // Show Laravel validation errors
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                showAlert("error", "{{ $error }}");
            @endforeach
        @endif

        // Show success message for email verification or account creation
        @if (session('success'))
            showAlert("success", "{{ session('success') }}");
        @endif

        // Show any other general messages
        @if (session('message'))
            showAlert("success", "{{ session('message') }}");
        @endif
    });

    function showAlert(type, message) {
        document.querySelectorAll(".alert-box").forEach(alert => alert.remove());

        const alertBox = document.createElement("div");
        alertBox.classList.add("alert-box", type === "success" ? "success" : "error");
        alertBox.innerHTML = `
            <strong>${type === "success" ? "Success!" : "Error!"}</strong>
            <span>${message}</span>
            <button type="button" onclick="this.parentElement.remove();">&times;</button>
        `;

        document.querySelector(".table-container").prepend(alertBox);
    }
</script>
</body>
</html>