<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Management Information System</title>
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
            <div class="header-title">
                <h1>Tickets</h1>
            </div>
            <div class="header-buttons">
                <!-- Create Ticket Button -->
                <button class="create-ticket">
                    <span class="icon">➕</span> New Ticket
                </button>

                <!-- Dropdown Filter -->
                <div class="dropdown">
                    <button class="dropdown-button">
                        <span class="icon">⚙️</span> Filter<span class="arrow">&#x25BC;</span>
                    </button>
                    <div class="dropdown-content">
                        <a href="#">Complete</a>
                        <a href="#">Pending</a>
                        <a href="#">Endorsed</a>
                        <a href="#">Technical Report</a>
                    </div>
                </div>

                <!-- Refresh Button -->
                <button class="refresh">
                    <span class="icon">&#x21bb;</span>
                </button>
            </div>
        </div>

        <!-- Tickets Table -->
        <div class="content">
            <div class="table-container">
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
                                        <button class="action-button">View</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <!-- No Records Message -->
                    <div class="no-records">NO RECORDS FOUND</div>
                @endif

                <!-- Results Count and Pagination Controls -->
                <div class="pagination-container">
                    <div class="results-count">
                        @if ($tickets->count() > 0)
                            Showing {{ $tickets->firstItem() }} to {{ $tickets->lastItem() }} of {{ $tickets->total() }} results
                        @else
                            Showing 1 to 0 of 0 results
                        @endif
                    </div>
                    <div class="pagination-buttons">
                        {{ $tickets->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include custom scripts -->
<script src="{{ asset('js/ticket.js') }}"></script>
</body>
</html>
