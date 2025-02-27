<style>
.form-popup-content-view {
    background-color: white;
    border: 2px solid #003067;
    padding: 20px;
    border-radius: 10px;
    margin-top: 25%;
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
                    <input type="text" id="viewStatusService" class="form-input-no" readonly>
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
                        id="service_type_walk_in"> Walk-In
                </label>
                <label>
                    <input type="radio" name="service_type" value="pull_out" required 
                        id="service_type_pull_out"> Pull-Out
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
                    <span id="viewCondition"></span>
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

        // Ensure modal is visible before capturing
        const previousDisplay = modal.style.display;
        modal.style.display = "block"; 

        // Store original background color
        const originalBg = modal.style.backgroundColor;

        // Change background to white before capturing
        modal.style.backgroundColor = "white";

        html2canvas(modal, {
            scale: 3,
            backgroundColor: "#ffffff",
            useCORS: true,
            windowWidth: modal.scrollWidth,
            windowHeight: modal.scrollHeight
        }).then(canvas => {
            const pdf = new jsPDF("p", "mm", "a4");

            const pageWidth = 210; // A4 width in mm
            const imgWidth = 250; // Set width for the modal in the PDF
            const imgHeight = (canvas.height * imgWidth) / canvas.width;

            // Calculate x position to center the modal
            const xPosition = (pageWidth - imgWidth) / 2;
            let yPosition = 10; // Top margin

            pdf.addImage(canvas, "PNG", xPosition, yPosition, imgWidth, imgHeight);

            // Handle multi-page PDFs if content is long
            let heightLeft = imgHeight;
            while (heightLeft > 297) {
                yPosition -= 297;
                pdf.addPage();
                pdf.addImage(canvas, "PNG", xPosition, yPosition, imgWidth, imgHeight);
                heightLeft -= 297;
            }

            // Restore original background color after capturing
            modal.style.backgroundColor = originalBg;

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


</script>
