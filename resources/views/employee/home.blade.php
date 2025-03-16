<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechTrack</title>
    <link rel="icon" href="{{ asset('images/Systembrowserlogo.png') }}" type="image/png">
    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/employee_home_style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
</head>
<body>
<div class="container">
    @auth
        @include('components.greetings', ['accountType' => auth()->user()->account_type])
    @endauth
    @include('components.sidebar')
    <div class="main-content">
        @include('components.navbar')
        <!-- EMAIL VERIFY ALERT MESSAGE -->
        @include('modals.email_verification_alert')
        <div id="ticketFormModal" class="modal" style="display: none;">
            <div class="modal-content">
                @include('components.ticket-form', ['technicalSupports' => $technicalSupports, 'formattedControlNo' => $formattedControlNo])
            </div>
        </div>
        <div class="dashboard-container">
            <div class="image-container" data-aos="fade-down">
                <img src="{{ asset('images/image-emp.png') }}" alt="Company Image">
            </div>
           <!-- Container for Side-by-Side Layout -->
            <div class="side-by-side-container" data-aos="fade-up">
                <!-- MIS Description Section -->
                <div class="miso-qa-container">
                    <div class="miso-question">
                        <p id="questionText"></p>
                    </div>
                    <div class="miso-answer">
                        <p id="answerText"></p>
                    </div>
                </div>

                <!-- Text Slideshow Section -->
                <div class="text-slideshow-container">
                    <h2>Our Responsibilities</h2>
                    <div class="text-slideshow">
                        <p id="slideshowText"></p>
                    </div>
                </div>
            </div>
            <div class="containerCards">
                <!-- Mission and Vision Card -->
                <div class="card mission-card" onclick="openModalHome('missionModal')">
                    <i class="fas fa-bullseye card-icon"></i>
                    <h3>Mission and Vision</h3>
                    <p>Our guiding principles for IT excellence.</p>
                </div>

                <!-- Core Function Service Card -->
                <div class="card core-function-card" onclick="openModalHome('coreFunctionModal')">
                    <i class="fas fa-cogs card-icon"></i>
                    <h3>Core Function Service</h3>
                    <p>Empowering Pasig City with reliable technical support.</p>
                </div>

                <!-- Contact Information Card -->
                <div class="card contact-card" onclick="openModalHome('contactModal')">
                    <i class="fas fa-phone-alt card-icon"></i>
                    <h3>Contact Information</h3>
                    <p>Reach out to us for assistance.</p>
                </div>
            </div>
            <div id="missionModal" class="modal-home mission-modal">
                <div class="modal-content-home">
                    <span class="close" onclick="closeModal('missionModal')">&times;</span>
                    <h2>Mission and Vision</h2>
                    <p><strong>Mission:</strong> To provide innovative and reliable IT solutions that enhance the efficiency and effectiveness of Pasig City's operations, ensuring seamless information management and technological support for all employees.</p>
                    <p><strong>Vision:</strong> To be a leader in IT and information management, driving digital transformation and fostering a culture of innovation and excellence within Pasig City.</p>
                </div>
            </div>
            <div id="coreFunctionModal" class="modal-home core-function-modal">
                <div class="modal-content-home">
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
            <div id="contactModal" class="modal-home contact-modal">
                <div class="modal-content-home">
                    <span class="close" onclick="closeModal('contactModal')">&times;</span>
                    <h2>Contact Information</h2>
                    <p><strong>Technical Support Head:</strong> Cecilio V. Demano</p>
                    <p><strong>Office Address:</strong> [Insert office address here]</p>
                    <p><strong>Email:</strong> [Insert email here]</p>
                    <p><strong>Phone Number:</strong> [Insert phone number here]</p>
                    <p><strong>Help Desk / Support System:</strong> [Insert details about the help desk or support system]</p>
                </div>
            </div>
            <div class="containerannouncement">
                <div class="slideshow">
                    <div class="slides">
                        <img src="{{ asset('images/slide1.jpg') }}" alt="Image 1">
                        <img src="{{ asset('images/slide2.png') }}" alt="Image 2">
                        <img src="{{ asset('images/technicalsupport.png') }}" alt="Image 3">
                    </div>
                    <button class="prev" onclick="prevSlide()">&#10094;</button>
                    <button class="next" onclick="nextSlide()">&#10095;</button>
                </div>
                <div class="right-section">
                    <div class="announcement">
                        <h2>Announcements</h2>
                        @if($announcements->isEmpty())
                            <p>Welcome to our platform! Stay updated with the latest news and events. Don't miss out on our upcoming community meetups.</p>
                        @else
                            @foreach($announcements as $announcement)
                                <div class="announcement-item">
                                    <h3>{{ $announcement->title }}</h3>
                                    <p>{{ $announcement->content }}</p>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="calendar">
                        <h2>Calendar</h2>
                        <div id="calendar">
                            @if($events->isEmpty())
                                <p>No events scheduled for today.</p>
                            @else
                                <div class="event-list">
                                    @foreach($events as $event)
                                        <div class="event-item">
                                            <h3>{{ $event->title }}</h3>
                                            <p>
                                                <strong>Start:</strong> {{ \Carbon\Carbon::parse($event->start)->format('M j, Y H:i') }}<br>
                                                <strong>End:</strong> {{ \Carbon\Carbon::parse($event->end)->format('M j, Y H:i') }}
                                            </p>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        <button class="calendar-btn" onclick="openCalendarModal()">
                            <i class="fas fa-calendar-alt"></i> Schedule Event
                        </button>
                    </div>
                </div>
            </div>
            <!-- Calendar Modal -->
            <div id="calendarModal" class="modal-home">
                <div class="modal-content-home">
                    <span class="close" onclick="closeCalendarModal()">&times;</span>
                    <h2>Schedule Event</h2>
                    <div id="fullCalendar"></div> <!-- FullCalendar will render here -->
                </div>
            </div>
            <div class="activity-panel" data-aos="fade-right">
                <button class="toggle-btn" onclick="toggleCompletedTickets()">
                    <i class="fas fa-folder"></i> Recents Activity
                </button>
                <h2>Pending Tickets</h2>
                <div class="activity-list">
                    @php
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
                                            {{ $ticket->status == 'in-progress' ? 'Pending' : ucfirst($ticket->status) }}
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
                                        <strong>Updated At:</strong> {{ $ticket->updated_at ? \Carbon\Carbon::parse($ticket->updated_at)->format('M j, Y') : 'Pending' }}
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
                            ->whereNot('status', 'in-progress')
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
                                        <span class="status completed">{{ ucfirst($ticket->status) }}</span>
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
                                        <strong>Completed At:</strong> {{ \Carbon\Carbon::parse($ticket->updated_at)->format('M j, Y') }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="no-activity-message">No completed tickets available.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    // Initialize FullCalendar
    function initializeCalendar() {
    const calendarEl = document.getElementById('fullCalendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        contentHeight: 'auto', // Adjust height dynamically
        aspectRatio: 1.5,
        events: '/events', // Fetch events from your backend
        editable: true,
        selectable: true,
        select: function (info) {
            const title = prompt('Enter event title:');
            if (title) {
                fetch('/events', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        title: title,
                        start: info.startStr,
                        end: info.endStr,
                        allDay: info.allDay
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        calendar.addEvent({
                            id: data.event.id,
                            title: title,
                            start: info.startStr,
                            end: info.endStr,
                            allDay: info.allDay
                        });
                        location.reload(); // Refresh the page to show the new event
                        }
                    });
                }
            },
            eventClick: function (info) {
                const action = prompt('Do you want to (1) Edit or (2) Delete this event? Enter 1 or 2:');
                if (action === '1') {
                    const newTitle = prompt('Enter new event title:');
                    if (newTitle) {
                        fetch(`/events/${info.event.id}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                title: newTitle
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                info.event.setProp('title', newTitle);
                                location.reload(); // Refresh the page to show the updated event
                            }
                        });
                    }
                } else if (action === '2') {
                    if (confirm('Are you sure you want to delete this event?')) {
                        fetch(`/events/${info.event.id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                info.event.remove();
                                location.reload(); // Refresh the page to remove the event
                            }
                        });
                    }
                }
            }
        });
        calendar.render();
    }

    // Calendar Modal Functions
    function openCalendarModal() {
        document.getElementById('calendarModal').style.display = 'block';
        initializeCalendar(); // Initialize the calendar when the modal opens
    }

    function closeCalendarModal() {
        document.getElementById('calendarModal').style.display = 'none';
    }
</script>
<script src="{{ asset('js/employee/home_script.js') }}"></script>
@include('components.chatbot')

</body>
</html> 