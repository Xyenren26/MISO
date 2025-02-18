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
                            <input type="text" id="first-name" name="first-name" placeholder="First Name" required
                            value="{{ Auth::user()->account_type === 'end_user' ? Auth::user()->first_name : '' }}">
                        </div>
                        <div class="personal-info-field">
                            <label for="last-name">Last Name:</label>
                            <input type="text" id="last-name" name="last-name" placeholder="Last Name" required
                            value="{{ Auth::user()->account_type === 'end_user' ? Auth::user()->last_name : '' }}">
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
                                <label><input type="checkbox" name="issues[]" value="Hardware Issue" onchange="updateSelectedConcerns(); toggleSubDropdown('hardwareDropdown', this)"> Hardware Issue</label>
                                <label><input type="checkbox" name="issues[]" value="Software Issue" onchange="updateSelectedConcerns(); toggleSubDropdown('softwareDropdown', this)"> Software Issue</label>
                                <label><input type="checkbox" name="issues[]" value="File Transfer" onchange="updateSelectedConcerns(); toggleSubDropdown('fileTransferDropdown', this)"> File Transfer</label>
                                <label><input type="checkbox" name="issues[]" value="Network Connectivity" onchange="updateSelectedConcerns(); toggleSubDropdown('networkDropdown', this)"> Network Connectivity</label>
                                <label><input type="checkbox" name="issues[]" value="Other" id="otherIssueCheckbox" onchange="toggleOtherInput(); updateSelectedConcerns()"> Other: Specify</label>
                            </div>
                        </div>

                        <!-- Sub-dropdowns for specific concerns -->
                        <div id="hardwareDropdown" class="sub-dropdown" style="display: none;">
                            <label for="hardwareIssue">Hardware Issues:</label>
                            <select id="hardwareIssue" name="hardwareIssue" onchange="toggleOtherSubInput(this, 'hardwareOtherInput'); updateSelectedConcerns()">
                                <option value="">Select Hardware Issue</option>
                                <option value="Broken Screen">Broken Screen</option>
                                <option value="Battery Issue">Battery Issue</option>
                                <option value="Keyboard Malfunction">Keyboard Malfunction</option>
                                <option value="Other">Other</option>
                            </select>
                            <input type="text" id="hardwareOtherInput" class="other-sub-issue" placeholder="Specify hardware issue" style="display: none;" oninput="updateSelectedConcerns()">
                        </div>

                        <div id="softwareDropdown" class="sub-dropdown" style="display: none;">
                            <label for="softwareIssue">Software Issues:</label>
                            <select id="softwareIssue" name="softwareIssue" onchange="toggleOtherSubInput(this, 'softwareOtherInput'); updateSelectedConcerns()">
                                <option value="">Select Software Issue</option>
                                <option value="System Crash">System Crash</option>
                                <option value="Application Not Responding">Application Not Responding</option>
                                <option value="License Expired">License Expired</option>
                                <option value="Other">Other</option>
                            </select>
                            <input type="text" id="softwareOtherInput" class="other-sub-issue" placeholder="Specify software issue" style="display: none;" oninput="updateSelectedConcerns()">
                        </div>

                        <div id="fileTransferDropdown" class="sub-dropdown" style="display: none;">
                            <label for="fileTransferIssue">File Transfer Issues:</label>
                            <select id="fileTransferIssue" name="fileTransferIssue" onchange="toggleOtherSubInput(this, 'fileTransferOtherInput'); updateSelectedConcerns()">
                                <option value="">Select File Transfer Issue</option>
                                <option value="Slow Transfer">Slow Transfer</option>
                                <option value="File Corruption">File Corruption</option>
                                <option value="Permission Denied">Permission Denied</option>
                                <option value="Other">Other</option>
                            </select>
                            <input type="text" id="fileTransferOtherInput" class="other-sub-issue" placeholder="Specify file transfer issue" style="display: none;" oninput="updateSelectedConcerns()">
                        </div>

                        <div id="networkDropdown" class="sub-dropdown" style="display: none;">
                            <label for="networkIssue">Network Connectivity Issues:</label>
                            <select id="networkIssue" name="networkIssue" onchange="toggleOtherSubInput(this, 'networkOtherInput'); updateSelectedConcerns()">
                                <option value="">Select Network Issue</option>
                                <option value="No Internet">No Internet</option>
                                <option value="Slow Connection">Slow Connection</option>
                                <option value="Frequent Disconnections">Frequent Disconnections</option>
                                <option value="Other">Other</option>
                            </select>
                            <input type="text" id="networkOtherInput" class="other-sub-issue" placeholder="Specify network issue" style="display: none;" oninput="updateSelectedConcerns()">
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
                        <input type="text" id="employeeId" name="employeeId" required
                        value="{{ Auth::user()->account_type === 'end_user' ? Auth::user()->employee_id : '' }}">
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
// Employee ID Validation
const employeeIdInput = document.getElementById('employeeId');
const errorMessage = document.getElementById('error-message');

employeeIdInput.addEventListener('input', function () {
    this.value = this.value.replace(/\D/g, '').slice(0, 7); // Allow only digits and limit to 7 characters

    if (this.value.length === 7) {
        errorMessage.style.display = 'none';
        this.setCustomValidity('');
    } else {
        errorMessage.style.display = 'inline';
        this.setCustomValidity('Invalid Employee ID');
    }
});

// Toggle "Other Concern" Input Field
document.getElementById('otherIssueCheckbox').addEventListener('change', function() {
    const otherContainer = document.getElementById('otherConcernContainer');
    otherContainer.style.display = this.checked ? 'block' : 'none';
    updateSelectedConcerns(); // Update selected concerns when toggling "Other"
});

function toggleSubDropdown(dropdownId, checkbox) {
    let dropdown = document.getElementById(dropdownId);
    if (checkbox.checked) {
        dropdown.style.display = "block";
    } else {
        dropdown.style.display = "none";
        // Reset dropdown selection and hide "Other" input if unchecked
        let selectElement = dropdown.querySelector("select");
        if (selectElement) selectElement.value = "";
        let otherInput = dropdown.querySelector(".other-sub-issue");
        if (otherInput) otherInput.style.display = "none";
    }
}

function toggleOtherSubInput(selectElement, otherInputId) {
    let otherInput = document.getElementById(otherInputId);
    if (selectElement.value === "Other") {
        otherInput.style.display = "block";
    } else {
        otherInput.style.display = "none";
        otherInput.value = "";
    }
}

function toggleOtherInput() {
    let otherCheckbox = document.getElementById("otherIssueCheckbox");
    let otherContainer = document.getElementById("otherConcernContainer");
    if (otherCheckbox.checked) {
        otherContainer.style.display = "block";
    } else {
        otherContainer.style.display = "none";
        document.getElementById("otherConcern").value = "";
    }
}

function updateSelectedConcerns() {
    let selectedConcerns = [];

    // Loop through checked main concerns
    document.querySelectorAll("input[name='issues[]']:checked").forEach(checkbox => {
        let mainConcern = checkbox.value;
        let subDropdown = document.getElementById(checkbox.getAttribute("onchange")?.match(/'(.*?)'/)?.[1]);

        if (subDropdown) {
            let selectElement = subDropdown.querySelector("select");
            let subConcern = selectElement && selectElement.value ? selectElement.value : "";

            // Check if "Other" is selected and get user input
            let otherInput = subDropdown.querySelector(".other-sub-issue");
            if (subConcern === "Other" && otherInput && otherInput.value.trim() !== "") {
                subConcern = otherInput.value;
            }

            if (subConcern) {
                selectedConcerns.push(`${mainConcern} - ${subConcern}`);
            } else {
                selectedConcerns.push(mainConcern);
            }
        } else {
            selectedConcerns.push(mainConcern);
        }
    });

    // Handle "Other" concern input separately
    let otherCheckbox = document.getElementById("otherIssueCheckbox");
    let otherConcernInput = document.getElementById("otherConcern");

    if (otherCheckbox?.checked && otherConcernInput?.value.trim() !== "") {
        selectedConcerns.push(otherConcernInput.value);
    }

    // Update the "Select Concern" button text
    document.getElementById("selectedConcerns").textContent = selectedConcerns.length > 0 ? selectedConcerns.join(", ") : "Select Concern";
}


// Add Event Listeners to Checkboxes for Sub-Dropdowns
document.querySelectorAll('.issue-dropdown-content input[type="checkbox"]').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const dropdownId = `${this.value.toLowerCase().replace(/ /g, '')}Dropdown`;
        toggleSubDropdown(dropdownId, this);
    });
});

// Add Event Listeners to Sub-Dropdowns
document.querySelectorAll('.sub-dropdown select').forEach(select => {
    select.addEventListener('change', updateSelectedConcerns);
});

// Add Event Listener to "Other Concern" Input Field
document.getElementById('otherConcern').addEventListener('input', updateSelectedConcerns);

// Form Validation
function validateForm() {
    const firstName = document.getElementById('first-name').value;
    const lastName = document.getElementById('last-name').value;
    const department = document.getElementById('department').value;
    const concern = document.getElementById('concern').value;
    const category = document.getElementById('category').value;
    const employeeId = document.getElementById('employeeId').value;
    const technicalSupport = document.getElementById('technicalSupport').value;
    const errorMessage = document.getElementById('errorMessage');

    // Check if all required fields are filled
    if (!firstName || !lastName || !department || !concern || !category || !employeeId || !technicalSupport) {
        errorMessage.style.display = 'block';
        return false;
    }

    // Check if "Other" concern is selected but no input is provided
    if (document.getElementById('otherIssueCheckbox').checked && !document.getElementById('otherConcern').value.trim()) {
        errorMessage.style.display = 'block';
        return false;
    }

    errorMessage.style.display = 'none';
    return true;
}

// Submit Button Event Listener
document.querySelector('.submit-btn').addEventListener('click', function(event) {
    if (!validateForm()) {
        event.preventDefault(); // Prevent form submission if validation fails
    } else {
        submitForm();
    }
});

// Submit Form
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
          size: A4
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