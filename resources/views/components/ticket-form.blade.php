<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enhanced Ticket Form</title>
    <style>
/* Main Form Container Styling */
.ticket-form-container {
            position: absolute;
            width: 78%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 1000; /* Ensure the form is in front of the overlay */
        }

        /* Close Button Styling */
        .close-modal {
            font-size: 24px;
            font-weight: bold;
            color: #003067;
            background: transparent;
            border: none;
            cursor: pointer;
            transition: color 0.3s ease;
        }
        .close-modal:hover {
            color: #ff0000; /* Change to red on hover */
        }

/* Fieldset and Legend Styling */
fieldset {
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 15px;
    margin-bottom: 20px;
}
legend {
    font-weight: bold;
    font-size: 16px;
    color: #003067;
}

/* Input, Textarea, and Select Styling */
.ticket-form-container input[type="text"],
.ticket-form-container textarea,
.ticket-form-container select {
    width: 100%;
    padding: 8px;
    border: 2px solid #003067;
    border-radius: 5px;
    margin-bottom: 15px;
    box-sizing: border-box;
    transition: border-color 0.3s ease;
}
input::placeholder,
textarea::placeholder {
    color: #007bff;
    opacity: 0.6;
}
input:focus,
textarea:focus,
select:focus {
    border-color: #0056b3;
    outline: none;
}
/* Dropdown Container */
.issue-dropdown {
    position: relative;
    display: inline-block;
    width: 100%;
    margin-bottom: 15px;
}

/* Dropdown Button */
.issue-dropbtn {
    width: 100%;
    padding: 8px;
    border: 2px solid #003067;
    border-radius: 5px;
    background-color: white;
    color: #003067;
    cursor: pointer;
    box-sizing: border-box;
    text-align: left;
    transition: border-color 0.3s ease, background-color 0.3s ease, color 0.3s ease;
}

/* Hover Effect for Dropdown Button */
.issue-dropbtn:hover {
    border-color: #00579d;
    background-color: #00579d;
    color: white;
}

/* Dropdown Content */
.issue-dropdown-content {
    display: none;
    position: absolute;
    background-color: white;
    min-width: 100%;
    box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
    z-index: 1;
    border: 2px solid #003067;
    border-radius: 5px;
    box-sizing: border-box;
    margin-top: 5px;
}

/* Dropdown Items (Checkbox Labels) */
.issue-dropdown-content label {
    display: block;
    padding: 8px;
    cursor: pointer;
    color: #003067;
    transition: background-color 0.3s ease, color 0.3s ease;
}

/* Hover Effect for Dropdown Items */
.issue-dropdown-content label:hover {
    background-color: #003067;
    color: white;
}

/* Checkbox Styling */
.issue-dropdown-content input[type="checkbox"] {
    margin-right: 8px;
}

/* Show Dropdown on Hover */
.issue-dropdown:hover .issue-dropdown-content {
    display: block;
}

/* Label Styling */
label {
    font-weight: bold;
    color: #333;
    display: block;
    margin-bottom: 5px;
}

/* Personal Information Row Layout */
.personal-info-container {
    display: flex;
    gap: 15px;
    align-items: center;
}
.personal-info-field {
    flex: 1;
}

/* Support Details Section */
.support-details-container {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

/* Time In Container */
.time-container {
    display: flex;
    justify-content: center;
    gap: 2rem;
    flex-wrap: wrap;
    margin-bottom: 20px;
}
.time-container input[type="datetime-local"] {
    width: 100%;
    padding: 10px;
    border: 2px solid #007bff;
    border-radius: 5px;
    background-color: #f0f8ff;
    color: #333;
    font-size: 16px;
}
.time-container input[type="datetime-local"]:focus {
    border-color: #0056b3;
}

/* Additional Concern Container */
#otherConcernContainer {
    display: none;
    background-color: #a7d4f7;
    padding: 10px;
    border: 1px solid #0056b3;
    border-radius: 5px;
    margin-bottom: 5px;
}

/* Control Number Styling */
.control-number {
    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 20px;
    font-weight: bold;
    color: red;
}

/* Heading for Technical Service Slip */
.ticket-form-container h2 {
    font-size: 2em;
    margin-bottom: 20px;
    font-weight: bold;
    border-bottom: 2px solid #1165c6;
    padding: 10px 20px;
    background-color: #003067;
    color: white;
    border-radius: 8px;
    text-align: center;
}

/* Form Actions */
.form-actions {
    display: flex;
    justify-content: center;
    gap: 15px;
    margin: 20px 0;
}

/* Button Styling */
.submit-btn {
    padding: 10px 20px;
    font-size: 16px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    color: white;
    transition: background-color 0.3s;
    background-color: #007bff;
}
.submit-btn:hover {
    background-color: #00214e;
}

    </style>
</head>
<body>
    <!-- Ticket Form Container -->
    <div class="content-wrapper">
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
                                <option value="">Select Department</option>

                                <!-- Office of the City Mayor -->
                                <optgroup label="OFFICE OF THE CITY MAYOR">
                                <option value="Office of the City Mayor (OCM)">Office of the City Mayor (OCM)</option>
                                <option value="Chief of Staff">Chief of Staff</option>
                                <option value="Mayors Office Staff II">Mayors Office Staff II</option>
                                </optgroup>

                              
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
</script>

</body>
</html>
