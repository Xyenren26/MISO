<!-- View Technical Report Modal -->

<style>
    #technicalReportViewModal{
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
        display: flex;
        justify-content: center;
        align-items: center;
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
    </style>
    
<div id="technicalReportViewModal" class="modal" style="display: none;">
    <div class="modal-content-technical-report">
        <span class="close" onclick="closeTechnicalReportViewModal()">&times;</span>
        <input type="hidden" id="viewTechnicalReportControlNo" name="viewTechnicalReportControlNo">
        <div id="waitingForApprovalTechnical" class="waiting-approval" style="display: none;">
            <p>🚨 Waiting for admin approval...</p>
        </div>
        <div class="form-header">Technical Report Details</div>

        <div class="form-group">
            <label>Date and Time</label>
            <input type="datetime-local" id="view-date-time" readonly>
        </div>

        <div class="form-group">
            <label>Department</label>
            <input type="text" id="view-department" readonly>
        </div>

        <div class="form-group">
            <label>End User</label>
            <input type="text" id="view-enduser" readonly>
        </div>

        <div class="form-group">
            <label>Specification</label>
            <textarea id="view-specification" readonly></textarea>
        </div>

        <div class="form-group">
            <label>Problem</label>
            <textarea id="view-problem" readonly></textarea>
        </div>

        <div class="form-group">
            <label>Work Done</label>
            <textarea id="view-workdone" readonly></textarea>
        </div>

        <div class="form-group">
            <label>Findings</label>
            <textarea id="view-findings" readonly></textarea>
        </div>

        <div class="form-group">
            <label>Recommendation</label>
            <textarea id="view-recommendation" readonly></textarea>
        </div>

        <!-- Signatures Section -->
        <div class="signatures">
            <div class="signature">
                <label>Reported By</label>
                <input type="text" id="view-reported-by" readonly>
                <label>Reported Date</label>
                <input type="datetime-local" id="view-reported-date" readonly>
            </div>
            <div class="signature">
                <label>Inspected By</label>
                <input type="text" id="view-inspected-by" readonly>
                <label>Inspected Date</label>
                <input type="datetime-local" id="view-inspected-date" readonly>
            </div>
        </div>
        <section class="form-popup-section" id="approvalSection">
            <h3 class="form-popup-title">Approval Details</h3>

            <div class="form-popup-input-group">
                <label class="form-popup-label">Noted By:</label>
                <input class="form-popup-input" id="viewNotedByTechnical" readonly>
            </div>    

            <div class="form-popup-input-group"> 
                <label class="form-popup-label">Approval Date:</label>
                <input class="form-popup-input" id="viewApproveDateTechnical" readonly>
            </div>
        </section>


        <button type="button" id="ButtonDownloadTechnical" onclick="downloadModalAsPDFTechnical()">Download PDF</button>

    </div>
</div>
<script>
    
function downloadModalAsPDFTechnical() {
    const { jsPDF } = window.jspdf;
    const modal = document.getElementById("technicalReportViewModal");

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
        const controlNo = document.getElementById("viewTechnicalReportControlNo").value || "Technical_Endorsement";
        pdf.save(`Technical_Report_${controlNo}.pdf`);

        modal.style.display = previousDisplay;
    });
}


</script>
