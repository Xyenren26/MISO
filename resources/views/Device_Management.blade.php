<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechTrack</title>
    <link rel="icon" href="{{ asset('images/Systembrowserlogo.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('css/Dev_Manage_Style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<div class="container">
    @include('components.sidebar')

    <div class="main-content">
        @include('components.navbar')

        <!-- Tabs -->
<!-- Tabs -->
<div class="header">
    <div class="tabs">
        <button class="tab-button {{ request('filter', 'all') == 'all' ? 'active' : '' }}" onclick="filterDevices('all')">
            <i class="fas fa-laptop"></i> All Devices
        </button>
        <button class="tab-button {{ request('filter') == 'in-repairs' ? 'active' : '' }}" onclick="filterDevices('in-repairs')">
            <i class="fas fa-tools"></i> In Repairs
            @if($inRepairsCount > 0)
                <span class="notifcounter">{{ $inRepairsCount }}</span>
            @endif
        </button>
        <button class="tab-button {{ request('filter') == 'repaired' ? 'active' : '' }}" onclick="filterDevices('repaired')">
            <i class="fas fa-check-circle"></i> Repaired
        </button>
        <button class="tab-button {{ request('filter') == 'new-deployment' ? 'active' : '' }}" onclick="filterDevices('new-deployment')">
            <i class="fas fa-plus-circle"></i> Device Deployment Record
        </button>
    </div>
</div>

<!-- Filter & Add Device -->
<div class="actions">
    <div class="search-container">
        <input type="text" id="searchInput" placeholder="Search..." class="search-input">
        <button class="search-button" onclick="fetchFilteredRecords(document.getElementById('searchInput').value)">
            <i class="fas fa-search"></i>
        </button>
    </div>


    <div class="spacer"></div>

    <div class="filter-section">
        <form action="{{ route('device_management') }}" method="GET">
            <div class="dropdown">
                <button class="dropdown-button">
                    <i class="fas fa-filter"></i> 
                    @if(request('filter') == 'new-deployment')
                        Filter by Status: 
                        @if(request('status') == 'new')
                            New
                        @elseif(request('status') == 'used')
                            Used
                        @else
                            All
                        @endif
                    @else
                        Filter by Condition: 
                        @if(request('condition') == 'working')
                            Working
                        @elseif(request('condition') == 'not-working')
                            Not Working
                        @elseif(request('condition') == 'needs-repair')
                            Needs Repair
                        @else
                            All Conditions
                        @endif
                    @endif
                    <span class="arrow">&#x25BC;</span>
                </button>

                <div class="dropdown-content">
                    @if(request('filter') == 'new-deployment')
                        <!-- Filter by Status for New Deployment -->
                        <a href="{{ route('device_management', ['status' => 'new', 'filter' => request('filter')]) }}">New</a>
                        <a href="{{ route('device_management', ['status' => 'used', 'filter' => request('filter')]) }}">Used</a>
                        <a href="{{ route('device_management', ['status' => '', 'filter' => request('filter')]) }}">All</a> <!-- ✅ Added "All" option -->
                    @else
                        <!-- Filter by Condition for other tabs -->
                        <a href="{{ route('device_management', ['condition' => 'working', 'filter' => request('filter')]) }}">Working</a>
                        <a href="{{ route('device_management', ['condition' => 'not-working', 'filter' => request('filter')]) }}">Not Working</a>
                        <a href="{{ route('device_management', ['condition' => 'needs-repair', 'filter' => request('filter')]) }}">Needs Repair</a>
                        <a href="{{ route('device_management', ['condition' => '', 'filter' => request('filter')]) }}">All Conditions</a>
                    @endif
                </div>
            </div>
        </form>
    </div>


    <div class="add-device-section">
    <form action="{{ route('device_management') }}" method="GET">
        <input type="hidden" name="filter" value="{{ request('filter', 'all') }}">
        <button type="button" class="add-device" onclick="openPopup('formPopup')">
            <span class="icon">➕</span> Add Device
        </button>
    </form>
</div>
</div>


        <div class="content">
            <div class="tab-content active">
                @if ($filter === 'new-deployment')
                    @if($filter === 'new-deployment' && isset($deployments) && $deployments->count() > 0)
                        <table class="device-table">
                            <thead>
                                <tr>
                                    <th>Control Number</th>
                                    <th>Purpose</th>
                                    <th>Status</th>
                                    <th>Components</th>
                                    <th>Software</th>
                                    <th>Received By</th>
                                    <th>Received Date</th>
                                    <th>QR CODE</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($deployments as $deployment)
                                    <tr>
                                        <td>{{ $deployment->control_number }}</td>
                                        <td>{{ $deployment->purpose }}</td>
                                        <td>{{ $deployment->status }}</td>
                                        <td>
                                            @if(is_array($deployment->components))
                                                {{ implode(', ', $deployment->components) }}
                                            @else
                                                {{ $deployment->components ?? 'No Components' }}
                                            @endif
                                        </td>
                                        <td>
                                            @if(is_array($deployment->software))
                                                {{ implode(', ', $deployment->software) }}
                                            @else
                                                {{ $deployment->software ?? 'No Software' }}
                                            @endif
                                        </td>
                                        <td>{{ $deployment->received_by }}</td>
                                        <td>{{ $deployment->received_date }}</td>
                                        
                                         <!-- QR Code column -->
                                         <td>
                                            <div class="qr-code" onclick="printQRCode()">
                                                {!! QrCode::size(100)->generate(route('generate.deployment.pdf', ['control_number' => $deployment->control_number])) !!}
                                            </div>
                                        </td>
                                        
                                        <!-- Actions column for View -->
                                        <td>
                                        <button class="view-btn" onclick="openDeploymentView({{ $deployment->id }})">
                                            <i class="fas fa-eye"></i> View
                                        </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="no-records">No Deployment Records Found</div>
                    @endif

                @else
                    @if ($serviceRequests && $serviceRequests->count() > 0)
                        <table class="device-table">
                            <thead>
                                <tr>
                                    <th>Form No.</th>
                                    <th>Service Type</th>
                                    <th>Employee Name</th>
                                    <th>Condition</th>
                                    <th>Status</th>
                                    <th>QR CODE</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($serviceRequests as $request)
                                    <tr>
                                        <td>{{ $request->form_no }}</td>
                                        <td style="color: {{ $request->service_type === 'walk_in' ? '#2563eb' : '#9333ea' }}; font-weight: bold;">
                                            {{ ucwords(str_replace('_', ' ', $request->service_type)) }}
                                        </td>
                                        <td>{{ ucwords(strtolower($request->name)) }}</td>
                                        <td>{{ $request->condition ? ucwords(strtolower($request->condition)) : 'No Condition Available' }}</td>
                                        <td style="color: {{ $request->status === 'in-repairs' ? '#dc2626' : '#16a34a' }}; font-weight: bold;">
                                            {{ ucwords(str_replace('-', ' ', $request->status)) }}
                                        </td>

                                        <td>
                                            @if($request->status === 'repaired')
                                                <div class="qr-code" onclick="printQRCode()">
                                                    {!! QrCode::size(100)->generate(route('generate.pdf', $request->form_no )) !!}
                                                </div>
                                            @else
                                                No QR Code Available
                                            @endif
                                        </td>
                                        <td>
                                            <button class="view-btn" onclick="openViewModal('{{ $request->form_no }}')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="status-button" onclick="openConfirmationModal('{{ $request->form_no }}')"
                                                @if($request->status === 'repaired') disabled @endif>
                                                <i class="fas fa-sync-alt"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="no-records">No Records Found</div>
                    @endif
                @endif
            </div>
        </div>

        <div class="pagination-container">
            <div class="results-count">
                @if ($filter === 'new-deployment')
                    @if ($deployments->count() > 0)
                        Showing {{ $deployments->firstItem() }} to {{ $deployments->lastItem() }} of {{ $deployments->total() }} results
                    @else
                        Showing 1 to 0 of 0 results
                    @endif
                @else
                    @if ($serviceRequests->count() > 0)
                        Showing {{ $serviceRequests->firstItem() }} to {{ $serviceRequests->lastItem() }} of {{ $serviceRequests->total() }} results
                    @else
                        Showing 1 to 0 of 0 results
                    @endif
                @endif
            </div>
            <div class="pagination-buttons">
                @if ($filter === 'new-deployment')
                    {{ $deployments->appends(['filter' => request('filter')])->links('pagination::bootstrap-4') }}
                @else
                    {{ $serviceRequests->appends(['filter' => request('filter')])->links('pagination::bootstrap-4') }}
                @endif
            </div>
        </div>

@include('modals.new_device_form')
@include('modals.view_device')
@include('modals.status_change')
@include('modals.new_device_deployment')
@include('modals.view_deployment')

<script src="{{ asset('js/Dev_Manage_Script.js') }}"></script>
<script>

function printQRCode() {
    var qrCodeDiv = document.querySelector('.qr-code');
    var printWindow = window.open('', '_blank', 'width=600,height=400');
    printWindow.document.write('<html><head><title>Print QR Code</title></head><body>');
    printWindow.document.write(qrCodeDiv.outerHTML);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.print();
}
document.addEventListener("DOMContentLoaded", function () {
        // Check if the filter is "new-deployment"
        const urlParams = new URLSearchParams(window.location.search);
        const filter = urlParams.get('filter');
        const addDeviceButton = document.querySelector(".add-device");

        if (filter === "new-deployment") {
            addDeviceButton.innerHTML = '<span class="icon">➕</span> New Device Record';
            addDeviceButton.setAttribute("onclick", "openDeploymentModal()");
        }
    });

    function filterDevices(filter, condition = '') {
        const url = new URL(window.location.href);
        url.searchParams.set('filter', filter);
        if (condition) {
            url.searchParams.set('condition', condition);
        }
        window.location.href = url.toString();
    }

    document.addEventListener("DOMContentLoaded", function () {
        const searchInput = document.getElementById("searchInput");
        const filterType = "{{ $filter }}"; // Get the filter type from Blade
        const tableBody = document.querySelector(".device-table tbody");

        searchInput.addEventListener("input", function () {
            const searchQuery = searchInput.value.trim().toLowerCase();
            fetchFilteredRecords(searchQuery);
        });

        function fetchFilteredRecords(searchQuery) {
            fetch(`{{ route('fetch.records') }}?filter=${filterType}&search=${searchQuery}`)
                .then(response => response.json())
                .then(data => {
                    renderData(data);
                })
                .catch(error => console.error("Error fetching data:", error));
        }

        function renderData(data) {
            const table = document.querySelector(".device-table"); // Select the table
            const tableBody = table.querySelector("tbody"); // Select the table body
            let noRecordsDiv = document.querySelector(".no-records"); // Check if "No Records Found" div exists

            if (!noRecordsDiv) {
                noRecordsDiv = document.createElement("div");
                noRecordsDiv.classList.add("no-search-record");
                noRecordsDiv.textContent = "NO RECORDS FOUND";
                table.parentElement.appendChild(noRecordsDiv); // Append after the table
            }

            tableBody.innerHTML = ""; // Clear previous rows

            if (data.length === 0) {
                table.style.display = "none"; // Hide the table
                noRecordsDiv.style.display = "block"; // Show "No Records Found"
                return;
            }

            table.style.display = "table"; // Show the table when data exists
            noRecordsDiv.style.display = "none"; // Hide "No Records Found"

            data.forEach(item => {
                const row = document.createElement("tr");

                if (filterType === "new-deployment") {
                    row.innerHTML = `
                        <td>${item.control_number}</td>
                        <td>${item.purpose || "No Purpose"}</td>
                        <td>${item.status || "Unknown"}</td>
                        <td>${Array.isArray(item.components) ? item.components.join(", ") : item.components || "No Components"}</td>
                        <td>${Array.isArray(item.software) ? item.software.join(", ") : item.software || "No Software"}</td>
                        <td>${item.received_by || "Unknown"}</td>
                        <td>${item.received_date || "No Date"}</td>
                        <td>
                            ${item.qr_code 
                                ? `<img src="${item.qr_code}" alt="QR Code" width="100" height="100" onclick="printQRCode()">` 
                                : "No QR Code Available"}
                        </td>
                        <td>
                            <button class="view-btn" onclick="openDeploymentView('${item.id}')">
                                <i class="fas fa-eye"></i> View
                            </button>
                        </td>
                    `;
                } else {
                    row.innerHTML = `
                        <td>${item.form_no}</td>
                        <td style="color: ${item.service_type === "walk_in" ? "#2563eb" : "#9333ea"}; font-weight: bold;">
                            ${capitalize(item.service_type.replace("_", " "))}</td>
                        <td>${capitalize(item.name || "No Name")}</td>
                        <td>${item.condition ? capitalize(item.condition) : "No Condition Available"}</td>
                        <td style="color: ${item.status === "in-repairs" ? "#dc2626" : "#16a34a"}; font-weight: bold;">
                            ${capitalize(item.status.replace("-", " "))}</td>
                        <td>
                            ${item.qr_code 
                                ? `<img src="${item.qr_code}" alt="QR Code" width="100" height="100" onclick="printQRCode()">` 
                                : "No QR Code Available"}
                        </td>
                        <td>
                            <button class="view-btn" onclick="openViewModal('${item.form_no}')">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="status-button" onclick="openConfirmationModal('${item.form_no}')" 
                                ${item.status === "repaired" ? "disabled" : ""}>
                                <i class="fas fa-sync-alt"></i>
                            </button>
                        </td>
                    `;
                }

                tableBody.appendChild(row);
            });
        }

        function capitalize(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        }
});

</script>
</body>
</html>
