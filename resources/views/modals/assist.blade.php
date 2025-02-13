<div id="assistModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close" onclick="closeAssistModal()">&times;</span>
        <h2>Select Technical Support</h2>
        <form id="assistForm" action="/api/pass-ticket" method="POST">
            @csrf <!-- CSRF token for form submission -->
            <input type="hidden" id="ticketControlNo" name="ticket_control_no">

            <div class="form-group">
                <label for="technicalSupport">Choose Technical Support:</label>
                <select id="technicalSupport" name="new_technical_support" required>
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
    async function submitAssist() {
  const ticketControlNo = document.getElementById('ticketControlNo').value;
  const technicalSupport = document.getElementById('technicalSupport').value;
  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');  // Get the CSRF token

  if (!ticketControlNo || !technicalSupport) {
    alert("Please select a technical support and ensure the ticket control number is correct.");
    return;
  }

  try {
    const response = await fetch('/api/pass-ticket', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,  // Include CSRF token in the header
      },
      body: JSON.stringify({
        ticket_control_no: ticketControlNo,
        new_technical_support: technicalSupport,
      }),
    });

    if (response.ok) {
      alert('Ticket successfully passed!');
      closeAssistModal();
    } else {
      const errorData = await response.json();
      console.error("Error response:", errorData);
      alert('Error passing ticket: ' + errorData.error || 'Unknown error');
    }
  } catch (error) {
    console.error("Request failed:", error);
    alert('Request failed: ' + error.message);
  }
}

</script>