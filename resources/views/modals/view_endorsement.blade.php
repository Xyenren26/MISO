<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<style>
      #endorsementViewModal{
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
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
    #ButtonEndorsement{
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
</style>
<div id="endorsementViewModal" class="modal" style="display: none;">
    <div class="endorsed-modal-content">
        <span class="close" onclick="closeEndorsementViewModal()">&times;</span>

        <div id="waitingForApproval" class="waiting-approval" style="display: none;">
            <p>🚨 Waiting for admin approval...</p>
        </div>


        <!-- Header Section -->
        <div class="modal-header">
            <img src="{{ asset('images/SystemLogo.png') }}" alt="System Logo">
            <h2>Technical Endorsement</h2>
            <p><strong>MISO</strong><br>Management Information Systems Office</p>
        </div>

        <!-- Control No Section -->
        <div class="modal-form-section">
            <label for="control_no">Control No:</label>
            <input type="text" id="ViewEndorsementcontrol_no" name="control_no" class="modal-input-box" readonly>
        </div>

        <!-- Department Section -->
        <div class="modal-form-section">
            <label for="department">Department/Office/Unit:</label>
            <input type="text" id="ViewEndorsementdepartment" name="department" class="modal-input-box" readonly>
        </div>

        <!-- Concern Section -->
        <div class="modal-form-section">
            <h3>Concern</h3>
            <div class="modal-two-column">

                <!-- Left Column - Network Issues -->
                <div class="modal-column">
                    <h4>Internet/System/Void</h4>
                    <div class="modal-checkbox-group">
                        @foreach(['IP ADDRESS', 'MAC ADDRESS', 'PING TEST', 'TRACET', 'NETWORK CABLE TEST', 'WEBSITE ACCESS', 'ROUTER / ACCESS POINT', 'VOID'] as $networkItem)
                            <div>
                                <input type="checkbox" name="network[]" value="{{ $networkItem }}" disabled>
                                <label>{{ $networkItem }}</label>
                                <input type="text" name="network_details[{{ $networkItem }}]" placeholder="" readonly>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Right Column - User Account Issues -->
                <div class="modal-column">
                    <h4>User Account</h4>
                    <div class="modal-checkbox-group">
                        @foreach([
                            'NEW DOMAIN ACCOUNT CREATION', 
                            'WINDOWS ACCOUNT RESET', 
                            'ADMIN', 
                            'FILE SHARING / FILE SERVER', 
                            'FOLDER CREATION', 
                            'FOLDER ACCESS'
                        ] as $userAccountItem)
                        <div class="inline-group">
                            <div class="inline-header">
                                <input type="checkbox" name="user_account[]" value="{{ $userAccountItem }}" disabled>
                                <label>{{ $userAccountItem }}</label>
                                <input type="text" name="user_account_details[{{ $userAccountItem }}]" 
                                    placeholder="{{ $userAccountItem === '' ? 'Folder Name' : '' }}" readonly>
                            </div>

                            @if($userAccountItem === 'FOLDER ACCESS')
                                <div class="inline-extra">
                                    <input type="text" name="user_account_details[FOLDER ACCESS_USER]" placeholder="" readonly>
                                </div>
                            @endif
                        </div>

                        @endforeach
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
                    <input type="date" name="endorsed_to_date" id="endorsed_to_date" class="modal-input-box-date" value="{{ date('Y-m-d') }}" disabled>
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
                <label for="endorsed_by">Endorsed By:</label>
                <input type="text" id="endorsed_by" name="endorsed_by" class="modal-input-box" placeholder="Enter Name">
            </div>
            <div class="modal-stacked-date-time">
                <div>
                    <label for="endorsed_by_date">Date:</label>
                    <input type="date" name="endorsed_by_date" id="endorsed_by_date" class="modal-input-box-date" value="{{ date('Y-m-d') }}" readonly>
                </div>
                <div>
                    <label for="endorsed_by_time">Time:</label>
                    <input type="time" name="endorsed_by_time" id="endorsed_by_time" class="modal-input-box-date" value="{{ date('H:i') }}" readonly>
                </div>
            </div>
            <div class="modal-form-section">
                <label for="endorsed_by_remarks">Remarks:</label>
                <input type="text" id="endorsed_by_remarks" name="endorsed_by_remarks" class="modal-input-box" placeholder="Enter Remarks">
            </div>
        </div>

        <section class="form-popup-section" id="approvalSection">
            <h3 class="form-popup-title">Approval Details</h3>

            <div class="form-popup-input-group">
                <label class="form-popup-label">Noted By:</label>
                <input class="form-popup-input" id="viewNotedBy" readonly>
            </div>    

            <div class="form-popup-input-group"> 
                <label class="form-popup-label">Approval Date:</label>
                <input class="form-popup-input" id="viewApproveDate" readonly>
            </div>
        </section>


        <button type="button" id="ButtonEndorsement" onclick="downloadModalAsPDF()">Download PDF</button>

    </div>
</div>
<script>
    
function downloadModalAsPDF() {
    const { jsPDF } = window.jspdf;
    const modal = document.getElementById("endorsementViewModal");

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
        const controlNo = document.getElementById("ViewEndorsementcontrol_no").value || "Technical_Endorsement";
        pdf.save(`Technical_Endorsement_${controlNo}.pdf`);

        modal.style.display = previousDisplay;
    });
}


</script>
