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
#updateButton{
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
#saveButton{
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
<div id="viewFormPopup" class="form-popup-container" style="display: none;">
    <div class="form-popup-content-view">
        <span class="form-popup-close-btn" onclick="closePopup('viewFormPopup')">×</span>
        <div class="form-popup-form-container">
            <header class="form-popup-header">

            <div class="rating-container" id="rating-containerPullOut">
                <label class="form-popup-label">Rating:</label>
                <div id="starRatingPullOut"></div>
            </div>
                
            <div id="waitingForApprovalService" class="waiting-approval" style="display: none;">
                <p>🚨 Waiting for admin approval...</p>
            </div>
            
            
                <div id="qrCodeContainer"  onclick="printQRCode()">
                    <img id="qrCodeImage" class="qr-code" src="" alt="QR Code">
                </div>

            <div class="status-container">
                    <label class="form-popup-label">Status:</label>
                    <input type="text" id="viewStatusService" class="status-text" readonly>
                </div>

                <div class="form-popup-logo">
                    <img src="images/systemlogo.png" alt="Logo">
                </div>
                <h1>ICT Equipment Service Request Form</h1>
                <div class="form-popup-form-info_no">
                    <label for="viewFormNoService" class="form-label-no">Form No.:</label>
                    <input type="text" id="viewFormNoService" class="form-input-no" readonly>
                </div>



            </header>

           <!-- Service Type (Radio Buttons) -->
            <section class="form-popup-section-radio">
                <label>
                    <input type="radio" name="service_type" value="walk_in" required 
                        id="service_type_walk_in" disabled> Walk-In
                </label>
                <label>
                    <input type="radio" name="service_type" value="pull_out" required 
                        id="service_type_pull_out"disabled> Pull-Out
                </label>
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
                    <label><input type="radio" name="condition[]" id="condition_working" value="working" disabled> Working</label>
                    <label><input type="radio" name="condition[]" id="condition_not_working" value="not-working" disabled> Not Working</label>
                    <label><input type="radio" name="condition[]" id="condition_needs_repair" value="needs-repair" disabled> Needs Repair</label>
                </div>
            </section>

            <!-- Equipment Description Section -->
            <section class="form-popup-section">
                <h3 class="form-popup-title">Equipment Description</h3>
                <table class="form-popup-table">
                    <thead>
                        <tr>
                            <th>Brand</th>
                            <th>Description</th>
                            <th>Motherboard</th>
                            <th>RAM</th>
                            <th>HDD</th>
                            <th>Accessories</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody id="viewEquipmentTable">
                        <!-- Dynamic rows will go here -->
                    </tbody>
                </table>
            </section>

            <!-- Technical Support Section -->
            <div class="form-popup-input-group">
                <label class="form-popup-label">Assigned Technical Support:</label>
                <input class="form-popup-input" id="viewTechnicalSupport" readonly>
            </div>

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


            <button type="button" id="ButtonService"onclick="downloadModalAsPDFService()">Download PDF</button>
            @if(in_array(auth()->user()->account_type, ['technical_support', 'administrator']))
                <button type="button" id="updateButton" onclick="toggleEditMode()">Update</button>
                <button type="button" id="saveButton" style="display: none;" onclick="saveChanges()">Save</button>
            @endif
        </div>
    </div>
</div>

<!-- Add a close button to hide the popup -->
<script>
    function closePopup(popupId) {
        document.getElementById(popupId).style.display = 'none';
    }
    
    function downloadModalAsPDFService() {
        const { jsPDF } = window.jspdf;
        const modal = document.getElementById("viewFormPopup");
        const modalContent = modal.querySelector(".form-popup-content-view");

        // Ensure modal is visible before capturing
        const previousDisplay = modal.style.display;
        modal.style.display = "block";

        // Hide buttons before capturing
        const elementsToHide = [
            modal.querySelector(".form-popup-close-btn"),
            document.getElementById("ButtonService"),
            document.getElementById("updateButton"),
            document.getElementById("saveButton")
        ];

        elementsToHide.forEach(el => {
            if (el) el.style.display = "none";
        });

        // Store original styles
        const originalStyles = {
            background: document.body.style.backgroundColor,
            width: modalContent.style.width,
            height: modalContent.style.height,
            position: modalContent.style.position,
            padding: modalContent.style.padding,
            margin: modalContent.style.margin,
        };

        // Make modal full-page with white background
        document.body.style.backgroundColor = "white";
        modalContent.style.width = "100vw";
        modalContent.style.height = "100vh";
        modalContent.style.position = "fixed";
        modalContent.style.padding = "20px";
        modalContent.style.margin = "0";

        html2canvas(modalContent, {
            scale: 3,
            backgroundColor: "#ffffff",
            useCORS: true,
            windowWidth: modalContent.scrollWidth,
            windowHeight: modalContent.scrollHeight
        }).then(canvas => {
            const pdf = new jsPDF("p", "mm", "a4");

            const imgWidth = 210; // A4 width in mm
            const imgHeight = (canvas.height * imgWidth) / canvas.width;

            pdf.addImage(canvas, "PNG", 0, 0, imgWidth, imgHeight);

            // Handle multi-page PDFs if content is long
            let heightLeft = imgHeight;
            let position = 0;

            while (heightLeft > 297) {
                position -= 297;
                pdf.addPage();
                pdf.addImage(canvas, "PNG", 0, position, imgWidth, imgHeight);
                heightLeft -= 297;
            }

            // Restore original styles
            document.body.style.backgroundColor = originalStyles.background;
            modalContent.style.width = originalStyles.width;
            modalContent.style.height = originalStyles.height;
            modalContent.style.position = originalStyles.position;
            modalContent.style.padding = originalStyles.padding;
            modalContent.style.margin = originalStyles.margin;

            // Show hidden buttons again
            elementsToHide.forEach(el => {
                if (el) el.style.display = "block";
            });

            // Get control number for filename
            const controlNo = document.getElementById("viewFormNoService").value || "Pullout";
            pdf.save(`PullOut_Device_${controlNo}.pdf`);

            modal.style.display = previousDisplay;
        });
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

    function toggleEditMode() {
        const formPopup = document.getElementById('viewFormPopup');
        const inputs = formPopup.querySelectorAll('input, textarea, select');
        const updateButton = document.getElementById('updateButton');
        const saveButton = document.getElementById('saveButton');

        // Enable all input fields, checkboxes, radios, and selects
        inputs.forEach(input => {
            if (input.type !== "button" && input.type !== "submit") {
                input.removeAttribute("readonly");
                input.removeAttribute("disabled");
            }
        });

        // Toggle button visibility
        if (updateButton) updateButton.style.display = 'none';
        if (saveButton) saveButton.style.display = 'block';
    }


    function saveChanges() {
        const inputs = document.querySelectorAll('#viewFormPopup .form-popup-input');
        const radios = document.querySelectorAll('#viewFormPopup input[type="radio"]');
        const updateButton = document.getElementById('updateButton');
        const saveButton = document.getElementById('saveButton');

        // Disable inputs and radios
        inputs.forEach(input => {
            input.readOnly = true;
        });

        radios.forEach(radio => {
            radio.disabled = true;
        });

        // Toggle button visibility
        updateButton.style.display = 'block';
        saveButton.style.display = 'none';

        // Save changes to the backend
        const formData = new FormData();
        inputs.forEach(input => {
            formData.append(input.id, input.value);
        });
        radios.forEach(radio => {
            if (radio.checked) {
                formData.append(radio.name, radio.value);
            }
        });

        fetch('/update-service-request', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
        })
        .then(response => response.json())
        .then(data => {
            alert('Changes saved successfully!');
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to save changes.');
        });
    }


</script>
