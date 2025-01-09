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
                        <p>{{ $ticketCountsByStatus['in-progress'] ?? 0 }}</p> <!-- Pending count -->
                        <canvas id="pendingTicketGraph"></canvas> <!-- Bar graph -->
                    </div>
                </div>

                <!-- Solved Tickets -->
                <div class="metrics-box">
                    <h4>Solved Ticket Request</h4>
                    <div class="metrics-content">
                        <p>{{ $ticketCountsByStatus['completed'] ?? 0 }}</p> <!-- Solved count -->
                        <canvas id="solvedTicketGraph"></canvas>
                    </div>
                </div>

                <!-- Endorsed Tickets -->
                <div class="metrics-box">
                    <h4>Endorsed Ticket</h4>
                    <div class="metrics-content">
                        <p>{{ $ticketCountsByStatus['endorsed'] ?? 0 }}</p> <!-- Endorsed count -->
                        <canvas id="endorsedTicketGraph"></canvas>
                    </div>
                </div>

                <!-- Technical Reports -->
                <div class="metrics-box">
                    <h4>Technical Report</h4>
                    <div class="metrics-content">
                        <p>{{ $ticketCountsByStatus['technical-report'] ?? 0 }}</p> <!-- Technical report count -->
                        <canvas id="technicalReportGraph"></canvas>
                    </div>
                </div>
            </div>

            <!-- Graph and Metrics -->
            <div class="graph-metrics-container">
                <!-- Graph Summary Performance -->
                <div class="graph-container">
                    <h3>Personal Performance</h3>
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

<!-- Include your JavaScript -->
<script src="{{ asset('js/Home_Script.js') }}"></script>

<script>
    // Labels and colors
    const labels = ['Urgent', 'Semi-Urgent', 'Non-Urgent'];
    const backgroundColors = ['#FF0000', '#FFA500', '#008000']; // Red, Orange, Green
    const borderColors = ['#FF0000', '#FFA500', '#008000']; // Red, Orange, Green

    // Data passed from the backend
    const pendingData = @json($pendingData);
    const solvedData = @json($solvedData);
    const endorsedData = @json($endorsedData);
    const technicalReportData = @json($technicalReportData);

    // Initialize charts
    createVerticalBarChart(document.getElementById('pendingTicketGraph'), pendingData, labels, backgroundColors, borderColors);
    createVerticalBarChart(document.getElementById('solvedTicketGraph'), solvedData, labels, backgroundColors, borderColors);
    createVerticalBarChart(document.getElementById('endorsedTicketGraph'), endorsedData, labels, backgroundColors, borderColors);
    createVerticalBarChart(document.getElementById('technicalReportGraph'), technicalReportData, labels, backgroundColors, borderColors);
    
    const inRepairs = @json($inRepairsCount); // "In Repairs" devices
    const repaired = @json($repairedCount);   // "Repaired" devices


// Calculate the total number of devices
var totalDevices = inRepairs + repaired;

// Completing the Device Management Doughnut Chart
var ctx2 = document.getElementById('deviceManagementGraph').getContext('2d');
var deviceManagementGraph = new Chart(ctx2, {
    type: 'doughnut', // Set chart type to doughnut
    data: {
        labels: ['In Repairs', 'Repaired'], // Labels for the chart sections
        datasets: [{
            data: [inRepairs, repaired], // Data for in repairs and repaired
            backgroundColor: ['#FF6347', '#32CD32'], // Red and Green colors
            borderColor: '#fff', // White border color for the chart sections
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top'
            },
            tooltip: {
                callbacks: {
                    label: function(tooltipItem) {
                        return tooltipItem.label + ': ' + tooltipItem.raw;
                    }
                }
            }
        }
    }
});


</script>


</body>
</html>
