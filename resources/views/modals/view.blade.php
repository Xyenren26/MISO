
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