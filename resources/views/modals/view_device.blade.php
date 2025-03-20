<style>
.form-popup-content-view {
    background-color: white;
    border: 2px solid #003067;
    padding: 20px;
    border-radius: 10px;
    margin-top: 30%;
    margin-left: auto;
    margin-right: auto;
    width: 60%;
    position: relative;
}
.waiting-approval {
    position: absolute;
    top: 10px;
    left: 15px;
    color: #d9534f; /* Red text */
    font-weight: bold;
    font-size: 14px;
    margin: 0;
    padding: 0;
}
.form-popup-section-radio {
    display: flex;
    gap: 20px; /* Adjust spacing between options */
    align-items: center; /* Aligns items vertically */
}

.qr-code {
    position: absolute;
    top: 10px;
    left: 15px;
    color: #d9534f; /* Red text */
    font-weight: bold;
    font-size: 14px;
    margin: 0;
    padding: 0;
    width: 100px;
}

.status-container {
    position: absolute;
    top: 102px;
    left: 15px;
    display: flex;
    align-items: center; /* Align label and status text */
    gap: 5px; /* Space between label and status */
    margin-top: 10px; /* Add spacing from the QR code */
}

.status-text {
    border: none;
    background: transparent;
    outline: none;
    font-weight: bold;
    color: #333; /* Match label color */
    font-size: 16px;
    text-transform: Uppercase;
}

/* Hide the first column (ID column) */
#viewEquipmentTable th:nth-child(1),
#viewEquipmentTable td:nth-child(1) {
    display: none;
}

.form-popup-form-info_no {
    display: flex;
    align-items: center;
    justify-content:center;
    gap: 8px; /* Adds space between label and input */
}

.form-label-no {
    font-weight: 600; /* Makes label bold */
}

.form-input-no {
    border: none;
    background: transparent;
    outline: none;
    color: #333; /* Dark gray for visibility */
    width: auto;
}

#ButtonService{
    margin-top: 20px;
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
#updateButtonService{
    margin-top: 20px;
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
#saveButtonService{
    margin-top: 20px;
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

.rating-container {
    position: absolute;
    top: 50px;
    right: 20px;
    background: #f8f9fa; /* Light background */
    padding: 5px 10px;
    border-radius: 5px;
    font-weight: bold;
    display: flex;
    align-items: center;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.rating-label {
    margin-right: 5px;
}

.rating-value {
    color: #ffcc00; /* Yellow for rating */
    font-size: 18px;
}


</style>
<!-- View Device Form Popup -->
<div id="viewFormPopup" class="form-popup-container" style="display: none;">
    <div class="form-popup-content-view">
        <span class="form-popup-close-btn" onclick="closePopup('viewFormPopup')">×</span>
        <div class="form-popup-form-container">

        <div class="rating-container" id="rating-containerPullOut">
            <label class="form-popup-label">Rating:</label>
            <div id="starRatingPullOut"></div>
        </div>
            
        <div id="waitingForApprovalService" class="waiting-approval" style="display: none;">
            <p>🚨 Waiting for Technical Head approval...</p>
        </div>
        
        
            <div id="qrCodeContainer"  onclick="printQRCode()">
               
            </div>

            <div class="status-container">
                <label class="form-popup-label">Status:</label>
                <input type="text" id="viewStatusService" class="status-text" readonly>
            </div>
            <header class="form-popup-header">
                <div class="form-popup-logo">
                    <img src="images/SystemLogo.png" alt="Logo">
                </div>
                <h1>ICT Equipment Service Request Form</h1>
                <div class="form-popup-form-info_no">
                    <label for="viewFormNoService" class="form-label-no">Form No.:</label>
                    <input type="text" id="viewFormNoService" class="form-input-no" readonly>
                </div>
            </header>

            <!-- Service Type (Radio Buttons) -->
            <section class="form-popup-section-radio">
                <label><input type="radio" name="service_type" id="view_service_type_walk_in" disabled> Walk-In</label>
                <label><input type="radio" name="service_type" id="view_service_type_pull_out" disabled> Pull-Out</label>
            </section>

            <!-- General Information Section -->
            <section class="form-popup-section">
                <h3 class="form-popup-title">General Information</h3>
                <div class="form-popup-row">
                    <div class="form-popup-input-group">
                        <label class="form-popup-label">Employee Name:</label>
                        <input class="form-popup-input" id="viewName" readonly>
                    </div>
                    <div class="form-popup-input-group">
                        <label class="form-popup-label">Employee ID:</label>
                        <input class="form-popup-input" id="viewEmployee_ID" readonly>
                    </div>
                </div>
                <div class="form-popup-input-group">
                    <label class="form-popup-label">Department / Office / Unit:</label>
                    <input class="form-popup-input" id="viewDepartment" readonly>
                </div>
                <div class="form-popup-checkbox-group">
                    <label class="form-popup-label">Condition of Equipment:</label>
                    <label><input type="radio" name="view_condition" id="view_condition_working" disabled> Working</label>
                    <label><input type="radio" name="view_condition" id="view_condition_not_working" disabled> Not Working</label>
                    <label><input type="radio" name="view_condition" id="view_condition_needs_repair" disabled> Needs Repair</label>
                </div>
            </section>

            <!-- Equipment Description Section -->
            <section class="form-popup-section">
                <h3 class="form-popup-title">Equipment Description</h3>
                <table class="form-popup-table">
                    <thead>
                        <tr>
                            <th>Brand</th>
                            <th>Device</th>
                            <th>Description</th>
                            <th>Serial Number</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody id="viewEquipmentTable">
                        <!-- Dynamic rows will be populated via JS -->
                    </tbody>
                </table>
            </section>

            <!-- Technical Support Section -->
            <div class="form-popup-input-group">
                <label class="form-popup-label">Assigned Technical Support:</label>
                <input class="form-popup-input" id="viewTechnicalSupport" readonly>
            </div>

            <!-- Approval Details -->
            <section class="form-popup-section" id="approvalSection">
                <h3 class="form-popup-title">Approval Details</h3>
                <div class="form-popup-input-group">
                    <label class="form-popup-label">Noted By:</label>
                    <input class="form-popup-input" id="viewNotedByService" readonly>
                </div>
                <div class="form-popup-input-group">
                    <label class="form-popup-label">Approval Date:</label>
                    <input class="form-popup-input" id="viewApproveDateService" readonly>
                </div>
            </section>

            <!-- QR Code & Status -->
            <div class="status-container">
                <label class="form-popup-label">Status:</label>
                <input type="text" id="viewStatusService" class="status-text" readonly>
            </div>

            <div id="qrCodeContainer" onclick="printQRCode()">
                <img id="qrCodeImage" class="qr-code" src="" alt="QR Code">
            </div>

            <!-- Action Buttons -->
            <button type="button" id="ButtonService" onclick="downloadModalAsPDFService()">Download PDF</button>
            @if(in_array(auth()->user()->account_type, ['technical_support', 'technical_support_head']))
                <button type="button" id="updateButtonService" onclick="toggleEditModeService()">Update</button>
                <button type="button" id="saveButtonService" style="display: none;" onclick="saveChangesService()">Save</button>
            @endif
        </div>
    </div>
</div>


<!-- Add a close button to hide the popup -->
<script>
    function closePopup(popupId) {
        document.getElementById(popupId).style.display = 'none';
    }

    function toggleEditModeService() {
        const tableBody = document.getElementById("viewEquipmentTable");
        const rows = tableBody.querySelectorAll("tr");

        // Enable editing for device, description, and remarks fields
        rows.forEach(row => {
            const brandInput = row.querySelector("td:nth-child(2) input");
            const deviceInput = row.querySelector("td:nth-child(3) input");
            const descInput = row.querySelector("td:nth-child(4) input");
            const serialInput = row.querySelector("td:nth-child(5) input");
            const remarksInput = row.querySelector("td:nth-child(6) input");
            if (brandInput) brandInput.readOnly = false; // Enable editing for brand
            if (deviceInput) deviceInput.readOnly = false; // Enable editing for device
            if (descInput) descInput.readOnly = false; // Enable editing for description
            if (serialInput) serialInput.readOnly = false;
            if (remarksInput) remarksInput.readOnly = false; // Enable editing for remarks
        });

        // Toggle button visibility
        document.getElementById("updateButtonService").style.display = "none";
        document.getElementById("saveButtonService").style.display = "block";
    }

    function saveChangesService() {
        const tableBody = document.getElementById("viewEquipmentTable");
        const rows = tableBody.querySelectorAll("tr");
        const formNo = document.getElementById("viewFormNoService").value;

        // Prepare the equipment data to send to the backend
        const equipmentData = [];
        rows.forEach(row => {
            const idInput = row.querySelector("input[data-field='id']");
            const brandInput = row.querySelector("input[data-field='brand']");
            const deviceInput = row.querySelector("input[data-field='device']");
            const descriptionInput = row.querySelector("input[data-field='description']");
            const serialInput = row.querySelector("input[data-field='serial']");
            const remarksInput = row.querySelector("input[data-field='remarks']");

            equipmentData.push({
                id: idInput.value, // Include the ID
                brand: brandInput.value,
                device: deviceInput.value,
                description: descriptionInput.value,
                serial_no: serialInput.value,
                remarks: remarksInput.value,
            });
        });

        // Send the updated equipment data to the backend
        fetch('/update-service-request', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({
                form_no: formNo,
                equipment: equipmentData,
            }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Equipment descriptions updated successfully!');
                // Disable editing after saving
                toggleEditModeService();
            } else {
                alert('Failed to update equipment descriptions.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while saving changes.');
        });
    }
    
    function downloadModalAsPDFService() {
        const formNo = document.getElementById('viewFormNoService').value;
        window.location.href = `/service-request/download/${formNo}`;
    }

    function printQRCode() {
        var qrCodeDiv = document.querySelector('.qr-code');
        var printWindow = window.open('', '_blank', 'width=600,height=400');
        printWindow.document.write('<html><head><title>Print QR Code</title></head><body>');
        printWindow.document.write(qrCodeDiv.outerHTML);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
    }
</script>
