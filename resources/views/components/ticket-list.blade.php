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
<meta name="csrf-token" content="{{ csrf_token() }}">


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
                    <button class="action-button" onclick="showTicketDetails('{{ $ticket->control_no }}')">
                        <i class="fas fa-eye"></i>
                    </button>         
                    <button class="action-button" onclick="showRemarksModal('{{ $ticket->control_no }}')">
                        <i class="fas fa-sticky-note"></i> <!-- For Remarks -->
                    </button>
                    <button class="action-button">
                        <i class="fas fa-comments"></i> <!-- For Chat -->
                    </button>
                    <button class="action-button" onclick="showAssistModal('{{ $ticket->control_no }}')">
                        <i class="fas fa-handshake"></i> <!-- For Assist -->
                    </button>
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
        <h2 class="head">
            Technical Service Slip
        </h2> <!-- Title for the Modal -->
        <!-- Container for Date/Time and Footer -->
        <div class="modal-footer-container">
            <!-- Footer Section -->
            <span class="footer-left">Management Information Systems Office</span>
            <!-- Date and Time Section -->
            <span class="modal-date-time">
              Date:
                <span id="ticketTimeIn"></span>
            </span>
        </div>
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
                        <label>Employee Name:</label>
                        <span id="ticketFirstName" class="boxed-span"></span>
                    </div>
                    <div class="personal-info-field">
                        <label>Employee ID:</label>
                        <span id="ticketEmployeeId" class="boxed-span"></span>
                    </div>
                    <div class="personal-info-field">
                        <label>Department:</label>
                        <span id="ticketDepartment" class="boxed-span"></span>
                    </div>
                </div>
            </fieldset>

            <!-- Concern/Problem Box -->
            <div class="concern-box">
                <label>Concern/Problem:</label>
                <span id="ticketConcern" class="boxed-span"></span>
            </div>

            <!-- Support Details Box -->
            <div class="support-details-box">
                <div class="support-detail">
                    <label class="support-label">Technical Support By:</label>
                    <span id="ticketTechnicalSupport" class="boxed-span support-value"></span>
                </div>
                  <!-- Support History Section -->
                  <div class="support-history-container">
                    <label class="support-label">Support History:</label>
                    <ul id="supportHistoryList" class="support-history-list">
                </div>
            </div>
        </form>
    </div>
</div>

<div id="assistModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close" onclick="closeAssistModal()">&times;</span>
        <h2>Select Technical Support</h2>
        <form id="assistForm" action="/api/pass-ticket" method="POST">
            @csrf <!-- CSRF token for form submission -->
            <input type="hidden" id="ticketControlNo" name="ticket_control_no">

            <div class="form-group">
                <label for="technicalSupport">Choose Technical Support:</label>
                <select id="technicalSupport" name="new_technical_support" required>
                    <option value="" disabled selected>Select Assist Technical Support</option>
                    @foreach($technicalSupports as $tech)
                        <option value="{{ $tech->employee_id }}">
                            {{ $tech->first_name }} {{ $tech->last_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <button type="submit" class="submit-btn">Submit</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal for Remarks -->
<div id="remarksModal" class="modal" style="display: none;">
    <div id="remarksModalContent" class="modal-content">
        <span class="close" onclick="closeRemarksModal()">&times;</span>
        <h3>Update Remarks and Status</h3>

        <!-- Remarks Input -->
        <div>
            <label for="remarksInput">Remarks:</label>
            <textarea id="remarksInput" placeholder="Enter your remarks"></textarea>
        </div>

        <!-- Status Dropdown -->
        <div>
            <label for="statusDropdown">Status:</label>
            <select id="statusDropdown">
                <option value="completed">Mark Ticket as Complete</option>
                <option value="endorsed">Endorsed Ticket</option>
                <option value="technical_report">Write Technical Report</option>
            </select>
        </div>

        <!-- Save Button -->
        <button class="action-button" onclick="saveRemarksAndStatus('{{ $ticket->control_no }}')">Save</button>
    </div>
</div>

<script src="{{ asset('js/Ticket_Components_Script.js') }}"></script>