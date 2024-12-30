<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Management Information System</title>
    
    <!-- Include your CSS -->
    <link rel="stylesheet" href="{{ asset('css/ticket_Style.css') }}">
</head>
<body>
<div class="container">
    <!-- Sidebar -->
    @include('components.sidebar')

    <!-- Main Content Area -->
    <div class="main-content">
        <!-- Navbar -->
        @include('components.navbar')

        <!-- Header Section -->
        <div class="header">
            <div class="ticket-header">
                <div class="header-title">
                    <span class="ticket-title">Tickets</span>
                </div>
                <div class="header-buttons">
                    <!-- Create Ticket Button -->
                    <button class="create-ticket">New Ticket</button>

                    <!-- Dropdown Filter -->
                    <div class="dropdown">
                        <button class="dropdown-button">
                            <span class="filter-icon">&#x1F50D;</span> Filter <span class="down-arrow">&#x25BC;</span>
                        </button>
                        <!-- Dropdown Content -->
                        <div class="dropdown-content">
                            <a href="#">Option 1</a>
                            <a href="#">Option 2</a>
                            <a href="#">Option 3</a>
                        </div>
                    </div>

                    <!-- Refresh Button -->
                    <button class="refresh-button">
                        <span class="refresh-icon">&#x21bb;</span> <!-- Refresh Icon -->
                    </button>
                </div>
            </div>
        </div>

        <!-- Tickets Table -->
        @if ($tickets->count() > 0)
            <table class="tickets-table">
                <thead>
                <tr>
                    <th>Control No</th>
                    <th>Name</th>
                    <th>Department</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($tickets as $ticket)
                    <tr>
                        <td>{{ $ticket->control_no }}</td>
                        <td>{{ $ticket->name }}</td>
                        <td>{{ $ticket->department }}</td>
                        <td>{{ ucfirst($ticket->priority) }}</td>
                        <td>{{ ucfirst($ticket->status) }}</td>
                        <td>
                            <!-- Actions here -->
                            <button class="action-button">View</button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <!-- Pagination Links -->
            <div class="pagination">
                {{ $tickets->links() }}
            </div>
        @else
            <!-- Centered No Records Message -->
            <div class="no-records">
                NO RECORDS FOUND
            </div>
        @endif

        <!-- Results Count at the Bottom -->
        <div class="result-count">
            @if ($tickets->count() > 0)
                Showing {{ $tickets->firstItem() }} to {{ $tickets->lastItem() }} of {{ $tickets->total() }} results
            @else
                Showing 1 to 0 of 0 results
            @endif
        </div>
    </div>
</div>

<!-- Include custom scripts -->
<script src="{{ asset('js/ticket.js') }}"></script>
</body>
</html>
