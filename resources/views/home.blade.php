<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechTrack</title>
    <link rel="icon" href="{{ asset('images/Systembrowserlogo.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('css/Home_Style.css') }}">
    <!-- Include Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Include Chart.js for graph rendering -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<div class="container">
    @auth
        @include('components.greetings', ['accountType' => auth()->user()->account_type])
    @endauth
    <!-- Sidebar -->
    @include('components.sidebar')

    <!-- Main Content Area -->
    <div class="main-content">
        <!-- Navbar -->
        @include('components.navbar')

        <!-- Image Container -->
        <div class="content-wrapper">
            <!-- Metrics Container (4 Blocks horizontally) -->
            <div class="metrics-container">
                <!-- Pending Tickets -->
                <div class="metrics-box" onclick="window.location.href='{{ route('ticket') }}';">
                    <h4>Pending Ticket Request</h4>
                    <div class="metrics-content">
                        <p id="pendingTicketCount">{{ $ticketCountsByStatus['in-progress'] ?? 0 }}</p>
                        <canvas id="pendingTicketGraph"></canvas>
                    </div>
                </div>

                <!-- Solved Tickets -->
                <div class="metrics-box">
                    <h4>Solved Ticket Request</h4>
                    <div class="metrics-content">
                        <p id="solvedTicketCount">{{ $ticketCountsByStatus['completed'] ?? 0 }}</p>
                        <canvas id="solvedTicketGraph"></canvas>
                    </div>
                </div>

                <!-- Endorsed Tickets -->
                <div class="metrics-box">
                    <h4>Endorsed Ticket</h4>
                    <div class="metrics-content">
                        <p id="endorsedTicketCount">{{ $ticketCountsByStatus['endorsed'] ?? 0 }}</p>
                        <canvas id="endorsedTicketGraph"></canvas>
                    </div>
                </div>

                <!-- Technical Reports -->
                <div class="metrics-box">
                    <h4>Technical Report</h4>
                    <div class="metrics-content">
                        <p id="technicalReportCount">{{ $ticketCountsByStatus['technical-report'] ?? 0 }}</p>
                        <canvas id="technicalReportGraph"></canvas>
                    </div>
                </div>
            </div>

            <!-- Graph and Metrics -->
            <div class="graph-metrics-container">
                <!-- Graph Summary Performance -->
                <div class="graph-container">
                    <h3>Personal Performance</h3>
                    <!-- Month Picker inside Graph -->
                    <form id="monthForm" action="{{ route('home') }}" method="GET">
                        <label for="monthPicker" class="month-label">Select Month: </label>
                        <input type="month" id="monthPicker" name="month" class="month-picker" max="" onchange="updatePerformanceGraph()" oninput="restrictFutureMonth(event)">
                    </form>
                    <canvas id="ticketPerformanceGraph"></canvas>
                </div>

                <!-- Donut Chart for Device Management -->
                <div class="graph-container-donut">
                    <h3>Device Management</h3>
                    <canvas id="deviceManagementGraph"></canvas>
                </div>
            </div>
            
            <!-- Ticket Management Records -->
            <div class="ticket-table-container">
                <div class="ticket-table-wrapper">
                    <h3>Ticket Management Records</h3>
                    
                    <!-- Filters -->
                    <div class="filters">
                        <label for="filter">Filter By:</label>
                        <select id="filter" onchange="toggleFilters()">
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                            <option value="annually">Annually</option>
                        </select>
                        <select id="monthFilter" class="hidden">
                            <option value="01">January</option>
                            <option value="02">February</option>
                            <option value="03">March</option>
                            <option value="04">April</option>
                            <option value="05">May</option>
                            <option value="06">June</option>
                            <option value="07">July</option>
                            <option value="08">August</option>
                            <option value="09">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select>
                        <input type="number" id="yearFilter" placeholder="Enter Year" class="hidden" min="2000" max="2099" value="{{ date('Y') }}">
                        <button onclick="fetchTickets()" class="filter-button">Apply Filter</button>
                        <button onclick="exportTickets()" class="export-button">Export</button>
                    </div>

                    <!--for export purpose -->
                    <input type="hidden" id="userName" value="{{ auth()->user()->name }}">

                    <!-- Table -->
                    <div class="table-wrapper">
                        <table id="ticketTable">
                            <thead>
                                <tr>
                                    <th>Control No</th>
                                    <th>Date and Time</th>
                                    <th>Employee Name</th>
                                    <th>Department</th>
                                    <th>Concern</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Remarks</th>
                                    <th>Rating</th>
                                    <th>Rating Remark</th>
                                </tr>
                            </thead>
                            <tbody id="ticketTableBody">
                                @foreach ($ticketRecords as $ticket)
                                    <tr>
                                        <td>{{ ucwords(strtolower($ticket->control_no)) }}</td>
                                        <td>{{ ucwords(strtolower($ticket->date_time)) }}</td>
                                        <td>{{ ucwords(strtolower($ticket->name)) }}</td>
                                        <td>{{ ucwords(strtolower($ticket->department)) }}</td>
                                        <td>{{ ucwords(strtolower($ticket->concern)) }}</td>
                                        <td>{{ ucwords(strtolower($ticket->priority)) }}</td>
                                        <td>{{ ucwords(strtolower($ticket->status)) }}</td>
                                        <td>{{ ucwords(strtolower($ticket->remarks)) }}</td>
                                        <td>{{ $ticket->rating_percentage ? $ticket->rating_percentage . '%' : 'N/A' }}</td>
                                        <td>{{ ucwords(strtolower($ticket->remark ?? 'N/A')) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Keep the PHP variables in the Blade file
    const pendingData = @json($pendingData);
    const solvedData = @json($solvedData);
    const endorsedData = @json($endorsedData);
    const technicalReportData = @json($technicalReportData);
    
    const inRepairs = @json($inRepairsCount);
    const repaired = @json($repairedCount);

    const solvedDataByDay = @json($solvedDataByDay);
    const technicalReportDataByDay = @json($technicalReportDataByDay);
    const ticketCountsByStatus = @json($ticketCountsByStatus);

    // Format the ticket count values
    document.getElementById("pendingTicketCount").textContent = formatNumber(@json($ticketCountsByStatus['in-progress'] ?? 0));
    document.getElementById("solvedTicketCount").textContent = formatNumber(@json($ticketCountsByStatus['completed'] ?? 0));
    document.getElementById("endorsedTicketCount").textContent = formatNumber(@json($ticketCountsByStatus['endorsed'] ?? 0));
    document.getElementById("technicalReportCount").textContent = formatNumber(@json($ticketCountsByStatus['technical-report'] ?? 0));
</script>

<script src="{{ asset('js/Home_Script.js') }}"></script>
</body>
</html>