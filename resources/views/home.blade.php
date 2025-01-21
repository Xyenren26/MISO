<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Management Information System</title>
    <link rel="stylesheet" href="{{ asset('css/Home_Style.css') }}">
    
    <!-- Include Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <!-- Include Chart.js for graph rendering -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<div class="container">
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
                <div class="metrics-box">
                    <h4>Pending Ticket Request</h4>
                    <div class="metrics-content">
                        <p id="pendingTicketCount">{{ $ticketCountsByStatus['in-progress'] ?? 0 }}</p> <!-- Pending count -->
                        <canvas id="pendingTicketGraph"></canvas> <!-- Bar graph -->
                    </div>
                </div>

                <!-- Solved Tickets -->
                <div class="metrics-box">
                    <h4>Solved Ticket Request</h4>
                    <div class="metrics-content">
                        <p id="solvedTicketCount">{{ $ticketCountsByStatus['completed'] ?? 0 }}</p> <!-- Solved count -->
                        <canvas id="solvedTicketGraph"></canvas>
                    </div>
                </div>

                <!-- Endorsed Tickets -->
                <div class="metrics-box">
                    <h4>Endorsed Ticket</h4>
                    <div class="metrics-content">
                        <p id="endorsedTicketCount">{{ $ticketCountsByStatus['endorsed'] ?? 0 }}</p> <!-- Endorsed count -->
                        <canvas id="endorsedTicketGraph"></canvas>
                    </div>
                </div>

                <!-- Technical Reports -->
                <div class="metrics-box">
                    <h4>Technical Report</h4>
                    <div class="metrics-content">
                        <p id="technicalReportCount">{{ $ticketCountsByStatus['technical-report'] ?? 0 }}</p> <!-- Technical report count -->
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
    
    console.log('Solved Data by Day:', @json($solvedDataByDay));
    console.log('Technical Report Data by Day:', @json($technicalReportDataByDay));



    // Format the ticket count values
    document.getElementById("pendingTicketCount").textContent = formatNumber(@json($ticketCountsByStatus['in-progress'] ?? 0));
    document.getElementById("solvedTicketCount").textContent = formatNumber(@json($ticketCountsByStatus['completed'] ?? 0));
    document.getElementById("endorsedTicketCount").textContent = formatNumber(@json($ticketCountsByStatus['endorsed'] ?? 0));
    document.getElementById("technicalReportCount").textContent = formatNumber(@json($ticketCountsByStatus['technical-report'] ?? 0));
</script>

<!-- to me huwag mong galawain lagi mo kasing namomove-->
<script src="{{ asset('js/Home_Script.js') }}"></script>
</body>
</html>
