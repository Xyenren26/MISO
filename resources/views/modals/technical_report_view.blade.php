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
    #ButtonDownloadTechnical{
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

    #ButtonEditTechnical{
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
        top: 22px;
        right: 60px;
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
    
<div id="technicalReportViewModal" class="modal" style="display: none;">
    <div class="modal-content-technical-report">
        <span class="close" onclick="closeTechnicalReportViewModal()">&times;</span>
        <input type="hidden" id="viewTechnicalReportControlNo" name="viewTechnicalReportControlNo">

        <div class="rating-container" id="rating-containerTechnical">
            <label class="form-popup-label">Rating:</label>
            <div id="starRatingTechnical"></div>
        </div>
        <div id="waitingForApprovalTechnical" class="waiting-approval" style="display: none;">
            <p>🚨 Waiting for Technical Head approval...</p>
        </div>
        <div class="form-header">Technical Report Details</div>

        <!-- Non-Editable Fields -->
        <div class="form-group">
            <label>Technical Report No.</label>
            <input type="text" id="view-TR_id" readonly disabled>
        </div>

        <div class="form-group">
            <label>Date and Time</label>
            <input type="datetime-local" id="view-date-time" readonly disabled>
        </div>

        <div class="form-group">
            <label>Department</label>
            <input type="text" id="view-department" readonly disabled>
        </div>

        <div class="form-group">
            <label>End User</label>
            <input type="text" id="view-enduser" readonly disabled>
        </div>

        <div class="form-group">
            <label>Problem</label>
            <textarea id="view-problem" readonly disabled></textarea>
        </div>

        <div class="form-group">
            <label>Work Done</label>
            <textarea id="view-workdone" readonly disabled></textarea>
        </div>

        <!-- Editable Fields -->
        <div class="form-group">
            <label>Specification</label>
            <textarea id="view-specification" readonly></textarea>
        </div>

        <div class="form-group">
            <label>Findings</label>
            <textarea id="view-findings" readonly></textarea>
        </div>

        <div class="form-group">
            <label>Recommendation</label>
            <textarea id="view-recommendation" readonly></textarea>
        </div>

        <!-- Non-Editable Signatures Section -->
        <div class="signatures">
            <div class="signature">
                <label>Inspected By</label>
                <input type="text" id="view-inspected-by" readonly disabled>
                <label>Inspected Date</label>
                <input type="datetime-local" id="view-inspected-date" readonly disabled>
            </div>
        </div>

        <!-- Non-Editable Approval Details -->
        <section class="form-popup-section" id="approvalSection">
            <h3 class="form-popup-title">Approval Details</h3>

            <div class="form-popup-input-group">
                <label class="form-popup-label">Noted By:</label>
                <input class="form-popup-input" id="viewNotedByTechnical" readonly disabled>
            </div>    

            <div class="form-popup-input-group"> 
                <label class="form-popup-label">Approval Date:</label>
                <input class="form-popup-input" id="viewApproveDateTechnical" readonly disabled>
            </div>
        </section>

        <!-- Buttons -->
        <button type="button" id="ButtonDownloadTechnical" onclick="downloadModalAsPDFTechnical()"  @if(auth()->user()->account_type === 'end_user') disabled @endif>
        Download PDF</button>
        @if(in_array(auth()->user()->account_type, ['technical_support', 'technical_support_head']))
            <button type="button" id="ButtonEditTechnical" onclick="toggleEditMode()">Edit</button>
        @endif
    </div>
</div>

<script>
    function downloadModalAsPDFTechnical() {
        const controlNo = document.getElementById('view-TR_id').value;
        window.location.href = `/technical-report/download/${controlNo}`;
    }

    let isEditMode = false;

    function toggleEditMode() {
        isEditMode = !isEditMode;
        const button = document.getElementById('ButtonEditTechnical');

        // Toggle editable fields
        const editableFields = [
            'view-specification',
            'view-findings',
            'view-recommendation'
        ];

        editableFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            field.readOnly = !isEditMode;
        });

        // Update button text
        button.textContent = isEditMode ? 'Save' : 'Edit';

        // Save logic when exiting edit mode
        if (!isEditMode) {
            saveReport();
        }
    }

    function saveReport() {
        const controlNo = document.getElementById('viewTechnicalReportControlNo').value;
        const data = {
            specification: document.getElementById('view-specification').value,
            findings: document.getElementById('view-findings').value,
            recommendation: document.getElementById('view-recommendation').value,
        };

        fetch(`/reports/${controlNo}`, {
            method: 'POST', // Ensure your backend route supports POST
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // CSRF token
            },
            body: JSON.stringify(data)
        })
        .then(response => {
            if (!response.ok) {
                // Handle non-JSON responses (e.g., HTML error pages)
                return response.text().then(text => {
                    throw new Error(`Server returned ${response.status}: ${text}`);
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert('Changes saved successfully!');
            } else {
                alert(`Failed to save changes: ${data.message}`);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while saving changes. Check the console for details.');
        });
    }

    function closeTechnicalReportViewModal() {
        document.getElementById('technicalReportViewModal').style.display = 'none';
    }

        
</script>
