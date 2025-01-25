<!-- Modal for Remarks -->
<div id="remarksModal" class="modal" style="display: none;">
    <div id="remarksModalContent" class="modal-content">
        <span class="close" onclick="closeRemarksModal()">&times;</span>
        <h3>Update Remarks and Status</h3>

        <!-- Remarks Input -->
        <div>
            <label for="remarksInput">Remarks:</label>
            <textarea id="remarksInput" placeholder="Enter your remarks"></textarea>
        </div>

        <!-- Status Dropdown -->
        <div>
            <label for="statusDropdown">Status:</label>
            <select id="statusDropdown">
                <option value="completed">Mark Ticket as Complete</option>
                <option value="endorsed">Endorsed Ticket</option>
                <option value="technical_report">Write Technical Report</option>
            </select>
        </div>

        <!-- Save Button -->
        <button class="action-button" onclick="saveRemarksAndStatus()">Save</button>

    </div>
</div>
