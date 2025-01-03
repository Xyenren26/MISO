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

            <!-- Graph and Metrics -->
            <div class="graph-metrics-container">
                <!-- Graph Summary Performance -->
                <div class="graph-container">
                    <h3>Ticket Performance (Solved Tickets by Day)</h3>
                    <canvas id="ticketPerformanceGraph"></canvas>
                </div>

                <!-- Metrics Section for Device Management -->
                <div class="metrics-section">
                    <h3>Device Management</h3>
                    <div class="metrics-box">
                        <h4>In Repairs</h4>
                        <p>25</p> <!-- Example number -->
                    </div>
                    <div class="metrics-box">
                        <h4>Repaired</h4>
                        <p>25</p> <!-- Example number -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include your JavaScript -->
<script src="{{ asset('js/Home_Script.js') }}"></script>
</body>
</html>
