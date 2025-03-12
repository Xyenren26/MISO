<!-- Modal for Remarks -->
<div id="remarksModal" class="modal" style="display: none;" data-control-no="">
    <div id="remarksModalContent" class="modal-content">
        <span class="close" onclick="closeRemarksModal()">&times;</span>
        <h3>Update Remarks and Status</h3>

        <!-- Description Section -->
        <div class="description-section">
            <p data-status="endorsed" style="display: none;">
                <strong>Endorsement:</strong> This status is utilized when the ticket request falls outside the scope of the Technical Support Division (MISO) or requires the expertise of other divisions, such as Infrastructure or the Application Support Division (MISO). It ensures the request is directed to the appropriate team for resolution.
            </p>
            <p data-status="technical-report" style="display: none;">
                <strong>Technical Report:</strong> This status is assigned to devices that have undergone repeated repairs (more than 10 instances) or are deemed irreparable. It documents the device's history and provides a formal recommendation for further action.
            </p>
            <p data-status="pull-out" style="display: none;">
                <strong>Turn Over Device:</strong> This status is used when a device requires advanced repair or diagnostics that can only be performed at the Management Information System Office (MISO). The device will be pulled out from its current location and worked on at the MISO facility.
            </p>
        </div>

        <!-- Remarks Input -->
        <div>
            <label for="remarksInput">Work Done:</label>
            <textarea id="remarksInput" placeholder="Enter your remarks"></textarea>
        </div>

        <!-- Status Dropdown -->
        <div>
            <label for="statusDropdown">Status:</label>
            <select id="statusDropdown" onchange="toggleDescription()">
                <option value="">Select a status</option>
                <option value="completed">Mark Ticket as Complete</option>
                <option value="endorsed">Endorsed Ticket</option>
                <option value="technical-report">Technical Report</option>
                <option value="pull-out">Turn Over Device</option>
            </select>
        </div>

        <!-- Save Button and Loading Spinner -->
        <button class="action-button" id="saveButton" onclick="saveRemarksAndStatus()">Save</button>
        <div id="loadingSpinner" class="loading-spinner" style="display: none;">
            <div class="spinner"></div>
            <p>Saving...</p>
        </div>
    </div>
</div>
<script>
// Function to toggle description based on selected status
function toggleDescription() {
    const status = document.getElementById("statusDropdown").value;
    const descriptions = document.querySelectorAll(".description-section p");

    // Hide all descriptions first
    descriptions.forEach(desc => {
        desc.style.display = "none";
    });

    // Show the description for the selected status
    if (status) {
        const selectedDesc = document.querySelector(`.description-section p[data-status="${status}"]`);
        if (selectedDesc) {
            selectedDesc.style.display = "block";
        }
    }
}

function saveRemarksAndStatus() {
    const modal = document.getElementById("remarksModal");
    const controlNo = modal.getAttribute("data-control-no");
    const remarks = document.getElementById("remarksInput").value;
    const status = document.getElementById("statusDropdown").value;
    const saveButton = document.getElementById("saveButton");
    const loadingSpinner = document.getElementById("loadingSpinner");

    if (!remarks.trim() || !status) {
        alert("Please enter work done and select a status before saving.");
        return;
    }

    // Disable the save button and show the loading spinner
    saveButton.disabled = true;
    loadingSpinner.style.display = "flex";

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
    .then(response => response.text())  // Read the response as text
    .then(text => {
        console.log("Response Text:", text);  // Log the raw response for debugging

        try {
            const data = JSON.parse(text);  // Try parsing it as JSON
            alert(data.message);
            closeRemarksModal();

            // Handle specific status actions
            if (status === "endorsed") {
                // Update the UI for endorsed tickets
                const endorsementControlNoElement = document.getElementById(`endorsement-control-no-${controlNo}`);
                if (endorsementControlNoElement) {
                    endorsementControlNoElement.textContent = `Endorsement Control No: ${data.newControlNo}`;
                }
            } else if (status === "technical-report") {
                // Redirect or show technical report details
                window.location.href = `/technical-reports/${controlNo}`;
            } else if (status === "pull-out") {
                // Show a message for turn-over devices
                alert("Device has been marked for pull-out to MISO for repair.");
            }

            // Reload the page to reflect changes
            location.reload();
        } catch (error) {
            alert(`Error parsing JSON: ${error.message}`);
        }
    })
    .catch(error => {
        console.error("Error details:", error);
        alert(`Error: ${error.message}`);
    })
    .finally(() => {
        // Re-enable the save button and hide the loading spinner
        saveButton.disabled = false;
        loadingSpinner.style.display = "none";
    });
}

// Function to close the remarks modal
function closeRemarksModal() {
    document.getElementById("remarksModal").style.display = "none";
}
</script>