<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechTrack Report and Analytics</title>
    <link rel="icon" href="{{ asset('images/Systembrowserlogo.png') }}" type="image/png">
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
                @php
                    $metrics = [
                        ['icon' => 'fas fa-ticket-alt', 'title' => 'Pending Tickets', 'count' => $pendingTickets],
                        ['icon' => 'fas fa-check-circle', 'title' => 'Closed Tickets', 'count' => $solvedTickets],
                        ['icon' => 'fas fa-share', 'title' => 'Endorsed Tickets', 'count' => $endorsedTickets],
                        ['icon' => 'fas fa-file-alt', 'title' => 'Technical Reports', 'count' => $technicalReports],
                    ];
                @endphp
                @foreach ($metrics as $metric)
                    <div class="metric-card">
                        <i class="{{ $metric['icon'] }}"></i>
                        <h3>{{ $metric['title'] }}</h3>
                        <p>{{ $metric['count'] ?? 0 }}</p>
                    </div>
                @endforeach
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
            <div style="display: flex; align-items: center; justify-content: space-between; 
                background-color: #f5f5f5; padding: 10px 20px; border-radius: 5px;">
                <h2>Performance Details (Team Analytics)</h2>
                <a href="{{ url('/export-technician-performance?month=' . $selectedDate) }}" class="exportButton">Export CSV</a>
            </div>

            <table class="team-analytics-table">
                <thead>
                    <tr>
                        <th>Technician Name / ID</th>
                        <th>Tickets Assigned</th>
                        <th>Tickets Solved</th>
                        <th>Endorsed Tickets</th>
                        <th>Pull Out Device</th>
                        <th>Technical Reports</th>
                        <th>Rating</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($technicians as $technician)
                        <tr>
                            <td>{{ $technician->first_name }} {{ $technician->last_name }} / {{ $technician->employee_id }}</td>
                            <td>{{ $technician->tickets_assigned ?? 'None' }}</td>
                            <td>{{ $technician->tickets_solved ?? 'None' }}</td>
                            <td>{{ $technician->endorsed_tickets ?? 'None' }}</td>
                            <td>{{ $technician->pull_out ?? 'None' }}</td>
                            <td>{{ $technician->technical_reports ?? 'None' }}</td>
                            <td>{{ $technician->rating ?? 'No Rating' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
// Handle Date Picker Change
document.getElementById('datePicker').addEventListener('change', function() {
    const selectedDate = this.value;
    const url = new URL(window.location.href);
    url.searchParams.set('date', selectedDate);
    window.location.href = url.toString();
});

// Donut Chart
new Chart(document.getElementById('combinedMetricsChart').getContext('2d'), { 
    type: 'doughnut', 
    data: {
        labels: ['Pending', 'Closed', 'Endorsed', 'Technical-Report'],
        datasets: [{
            data: [{{ $pendingTickets }}, {{ $solvedTickets }}, {{ $endorsedTickets }}, {{ $technicalReports }}],
            backgroundColor: ['#003067', '#0073e6', '#28a745', '#ffc107'],
            hoverOffset: 20
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'right' } },
        cutout: '65%'
    }
});

// Technician Performance Chart
const ctx = document.getElementById('technicalSupportChart');

if (ctx) {
    let techData = @json($technicianChartData) || [];

    // Ensure data format is correct
    techData = techData.map(dataset => ({
        label: dataset.label || "Unknown Technician",
        data: dataset.data || Array(31).fill(0),
        borderColor: dataset.borderColor || "#000000",
        backgroundColor: dataset.backgroundColor || "rgba(0,0,0,0.2)",
        fill: true
    }));

    console.log("Processed Tech Data:", techData);

    // Handle no data case
    if (techData.length === 0 || techData.every(d => d.data.every(v => v === 0))) {
        techData = [{
            label: "No data available for technician performance chart.",
            data: Array(31).fill(0),
            borderColor: "#cccccc",
            backgroundColor: "rgba(200,200,200,0.5)",
            fill: true
        }];
        console.warn("No data available for technician performance chart.");
    }

    new Chart(ctx.getContext('2d'), {
        type: 'line',
        data: { labels: Array.from({length: 31}, (_, i) => i + 1), datasets: techData },
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
