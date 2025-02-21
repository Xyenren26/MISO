<style>
#equipmentItemsSection {
    display: block;
    visibility: visible;
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
.qr-code-deployment {
    position: absolute;
    top: 58px;
    right: 34px;
    color: #d9534f; /* Red text */
    font-weight: bold;
    font-size: 14px;
    margin: 0;
    padding: 0;
    width: 90px;
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

</style>
<!-- View Deployment Modal -->
<div id="deploymentview" class="deploymentmodal" style="display: none;">
    <div class="modal-content-deployment">
        <span class="close" onclick="closeDeploymentview()">&times;</span>
        <div class="form-container">
            <header>
                <div class="logo">
                    <img src="images/systemlogo.png" alt="Logo">
                </div>
                <div class="title">
                    <h1>IT EQUIPMENT / SOFTWARE / I.S. ACKNOWLEDGEMENT RECEIPT FORM</h1>
                    <p>Management Information System Office</p>
                </div>
                <div id="waitingForApprovalDeployment" class="waiting-approval" style="display: none;">
                    <p>🚨 Waiting for admin approval...</p>
                </div>
                <div id="qrCodeContainerDeployment" onclick="printQRCodeDeployment()">
                    <img id="qrCodeImageDeployment" class="qr-code-deployment" src="" alt="QR Code">
                </div>




            </header>
            <form id="deploymentForm">
                <table>
                    <tr>
                        <th colspan="4">Purpose</th>
                        <td colspan="4">
                            <textarea id="purpose" name="purpose" rows="2" style="width: 98%;" readonly></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th>Control Number:</th>
                        <td colspan="3"><input id="control_number" type="text" name="control_number" readonly></td>
                        <th>Status:</th>
                        <td><input id="status_new" type="radio" name="status" value="new" disabled> New</td>
                        <td><input id="status_used" type="radio" name="status" value="used" disabled> Used</td>
                    </tr>
                    <tr>
                        <th>Components:</th>
                        <td><input type="checkbox" id="component_cpu" disabled> CPU</td>
                        <td><input type="checkbox" id="component_monitor" disabled> Monitor</td>
                        <td><input type="checkbox" id="component_printer" disabled> Printer</td>
                        <td><input type="checkbox" id="component_ups" disabled> UPS</td>
                        <td><input type="checkbox" id="component_switch" disabled> Switch</td>
                        <td><input type="checkbox" id="component_keyboard" disabled> Keyboard</td>
                        <td><input type="checkbox" id="component_mouse" disabled> Mouse</td>
                    </tr>
                    <tr>
                        <th colspan="2">Software / I.S.</th>
                        <td colspan="6" id="software">
                            <input type="checkbox" id="software_google_workspace" disabled> Google Workspace
                            <input type="checkbox" id="software_ms_office" disabled> MS Office
                            <input type="checkbox" id="software_others" disabled> Others
                        </td>
                    </tr>
                </table>

                <!-- Equipment Items Section -->
                <div id="equipmentItemsSection">
                    <table>
                        <tr>
                            <th>Description</th>
                            <th>Serial Number</th>
                            <th>Quantity</th>
                        </tr>
                        <tr>
                            <td><input id="equipment_description" type="text" name="description" readonly></td>
                            <td><input id="equipment_serial_number" type="text" name="serial_number" readonly></td>
                            <td><input id="equipment_quantity" type="number" name="quantity" readonly></td>
                        </tr>
                    </table>
                </div>
                <table>
                    <tr>
                        <th>Brand/Name</th>
                        <td colspan="7"><input id="brand_name" type="text" name="brand_name" readonly></td>
                    </tr>
                    <tr>
                        <th>Specification</th>
                        <td colspan="7"><textarea id="specification" name="specification" readonly></textarea></td>
                    </tr>
                    <tr>
                        <th>Received By</th>
                        <td><input id="received_view_by" type="text" name="received_by" readonly></td>
                        <th>Issued By</th>
                        <td><input id="issued_view_by" type="text" name="issued_by" readonly></td>
                    </tr>
                    <tr>
                        <th>Date</th>
                        <td><input id="received_date" type="date" name="received_date" readonly></td>
                        <th>Date</th>
                        <td><input id="issued_date" type="date" name="issued_date" readonly></td>
                    </tr>
                </table>
                 <section class="form-popup-section" id="approvalSection">
                    <h3 class="form-popup-title">Approval Details</h3>

                    <div class="form-popup-input-group">
                        <label class="form-popup-label">Noted By:</label>
                        <input class="form-popup-input" id="viewNotedByDeployment" readonly>
                    </div>    

                    <div class="form-popup-input-group"> 
                        <label class="form-popup-label">Approval Date:</label>
                        <input class="form-popup-input" id="viewApproveDateDeployment" readonly>
                    </div>
                </section>


                <button type="button" id="ButtonDeployment"onclick="downloadModalAsPDFDeployment()">Download PDF</button>
            </form>
        </div>
    </div>
</div>
<!-- Add a close button to hide the popup -->
<script>
    function closePopup(popupId) {
        document.getElementById(popupId).style.display = 'none';
    }
    
    function downloadModalAsPDFDeployment() {
        const { jsPDF } = window.jspdf;
        const modal = document.getElementById("deploymentview");

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
            const controlNo = document.getElementById("control_number").value || "Deployment";
            pdf.save(`Deployment_Form_${controlNo}.pdf`);

            modal.style.display = previousDisplay;
        });
    }
    function printQRCodeDeployment() {
        var qrCodeDiv = document.querySelector('.qr-code-deployment');
        var printWindow = window.open('', '_blank', 'width=600,height=400');
        printWindow.document.write('<html><head><title>Print QR Code</title></head><body>');
        printWindow.document.write(qrCodeDiv.outerHTML);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
    }
</script>
