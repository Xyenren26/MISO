<div id="assistModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close" onclick="closeAssistModal()">&times;</span>
        <h2>Select Technical Support</h2>
        <form id="assistForm" onsubmit="event.preventDefault(); submitAssist();">
            @csrf <!-- CSRF token for form submission -->
            <input type="hidden" id="ticketControlNo" name="ticket_control_no">

            <div class="form-group">
                <label for="technicalSupport">Choose Technical Support:</label>
                <select id="technicalSupportAssist" name="new_technical_support" required>
                    <option value="" disabled selected>Select Assist Technical Support</option>
                    @foreach($technicalAssistSupports as $tech)
                        <option value="{{ $tech->employee_id }}">
                            {{ $tech->first_name }} {{ $tech->last_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <button type="submit" class="submit-btn">Submit</button>
            </div>
        </form>
    </div>
</div>

<script>
    function showAlert(type, message) {
        // Remove existing alerts
        document.querySelectorAll(".alert-box").forEach(alert => alert.remove());

        const alertBox = document.createElement("div");
        alertBox.classList.add("alert-box", "px-4", "py-3", "rounded-lg", "relative", "mb-4");

        if (type === "success") {
            alertBox.classList.add("bg-green-100", "border", "border-green-400", "text-green-700");
        } else {
            alertBox.classList.add("bg-red-100", "border", "border-red-400", "text-red-700");
        }

        alertBox.innerHTML = `
            <strong class="font-bold">${type === "success" ? "Success!" : "Error!"}</strong>
            <span class="block sm:inline">${message}</span>
            <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.remove();">
                &times;
            </button>
        `;

        document.querySelector(".tickets-table").prepend(alertBox);
    }
    async function submitAssist() {
        const ticketControlNo = document.getElementById('ticketControlNo').value;
        const technicalSupport = document.getElementById('technicalSupportAssist').value;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        if (!ticketControlNo || !technicalSupport) {
            showAlert("error", "Please select a technical support and ensure the ticket control number is correct.");
            return;
        }

        try {
            const response = await fetch('/api/pass-ticket', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({
                    ticket_control_no: ticketControlNo,
                    new_technical_support: technicalSupport,
                }),
            });

            const responseData = await response.json();

            if (response.ok) {
                showAlert("success", "Ticket successfully passed!");
                closeAssistModal();
            } else {
                console.error("Error response:", responseData);
                showAlert("error", responseData.error || "Unknown error occurred while passing the ticket.");
            }
        } catch (error) {
            console.error("Request failed:", error);
            showAlert("error", "Request failed: " + error.message);
        }
    }
</script>
