<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Management Information System</title>
    <link rel="stylesheet" href="{{ asset('css/Dev_Manage_Style.css') }}">
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
            <div class="header-title">
                <h1>Device Management</h1>
            </div>
            <div class="header-buttons">
                <button class="add-device">
                    <span class="icon">➕</span> Add New Device
                </button>

                <!-- Dropdown Filter -->
                <div class="dropdown">
                    <button class="dropdown-button">
                        <span class="icon">⚙️</span> Filter<span class="arrow">&#x25BC;</span>
                    </button>
                    <div class="dropdown-content">
                        <a href="?filter=in-repairs">In Repairs</a>
                        <a href="?filter=repaired">Repaired</a>
                        <a href="?filter=new-device-distribution">New Device Distribution</a>
                    </div>
                </div>

                <button class="refresh">
                    <span class="icon">&#x21bb;</span>
                </button>
            </div>
        </div>

        <!-- Content Section -->
        <div class="content">
            <!-- Table Section -->
            <div class="table-container">
                @if ($devices->count() > 0)
                    <table class="device-table">
                        <thead>
                            <tr>
                                <th>Control No.</th>
                                <th>Name</th>
                                <th>Device</th>
                                <th>Status</th>
                                <th>Technical Support</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($devices as $device)
                                <tr>
                                    <td>{{ $device->control_no }}</td>
                                    <td>{{ $device->name }}</td>
                                    <td>{{ $device->device }}</td>
                                    <td>{{ ucfirst($device->status) }}</td>
                                    <td>{{ $device->technical_support }}</td>
                                    <td>{{ $device->date }}</td>
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
</body>
</html>
