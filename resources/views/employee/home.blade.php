<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechTrack</title>
    <link rel="icon" href="{{ asset('images/Systembrowserlogo.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('css/employee/Emp_Home_Style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/employee/Home_Style.css') }}">
    
    <!-- Include Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Include AOS for animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.1/aos.css">

    <!-- Include Chart.js for future graph rendering -->
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

        <!-- Dashboard Layout -->
        <div class="dashboard-container">
            
            <!-- Image Container (Now on Top) -->
            <div class="image-container" data-aos="fade-down">
                <img src="{{ asset('images/image-emp.png') }}" alt="Company Image">
            </div>

            <!-- Overview Cards (in a Row) -->
            <div class="overview-section">
            <div class="card" data-aos="fade-up">
                <div class="card-title">In-Progress Requests</div>
                <div class="card-number">{{ $inProgressRequests }}</div>
            </div>
            <div class="card" data-aos="fade-up" data-aos-delay="100">
                <div class="card-title">Closed Tickets</div>
                <div class="card-number">{{ $resolvedTickets }}</div>
            </div>
        </div>


            <!-- Section 2: Smart Activity Panel -->
            <div class="activity-panel" data-aos="fade-right">
                <h2>Recent Activity</h2>
                <div class="activity-list">
                    @php
                        $recentTickets = \App\Models\Ticket::latest('time_in')->take(3)->get(); // Fetch last 3 tickets
                    @endphp

                    @if($recentTickets->isNotEmpty())
                        @foreach($recentTickets as $ticket)
                            <div class="activity-item" >
                                <div class="row">
                                    <div class="col">
                                        <strong>Ticket No:</strong> {{ $ticket->control_no }}
                                    </div>
                                    <div class="col">
                                        <strong>Concern:</strong> {{ $ticket->concern }}
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <strong>Status:</strong> 
                                        <span class="status {{ strtolower($ticket->status) }}">
                                            {{ ucfirst($ticket->status) }}
                                        </span>
                                    </div>
                                    <div class="col">
                                        <strong>Assigned To:</strong> {{ $ticket->technical_support_name ?? 'Unassigned' }}
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <strong>Time In:</strong> {{ \Carbon\Carbon::parse($ticket->time_in)->format('M j, Y')  }}
                                    </div>
                                    <div class="col">
                                        <strong>Time Out:</strong> 
                                        {{ $ticket->time_out ? \Carbon\Carbon::parse($ticket->time_out)->format('M j, Y') : 'Pending' }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p>No recent activity available.</p>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Scripts -->
<script src="{{ asset('js/employee/Home_Script.js') }}"></script>
<script src="{{ asset('js/employee/Ticket_Script.js') }}"></script>

<!-- Include AOS JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.1/aos.js"></script>
<script>
    AOS.init({
        duration: 800, // Animation duration in milliseconds
        easing: "ease-in-out", // Smooth transition
        once: true // Animation happens only once
    });
</script>

</body>
</html>
