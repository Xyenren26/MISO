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
                    @foreach($technicalSupports as $tech)
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