<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechTrack</title>
    <link rel="icon" href="{{ asset('images/Systembrowserlogo.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('css/ticket_style.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    @include('components.sidebar')
    @include('components.navbar')
    <div class="main-content">
        <!-- Header Section -->
        <div class="header">
            <!-- Tabs Section -->
            <div class="tabs">
                <button class="tab-button active" data-status="recent" onclick="filterTickets('recent', event)">
                    <i class="fas fa-laptop"></i> Recent
                </button>
                <button class="tab-button" data-status="in-progress" onclick="filterTickets('in-progress', event)">
                    <i class="fas fa-tools"></i> In-Progress
                    @if($inProgressCount > 0)
                        <span class="notifcounter">{{ $inProgressCount }}</span>
                    @endif
                </button>
                
                <!-- Processing Tickets -->
                <button class="tab-button" data-status="processing" onclick="filterTickets('processing', event)">
                    <i class="fas fa-spinner"></i> Processing
                    @if($inProcessingCount > 0)
                        <span class="notifcounter">{{ $inProcessingCount }}</span>
                    @endif
                </button>

                <!-- Closed Tickets -->
                <button class="tab-button" data-status="closed" onclick="filterTickets('closed', event)">
                    <i class="fas fa-check-circle"></i> Closed Ticket
                </button>
            </div>
        </div>

        <!-- Filter and Add New Ticket Section (Below Tabs) -->
        <div class="actions">
            <!-- Search Container -->
            <div class="search-container">
                <input type="text" id="ticketSearch" placeholder="Search..." class="search-input">
                <button type="button" class="search-button" onclick="applyFilters()">
                    <i class="fas fa-search"></i>
                </button>
            </div>

            <!-- Space Between Search and Filter/Add New Buttons -->
            <div class="spacer"></div>

            <!-- Filter Section -->
            <div class="filter-section">
                <div class="dropdown">
                    <button class="dropdown-button">
                        <i class="fas fa-filter"></i> Filter Record <span class="arrow">&#x25BC;</span>
                    </button>
                    <div class="dropdown-content">
                        <a href="javascript:void(0);" onclick="filterByPriority(null)">All Priorities</a>
                        <a href="javascript:void(0);" onclick="filterByPriority('urgent')">Urgent</a>
                        <a href="javascript:void(0);" onclick="filterByPriority('semi-urgent')">Semi-Urgent</a>
                        <a href="javascript:void(0);" onclick="filterByPriority('non-urgent')">Non-Urgent</a>
                    </div>
                </div>
            </div>

            <!-- Date Filter -->
            <div class="date-filter-container">
                <label for="fromDate">From:</label>
                <input type="date" id="fromDate" class="date-filter">

                <label for="toDate">To:</label>
                <input type="date" id="toDate" class="date-filter">
            </div>

        </div>

        <!-- Modal for Creating a New Ticket -->
        <div id="ticketFormModal" class="modal" style="display: none;">
            <div class="modal-content">
                @include('components.ticket-form', ['technicalSupports' => $technicalSupports, 'formattedControlNo' => $formattedControlNo])
            </div>
        </div>

        <div class="content">
            <div class="table-container">
                @if ($tickets->count() > 0)
                    <div id="ticket-list" class="table-container">
                        @include('components.employee.ticket-list', ['tickets' => $tickets, 'technicalSupports' => $technicalSupports])
                    </div>
                @else
                    <div class="no-records">NO RECORDS FOUND</div>
                @endif
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/Ticket_Employee_Script.js') }}"></script>
</body>
</html>
