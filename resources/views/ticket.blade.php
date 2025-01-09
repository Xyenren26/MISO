<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Management Information System</title>
    <link rel="stylesheet" href="{{ asset('css/ticket_Style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-dyHHyKfrpNltGPrdAPLoTrI1kSxU7+G+XKAGwJQ1Ng5wzrsBtceVrldwuhM12a3SWHiS+N/hdE4ZZhd1xKyWEw==" crossorigin="anonymous" referrerpolicy="no-referrer">
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
                <button class="tab-button active" onclick="filterTickets('recent', event)">
                    <i class="fas fa-laptop"></i> Recent
                </button>
                <button class="tab-button" onclick="filterTickets('in-progress', event)">
                    <i class="fas fa-tools"></i> In-Progress
                </button>
                <button class="tab-button" onclick="filterTickets('complete', event)">
                    <i class="fas fa-check-circle"></i> Solved
                </button>
                <button class="tab-button" onclick="filterTickets('endorsed', event)">
                    <i class="fas fa-arrow-right"></i> Endorsed
                </button>
            </div>
        </div>

<!-- Filter and Add New Ticket Section (Below Tabs) -->
<div class="actions">
    <!-- Search Container -->
    <div class="search-container">
        <input type="text" placeholder="Search..." class="search-input">
        <button class="search-button"><i class="fas fa-search"></i></button>
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
                <a href="{{ url()->current() }}">All Devices</a>
                @foreach ($devices as $device)
                    <a href="{{ url()->current() }}?device={{ urlencode($device) }}">{{ $device }}</a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Add New Ticket Button -->
    <div class="add-ticket-section">
        <button class="add-ticket" onclick="openTicketFormModal()">
            <span class="icon">➕</span> Add Ticket
        </button>
    </div>

    <!-- Refresh Button -->
    <button class="refresh" onclick="window.location.reload();">
        <span class="icon">&#x21bb;</span>
    </button>
</div>

<!-- Modal for Creating a New Ticket -->
<div id="ticketFormModal" class="modal" style="display: none;">
    <div class="modal-content">
        <button class="close-modal" onclick="closeTicketFormModal()">✖</button>
        <x-ticket-form :techSupport="$techSupport" :nextControlNo="$nextControlNo" />
    </div>
</div>

<div class="content">
    <div class="table-container">
        @if ($tickets->count() > 0)
            <div id="ticket-list" class="table-container">
                @include('components.ticket-list', ['tickets' => $tickets])
            </div>
        @else
            <div class="no-records">NO RECORDS FOUND</div>
        @endif

        <div class="pagination-container">
            <div class="results-count">
                @if ($tickets->count() > 0)
                    Showing {{ $tickets->firstItem() }} to {{ $tickets->lastItem() }} of {{ $tickets->total() }} results
                @else
                    Showing 1 to 0 of 0 results
                @endif
            </div>
            <div class="pagination-buttons">
                {{ $tickets->appends(request()->input())->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>

    </div>
</div>
<script>
    function filterTickets(status) {
        window.location.href = `?filter=${status}`;
    }
</script>
<script src="{{ asset('js/Ticket_Script.js') }}"></script>
</body>
</html>
