<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Management Information System</title>
    <link rel="stylesheet" href="{{ asset('css/Report_Style.css') }}">

    <!-- Include Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Include Chart.js for graph rendering -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="container">
    @include('components.sidebar')
    <div class="main-content">
        @include('components.navbar')

        <!-- Key Metrics Summary Section -->
        <div class="key-metrics-summary">
            <h2>Key Metrics Summary (KPIs)</h2>
            <div class="metrics-cards">
                <div class="metric-card"><i class="fas fa-ticket-alt"></i><h3>Pending Tickets</h3><p>{{ $pendingTickets }}</p></div>
                <div class="metric-card"><i class="fas fa-check-circle"></i><h3>Solved Tickets</h3><p>{{ $solvedTickets }}</p></div>
                <div class="metric-card"><i class="fas fa-share"></i><h3>Endorsed Tickets</h3><p>{{ $endorsedTickets }}</p></div>
                <div class="metric-card"><i class="fas fa-file-alt"></i><h3>Technical Reports</h3><p>{{ $technicalReports }}</p></div>
                <div class="metric-card"><i class="fas fa-tools"></i><h3>Devices in Repair</h3><p>{{ $devicesInRepair }}</p></div>
                <div class="metric-card"><i class="fas fa-cogs"></i><h3>Repaired Devices</h3><p>{{ $repairedDevices }}</p></div>
            </div>
        </div>

        <!-- Date Picker for Filtering -->
        <div class="date-filter">
            <label for="datePicker">Select Date:</label>
            <input type="month" id="datePicker" max="{{ date('Y-m') }}" value="{{ $selectedDate }}">
        </div>

        <!-- Metrics Charts -->
        <div class="charts-container">
            <div class="donut-chart"><canvas id="combinedMetricsChart"></canvas></div>
            <div class="line-chart"><canvas id="technicalSupportChart"></canvas></div>
        </div>

        <!-- Performance Details (Team Analytics) Section -->
        <div class="performance-details">
            <h2>Performance Details (Team Analytics)</h2>
            <table class="team-analytics-table">
                <thead>
                    <tr>
                        <th>Technician Name / ID</th>
                        <th>Tickets Assigned</th>
                        <th>Tickets Solved</th>
                        <th>Average Resolution Time</th>
                        <th>Endorsed Tickets</th>
                        <th>Pull Out Device</th>
                        <th>Technical Reports Submitted</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($technicians as $technician)
                        <tr>
                            <td>{{ $technician->first_name }} {{ $technician->last_name }} / {{ $technician->employee_id }}</td>
                            <td>{{ $technician->tickets_assigned ?? 'None' }}</td>
                            <td>{{ $technician->tickets_solved ?? 'None' }}</td>
                            <td>{{ $technician->avg_resolution_time ? $technician->avg_resolution_time . ' hours' : 'None' }}</td>
                            <td>{{ $technician->endorsed_tickets ?? 'None' }}</td>
                            <td>{{ $technician->pull_out ?? 'None' }}</td>
                            <td>{{ $technician->technical_reports ?? 'None' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
document.getElementById('datePicker').addEventListener('change', function() {
    const selectedDate = this.value;
    const url = new URL(window.location.href);
    url.searchParams.set('date', selectedDate);
    window.location.href = url.toString();
});

//  Donut Chart Data
const donutData = {
    labels: ['Pending', 'Solved', 'Endorsed', 'Reports', 'In Repair', 'Repaired'],
    datasets: [{
        data: [{{ $pendingTickets }}, {{ $solvedTickets }}, {{ $endorsedTickets }}, {{ $technicalReports }}, {{ $devicesInRepair }}, {{ $repairedDevices }}],
        backgroundColor: ['#003067', '#0073e6', '#28a745', '#ffc107', '#dc3545', '#6f42c1'],
        hoverOffset: 20
    }]
};

const donutOptions = {
    responsive: true,
    plugins: {
        legend: { position: 'right', labels: { usePointStyle: true, font: { size: 14 } } },
        tooltip: { callbacks: { label: (tooltipItem) => `${tooltipItem.label}: ${tooltipItem.raw}` } }
    },
    cutout: '65%'
};

//  Donut Chart Initialization
new Chart(document.getElementById('combinedMetricsChart').getContext('2d'), { 
    type: 'doughnut', 
    data: donutData, 
    options: donutOptions 
});

//  Technician Performance Chart
const daysInMonth = [...Array(31).keys()].map(i => i + 1); // Generate days 1-31

const ctx = document.getElementById('technicalSupportChart');

if (ctx) {
    let techData = @json($technicianChartData);

    // Ensure it's an array
    if (!Array.isArray(techData) || techData.length === 0) {
        console.warn("Invalid or empty techData. Resetting to empty array.");
        techData = [];
    }


    techData = techData.map(dataset => ({
        label: dataset.label || "Unknown Technician",
        data: Array.isArray(dataset.data) ? dataset.data.slice(0, new Date().getDate()) : Array(31).fill(0),
        borderColor: dataset.borderColor || "#000000",
        backgroundColor: dataset.backgroundColor || "rgba(0,0,0,0.2)",
        fill: true
    }));

    console.log("Processed Tech Data:", techData);


    // Check if dataset is empty or all values are zero
    const hasNoData = techData.length === 0 || techData.every(dataset => dataset.data.every(v => v === 0));

    if (hasNoData) {
        techData = [{
            label: "Report: 231 No data available for technician performance chart.",
            data: Array(31).fill(0),
            borderColor: "#cccccc",
            backgroundColor: "rgba(200,200,200,0.5)",
            fill: true
        }];
        console.warn("No data available for technician performance chart.");
    }

    new Chart(ctx.getContext('2d'), {
        type: 'line',
        data: { labels: daysInMonth, datasets: techData },
        options: {
            responsive: true,
            plugins: { legend: { position: 'top' } },
            scales: {
                x: { title: { display: true, text: 'Days of the Month' } },
                y: { title: { display: true, text: 'Tickets' }, beginAtZero: true }
            }
        }
    });
}
</script>
</body>
</html>
