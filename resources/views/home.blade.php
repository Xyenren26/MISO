<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Management Information System</title>
    
    <!-- Include your CSS -->
    <link rel="stylesheet" href="{{ asset('css/Home_Style.css') }}">
    
    <!-- Include Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <!-- Include Chart.js for graph rendering (or your preferred graph library) -->
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
                <div class="metrics-box">
                    <h4>Pending Ticket Request</h4>
                    <p>0</p> <!-- Example number -->
                </div>
                <div class="metrics-box">
                    <h4>Solved Ticket Request</h4>
                    <p>0</p> <!-- Example number -->
                </div>
                <div class="metrics-box">
                    <h4>Endorsed Ticket</h4>
                    <p>0</p> <!-- Example number -->
                </div>
                <div class="metrics-box">
                    <h4>Technical Report</h4>
                    <p>0</p> <!-- Example number -->
                </div>
            </div>

            <!-- Graph Summary Performance -->
            <div class="graph-container">
                <h3>Graph Summary Performance</h3>
                <canvas id="ticketPerformanceGraph"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Include your JavaScript -->
<script src="{{ asset('js/Home_Script.js') }}"></script>

<!-- Chart.js Script to render the graph -->
<script>
    const ctx = document.getElementById('ticketPerformanceGraph').getContext('2d');
    const ticketPerformanceGraph = new Chart(ctx, {
        type: 'bar', // You can change this to 'line' or any other type of graph
        data: {
            labels: ['Pending Ticket Request', 'Solved Ticket Request', 'Endorsed Ticket', 'Technical Report'],
            datasets: [{
                label: 'Ticket Requests',
                data: [0, 0, 0, 0], // Replace with dynamic data if needed
                backgroundColor: ['#ff9999', '#66b3ff', '#99ff99', '#ffcc99'],
                borderColor: ['#ff6666', '#3399ff', '#66cc66', '#ff9966'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

</body>
</html>
