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
    @include('components.sidebar')

    <div class="main-content">
        @include('components.navbar')

        <!-- Tabs -->
        <div class="header">
            <div class="tabs">
                <button class="tab-button {{ request('filter', 'all') == 'all' ? 'active' : '' }}" onclick="filterDevices('all')">
                    <i class="fas fa-laptop"></i> All Devices
                </button>
                <button class="tab-button {{ request('filter') == 'in-repairs' ? 'active' : '' }}" onclick="filterDevices('in-repairs')">
                    <i class="fas fa-tools"></i> In Repairs
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
                <button class="search-button"><i class="fas fa-search"></i></button>
            </div>

            <div class="spacer"></div>

            <div class="filter-section">
                <form action="{{ route('device_management') }}" method="GET">
                    <div class="dropdown">
                        <button class="dropdown-button">
                            <i class="fas fa-filter"></i> Filter by Condition <span class="arrow">&#x25BC;</span>
                        </button>
                        <div class="dropdown-content">
                            <a href="{{ route('device_management', ['condition' => 'working']) }}">Working</a>
                            <a href="{{ route('device_management', ['condition' => 'not-working']) }}">Not Working</a>
                            <a href="{{ route('device_management', ['condition' => 'needs-repair']) }}">Needs Repair</a>
                            <a href="{{ route('device_management', ['condition' => '']) }}">All Conditions</a>
                        </div>
                    </div>
                </form>
            </div>



            <div class="add-device-section">
                <button class="add-device" onclick="openPopup('formPopup')">
                    <span class="icon">➕</span> Add Device
                </button>
            </div>
        </div>

        <!-- Devices List -->
        <div class="content">
            <div class="tab-content active">
                @if ($serviceRequests->count() > 0)
                    <table class="device-table">
                        <thead>
                            <tr>
                                <th>Form No.</th>
                                <th>Service Type</th>
                                <th>Department</th>
                                <th>Condition</th> <!-- New column for Condition -->
                                <th>Status</th> <!-- Status column -->
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($serviceRequests as $request)
                                @php
                                    $remarks = $request->equipmentDescriptions->pluck('remarks')->filter()->implode(', ');
                                    $condition = json_decode($request->condition, true); // Decoding the condition JSON
                                @endphp
                                <tr>
                                    <td>{{ $request->form_no }}</td>
                                    <td>{{ ucfirst($request->service_type) }}</td>
                                    <td>{{ $request->department }}</td>
                                    <td>
                                        @if($request->condition)
                                            {{ $request->condition }}  <!-- Display the condition if it's available -->
                                        @else
                                            No Condition Available  <!-- Display this message if no condition is set -->
                                        @endif
                                    </td>
                                    <td>{{ ucfirst($request->status) }}</td>
                                    <td>
                                        <button class="view-btn" onclick="openViewModal('{{ $request->form_no }}')">View</button>
                                        <button class="remarks-button" onclick="addRemarks('{{ $request->form_no }}')">
                                            <i class="fas fa-comment"></i> Remarks
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="no-records">NO RECORDS FOUND</div>
                @endif

                <div class="pagination-container">
                    <div class="results-count">
                        @if ($serviceRequests->count() > 0)
                            Showing {{ $serviceRequests->firstItem() }} to {{ $serviceRequests->lastItem() }} of {{ $serviceRequests->total() }} results
                        @else
                            Showing 1 to 0 of 0 results
                        @endif
                    </div>
                    <div class="pagination-buttons">
                        {{ $serviceRequests->appends(['filter' => request('filter')])->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('modals.new_device_form')
@include('modals.view_device')

<script src="{{ asset('js/Dev_Manage_Script.js') }}"></script>
<script>
    function filterDevices(filter, condition = '') {
        const url = new URL(window.location.href);
        url.searchParams.set('filter', filter);
        if (condition) {
            url.searchParams.set('condition', condition);
        }
        window.location.href = url.toString();
    }

    function addRemarks(formNo) {
        let remark = prompt("Enter remarks for " + formNo + ":");
        if (remark) {
            fetch(`/add-remarks/${formNo}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ remark })
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      alert('Remarks updated!');
                      location.reload();
                  } else {
                      alert('Failed to update remarks.');
                  }
              });
        }
    }
</script>
</body>
</html>
