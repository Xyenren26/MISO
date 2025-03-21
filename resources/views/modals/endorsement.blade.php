<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    #endorsementModal{
        position: fixed;
        z-index: 999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
    }
    /* Modal Container */
    .endorsed-modal-content {
        max-width: 900px;
        margin: 20px auto;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 8px;
        background-color: #f9f9f9;
        z-index: 2; /* Set a high z-index value */
        position: relative; /* Make sure z-index works */
        top: 50%; /* Center vertically */
        transform: translate(0%, -20%); /* Offset to truly center the modal */
    }

    /* Header Section */
    .modal-header {
        text-align: center;
        margin-bottom: 20px;
    }

    .modal-header img {
        width: 200px;
        height: auto;
        margin: 0 auto;
        display: block;
    }

    .modal-header h2 {
        background-color: #003067;
        color: white;
        padding: 15px;
        margin: 0;
        text-transform: uppercase;
    }

    .modal-header p {
        text-align: right;
        font-size: 14px;
        color: #333;
    }

    /* Form Section */
    .modal-form-section {
        margin-bottom: 15px;
    }

    .modal-form-section label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    .modal-input-box {
        width: 96%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .modal-input-box-date{
        width: 325%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }


    /* Two Column Layout */
    .modal-two-column {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
    }

    .modal-column {
        flex: 1;
        min-width: 250px;
        background: #ffffff;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 15px;
    }

    /* Checkbox Group */
    .modal-checkbox-group {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .modal-checkbox-group div {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .modal-checkbox-group label {
        flex: 1;
        font-size: 16px;
    }

    .modal-checkbox-group input[type="text"] {
        flex: 2;
        padding: 5px;
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .inline-group {
        display: flex;
        flex-direction: column;
        gap: 5px;
        align-items: flex-start; /* Ensures everything starts from the left */
    }

    .inline-header {
        display: flex;
        align-items: center; /* Align items properly */
        gap: 10px;
        justify-content: flex-start; /* Ensures content aligns to the left */
    }

    .full-width {
        width: 58%;
        margin-left: 145px;
    }

    /* Footer Section */
    .modal-footer {
        margin-top: 20px;
        padding-top: 15px;
        border-top: 1px solid #ccc;
    }

    .modal-stacked-date-time {
        display: flex;
        gap: 363px;
        flex-wrap: wrap;
    }

    .endorsementsave {
        width: 100%;
        padding: 8px;
        background: #007BFF;
        color: #fff;
        font-weight: bold;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: 0.3s;
    }

    .endorsementsave:hover {
        background-color: #0056b3;
    }

    /* Responsive Design */
    @media (max-width: 600px) {
        .modal-two-column {
            flex-direction: column;
        }

        .modal-stacked-date-time {
            flex-direction: column;
        }
    }
    /* Responsive Design */
@media (max-width: 768px) {
    .endorsed-modal-content {
        width: 70%; /* Adjust width for smaller screens */
        padding: 15px; /* Reduce padding */
    }

    .modal-header h2 {
        font-size: 1.2rem; /* Smaller font size */
    }

    .modal-two-column {
        flex-direction: column; /* Stack columns vertically */
        gap: 10px; /* Reduce gap */
    }

    .modal-stacked-date-time {
        flex-direction: column; /* Stack date and time vertically */
        gap: 10px; /* Reduce gap */
    }

    .modal-column {
        min-width: 70%; /* Full width for columns */
    }

    .modal-input-box,
    .modal-input-box-date {
        font-size: 12px; /* Smaller font size for inputs */
    }
}

@media (max-width: 480px) {
    .modal-header img {
        width: 150px; /* Smaller logo */
    }

    .modal-header h2 {
        font-size: 1rem; /* Smaller font size */
        padding: 10px; /* Reduce padding */
    }

    .modal-header p {
        font-size: 12px; /* Smaller font size */
    }

    .endorsementsave {
        font-size: 14px; /* Smaller font size */
    }
    /* Form Section */
    .modal-form-section {
        margin-bottom: 15px;
        text-align: left;
    }

    .modal-form-section label {
      text-align: left;
    }
    .modal-input-box {
        width: 95%;
   }

    .modal-input-box-date{
        width: 95%;
    }
    /* Footer Section */
    .modal-footer {
       text-align:left;
    }
}
</style>

<!-- Modal HTML Structure -->
<form id="endorsementForm" action="{{ route('endorsements.store') }}" method="POST">
    @csrf
    <input type="hidden" name="ticket_id" id="ticket_id" value="{{ $ticket->ticket_id ?? '' }}">
    <div id="endorsementModal" class="modal" style="display: none;">
        <div class="endorsed-modal-content">
            <span class="close" onclick="closeEndorsementModal()">&times;</span>

            <!-- Header Section -->
            <div class="modal-header">
                <img src="{{ asset('images/SystemLogo.png') }}" alt="System Logo">
                <h2>Technical Endorsement</h2>
                <p><strong>MISO</strong><br>Management Information Systems Office</p>
            </div>
            <!-- Control No Section -->
            <div class="modal-form-section">
                <label for="control_no">Control No:</label>
                <input type="text" id="EndorsementControlNo" name="control_no" class="modal-input-box" readonly>
            </div>
            <div class="modal-form-section">
                <label for="employee">Employee Name:</label>
                <input type="text" id="EndorsementEmployee" name="employeee" class="modal-input-box" readonly>
            </div>
            <div class="modal-form-section">
                <label for="employee">Employee ID:</label>
                <input type="text" id="EndorsementEmployeeID" name="employeeeid" class="modal-input-box" readonly>
            </div>

            <!-- Department Section -->
            <div class="modal-form-section">
                <label for="department">Department/Office/Unit:</label>
                <input type="text" id="EndorsementDepartment" name="department" class="modal-input-box" readonly>
            </div>

            <!-- Concern Section -->
            <div class="modal-form-section">
                <h3>Concern</h3>
                <div class="modal-two-column">

                    <!-- Left Column -->
                    <div class="modal-column">
                        <div class="modal-checkbox-group">
                            <div>
                                <input type="checkbox" name="network[]" value="Network diagnostics">
                                <label>Network diagnostics</label>
                                <input type="text" name="network_details[Network diagnostics]" placeholder="Details" style="display: none;">
                            </div>
                            <div>
                                <input type="checkbox" name="network[]" value="Connectivity and access issues">
                                <label>Connectivity and access issues</label>
                                <input type="text" name="network_details[Connectivity and access issues]" placeholder="Details" style="display: none;">
                            </div>
                            <div>
                                <input type="checkbox" name="network[]" value="Account creation and management">
                                <label>Account creation and management</label>
                                <input type="text" name="network_details[Account creation and management]" placeholder="Details" style="display: none;">
                            </div>
                            <div>
                                <input type="checkbox" name="network[]" value="File sharing and folder permissions">
                                <label>File sharing and folder permissions</label>
                                <input type="text" name="network_details[File sharing and folder permissions]" placeholder="Details" style="display: none;">
                            </div>
                            <div>
                                <input type="checkbox" name="network[]" value="others">
                                <label>Others</label>
                                <input type="text" name="network_details[others]" placeholder="Details" style="display: none;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
            <!-- Endorsed To Section -->
            <div class="modal-footer">
                <h3>Endorsed To</h3>
                <div class="modal-form-section">
                    <label for="endorsed_to">Endorsed To:</label>
                    <input type="text" id="endorsed_to" name="endorsed_to" class="modal-input-box" placeholder="Enter Name">
                </div>
                <div class="modal-stacked-date-time">
                    <div>
                        <label for="endorsed_to_date">Date:</label>
                        <input type="date" name="endorsed_to_date" id="endorsed_to_date" class="modal-input-box-date" value="{{ date('Y-m-d') }}" readonly>
                    </div>
                    <div>
                        <label for="endorsed_to_time">Time:</label>
                        <input type="time" name="endorsed_to_time" id="endorsed_to_time" class="modal-input-box-date" value="{{ date('H:i') }}" readonly>
                    </div>
                </div>
                <div class="modal-form-section">
                    <label for="endorsed_to_remarks">Remarks:</label>
                    <input type="text" id="endorsed_to_remarks" name="endorsed_to_remarks" class="modal-input-box" placeholder="Enter Remarks">
                </div>
            </div>

            <!-- Endorsed By Section -->
            <div class="modal-footer">
                <h3>Endorsed By</h3>
                <div class="modal-form-section">
                <label for="technical_division">Technical Division:</label>
                <input type="text" id="technical_Support_division" name="endorsed_by" class="modal-input-box"  readonly>
                </div>
                <div class="modal-stacked-date-time">
                    <div>
                        <label for="date">Date:</label>
                        <input type="date" id="endorsed_by_date" name="endorsed_by_date" class="modal-input-box-date" value="{{ date('Y-m-d') }}" readonly>
                    </div>
                    <div>
                        <label for="time">Time:</label>
                        <input type="time" id="endorsed_by_time" name="endorsed_by_time" class="modal-input-box-date" value="{{ date('H:i') }}" readonly>
                    </div>
                </div>
                <div class="modal-form-section">
                    <label for="remarks">Work Done:</label>
                    <input id="remarkstechnical" name="endorsed_by_remarks" class="modal-input-box"  readonly>
                </div>
            </div>
            <button type="submit" class="endorsementsave">Save</button>
        </div>
    </div>
</form>
<script>
document.addEventListener('change', function(event) {
    if (event.target.type === 'checkbox') {
        // Special case for "FOLDER ACCESS"
        if (event.target.value === 'FOLDER ACCESS') {
            var parentElement = event.target.closest('.inline-group');
            if (parentElement) {
                var folderNameInput = parentElement.querySelector('input[name="user_account_details[FOLDER ACCESS]"]');
                var userNameInput = parentElement.querySelector('input[name="user_account_details[FOLDER ACCESS_USER]"]');

                if (event.target.checked) {
                    folderNameInput.style.display = 'block';
                    userNameInput.style.display = 'block';
                } else {
                    folderNameInput.style.display = 'none';
                    folderNameInput.value = ''; // Clear input
                    userNameInput.style.display = 'none';
                    userNameInput.value = ''; // Clear input
                }
            }
        } else {
            // Default behavior for all other checkboxes (one input field per checkbox)
            var inputField = event.target.parentElement.querySelector('input[type="text"]');
            if (inputField) {
                if (event.target.checked) {
                    inputField.style.display = 'block';
                } else {
                    inputField.style.display = 'none';
                    inputField.value = ''; // Clear input when unchecked
                }
            }
        }
    }
});

document.getElementById('endorsementForm').addEventListener('submit', function (event) {
    event.preventDefault(); // Prevent default form submission

    let formData = new FormData(this);
    
    // Log all form data before sending
    console.log('Form Data before submission:');
    formData.forEach((value, key) => {
        console.log(key, value);
    });

    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Response:', data);
        if (data.success) {
            alert(data.message);
            location.reload(); // Reload the page or close modal
        } else {
            alert('Failed to save endorsement.');
        }
    })
    .catch(error => console.error('Error:', error));
});

</script>
