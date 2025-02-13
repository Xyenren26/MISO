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
    </style>
    
<div id="technicalReportViewModal" class="modal" style="display: none;">
    <div class="modal-content-technical-report">
        <span class="close" onclick="closeTechnicalReportViewModal()">&times;</span>
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
            <div class="signature">
                <label>Noted By</label>
                <input type="text" id="view-noted-by" readonly>
                <label>Noted Date</label>
                <input type="datetime-local" id="view-noted-date" readonly>
            </div>
        </div>
    </div>
</div>
