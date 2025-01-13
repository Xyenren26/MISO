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
            <form id="ticketForm" action="/ticket" method="POST">
            <div class="control-number" id="controlNumber">{{ $nextControlNo }}</div>
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
                                <!-- Loop through techSupport and populate the dropdown -->
                               
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
                    <button type="button" class="submit-btn">Submit</button>
                </div>
            </form>
        </div>
    </div>



    <script>
        // Open Modal
        function openModal() {
            document.getElementById('confirmationModal').style.display = 'flex';
        }

        // Close Modal
        function closeModal() {
            document.getElementById('confirmationModal').style.display = 'none';
        }

        // Placeholder for form submission
        function submitForm() {
            document.getElementById('ticketForm').submit();
        }
    </script>
</body>
</html>
