<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Management Information System</title>
    <link rel="stylesheet" href="{{ asset('css/Dev_Manage_Style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<div class="container">
    <!-- Sidebar -->
    @include('components.sidebar')

    <!-- Main Content Area -->
    <div class="main-content">
        <!-- Navbar -->
        @include('components.navbar')

        <!-- Header Section -->
        <div class="header">
            <!-- Tabs Section -->
            <div class="tabs">
                <button class="tab-button active" onclick="showTab('all')">
                    <i class="fas fa-laptop"></i> All Devices
                </button>
                <button class="tab-button" onclick="showTab('in-repairs')">
                    <i class="fas fa-tools"></i> In Repairs
                </button>
                <button class="tab-button" onclick="showTab('repaired')">
                    <i class="fas fa-check-circle"></i> Repaired
                </button>
                <button class="tab-button" onclick="showTab('new-deployment')">
                    <i class="fas fa-plus-circle"></i> Device Deployment Record
                </button>
            </div>
        </div>

        <!-- Filter and Add New Device Section (Below Tabs) -->
        <div class="actions">
            <!-- Search Container -->
            <div class="search-container">
                <input type="text" placeholder="Search..." class="search-input">
                <button class="search-button"><i class="fas fa-search"></i></button>
            </div>

            <!-- Space Between Search and Filter/Add New Buttons -->
            <div class="spacer"></div>

            <!-- Filter and Add New Device Section (Right side) -->
            <div class="filter-section">
                <div class="dropdown">
                    <button class="dropdown-button">
                        <i class="fas fa-filter"></i> Filter Device <span class="arrow">&#x25BC;</span>
                    </button>
                    <div class="dropdown-content">
                        <a href="?filter=option1">Option 1</a>
                        <a href="?filter=option2">Option 2</a>
                        <a href="?filter=option3">Option 3</a>
                    </div>
                </div>
            </div>
            <div class="add-device-section">
                <button class="add-device">
                    <span class="icon">➕</span> Add Device
                </button>
            </div>
        </div>

        <!-- Content Section -->
        <div class="content">
            <!-- Data Tables for Each Tab -->
            <div id="all" class="tab-content active">
            @if ($devices->count() > 0)
                <table class="device-table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Control No.</th>
                            <th>Device</th>
                            <th>Name</th>
                            <th>Department</th>
                            <th>Device Status</th>
                            <th>Created</th>
                            <th>Last Update</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($devices as $device)
                            <tr>
                                <td><input type="checkbox" /></td>
                                <td>{{ $device->control_no }}</td>
                                <td>{{ $device->device }}</td>
                                <td>{{ $device->name }}</td>
                                <td>{{ $device->department }}</td>
                                <td>{{ ucfirst($device->status) }}</td>
                                <td>{{ $device->created_at }}</td>
                                <td>{{ $device->updated_at }}</td>
                                <td><button class="menu-button">⋮</button></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                    <!-- No Records Message -->
                    <div class="no-records">NO RECORDS FOUND</div>
                @endif

                <!-- Results Count and Pagination Controls -->
                <div class="pagination-container">
                    <div class="results-count">
                        @if ($devices->count() > 0)
                            Showing {{ $devices->firstItem() }} to {{ $devices->lastItem() }} of {{ $devices->total() }} results
                        @else
                            Showing 1 to 0 of 0 results
                        @endif
                    </div>
                    <div class="pagination-buttons">
                        {{ $devices->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include custom scripts -->
<script src="{{ asset('js/Dev_Manage_Script.js') }}"></script>
<script>
    // JavaScript to handle tab switching
    function showTab(tabId) {
        const tabs = document.querySelectorAll('.tab-content');
        tabs.forEach(tab => tab.classList.remove('active'));

        const buttons = document.querySelectorAll('.tab-button');
        buttons.forEach(button => button.classList.remove('active'));

        document.getElementById(tabId).classList.add('active');
        event.target.classList.add('active');
    }
</script>
</body>
</html>
