@php
    function getPriorityClass($priority) {
        switch (strtolower($priority)) {
            case 'urgent':
                return 'priority-urgent';
            case 'semi-urgent':
                return 'priority-semi-urgent';
            case 'non-urgent':
                return 'priority-non-urgent';
            default:
                return '';
        }
    }

    function getStatusClass($status) {
        switch (strtolower($status)) {
            case 'endorsed':
                return 'status-endorsed';
            case 'completed':
                return 'status-completed';
            case 'in-progress':
                return 'status-in-progress';
            case 'technical-report':
                return 'status-technical-report';
            default:
                return '';
        }
    }
@endphp
<link rel="stylesheet" href="{{ asset('css/ticket_components_Style.css') }}">

<table class="tickets-table">
    <thead>
        <tr>
            <th>Control No</th>
            <th>Name</th>
            <th>Department</th>
            <th>Concern</th>
            <th>Priority</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($tickets as $ticket)
            <tr>
                <td>{{ $ticket->control_no }}</td>
                <td>{{ $ticket->name }}</td>
                <td>{{ $ticket->department }}</td>
                <td>{{ $ticket->concern }}</td>
                <td class="{{ getPriorityClass($ticket->priority) }}">{{ ucfirst($ticket->priority) }}</td>
                <td class="{{ getStatusClass($ticket->status) }}">{{ ucfirst($ticket->status) }}</td>
                <td>
                    <button class="action-button" onclick="showTicketDetails('{{ $ticket->control_no }}')">View</button>
                    <button class="action-button">Remarks</button>
                </td>
            </tr>
        @empty
            <tr class="no-records-row">
                <td colspan="7">
                    <div class="no-records">NO RECORDS FOUND</div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

<!-- Pagination Section -->
<div class="pagination-container">
    @if ($tickets->hasPages())
        <div class="pagination-buttons">
            {{ $tickets->appends(request()->input())->links('pagination::bootstrap-4') }}
        </div>
        <div class="results-count">
            Showing {{ $tickets->firstItem() }} to {{ $tickets->lastItem() }} of {{ $tickets->total() }} results
        </div>
    @endif
</div>


<!-- Modal Popup (Initially Hidden) -->
<div id="ticketModal" class="ticket-modal">
    <div class="ticket-modal-content">
        <button class="close-modal" onclick="closeModal()">✖</button>

        <!-- Print Button -->
        <button class="print-modal" onclick="printModal()">🖨️ Print</button>

        <!-- Ticket Form (Inside Modal) -->
        <h2 class="head">Technical Service Slip</h2> <!-- Title for the Modal -->

        <!-- Display Ticket Data (Read-Only) -->
        <form id="ticketFormModal">
            <!-- Control Number with Image -->
            <div class="control-numbers" id="controlNumber">
                <img src="{{ asset('images/SystemLogo.png') }}" alt="System Logo" id="systemLogo" class="system-logo" />
                <span id="ticketControlNumber" class="boxed-span"></span>
                <span id="ticketPriority" class="boxed-span priority"></span>
            </div>



            <!-- Personal Information -->
            <fieldset>
                <legend>Personal Information</legend>
                <div class="personal-info-container">
                    <div class="personal-info-field">
                        <label>First Name:</label>
                        <span id="ticketFirstName" class="boxed-span"></span>
                    </div>
                    <div class="personal-info-field">
                        <label>Department:</label>
                        <span id="ticketDepartment" class="boxed-span"></span>
                    </div>
                </div>
            </fieldset>

            <!-- Ticket Details -->
            <fieldset>
                <legend>Ticket Details</legend>
                <div class="personal-info-container"> 
                    <div class="personal-info-field"> 
                        <label>Concern/Problem:</label>
                        <span id="ticketConcern" class="boxed-span"></span>
                    </div>
                    <div class="personal-info-field"> 
                        <label>Employee ID:</label>
                        <span id="ticketEmployeeId" class="boxed-span"></span>
                    </div>
                </div>
            </fieldset>


            <!-- Support Details -->
            <fieldset>
                <legend>Support Details</legend>
                <div class="personal-info-container"> 
                    <div class="personal-info-field"> 
                        <label class="support-label">Technical Support By:</label>
                        <span id="ticketTechnicalSupport" class="boxed-span support-value"></span>
                    </div>
                    <div class="personal-info-field"> 
                        <label class="support-label">Created At:</label>
                        <span id="ticketTimeIn" class="boxed-span support-value"></span>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>
</div>
<script src="{{ asset('js/Ticket_Components_Script.js') }}"></script>