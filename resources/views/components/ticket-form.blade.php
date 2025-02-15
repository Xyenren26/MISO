<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enhanced Ticket Form</title>
    <link rel="stylesheet" href="{{ asset('css/ticket_components_Style.css') }}">
</head>
<body>
    <!-- Ticket Form Container -->
    <div class="content-wrapper-form">
        <div class="ticket-form-container">
        <button class="close-modal" onclick="closeTicketFormModal()">✖</button>
            <h2>Technical Service Slip</h2> <!-- New heading added here -->
            <form id="ticketForm" action="{{ route('ticket.store') }}" method="POST">
            @csrf
                <div class="control-number" id="controlNumber">
                    {{ $formattedControlNo }}
                    <input type="hidden" name="controlNumber" value="{{ $formattedControlNo }}">
                </div>
           
                <!-- Row 1: Personal Information -->
                <fieldset>
                    <legend>Personal Information</legend>
                    <div class="personal-info-container">
                        <div class="personal-info-field">
                            <label for="first-name">First Name:</label>
                            <input type="text" id="first-name" name="first-name" placeholder="First Name" required>
                        </div>
                        <div class="personal-info-field">
                            <label for="last-name">Last Name:</label>
                            <input type="text" id="last-name" name="last-name" placeholder="Last Name" required>
                        </div>
                        <div class="personal-info-field">
                        <label for="department">Department</label>
                            <select id="department" name="department" class="department-select" required>
                                @include('components.list_department')
                            </select>
                        </div>
                    </div>
                </fieldset>

                <!-- Row 2: Ticket Details -->
                <fieldset>
                    <legend>Ticket Details</legend>
                    <div class="form-row">
                        <label for="concern">Concern/Problem:</label>
                        <div class="issue-dropdown">
                            <button class="issue-dropbtn" id="selectedConcerns">Select Concern</button>
                            <div class="issue-dropdown-content">
                                <label><input type="checkbox" name="issues[]" value="Hardware Issue" onchange="updateSelectedConcerns()"> Hardware Issue</label>
                                <label><input type="checkbox" name="issues[]" value="Software Issue" onchange="updateSelectedConcerns()"> Software Issue</label>
                                <label><input type="checkbox" name="issues[]" value="File Transfer" onchange="updateSelectedConcerns()"> File Transfer</label>
                                <label><input type="checkbox" name="issues[]" value="Network Connectivity" onchange="updateSelectedConcerns()"> Network Connectivity</label>
                                <label><input type="checkbox" name="issues[]" value="Other" id="otherIssueCheckbox" onchange="toggleOtherInput(); updateSelectedConcerns()"> Other: Specify</label>
                            </div>
                        </div>

                        <div id="otherConcernContainer" style="display: none;">
                            <label for="otherConcern">Please Specify:</label>
                            <input type="text" id="otherConcern" name="otherConcern" placeholder="Specify your concern" oninput="updateSelectedConcerns()">
                        </div>



                        <label for="category">Priority:</label>
                        <select id="category" name="category" required>
                            <option value="" disabled selected>Select Priority</option>
                            <option value="urgent">Urgent</option>
                            <option value="semi-urgent">Semi-Urgent</option>
                            <option value="non-urgent">Non-Urgent</option>
                        </select>
                        
                        <label for="employeeId">Employee ID:</label>
                        <input type="text" id="employeeId" name="employeeId" required>
                        <span id="error-message" style="color: red; display: none;">Employee ID must be a 7-digit whole number.</span>

                    </div>
                </fieldset>

                    <!-- Row 3: Support Details -->
                <fieldset>
                    <legend>Support Details</legend>
                    <div class="support-details-container">
                        <div class="support-details-field">
                            <label for="technicalSupport">Technical Support By:</label>
                            <select id="technicalSupport" name="technicalSupport" required>
                                <option value="" disabled selected>Select Technical Support</option>
                                @foreach($technicalSupports as $tech)
                                    <option value="{{ $tech->employee_id }}">{{ $tech->first_name }} {{ $tech->last_name }}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>
                </fieldset>

                <!-- Error message container -->
                <div id="errorMessage" style="color: red; display: none; text-align:center; margin-top: 10px;">
                    <p>Please fill in all fields.</p>
                </div>

                <!-- Submit Button -->
                <div class="form-actions">
                    <button type="submit" class="submit-btn">Submit</button>
                </div>
            </form>
        </div>
    </div>




<script>
const employeeIdInput = document.getElementById('employeeId');
const errorMessage = document.getElementById('error-message');

employeeIdInput.addEventListener('input', function () {
  // Remove any non-numeric characters and limit to 7 digits
  this.value = this.value.replace(/\D/g, '').slice(0, 7);

  // Check if the input length is exactly 7 digits
  if (this.value.length === 7) {
    errorMessage.style.display = 'none'; // Hide error message
    this.setCustomValidity(''); // Clear validation message
  } else {
    errorMessage.style.display = 'inline'; // Show error message
    this.setCustomValidity('Invalid Employee ID'); // Set validation message
  }
});
 document.getElementById('otherConcernCheckbox').addEventListener('change', function() {
  const otherContainer = document.getElementById('otherConcernContainer');
  if (this.checked) {
      otherContainer.style.display = 'block';
  } else {
      otherContainer.style.display = 'none';
  }
});
function updateSelectedConcerns() {
  const checkboxes = document.querySelectorAll('.issue-dropdown-content input[type="checkbox"]:checked');
  const selectedConcerns = Array.from(checkboxes)
      .filter(checkbox => checkbox.value !== 'Other') // Exclude "Other"
      .map(checkbox => checkbox.value);
  const otherInput = document.getElementById('otherConcern').value.trim();

  // Add "Other" input if it's filled
  if (document.getElementById('otherIssueCheckbox').checked && otherInput) {
      selectedConcerns.push(otherInput);
  }

  // Update the button text
  const dropbtn = document.getElementById('selectedConcerns');
  dropbtn.textContent = selectedConcerns.length > 0 ? selectedConcerns.join(', ') : 'Select Concern';
}

function toggleOtherInput() {
  const otherInputContainer = document.getElementById('otherConcernContainer');
  const otherCheckbox = document.getElementById('otherIssueCheckbox');

  otherInputContainer.style.display = otherCheckbox.checked ? 'block' : 'none';
}



  // Check if all required fields are filled before submitting the form
  function validateForm() {
      var firstName = document.getElementById('first-name').value;
      var lastName = document.getElementById('last-name').value;
      var department = document.getElementById('department').value;
      var concern = document.getElementById('concern').value;
      var category = document.getElementById('category').value;
      var employeeId = document.getElementById('employeeId').value;
      var technicalSupport = document.getElementById('technicalSupport').value;
      var errorMessage = document.getElementById('errorMessage');

      // Check if all required fields are filled
      if (!firstName || !lastName || !department || !concern || !category || !employeeId || !technicalSupport) {
          errorMessage.style.display = 'block'; // Show error message
          return false; // Prevent form submission
      }

      // If "Other" concern is selected, ensure "Specify" field is filled
      if (concern === 'other' && !document.getElementById('otherConcern').value) {
          errorMessage.style.display = 'block'; // Show error message
          return false; // Prevent form submission
      }

      errorMessage.style.display = 'none'; // Hide error message
      return true; // Allow form submission
  }

  // Attach the validateForm function to the submit button
  document.querySelector('.submit-btn').addEventListener('click', function(event) {
      if (!validateForm()) {
          event.preventDefault(); // Prevent form submission if validation fails
      } else {
          submitForm(); // Submit form if validation passes
      }
  });

  // Submit the form
  function submitForm() {
      document.getElementById('ticketForm').submit();
  }

  function printModal() {
    const modalContent = document.querySelector('#ticketModal .ticket-modal-content');
    const originalContent = document.body.innerHTML;
  
    // Temporarily hide the navigation, sidebar, and modal buttons
    const nav = document.querySelector('.navbar');
    const sidebar = document.querySelector('.sidebar');
    const header = document.querySelector('.head');
    const closeModalButton = modalContent.querySelector('.close-modal'); // Target close button inside modal
    const printModalButton = modalContent.querySelector('.print-modal'); // Target print button inside modal
    
    if (nav) nav.style.display = 'none';
    if (sidebar) sidebar.style.display = 'none';
    if (header) {
        header.style.marginTop = '70px';
    }
    if (closeModalButton) closeModalButton.style.display = 'none';
    if (printModalButton) printModalButton.style.display = 'none';
  
    // Get the HTML content of the modal (exclude close and print buttons)
    const printContent = modalContent.innerHTML;
  
    // Add CSS to control print layout and page breaks
    const style = `
      <style>
        @page {
          size: A4;
          margin: 0;
        }
        body {
          margin: 0;
          padding: 0;
        }
        .ticket-modal-content {
          width: 100%;
          height: auto;
          overflow: hidden;
          page-break-before: always;
        }
        .ticket-modal-content * {
          font-size: 12px; /* Adjust size as needed */
          word-wrap: break-word;
        }
      </style>
    `;
  
    // Insert print styles into the document
    const head = document.querySelector('head');
    const styleTag = document.createElement('style');
    styleTag.innerHTML = style;
    head.appendChild(styleTag);
  
    // Print the content
    window.print();

    // Restore original content after printing
    document.body.innerHTML = originalContent;

    // Reload the page to restore JavaScript functionality
    location.reload();
}

</script>


</body>
</html>
