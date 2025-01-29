
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
<script>
    // Function to show ticket details in the modal
function showTicketDetails(controlNo) {
  console.log('Control Number:', controlNo); // Debugging: Check if the control_no is being passed correctly
  
  fetch('/ticket-details/' + controlNo) // Assuming this endpoint fetches ticket details by control_no
      .then(response => response.json())
      .then(data => {
          console.log('Ticket Details:', data); // Ensure the correct ticket data is received
  
          // Populate modal with ticket data
          document.getElementById('ticketControlNumber').innerText = data.ticket.control_no;
          document.getElementById('ticketFirstName').innerText = data.ticket.name;
          document.getElementById('ticketDepartment').innerText = data.ticket.department;
          document.getElementById('ticketConcern').innerText = data.ticket.concern;
          document.getElementById('ticketPriority').innerText = data.ticket.priority;
          document.getElementById('ticketEmployeeId').innerText = data.ticket.employee_id;
          document.getElementById('ticketTechnicalSupport').innerText = data.ticket.technical_support_name;
          document.getElementById('ticketTimeIn').innerText = data.ticket.time_in;
  
          // Populate the Support History Section
          const historyList = document.getElementById('supportHistoryList');
          historyList.innerHTML = ''; // Clear the list before appending new items
          
          // Check if we have ticket history data
          if (data.ticketHistory) {
              const historyItem = document.createElement('li');
              historyItem.innerHTML = `
                  <strong>Previous Support:</strong> ${data.ticketHistory.previous_technical_support_name} 
                  <strong>New Support:</strong> ${data.ticketHistory.new_technical_support_name} 
                  <strong>Changed At:</strong> ${data.ticketHistory.changed_at}
              `;
              historyList.appendChild(historyItem);
          } else {
              const noHistoryItem = document.createElement('li');
              noHistoryItem.innerText = 'No support history available.';
              historyList.appendChild(noHistoryItem);
          }
  
          // Show modal
          document.getElementById('ticketModal').style.display = "block";
      })
      .catch(error => {
          console.error('Error fetching ticket details:', error);
      });
}
</script>