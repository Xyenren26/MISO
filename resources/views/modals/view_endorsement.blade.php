<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<link rel="stylesheet" href="{{ asset('css/ticket_Style.css') }}">
<link rel="stylesheet" href="{{ asset('css/ticket_components_Style.css') }}">
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
    .rating-container {
    position: absolute;
    top: 50px;
    right: 40px;
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
<div id="endorsementViewModal" class="modal" style="display: none;">
    <div class="endorsed-modal-content">
        <span class="close" onclick="closeEndorsementViewModal()">&times;</span>

        <div id="waitingForApproval" class="waiting-approval" style="display: none;">
            <p>🚨 Waiting for admin approval...</p>
        </div>

        <div class="rating-container" id="rating-containerEndorsement">
            <label class="form-popup-label">Rating:</label>
            <div id="starRatingEndorsement"></div>
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
            <input type="text" id="ViewEndorsementcontrol_no" name="control_no" class="modal-input-box" readonly disabled>
        </div>

        <!-- Department Section -->
        <div class="modal-form-section">
            <label for="department">Department/Office/Unit:</label>
            <input type="text" id="ViewEndorsementdepartment" name="department" class="modal-input-box" readonly disabled>
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
                                <input type="text" name="network_details[{{ $networkItem }}]" placeholder="" readonly disabled>
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
                                    placeholder="{{ $userAccountItem === '' ? 'Folder Name' : '' }}" readonly disabled>
                            </div>

                            @if($userAccountItem === 'FOLDER ACCESS')
                                <div class="inline-extra">
                                    <input type="text" name="user_account_details[FOLDER ACCESS_USER]" placeholder="" readonly disabled>
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
                <input type="text" id="endorsed_to" name="endorsed_to" class="modal-input-box" readonly disabled>
            </div>
            <div class="modal-stacked-date-time">
                <div>
                    <label for="endorsed_to_date">Date:</label>
                    <input type="date" name="endorsed_to_date" id="endorsed_to_date" class="modal-input-box-date" value="{{ date('Y-m-d') }}" readonly disabled>
                </div>
                <div>
                    <label for="endorsed_to_time">Time:</label>
                    <input type="time" name="endorsed_to_time" id="endorsed_to_time" class="modal-input-box-date" value="{{ date('H:i') }}" readonly disabled>
                </div>
            </div>
            <div class="modal-form-section">
                <label for="endorsed_to_remarks">Remarks:</label>
                <input type="text" id="endorsed_to_remarks" name="endorsed_to_remarks" class="modal-input-box" readonly disabled>
            </div>
        </div>
        <!-- Endorsed By Section -->
        <div class="modal-footer">
            <h3>Endorsed By</h3>
            <div class="modal-form-section">
                <label for="endorsed_by">Endorsed By:</label>
                <input type="text" id="endorsed_by" name="endorsed_by" class="modal-input-box" readonly disabled>
            </div>
            <div class="modal-stacked-date-time">
                <div>
                    <label for="endorsed_by_date">Date:</label>
                    <input type="date" name="endorsed_by_date" id="endorsed_by_date" class="modal-input-box-date" value="{{ date('Y-m-d') }}" readonly disabled>
                </div>
                <div>
                    <label for="endorsed_by_time">Time:</label>
                    <input type="time" name="endorsed_by_time" id="endorsed_by_time" class="modal-input-box-date" value="{{ date('H:i') }}" readonly disabled>
                </div>
            </div>
            <div class="modal-form-section">
                <label for="endorsed_by_remarks">Remarks:</label>
                <input type="text" id="endorsed_by_remarks" name="endorsed_by_remarks" class="modal-input-box" readonly disabled>
            </div>
        </div>

        <section class="form-popup-section" id="approvalSection">
            <h3 class="form-popup-title">Approval Details</h3>

            <div class="form-popup-input-group">
                <label class="form-popup-label">Noted By:</label>
                <input class="form-popup-input" id="viewNotedBy" readonly disabled>
            </div>    

            <div class="form-popup-input-group"> 
                <label class="form-popup-label">Approval Date:</label>
                <input class="form-popup-input" id="viewApproveDate" readonly disabled>
            </div>
        </section>

        <button type="button" id="ButtonEndorsement" onclick="downloadModalAsPDF()">Download PDF</button>

    </div>
</div>
<script>
function downloadModalAsPDF() {
    const { jsPDF } = window.jspdf;
    const modal = document.getElementById("endorsementViewModal");
    const modalContent = modal.querySelector(".endorsed-modal-content");

    // Ensure modal is visible before capturing
    const previousDisplay = modal.style.display;
    modal.style.display = "block";

    // Hide buttons before capturing
    const closeButton = modal.querySelector(".close");
    const printButton = document.getElementById("ButtonEndorsement");

    if (closeButton) closeButton.style.display = "none";
    if (printButton) printButton.style.display = "none";

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
        if (closeButton) closeButton.style.display = "block";
        if (printButton) printButton.style.display = "block";

        // Get control number for filename
        const controlNo = document.getElementById("ViewEndorsementcontrol_no").value || "Technical_Endorsement";
        pdf.save(`Technical_Endorsement_${controlNo}.pdf`);

        modal.style.display = previousDisplay;
    });
}

</script>
