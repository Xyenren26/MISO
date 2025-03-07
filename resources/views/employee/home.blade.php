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

             <!-- Container for Cards -->
            <div class="containerCards">
                <!-- Mission and Vision Card -->
                <div class="card mission-card" onclick="openModal('missionModal')">
                <h3>Mission and Vision</h3>
                <p>Our guiding principles for IT excellence.</p>
                </div>

                <!-- Core Function Service Card -->
                <div class="card core-function-card" onclick="openModal('coreFunctionModal')">
                <h3>Core Function Service</h3>
                <p>Empowering Pasig City with reliable technical support.</p>
                </div>

                <!-- Contact Information Card -->
                <div class="card contact-card" onclick="openModal('contactModal')">
                <h3>Contact Information</h3>
                <p>Reach out to us for assistance.</p>
                </div>
            </div>

            <!-- Modals -->
            <!-- Mission and Vision Modal -->
            <div id="missionModal" class="modal mission-modal">
                <div class="modal-content">
                <span class="close" onclick="closeModal('missionModal')">&times;</span>
                <h2>Mission and Vision</h2>
                <p><strong>Mission:</strong> To provide innovative and reliable IT solutions that enhance the efficiency and effectiveness of Pasig City's operations, ensuring seamless information management and technological support for all employees.</p>
                <p><strong>Vision:</strong> To be a leader in IT and information management, driving digital transformation and fostering a culture of innovation and excellence within Pasig City.</p>
                </div>
            </div>

            <!-- Core Function Service Modal -->
            <div id="coreFunctionModal" class="modal core-function-modal">
                <div class="modal-content">
                <span class="close" onclick="closeModal('coreFunctionModal')">&times;</span>
                <h2>Core Function Service</h2>
                <p>The Technical Support Division of MISO is dedicated to providing comprehensive IT support to all Pasig City employees. Our services include:</p>
                <ul>
                    <li><strong>Hardware Support:</strong> Troubleshooting and maintenance of computers, printers, and other devices.</li>
                    <li><strong>Software Support:</strong> Installation, configuration, and troubleshooting of software applications.</li>
                    <li><strong>Network Support:</strong> Ensuring stable and secure network connectivity across all departments.</li>
                    <li><strong>System Maintenance:</strong> Regular updates and maintenance of IT systems to ensure optimal performance.</li>
                    <li><strong>User Training:</strong> Providing training sessions to enhance employees' IT skills and knowledge.</li>
                </ul>
                </div>
            </div>

            <!-- Contact Information Modal -->
            <div id="contactModal" class="modal contact-modal">
                <div class="modal-content">
                <span class="close" onclick="closeModal('contactModal')">&times;</span>
                <h2>Contact Information</h2>
                <p><strong>Office Address:</strong> [Insert office address here]</p>
                <p><strong>Email & Phone Number:</strong> [Insert email and phone number here]</p>
                <p><strong>Help Desk / Support System:</strong> [Insert details about the help desk or support system]</p>
                </div>
            </div>

        <!-- Section 2: Smart Activity Panel -->
        <div class="activity-panel" data-aos="fade-right">
             <!-- Section 3: Completed Tickets (Collapsible) -->
             <button class="toggle-btn" onclick="toggleCompletedTickets()">
                <i class="fas fa-folder"></i> Recents Activity
            </button>
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


<div class="activity-panel completed-tickets" id="completedTicketsPanel" style="display: none;" data-aos="fade-right">
    <h2>Closed Tickets</h2>
    <div class="activity-list">
        @php
        $completedTickets = \App\Models\Ticket::where('employee_id', auth()->user()->employee_id)
            ->whereNot('status', 'in-progress') // Excludes "in-progress" tickets
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

    // Function to open modal
    function openModal(modalId) {
      document.getElementById(modalId).style.display = "block";
    }

    // Function to close modal
    function closeModal(modalId) {
      document.getElementById(modalId).style.display = "none";
    }

    // Close modal when clicking outside of it
    window.onclick = function(event) {
      if (event.target.classList.contains('modal')) {
        event.target.style.display = "none";
      }
    }
  </script>


<script src="{{ asset('js/employee/home_script.js') }}"></script>
</body>
</html>