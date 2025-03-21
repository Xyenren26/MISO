<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enhanced Ticket Form</title>
    <link rel="stylesheet" href="{{ asset('css/ticket_components_style.css') }}">
</head>
<body>
    <!-- Ticket Form Container -->
    <div class="content-wrapper-form">
        <div class="ticket-form-container">
            <button class="close-modal" onclick="closeTicketFormModal()">✖</button>
            <h2>Technical Service Slip</h2>
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
                                   value="{{ Auth::user()->first_name }}" {{ Auth::user()->account_type === 'end_user' ? 'readonly' : '' }}>
                        </div>
                        <div class="personal-info-field">
                            <label for="last-name">Last Name:</label>
                            <input type="text" id="last-name" name="last-name" placeholder="Last Name" required
                                   value="{{ Auth::user()->last_name }}" {{ Auth::user()->account_type === 'end_user' ? 'readonly' : '' }}>
                        </div>
                        <div class="personal-info-field">
                            <label for="department">Department</label>
                            <select id="department" name="department" class="department-select" required {{ Auth::user()->account_type === 'end_user' ? 'disabled' : '' }}>
                                @if (Auth::user()->account_type === 'end_user')
                                    <option value="{{ Auth::user()->department }}" selected>{{ Auth::user()->department }}</option>
                                @endif
                            </select>
                            <!-- Hidden input for department -->
                            <input type="hidden" name="department" value="{{ Auth::user()->department }}">
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
                                <label><input type="radio" name="issues[]" value="Hardware Issue" onchange="updateSelectedConcerns(); toggleSubDropdown('hardwareissueDropdown', this); setPriority('urgent')"> Hardware Issue</label>
                                <label><input type="radio" name="issues[]" value="Software Issue" onchange="updateSelectedConcerns(); toggleSubDropdown('softwareissueDropdown', this); setPriority('high')"> Software Issue</label>
                                <label><input type="radio" name="issues[]" value="File Transfer" onchange="updateSelectedConcerns(); toggleSubDropdown('filetransferDropdown', this); setPriority('low')"> File Transfer</label>
                                <label><input type="radio" name="issues[]" value="Network Connectivity" onchange="updateSelectedConcerns(); toggleSubDropdown('networkconnectivityDropdown', this); setPriority('medium')"> Network Connectivity</label>
                                <label><input type="radio" name="issues[]" value="Other" id="otherIssueCheckbox" onchange="toggleOtherInput(); updateSelectedConcerns(); setPriority('medium')"> Other: Specify</label>
                            </div>
                        </div>

                        <!-- Sub-dropdowns for specific concerns -->
                        <div id="hardwareissueDropdown" class="sub-dropdown" style="display: none;">
                            <label for="hardwareIssue">Hardware Issues:</label>
                            <select id="hardwareIssue" name="hardwareIssue" onchange="toggleOtherSubInput(this, 'hardwareOtherInput'); updateSelectedConcerns()">
                                <option value="">Select Hardware Issue</option>
                                <option value="Broken Screen">Broken Screen</option>
                                <option value="Battery Issue">Battery Issue</option>
                                <option value="Keyboard Malfunction">Keyboard Malfunction</option>
                                <option value="Printer Not Working">Printer Not Working</option>
                                <option value="Mouse Not Responding">Mouse Not Responding</option>
                                <option value="Power Supply Failure">Power Supply Failure</option>
                                <option value="Other">Other</option>
                            </select>
                            <input type="text" id="hardwareOtherInput" class="other-sub-issue" placeholder="Specify hardware issue" style="display: none;" oninput="updateSelectedConcerns()">
                        </div>

                        <div id="softwareissueDropdown" class="sub-dropdown" style="display: none;">
                            <label for="softwareIssue">Software Issues:</label>
                            <select id="softwareIssue" name="softwareIssue" onchange="toggleOtherSubInput(this, 'softwareOtherInput'); updateSelectedConcerns()">
                                <option value="">Select Software Issue</option>
                                <option value="System Crash">System Crash</option>
                                <option value="Application Not Responding">Application Not Responding</option>
                                <option value="License Expired">License Expired</option>
                                <option value="Operating System Error">Operating System Error</option>
                                <option value="Software Installation Failure">Software Installation Failure</option>
                                <option value="Virus/Malware Infection">Virus/Malware Infection</option>
                                <option value="Other">Other</option>
                            </select>
                            <input type="text" id="softwareOtherInput" class="other-sub-issue" placeholder="Specify software issue" style="display: none;" oninput="updateSelectedConcerns()">
                        </div>

                        <div id="filetransferDropdown" class="sub-dropdown" style="display: none;">
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

                        <div id="networkconnectivityDropdown" class="sub-dropdown" style="display: none;">
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
                        <select id="category" name="category" required onchange="setPriority(this.value)" {{ Auth::user()->account_type === 'end_user' ? 'disabled' : '' }}>
                            <option value="" disabled selected>Select Priority</option>
                            <option value="urgent">Urgent (1-3 days)</option>
                            <option value="high">High (4-12 hours)</option>
                            <option value="medium">Medium (30 min - 4 hours)</option>
                            <option value="low">Low (10-30 min)</option>
                        </select>
                        <!-- Hidden input for priority -->
                        <input type="hidden" name="category" value="medium"> <!-- Set a default value or dynamically update it -->

                        <!-- Priority Description -->
                        <div id="priorityDescription" style="margin-top: 10px; font-style: italic; color: #555;"></div>

                        <label for="employeeId">Employee ID:</label>
                        <input type="text" id="employeeId" name="employeeId" required
                            value="{{ str_pad(Auth::user()->employee_id, 7, '0', STR_PAD_LEFT) }}" {{ Auth::user()->account_type === 'end_user' ? 'readonly' : '' }}>
                        <span id="error-message" style="color: red; display: none;">Employee ID must be a 7-digit whole number.</span>
                    </div>
                </fieldset>

               <!-- Row 3: Support Details -->
                <fieldset>
                    <legend>Support Details</legend>
                    <div class="support-details-container">
                        <div class="support-details-field">
                            <label for="technicalSupport">Technical Support By:</label>
                            <select id="technicalSupport" name="technicalSupport" required {{ Auth::user()->account_type === 'end_user' ? 'disabled' : '' }}>
                                <option value="" disabled selected>Select Technical Support</option>
                                @foreach($technicalSupports as $tech)
                                    <option value="{{ $tech->employee_id }}">{{ $tech->first_name }} {{ $tech->last_name }}</option>
                                @endforeach
                            </select>
                            <!-- Hidden input for technicalSupport -->
                            <input type="hidden" name="technicalSupport" value="{{ $technicalSupports->isNotEmpty() ? $technicalSupports[0]->employee_id : '' }}">
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
    // Function to fetch departments and populate the dropdown
    async function populateDepartments() {
        try {
            // Fetch departments from the API
            const response = await fetch('/departments');
            if (!response.ok) throw new Error('Failed to fetch departments');
            const departments = await response.json();

            // Get all select elements with the class 'department-select'
            const selectElements = document.querySelectorAll('.department-select');

            // Loop through each select element
            selectElements.forEach(select => {
                // Clear any existing options
                select.innerHTML = '';

                // Add the default option
                const defaultOption = document.createElement('option');
                defaultOption.value = '';
                defaultOption.text = 'Select Department';
                select.appendChild(defaultOption);

                // Loop through the grouped departments
                for (const [groupName, groupDepartments] of Object.entries(departments)) {
                    // Create an optgroup element
                    const optgroup = document.createElement('optgroup');
                    optgroup.label = groupName;

                    // Loop through the departments in the group
                    groupDepartments.forEach(department => {
                        // Create an option element
                        const option = document.createElement('option');
                        option.value = department.name;
                        option.text = department.name;

                        // Preselect the user's department (if applicable)
                        if (department.name === "{{ Auth::user()->department }}") {
                            option.selected = true;
                        }

                        // Append the option to the optgroup
                        optgroup.appendChild(option);
                    });

                    // Append the optgroup to the select element
                    select.appendChild(optgroup);
                }
            });
        } catch (error) {
            console.error('Error fetching departments:', error);
            // Display a user-friendly error message in all select elements
            document.querySelectorAll('.department-select').forEach(select => {
                select.innerHTML = '<option value="">Failed to load departments. Please try again later.</option>';
            });
        }
    }

    // Call the function to populate the dropdown when the page loads
    document.addEventListener('DOMContentLoaded', populateDepartments);

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

    // Function to hide all sub-dropdowns and the "Other Concern" input field
    function hideAllSubDropdownsAndOtherInput() {
        const subDropdowns = document.querySelectorAll('.sub-dropdown');
        subDropdowns.forEach(dropdown => {
            dropdown.style.display = 'none';
        });

        // Hide the "Other Concern" input field
        const otherContainer = document.getElementById('otherConcernContainer');
        otherContainer.style.display = 'none';
        document.getElementById('otherConcern').value = ""; // Clear the input field
    }

    // Function to toggle sub-dropdowns
    function toggleSubDropdown(dropdownId, radio) {
        // Hide all sub-dropdowns and the "Other Concern" input field first
        hideAllSubDropdownsAndOtherInput();

        // Show the selected sub-dropdown if the radio is checked
        if (radio.checked) {
            let dropdown = document.getElementById(dropdownId);
            if (dropdown) {
                dropdown.style.display = "block";
            }
        }
    }

    // Function to toggle "Other" input in sub-dropdowns
    function toggleOtherSubInput(selectElement, otherInputId) {
        let otherInput = document.getElementById(otherInputId);
        if (selectElement.value === "Other") {
            otherInput.style.display = "block";
        } else {
            otherInput.style.display = "none";
            otherInput.value = "";
        }
    }

    // Function to toggle "Other Concern" Input Field
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

    // Function to update selected concerns and set priority
function updateSelectedConcerns() {
    let selectedConcerns = [];
    let priority = "medium"; // Default priority

    // Iterate through all checked radio buttons
    document.querySelectorAll("input[name='issues[]']:checked").forEach(checkbox => {
        let mainConcern = checkbox.value;
        let subDropdown = document.getElementById(checkbox.getAttribute("onchange")?.match(/'(.*?)'/)?.[1]);

        if (subDropdown) {
            let selectElement = subDropdown.querySelector("select");
            let subConcern = selectElement && selectElement.value ? selectElement.value : "";

            let otherInput = subDropdown.querySelector(".other-sub-issue");
            if (subConcern === "Other" && otherInput && otherInput.value.trim() !== "") {
                subConcern = otherInput.value;
            }

            if (subConcern) {
                selectedConcerns.push(`${mainConcern} - ${subConcern}`);
            } else {
                selectedConcerns.push(mainConcern);
            }

            // Set priority based on sub-concern
            if (mainConcern === "Hardware Issue") {
                switch (subConcern) {
                    case "Broken Screen":
                        priority = "urgent";
                        break;
                    case "Battery Issue":
                    case "Keyboard Malfunction":
                    case "Printer Not Working":
                    case "Power Supply Failure":
                        priority = "high";
                        break;
                    case "Mouse Not Responding":
                        priority = "medium";
                        break;
                    default:
                        priority = "medium";
                }
            } else if (mainConcern === "Software Issue") {
                switch (subConcern) {
                    case "Operating System Error":
                        priority = "high";
                        break;
                    case "Virus/Malware Infection":
                        priority = "medium";
                        break;
                    case "System Crash":
                    case "Application Not Responding":
                    case "License Expired":
                    case "Software Installation Failure":
                        priority = "low";
                        break;
                    default:
                        priority = "medium";
                }
            } else if (mainConcern === "File Transfer") {
                priority = "low";
            } else if (mainConcern === "Network Connectivity") {
                priority = "medium";
            }
        } else {
            selectedConcerns.push(mainConcern);

            // Set priority for main concerns without sub-dropdowns
            switch (mainConcern) {
                case "Hardware Issue":
                    priority = "high";
                    break;
                case "Software Issue":
                    priority = "medium";
                    break;
                case "File Transfer":
                    priority = "low";
                    break;
                case "Network Connectivity":
                    priority = "medium";
                    break;
                case "Other":
                    priority = "medium";
                    break;
                default:
                    priority = "medium";
            }
        }
    });

    // Handle "Other: Specify" input
    let otherCheckbox = document.getElementById("otherIssueCheckbox");
    let otherConcernInput = document.getElementById("otherConcern");

    if (otherCheckbox?.checked && otherConcernInput?.value.trim() !== "") {
        selectedConcerns.push(otherConcernInput.value);
    }

    // Ensure the form has an input field for concerns
    let form = document.getElementById("ticketForm"); // Update with your actual form ID
    let existingInput = document.querySelector("input[name='concern']");

    if (!existingInput) {
        let concernInput = document.createElement("input");
        concernInput.type = "hidden";
        concernInput.name = "concern";
        form.appendChild(concernInput);
        existingInput = concernInput;
    }

    // Assign selected concerns as value before form submission
    existingInput.value = selectedConcerns.join(", ");

    // Update the selected concerns button text
    document.getElementById("selectedConcerns").textContent = selectedConcerns.length > 0 ? selectedConcerns.join(", ") : "Select Concern";

    // Set the priority in a hidden input field
    let priorityInput = document.querySelector("input[name='priority']");
    if (!priorityInput) {
        priorityInput = document.createElement("input");
        priorityInput.type = "hidden";
        priorityInput.name = "priority";
        form.appendChild(priorityInput);
    }
    priorityInput.value = priority;

    // Call setPriority to update the dropdown and description
    setPriority(priority);

    console.log("Priority set to:", priority); // For debugging
}

// Function to set priority and description based on the selected concern
function setPriority(priority) {
    const priorityDropdown = document.getElementById('category');
    const descriptionElement = document.getElementById('priorityDescription');

    // Set the priority value in the dropdown
    if (priorityDropdown) {
        priorityDropdown.value = priority;
    }

    // Update the description based on the selected priority
    let description = '';
    switch (priority) {
        case 'urgent':
            description = 'Critical issues that need immediate attention, significantly impacting operations. (1-3 days)';
            break;
        case 'high':
            description = 'Important issues that need to be addressed quickly but are not system-critical. (4-12 hours)';
            break;
        case 'medium':
            description = 'Moderate impact issues that should be resolved soon but do not halt work. (30 min - 4 hours)';
            break;
        case 'low':
            description = 'Minor issues or routine tasks that have minimal impact and can be scheduled later. (10-30 min)';
            break;
        default:
            description = 'Please select a priority level.';
    }

    // Display the description
    if (descriptionElement) {
        descriptionElement.textContent = description;
    }
}

    // Add Event Listeners to Radio Buttons for Sub-Dropdowns
    document.querySelectorAll('.issue-dropdown-content input[type="radio"]').forEach(radio => {
        radio.addEventListener('change', function() {
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

    // Auto-pick first available option in technical support dropdown
    document.addEventListener("DOMContentLoaded", function () {
        let techSelect = document.getElementById("technicalSupport");
        if (techSelect.options.length > 1) {
            techSelect.selectedIndex = 1; // Auto-pick first available option
        }
    });

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

    // Print Modal Functionality
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