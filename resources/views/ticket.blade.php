<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechTrack</title>
    <link rel="icon" href="{{ asset('images/Systembrowserlogo.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('css/ticket_Style.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    @include('components.sidebar')
    <div class="main-content">
        @include('components.navbar')
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
                <button class="tab-button" data-status="completed" onclick="filterTickets('completed', event)">
                    <i class="fas fa-check-circle"></i> Solved
                </button>
                <button class="tab-button" data-status="endorsed" onclick="filterTickets('endorsed', event)">
                    <i class="fas fa-arrow-right"></i> Endorsed
                    @if($endorsedCount > 0)
                        <span class="notifcounter">{{ $endorsedCount }}</span>
                    @endif
                </button>
                <button class="tab-button" data-status="technical-report" onclick="filterTickets('technical-report', event)">
                    <i class="fas fa-times-circle"></i> Technical-Report
                    @if($technicalReportCount > 0)
                        <span class="notifcounter">{{ $technicalReportCount }}</span>
                    @endif
                </button>
                <button class="tab-button" data-status="pull-out" onclick="filterTickets('pull-out', event)">
                    <i class="fas fa-times-circle"></i> Equipment Handover
                    @if($pullOutCount > 0)
                        <span class="notifcounter">{{ $pullOutCount }}</span>
                    @endif
                </button>
                <button class="tab-button" data-status="deployment" onclick="filterTickets('deployment', event)">
                    <i class="fas fa-times-circle"></i> New Device Deployment
                    @if($deploymentCount > 0)
                        <span class="notifcounter">{{ $deploymentCount }}</span>
                    @endif
                </button>
            </div>
        </div>

<!-- Filter and Add New Ticket Section (Below Tabs) -->
<div class="actions">
    <!-- Search Container -->
    <div class="search-container">
        <input type="text" id="ticketSearch" placeholder="Search..." class="search-input">
        <button type="button" class="search-button">
            <i class="fas fa-search"></i>
        </button>
    </div>



    <!-- Space Between Search and Filter/Add New Buttons -->
    <div class="spacer"></div>

   <!-- Filter and Add New Ticket Section (Right side) -->
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

    <!-- Add New Ticket Button -->
    <div class="add-ticket-section">
        <button class="add-ticket" onclick="openTicketFormModal()">
            <span class="icon">➕</span> Request Support
        </button>
    </div>
</div>

<!-- Modal for Creating a New Ticket -->
<div id="ticketFormModal" class="modal" style="display: none;">
    <div class="modal-content">
        <!-- Ensure data is passed to the component correctly -->
        @include('components.ticket-form', ['technicalSupports' => $technicalSupports, 'formattedControlNo' => $formattedControlNo])

    </div>
</div>

<div class="content">
    <div class="table-container">
        @if ($tickets->count() > 0)
            <div id="ticket-list" class="table-container">
                @include('components.ticket-list', ['tickets' => $tickets, 'technicalSupports' => $technicalSupports])
            </div>
        @else
            <div class="no-records">NO RECORDS FOUND</div>
        @endif
    </div>
</div>

<script src="{{ asset('js/Ticket_Script.js') }}"></script>
</body>
</html>