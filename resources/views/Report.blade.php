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

    <!-- Include Vite bundle -->
    <!-- @vite('resources/js/app.jsx')  This includes your compiled React assets -->
</head>
<body>
<div class="container">
    <!-- Sidebar -->
    @include('components.sidebar')

    <!-- Main Content Area -->
    <div class="main-content">
        <!-- Navbar -->
        @include('components.navbar')

        <!-- Key Metrics Summary Section -->
        <div class="key-metrics-summary">
            <h2>Key Metrics Summary (KPIs)</h2>
            <div class="metrics-cards">
                <div class="metric-card">
                    <i class="fas fa-ticket-alt"></i>
                    <h3>Pending Tickets</h3>
                    <p>15</p>
                </div>
                <div class="metric-card">
                    <i class="fas fa-check-circle"></i>
                    <h3>Solved Tickets</h3>
                    <p>50</p>
                </div>
                <div class="metric-card">
                    <i class="fas fa-share"></i>
                    <h3>Endorsed Tickets</h3>
                    <p>10</p>
                </div>
                <div class="metric-card">
                    <i class="fas fa-file-alt"></i>
                    <h3>Technical Reports</h3>
                    <p>25</p>
                </div>
                <div class="metric-card">
                    <i class="fas fa-tools"></i>
                    <h3>Devices in Repair</h3>
                    <p>7</p>
                </div>
                <div class="metric-card">
                    <i class="fas fa-cogs"></i>
                    <h3>Repaired Devices</h3>
                    <p>30</p>
                </div>
            </div>
        </div>

        <!-- Metrics Charts -->
        <div class="charts-container">
            <!-- Donut Chart -->
            <div class="donut-chart">
                <canvas id="combinedMetricsChart"></canvas>
            </div>
            <!-- Line Chart -->
            <div class="line-chart">
                <canvas id="technicalSupportChart"></canvas>
            </div>
        </div>

        <!-- Performance Details (Team Analytics) Section -->
        <div class="performance-details">
            <h2>Performance Details (Team Analytics)</h2>
            <table class="team-analytics-table">
                <thead>
                    <tr>
                        <th>Technician Name/ID</th>
                        <th>Tickets Assigned</th>
                        <th>Tickets Solved</th>
                        <th>Average Resolution Time</th>
                        <th>Endorsed Tickets</th>
                        <th>Technical Reports Submitted</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Tech A / 001</td>
                        <td>20</td>
                        <td>15</td>
                        <td>3 hours</td>
                        <td>5</td>
                        <td>3</td>
                    </tr>
                    <tr>
                        <td>Tech B / 002</td>
                        <td>25</td>
                        <td>20</td>
                        <td>2 hours</td>
                        <td>3</td>
                        <td>4</td>
                    </tr>
                    <tr>
                        <td>Tech C / 003</td>
                        <td>18</td>
                        <td>16</td>
                        <td>4 hours</td>
                        <td>2</td>
                        <td>5</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // Data for Donut Chart
    const donutData = {
        labels: [
            'Pending Tickets',
            'Solved Tickets',
            'Endorsed Tickets',
            'Technical Reports',
            'Devices in Repair',
            'Repaired Devices'
        ],
        datasets: [{
            data: [15, 50, 10, 25, 7, 30],
            backgroundColor: ['#003067', '#0073e6', '#28a745', '#ffc107', '#dc3545', '#6f42c1'],
            hoverOffset: 20
        }]
    };

    const donutOptions = {
        responsive: true,
        plugins: {
            legend: {
                position: 'right',
                align: 'start',
                labels: { usePointStyle: true, font: { size: 14 } }
            },
            tooltip: {
                callbacks: {
                    label: (tooltipItem) => `${tooltipItem.label}: ${tooltipItem.raw}`
                }
            }
        },
        cutout: '65%' // Adjusted for a slightly larger chart
    };

    // Initialize Donut Chart
    const donutCtx = document.getElementById('combinedMetricsChart').getContext('2d');
    new Chart(donutCtx, { type: 'doughnut', data: donutData, options: donutOptions });

    // Data for Line Chart
    const daysInMonth = Array.from({ length: 31 }, (_, i) => i + 1);
    const techData = {
        labels: daysInMonth,
        datasets: [
            {
                label: 'Tech A',
                data: Array.from({ length: 31 }, () => Math.floor(Math.random() * 20)),
                borderColor: '#003067',
                backgroundColor: 'rgba(0, 48, 103, 0.2)',
                fill: true
            },
            {
                label: 'Tech B',
                data: Array.from({ length: 31 }, () => Math.floor(Math.random() * 20)),
                borderColor: '#0073e6',
                backgroundColor: 'rgba(0, 115, 230, 0.2)',
                fill: true
            },
            {
                label: 'Tech C',
                data: Array.from({ length: 31 }, () => Math.floor(Math.random() * 20)),
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.2)',
                fill: true
            }
        ]
    };

    const lineOptions = {
        responsive: true,
        plugins: {
            legend: { position: 'top', align: 'end' },
        },
        scales: {
            x: { title: { display: true, text: 'Days of the Month' } },
            y: { title: { display: true, text: 'Number of Tickets' }, beginAtZero: true }
        }
    };

    // Initialize Line Chart
    const lineCtx = document.getElementById('technicalSupportChart').getContext('2d');
    new Chart(lineCtx, { type: 'line', data: techData, options: lineOptions });
</script>
</body>
</html>
