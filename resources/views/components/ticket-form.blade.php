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
                            <button class="issue-dropbtn" id="selectedConcerns" disabled>Select Concern</button>
                            <div class="issue-dropdown-content" id="mainConcernsDropdown">
                                <!-- Main concerns will be loaded dynamically from database -->
                                @foreach($mainConcerns as $concern)
                                    <label>
                                        <input type="radio" name="issues[]" value="{{ $concern->name }}" 
                                            data-concern-id="{{ $concern->id }}"
                                            data-assigned-user-id="{{ $concern->assigned_user_id }}"
                                            data-assign-to-all="{{ $concern->assign_to_all_tech }}"
                                            data-default-priority="{{ $concern->default_priority }}"
                                            onchange="loadSubConcerns(this); updateSelectedConcerns(); setPriority(this.dataset.defaultPriority || 'medium')">
                                        {{ $concern->name }}
                                    </label>
                                @endforeach
                                <label>
                                    <input type="radio" name="issues[]" value="Other" id="otherIssueCheckbox" 
                                        onchange="toggleOtherInput(); updateSelectedConcerns(); setPriority('medium')">
                                    Other: Specify
                                </label>
                            </div>
                        </div>

                        <!-- Dynamic sub-dropdown container -->
                        <div id="subConcernsContainer">
                            <!-- Sub-concerns will be loaded here dynamically -->
                        </div>

                        <div id="otherConcernContainer" style="display: none;">
                            <label for="otherConcern">Please Specify:</label>
                            <input type="text" id="otherConcern" name="otherConcern" placeholder="Specify your concern" oninput="updateSelectedConcerns()">
                        </div>

                        <label for="category">Priority:</label>
                        <select id="category" name="category" required onchange="setPriority(this.value)" {{ Auth::user()->account_type === 'end_user' ? 'disabled' : '' }}>
                            <option value="" readonly selected>Select Priority</option>
                            <option value="urgent">Urgent (1-3 days)</option>
                            <option value="high">High (4-12 hours)</option>
                            <option value="medium">Medium (30 min - 4 hours)</option>
                            <option value="low">Low (10-30 min)</option>
                        </select>
                        <input type="hidden" name="category" value="">

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
                                    <option value="{{ $tech->employee_id }}" 
                                        data-user-id="{{ $tech->id }}">{{ $tech->first_name }} {{ $tech->last_name }}</option>
                                @endforeach
                            </select>
                            <!-- Hidden input for technicalSupport -->
                            <input type="hidden" name="technicalSupport" value="">
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
    // DOM Elements
    const elements = {
        employeeId: document.getElementById('employeeId'),
        errorMessage: document.getElementById('error-message'),
        otherIssueCheckbox: document.getElementById('otherIssueCheckbox'),
        otherConcernContainer: document.getElementById('otherConcernContainer'),
        otherConcern: document.getElementById('otherConcern'),
        selectedConcerns: document.getElementById('selectedConcerns'),
        priorityDropdown: document.getElementById('category'),
        priorityDescription: document.getElementById('priorityDescription'),
        form: document.getElementById('ticketForm'),
        subConcernsContainer: document.getElementById('subConcernsContainer'),
        technicalSupportSelect: document.getElementById('technicalSupport')
    };

    // Department Functions
    async function populateDepartments() {
        try {
            const response = await fetch('/departments');
            if (!response.ok) throw new Error('Failed to fetch departments');
            const departments = await response.json();

            document.querySelectorAll('.department-select').forEach(select => {
                select.innerHTML = '';
                const defaultOption = new Option('Select Department', '');
                select.add(defaultOption);

                for (const [groupName, groupDepartments] of Object.entries(departments)) {
                    const optgroup = document.createElement('optgroup');
                    optgroup.label = groupName;

                    groupDepartments.forEach(department => {
                        const option = new Option(department.name, department.name);
                        if (department.name === "{{ Auth::user()->department }}") {
                            option.selected = true;
                        }
                        optgroup.appendChild(option);
                    });

                    select.appendChild(optgroup);
                }
            });
        } catch (error) {
            console.error('Error fetching departments:', error);
            document.querySelectorAll('.department-select').forEach(select => {
                select.innerHTML = '<option value="">Failed to load departments. Please try again later.</option>';
            });
        }
    }

    // Employee ID Validation
    function setupEmployeeIdValidation() {
        elements.employeeId.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '').slice(0, 7);
            if (this.value.length === 7) {
                elements.errorMessage.style.display = 'none';
                this.setCustomValidity('');
            } else {
                elements.errorMessage.style.display = 'inline';
                this.setCustomValidity('Invalid Employee ID');
            }
        });
    }

   // Update the setupConcernHandlers function
    function setupConcernHandlers() {
        // Toggle "Other Concern" input
        elements.otherIssueCheckbox.addEventListener('change', function() {
            // Hide all sub-dropdowns first
            hideAllSubDropdownsAndOtherInput();
            
            // Then show the "Other" input if checked
            elements.otherConcernContainer.style.display = this.checked ? 'block' : 'none';
            updateSelectedConcerns();
            
            // Reset technical support selection when "Other" is selected
            if (this.checked) {
                resetTechnicalSupport();
            }
        });

        // Event listeners for radio buttons
        document.querySelectorAll('.issue-dropdown-content input[type="radio"]').forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.id !== 'otherIssueCheckbox') {
                    const dropdownId = `${this.value.toLowerCase().replace(/ /g, '')}Dropdown`;
                    toggleSubDropdown(dropdownId, this);
                }
                updateSelectedConcerns();
            });
        });

        // Event listener for "Other Concern" input
        elements.otherConcern.addEventListener('input', updateSelectedConcerns);
    }

    function hideAllSubDropdownsAndOtherInput() {
        elements.subConcernsContainer.innerHTML = '';
        elements.otherConcernContainer.style.display = 'none';
        elements.otherConcern.value = '';
    }

    // Update the toggleSubDropdown function
    function toggleSubDropdown(dropdownId, radio) {
        // Hide all sub-dropdowns
        elements.subConcernsContainer.innerHTML = '';
        
        // Hide the "Other Concern" input when a different radio is selected
        elements.otherConcernContainer.style.display = 'none';
        elements.otherConcern.value = '';
        
        // Show the selected sub-dropdown if the radio is checked
        if (radio.checked && radio.id !== 'otherIssueCheckbox') {
            const dropdown = document.getElementById(dropdownId);
            if (dropdown) dropdown.style.display = "block";
        }
    }

    // Priority Management
    function setPriority(priority) {
        if (elements.priorityDropdown) {
            elements.priorityDropdown.value = priority;
        }

        const descriptions = {
            'urgent': 'Critical issues that need immediate attention, significantly impacting operations. (1-3 days)',
            'high': 'Important issues that need to be addressed quickly but are not system-critical. (4-12 hours)',
            'medium': 'Moderate impact issues that should be resolved soon but do not halt work. (30 min - 4 hours)',
            'low': 'Minor issues or routine tasks that have minimal impact and can be scheduled later. (10-30 min)',
            'default': 'Please select a priority level.'
        };

        if (elements.priorityDescription) {
            elements.priorityDescription.textContent = descriptions[priority] || descriptions.default;
        }
    }

    // Form Submission
    function validateForm() {
        const requiredFields = [
            document.getElementById('first-name').value,
            document.getElementById('last-name').value,
            document.getElementById('department').value,
            document.querySelector("input[name='concern']")?.value,
            elements.priorityDropdown.value,
            elements.employeeId.value,
            document.getElementById('technicalSupport').value
        ];

        const otherConcernCheck = elements.otherIssueCheckbox.checked && !elements.otherConcern.value.trim();
        const isValid = requiredFields.every(field => field) && !otherConcernCheck;

        document.getElementById('errorMessage').style.display = isValid ? 'none' : 'block';
        return isValid;
    }

    function setupFormSubmission() {
        document.querySelector('.submit-btn').addEventListener('click', function(event) {
            if (!validateForm()) {
                event.preventDefault();
            }
        });
    }

    // Reset technical support selection
    function resetTechnicalSupport() {
        if (elements.technicalSupportSelect) {
            elements.technicalSupportSelect.selectedIndex = 0;
            document.querySelector('input[name="technicalSupport"]').value = '';
        }
    }

    // Auto-assign technical support based on concern
    function autoAssignTechnicalSupport(concernId, assignedUserId, assignToAllTech) {
        if (!elements.technicalSupportSelect) return;
        
        // If assign_to_all_tech is true (1), auto-assign to a random technician
        if (assignToAllTech === '1') {
            assignRandomTechnician();
            return;
        }
        
        // If no assigned user ID, assign to a random technician
        if (!assignedUserId || assignedUserId === 'null') {
            assignRandomTechnician();
            return;
        }
        
        // Find the option with matching user ID
        for (let i = 0; i < elements.technicalSupportSelect.options.length; i++) {
            const option = elements.technicalSupportSelect.options[i];
            if (option.dataset.userId === assignedUserId) {
                elements.technicalSupportSelect.selectedIndex = i;
                document.querySelector('input[name="technicalSupport"]').value = option.value;
                return;
            }
        }
        
        // If no match found, assign to a random technician
        assignRandomTechnician();
    }

    // Assign to a random technician
    function assignRandomTechnician() {
        if (!elements.technicalSupportSelect || elements.technicalSupportSelect.options.length <= 1) return;
        
        // Skip the first option (disabled "Select Technical Support")
        const randomIndex = Math.floor(Math.random() * (elements.technicalSupportSelect.options.length - 1)) + 1;
        const randomOption = elements.technicalSupportSelect.options[randomIndex];
        
        elements.technicalSupportSelect.selectedIndex = randomIndex;
        document.querySelector('input[name="technicalSupport"]').value = randomOption.value;
    }

    // Dynamic Sub-Concerns
    async function loadSubConcerns(selectedRadio) {
        const concernId = selectedRadio.dataset.concernId;
        if (!concernId) return;

        try {
            const response = await fetch(`/concerns/${concernId}/sub-concerns`);
            if (!response.ok) throw new Error('Failed to fetch sub-concerns');
            const subConcerns = await response.json();

            if (subConcerns.length > 0) {
                const dropdownId = `subConcernDropdown_${concernId}`;
                const optionsHTML = subConcerns.map(concern => 
                    `<option value="${concern.name}" 
                        data-default-priority="${concern.default_priority || 'medium'}"
                        data-assigned-user-id="${concern.assigned_user_id}"
                        data-assign-to-all="${concern.assign_to_all_tech}"
                        data-concern-id="${concern.id}">
                        ${concern.name}
                    </option>`
                ).join('');

                elements.subConcernsContainer.innerHTML = `
                    <div id="${dropdownId}" class="sub-dropdown" style="display: block;">
                        <label for="subConcern_${concernId}">${selectedRadio.value} Options:</label>
                        <select id="subConcern_${concernId}" name="subConcern_${concernId}" 
                                onchange="handleSubConcernChange(this, '${selectedRadio.value}', '${dropdownId}')">
                            <option value="">Select ${selectedRadio.value} Option</option>
                            ${optionsHTML}
                            <option value="Other">Other</option>
                        </select>
                        <input type="text" id="subConcernOther_${concernId}" 
                            class="other-sub-issue" placeholder="Specify ${selectedRadio.value.toLowerCase()} issue" 
                            style="display: none;" oninput="updateSelectedConcerns()">
                    </div>
                `;
            }
            
            // Auto-assign technical support based on main concern
            autoAssignTechnicalSupport(
                concernId,
                selectedRadio.dataset.assignedUserId,
                selectedRadio.dataset.assignToAll
            );
        } catch (error) {
            console.error('Error loading sub-concerns:', error);
        }
    }

    function handleSubConcernChange(selectElement, mainConcernName, dropdownId) {
        const concernId = dropdownId.split('_')[1];
        const otherInput = document.getElementById(`subConcernOther_${concernId}`);
        
        otherInput.style.display = selectElement.value === "Other" ? "block" : "none";
        if (selectElement.value !== "Other") otherInput.value = "";
        
        const defaultPriority = selectElement.selectedOptions[0]?.dataset.defaultPriority || 'medium';
        setPriority(defaultPriority);
        updateSelectedConcerns();
        
        // Auto-assign technical support based on sub-concern
        if (selectElement.value !== "Other") {
            autoAssignTechnicalSupport(
                selectElement.selectedOptions[0]?.dataset.concernId,
                selectElement.selectedOptions[0]?.dataset.assignedUserId,
                selectElement.selectedOptions[0]?.dataset.assignToAll
            );
        } else {
            assignRandomTechnician();
        }
    }

    function updateSelectedConcerns() {
        const mainConcernRadio = document.querySelector('input[name="issues[]"]:checked');
        if (!mainConcernRadio) {
            elements.selectedConcerns.textContent = "Select Concern";
            return;
        }

        const mainConcern = mainConcernRadio.value;
        let selectedConcern = "";
        let priority = "";

        if (mainConcern === "Other") {
            if (elements.otherConcern.value.trim()) {
                selectedConcern = elements.otherConcern.value.trim();
                priority = "medium";
            }
        } else {
            const concernId = mainConcernRadio.dataset.concernId;
            
            if (concernId) {
                const subConcernSelect = document.getElementById(`subConcern_${concernId}`);
                if (subConcernSelect?.value) {
                    let subConcern = subConcernSelect.value;
                    const otherInput = document.getElementById(`subConcernOther_${concernId}`);
                    
                    if (subConcern === "Other" && otherInput?.value.trim()) {
                        subConcern = otherInput.value.trim();
                    }
                    
                    if (subConcern) {
                        selectedConcern = `${mainConcern} - ${subConcern}`;
                    } else {
                        selectedConcern = mainConcern;
                    }
                    
                    priority = subConcernSelect.selectedOptions[0]?.dataset.defaultPriority || 'medium';
                } else {
                    selectedConcern = mainConcern;
                    priority = mainConcernRadio.dataset.defaultPriority || 'medium';
                }
            } else {
                selectedConcern = mainConcern;
                priority = "medium";
            }
        }

        // Update hidden concern input (still stores full path for backend)
        let concernInput = elements.form.querySelector("input[name='concern']");
        if (!concernInput) {
            concernInput = document.createElement("input");
            concernInput.type = "hidden";
            concernInput.name = "concern";
            elements.form.appendChild(concernInput);
        }
        concernInput.value = selectedConcern;

        // Update UI - show only the most specific level
        elements.selectedConcerns.textContent = selectedConcern || "Select Concern";

        if (priority) {
            setPriority(priority);
            document.querySelector('input[name="category"]').value = priority;
        }
    }

    // Print Functionality
    function printModal() {
        const modalContent = document.querySelector('#ticketModal .ticket-modal-content');
        const originalContent = document.body.innerHTML;

        // Hide elements
        const elementsToHide = [
            document.querySelector('.navbar'),
            document.querySelector('.sidebar'),
            document.querySelector('.head'),
            modalContent?.querySelector('.close-modal'),
            modalContent?.querySelector('.print-modal')
        ];

        elementsToHide.forEach(el => el && (el.style.display = 'none'));

        // Add print styles
        const style = document.createElement('style');
        style.innerHTML = `
            @page { size: A4; margin: 0; }
            body { margin: 0; padding: 0; }
            .ticket-modal-content { 
                width: 100%; height: auto; 
                overflow: hidden; page-break-before: always; 
            }
            .ticket-modal-content * { font-size: 12px; word-wrap: break-word; }
        `;
        document.head.appendChild(style);

        window.print();
        document.body.innerHTML = originalContent;
        location.reload();
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', () => {
        populateDepartments();
        setupEmployeeIdValidation();
        setupConcernHandlers();
        setupFormSubmission();
        
        // Auto-select first technical support option if no auto-assignment
        const techSelect = document.getElementById('technicalSupport');
        if (techSelect?.options.length > 1) {
            techSelect.selectedIndex = 1;
        }
    });
</script>
</body>
</html>