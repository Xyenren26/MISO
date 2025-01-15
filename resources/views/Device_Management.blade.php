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
                <button class="add-device" onclick="openPopup('formPopup')">
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

<!-- Add Device Form Popup -->
<div id="formPopup" class="form-popup-container" style="display: none;">
    <div class="form-popup-content">
        <span class="form-popup-close-btn" onclick="closePopup('formPopup')">×</span>
        <div class="form-popup-form-container">
            <header class="form-popup-header">
                <div class="form-popup-logo">
                    <img src="images/pasiglogo.png" alt="Logo">
                </div>
                <h1>ICT Equipment Service Request Form</h1>
                <div class="form-popup-form-info">
                    <span>Form No.: SP4-2024-004A</span>
                </div>
            </header>

            <section class="form-popup-section">
                <label><input type="radio" name="service_type" value="walk_in"> Walk-In</label>
                <label><input type="radio" name="service_type" value="pull_out"> Pull-Out</label>
            </section>

            <form>
                <!-- General Information Section -->
                <section class="form-popup-section">
                    <h3 class="form-popup-title">General Information</h3>
                    <div class="form-popup-input-group">
                        <label class="form-popup-label">Department / Office / Unit:</label>
                        <input class="form-popup-input" type="text" name="department" required>
                    </div>
                    <div class="form-popup-input-group">
                        <label class="form-popup-label">Brand:</label>
                        <input class="form-popup-input" type="text" name="brand" required>
                    </div>
                    <div class="form-popup-checkbox-group">
                        <label class="form-popup-label">Condition of Equipment:</label>
                        <label><input type="checkbox" name="condition" value="working"> Working</label>
                        <label><input type="checkbox" name="condition" value="not-working"> Not Working</label>
                        <label><input type="checkbox" name="condition" value="needs-repair"> Needs Repair</label>
                    </div>
                </section>

                <!-- Equipment Description Section -->
                <section class="form-popup-section">
                    <h3 class="form-popup-title">Equipment Description</h3>
                    <table class="form-popup-table">
                        <thead>
                            <tr>
                                <th>Description</th>
                                <th>Motherboard</th>
                                <th>RAM</th>
                                <th>HDD</th>
                                <th>Accessories</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>System Unit</td>
                                <td><input type="checkbox" name="system_motherboard"></td>
                                <td><input type="checkbox" name="system_ram"></td>
                                <td><input type="checkbox" name="system_hdd"></td>
                                <td><input type="checkbox" name="system_accessories"></td>
                                <td><input class="form-popup-input" type="text" name="system_remarks"></td>
                            </tr>
                            <tr>
                                <td>Laptop</td>
                                <td><input type="checkbox" name="laptop_motherboard"></td>
                                <td><input type="checkbox" name="laptop_ram"></td>
                                <td><input type="checkbox" name="laptop_hdd"></td>
                                <td><input type="checkbox" name="laptop_accessories"></td>
                                <td><input class="form-popup-input" type="text" name="laptop_remarks"></td>
                            </tr>
                            <tr>
                                <td colspan="6" class="form-popup-subheader">Printer and UPS</td>
                            </tr>
                            <tr>
                                <td>Printer</td>
                                <td colspan="5"><input class="form-popup-input" type="text" name="printer_remarks" placeholder="Remarks"></td>
                            </tr>
                            <tr>
                                <td>UPS</td>
                                <td colspan="5"><input class="form-popup-input" type="text" name="ups_remarks" placeholder="Remarks"></td>
                            </tr>
                        </tbody>
                    </table>
                </section>

                <button class="form-popup-button" type="submit">Submit</button>
            </form>
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

    // JavaScript for Popup
    function openPopup(popupId) {
        document.getElementById(popupId).style.display = 'block';
    }

    function closePopup(popupId) {
        document.getElementById(popupId).style.display = 'none';
    }
</script>
</body>
</html>
