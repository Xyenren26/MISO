<!-- Modal for Remarks -->
<div id="remarksModal" class="modal" style="display: none;" data-control-no="">
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

<script>
  // Function to save remarks and status
function saveRemarksAndStatus() {
  const modal = document.getElementById("remarksModal");
  const controlNo = modal.getAttribute("data-control-no");
  const remarks = document.getElementById("remarksInput").value;
  const status = document.getElementById("statusDropdown").value;

  if (!remarks.trim()) {
      alert("Please enter remarks before saving.");
      return;
  }

  // Prepare the data for the AJAX request
  const requestData = {
      control_no: controlNo,
      remarks: remarks,
      status: status
  };

  // Send the request via Fetch API
  fetch("/tickets/update-remarks", {
      method: "POST",
      headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
      },
      body: JSON.stringify(requestData)
  })
  .then(response => {
      if (!response.ok) {
          throw new Error("Failed to update the ticket.");
      }
      return response.json();
  })
  .then(data => {
      alert(data.message);
      closeRemarksModal();

      // If the status is "endorsed", open the endorsement modal
      if (status === "endorsed") {
          openEndorsementModal(controlNo);
      }

      // Update the UI dynamically to reflect the new status
      const remarksButton = document.getElementById(`remarks-btn-${controlNo}`);
      if (remarksButton) {
          remarksButton.outerHTML = `
              <button class="action-button" onclick="openEndorsementModal('${controlNo}')">
                  <i class="fas fa-thumbs-up"></i>
              </button>
          `;
      }

      // Update the control number dynamically if needed (after endorsement)
      if (data.newControlNo) {
          const endorsementControlNoElement = document.getElementById(`endorsement-control-no-${controlNo}`);
          if (endorsementControlNoElement) {
              endorsementControlNoElement.textContent = `Endorsement Control No: ${data.newControlNo}`;
          }
      }
  })
  .catch(error => {
    console.error("Error details:", error);
    alert(`Error: ${error.message}`);
});
}
</script>