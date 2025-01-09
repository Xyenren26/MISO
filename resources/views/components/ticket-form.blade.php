<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Form</title>
    <style>
        
        h2 {
            color: #003067;
            text-align: center;
            letter-spacing: 1px;
        }

        .content-wrapper {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .ticket-form-container {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            width: 80%;
            max-width: 800px;
        }

        fieldset {
            border: 1px solid #003067;
            padding: 10px;
            margin-bottom: 20px;
        }

        legend {
            color: #003067;
            font-weight: bold;
        }

        .personal-info-container, .form-row, .support-details-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .personal-info-field, .form-row label, .form-row input, .support-details-field {
            display: flex;
            flex-direction: column;
            width: 48%;
        }

        .personal-info-field input, .support-details-field input, .form-row select {
            padding: 5px;
            border: 1px solid #003067;
        }

        .form-actions {
            text-align: center;
        }

        .submit-btn {
            background-color: #003067;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .submit-btn:hover {
            background-color: #a2d1f8;
        }

        #errorMessage {
            color: red;
            display: none;
            text-align: center;
            margin-top: 10px;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            width: 400px;
        }

        .modal-actions {
            text-align: center;
            margin-top: 20px;
        }

        .modal-actions button {
            background-color: #003067;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            margin: 5px;
        }

        .modal-actions button:hover {
            background-color: #a2d1f8;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .data-box {
            padding: 5px;
            border: 1px solid #003067;
            width: 100%;
        }

        /* Centering the content-wrapper */
.content-wrapper {
    display: flex;
    justify-content: center;
    align-items: center; /* Vertically center */
    height: 100vh; /* Use full viewport height */
    margin-top: 0; /* Remove any top margin */
}

/* Ticket Form Container */
.ticket-form-container {
    background-color: white;
    padding: 20px;
    border-radius: 5px;
    width: 80%;
    max-width: 800px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Optional: Adding a shadow for better visibility */
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    justify-content: center;
    align-items: center;
}

.modal-content {
    margin-left:700px;
    background-color: white;
    padding: 20px;
    border-radius: 5px;
    width: 400px;
    max-width: 90%;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Optional: Adding a shadow for the modal */
}

    </style>
</head>
<body>

    <!-- Ticket Form Container -->
    <div class="content-wrapper">
        <div class="ticket-form-container">
            <h2>Technical Service Slip</h2>
            <!-- Control Number Display -->
            <div class="control-number" id="controlNumber">{{ $nextControlNo ?? 'Not Available' }}</div>
            <form id="ticketForm" action="/ticket" method="POST">
                @csrf
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
                            <label for="department">Department:</label>
                            <select id="department" name="department" required>
                                <option value="" disabled selected>Select Department</option>
                                <option>GSO Department</option>
                                <option>ACCOUNTANCY Department</option>
                                <option>PUSO Department</option>
                                <option>MAYORS OFFICE Department</option>
                            </select>
                        </div>
                    </div>
                </fieldset>

                <!-- Row 2: Ticket Details -->
                <fieldset>
                    <legend>Ticket Details</legend>
                    <div class="form-row">
                        <label for="concern">Concern/Problem:</label>
                        <select id="concern" name="concern" required onchange="toggleOtherInput()">
                            <option value="" disabled selected>Select Concern</option>
                            <option value="Hardware Issue">Hardware Issue</option>
                            <option value="Software Issue">Software Issue</option>
                            <option value="file-transfer">File Transfer</option>
                            <option value="Network Connectivity">Network Connectivity</option>
                            <option value="other">Other: Specify</option>
                        </select>

                        <!-- Specify Other Concern -->
                        <div id="otherConcernContainer" style="display: none;">
                            <label for="otherConcern">Please Specify:</label>
                            <input type="text" id="otherConcern" name="otherConcern" placeholder="Specify your concern">
                        </div>

                        <label for="category">Category:</label>
                        <select id="category" name="category" required>
                            <option value="" disabled selected>Select Category</option>
                            <option value="Priority">Priority</option>
                            <option value="Semi-Urgent">Semi-Urgent</option>
                            <option value="Non-Urgent">Non-Urgent</option>
                        </select>

                        <label for="employeeId">Employee ID:</label>
                        <input type="text" id="employeeId" name="employeeId" required>
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
                                @if(isset($techSupport))
                                    @foreach($techSupport as $support)
                                        <option value="{{ $support->EmployeeID }}">{{ $support->FirstName }} {{ $support->LastName }}</option>
                                    @endforeach
                                @else
                                    <option value="" disabled>No Technical Support Available</option>
                                @endif
                            </select>
                        </div>
                        <div class="time-container">
                            <div class="support-details-field">
                                <label for="timeIn">Time In:</label>
                                <input type="datetime-local" id="timeIn" name="timeIn" required>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <!-- Error Message Container -->
                <div id="errorMessage" style="color: red; display: none; text-align:center; margin-top: 10px;">
                    <p>Please fill in all fields.</p>
                </div>

                <!-- Submit Button -->
                <div class="form-actions">
                    <button type="button" class="submit-btn" onclick="validateAndSubmit()">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <div id="confirmationModal" class="modal">
        <div class="modal-content">
            <h2>Confirmation Details</h2>
            <div id="confirmationDetails">
                <div class="detail-row">
                    <label for="first-name">First Name:</label>
                    <div class="data-box" id="first-name-data"></div>
                    <label for="last-name">Last Name:</label>
                    <div class="data-box" id="last-name-data"></div>
                </div>
                <div class="detail-row">
                    <label for="department">Department:</label>
                    <div class="data-box" id="department-data"></div>
                </div>
                <div class="detail-row">
                    <label for="concern">Concern:</label>
                    <div class="data-box" id="concern-data"></div>
                </div>
                <div class="detail-row">
                    <label for="category">Category:</label>
                    <div class="data-box" id="category-data"></div>
                    <label for="employeeId">Employee ID:</label>
                    <div class="data-box" id="employee-id-data"></div>
                </div>
            </div>
            <div class="modal-actions">
                <button type="button" onclick="submitForm()">Submit</button>
                <button type="button" onclick="closeModal()">Close</button>
            </div>
        </div>
    </div>

    <script>
        // Toggle visibility of "Other Concern" input
        function toggleOtherInput() {
            const concern = document.getElementById('concern').value;
            const otherConcernContainer = document.getElementById('otherConcernContainer');
            otherConcernContainer.style.display = (concern === 'other') ? 'block' : 'none';
        }

        // Validate and submit the form
        function validateAndSubmit() {
            const form = document.getElementById('ticketForm');
            const errorMessage = document.getElementById('errorMessage');
            if (!form.checkValidity()) {
                errorMessage.style.display = 'block';
            } else {
                errorMessage.style.display = 'none';
                form.submit();
            }
        }

        // Open modal for confirmation
        function openModal() {
            const modal = document.getElementById('confirmationModal');
            modal.style.display = 'flex';
        }

        // Close the modal
        function closeModal() {
            const modal = document.getElementById('confirmationModal');
            modal.style.display = 'none';
        }

        // Submit the form inside modal
        function submitForm() {
            document.getElementById('ticketForm').submit();
        }
    </script>

</body>
</html>
