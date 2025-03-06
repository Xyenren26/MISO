<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechTrack</title>
    <link rel="icon" href="{{ asset('images/Systembrowserlogo.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('css/employee_home_style.css') }}">
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
       <!-- Modal for Creating a New Ticket -->
        <div id="ticketFormModal" class="modal" style="display: none;">
            <div class="modal-content">
                <!-- Ensure data is passed to the component correctly -->
                @include('components.ticket-form', ['technicalSupports' => $technicalSupports, 'formattedControlNo' => $formattedControlNo])

            </div>
        </div>
        <!-- Dashboard Layout -->
        <div class="dashboard-container">
            
            <!-- Image Container (Now on Top) -->
            <div class="image-container" data-aos="fade-down">
                <img src="{{ asset('images/image-emp.png') }}" alt="Company Image">
            </div>

            <!-- Section 1: Responsibilities Slideshow -->
            <div class="text-slideshow-container" data-aos="fade-up">
                <h2>Our Responsibilities</h2>
                <div class="text-slideshow">
                    <p id="slideshowText"></p>
                </div>
            </div>


        <!-- Section 2: Smart Activity Panel -->
        <div class="activity-panel" data-aos="fade-right">
            <h2>In-Progress Tickets</h2>
            <div class="activity-list">
                @php
                    // Fetch only tickets that are in-progress for the logged-in employee
                    $inProgressTickets = \App\Models\Ticket::where('employee_id', auth()->user()->employee_id)
                        ->where('status', 'in-progress')
                        ->latest('time_in')
                        ->get();
                @endphp

                @if($inProgressTickets->isNotEmpty())
                    @foreach($inProgressTickets as $ticket)
                        <div class="activity-item">
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
                                    <span class="status in-progress">
                                        {{ ucfirst($ticket->status) }}
                                    </span>
                                </div>
                                <div class="col">
                                    <strong>Assigned To:</strong> {{ $ticket->technical_support_name ?? 'Unassigned' }}
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <strong>Time In:</strong> {{ \Carbon\Carbon::parse($ticket->time_in)->format('M j, Y') }}
                                </div>
                                <div class="col">
                                    <strong>Updated At:</strong> 
                                    {{ $ticket->updated_at ? \Carbon\Carbon::parse($ticket->updated_at)->format('M j, Y') : 'Pending' }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="no-activity-message">No in-progress tickets.</p>
                @endif
            </div>
        </div>

        
 <!-- Section 3: Completed Tickets (Collapsible) -->
<button class="toggle-btn" onclick="toggleCompletedTickets()">
    <i class="fas fa-folder"></i> Recents Activity
</button>

<div class="activity-panel completed-tickets" id="completedTicketsPanel" style="display: none;" data-aos="fade-right">
    <h2>Closed Tickets</h2>
    <div class="activity-list">
        @php
            // Fetch only tickets that are marked as "complete" for the logged-in employee
            $completedTickets = \App\Models\Ticket::where('employee_id', auth()->user()->employee_id)
                ->where('status', 'completed')
                ->latest('updated_at')
                ->get();
        @endphp

        @if($completedTickets->isNotEmpty())
            @foreach($completedTickets as $ticket)
                <div class="activity-item">
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
                            <span class="status completed">
                                {{ ucfirst($ticket->status) }}
                            </span>
                        </div>
                        <div class="col">
                            <strong>Assigned To:</strong> {{ $ticket->technical_support_name ?? 'Unassigned' }}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <strong>Time In:</strong> {{ \Carbon\Carbon::parse($ticket->time_in)->format('M j, Y') }}
                        </div>
                        <div class="col">
                            <strong>Completed At:</strong> 
                            {{ \Carbon\Carbon::parse($ticket->updated_at)->format('M j, Y') }}
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <p class="no-activity-message">No completed tickets available.</p>
        @endif
    </div>
</div>

<!-- to me huwag mong galawain lagi mo kasing namomove-->
<script src="{{ asset('js/Home_Script.js') }}"></script>
<script>
function toggleModal(modalId, show) {
    let modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = show ? 'block' : 'none';
    } else {
        console.error('Modal not found:', modalId);
    }
}

function openTicketFormModal() {
    toggleModal('ticketFormModal', true);
}

function closeTicketFormModal() {
    toggleModal('ticketFormModal', false);
}

</script>

<script src="{{ asset('js/employee/home_script.js') }}"></script>
</body>
</html>
