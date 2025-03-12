<style>
    /* Style for In and Out New Form */
.form-popup-container {
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

/* Popup Content */
.form-popup-content {
    background-color: white;
    border: 2px solid #003067;
    padding: 20px;
    border-radius: 10px;
    margin: 10% auto;
    width: 60%;
    position: relative;
}

/* Close Button */
.form-popup-close-btn {
    position: absolute;
    top: 10px;
    right: 15px;
    font-size: 24px;
    cursor: pointer;
    color: #003067;
}

/* Header Section */
.form-popup-header {
    text-align: center;
    margin-bottom: 20px;
}

.form-popup-header .form-popup-logo img {
    width: 200px;
}

.form-popup-header h1 {
    font-size: 1.5em;
    margin: 10px 0;
}

.form-popup-header .form-popup-form-info {
    font-size: 0.9em;
    color: #555;
}

/* Form Section */
.form-popup-section {
    margin-bottom: 20px;
}

.form-popup-section-radio {
    display: flex;
    gap: 20px; /* Adjust spacing between options */
    align-items: center; /* Aligns items vertically */
}


.form-popup-row {
    display: flex;
    gap: 15px; /* Adjust spacing */
}

/* Input Group */
.form-popup-input-group {
    margin-bottom: 10px;
    display: flex;
    flex-direction: column;
    flex: 1;
}

.form-popup-label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
}

.form-popup-input {
    width: 95%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 5px;
}

/* Button Styling */
.form-popup-button {
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

.form-popup-button:hover {
    background: #0056b3;
}

/* Table Styling */
.form-popup-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

.form-popup-table th,
.form-popup-table td {
    border: 1px solid #ddd;
    text-align: center;
    padding: 10px;
}

/* Checkbox and Radio Groups */
.form-popup-checkbox-group,
.form-popup-radio-group {
    margin-bottom: 10px;
}

.form-popup-checkbox-group label,
.form-popup-radio-group label {
    display: inline-block;
    margin-right: 10px;
}

</style>

<!-- Add Device Form Popup -->
<div id="formPopup" class="form-popup-container" style="display: none;">
    <div class="form-popup-content">
        <span class="form-popup-close-btn" onclick="closePopup('formPopup')">×</span>
        <div class="form-popup-form-container">
            <header class="form-popup-header">
                <div class="form-popup-logo">
                    <img src="images/SystemLogo.png" alt="Logo">
                </div>
                <h1>ICT Equipment Service Request Form</h1>
                <div class="form-popup-form-info">
                    <span>Form No.: {{ $nextFormNo }}</span>
                </div>

            </header>
            <form action="{{ route('service.request.store') }}" method="POST">
                @csrf
                <input type="hidden" id="ticket_id" name="ticket_id">
                <section class="form-popup-section-radio">
                    <label><input type="radio" name="service_type" value="walk_in" required> Walk-In</label>
                    <label><input type="radio" name="service_type" value="pull_out" required id="pullOut"> Pull-Out</label>
                </section>

                <!-- General Information Section -->
                <section class="form-popup-section">
                    <h3 class="form-popup-title">General Information</h3>
                    <div class="form-popup-row">
                        <div class="form-popup-input-group">
                            <label class="form-popup-label">Employee Name:</label>
                            <input class="form-popup-input" type="text" name="name" id="name" required>
                        </div>
                        
                        <div class="form-popup-input-group">
                            <label class="form-popup-label">Employee ID:</label>
                            <input class="form-popup-input" type="text" name="employee_id" id="employee_id" required>
                        </div>
                    </div>
                    <div class="form-popup-input-group">
                        <label class="form-popup-label">Department / Office / Unit:</label>
                        <input class="form-popup-input" type="text" name="department" id="Pulloutdepartment" required>
                    </div>
                    <div class="form-popup-checkbox-group">
                        <label class="form-popup-label">Condition of Equipment:</label>
                        <label><input type="radio" name="condition[]" value="working"> Working</label>
                        <label><input type="radio" name="condition[]" value="not-working"> Not Working</label>
                        <label><input type="radio" name="condition[]" value="needs-repair"> Needs Repair</label>
                    </div>
                </section>

                <!-- Equipment Description Section -->
                <section class="form-popup-section">
                    <h3 class="form-popup-title">Equipment Description</h3>
                    <table class="form-popup-table" id="equipmentTable">
                        <thead>
                            <tr>
                                <th>Brand</th>
                                <th>Device</th>
                                <th>Description</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input class="form-popup-input" type="text" name="equipment[0][brand]" placeholder="Brand"></td>
                                <td><input class="form-popup-input" type="text" name="equipment[0][device]" placeholder="Device"></td>
                                <td><input class="form-popup-input" type="text" name="equipment[0][description]" placeholder="Description"></td>
                                <td><input class="form-popup-input" type="text" name="equipment[0][remarks]" placeholder="Remarks"></td>
                            </tr>
                        </tbody>
                    </table>
                    <button type="button" class="form-popup-button" id="addDeviceBtn" onclick="addNewDeviceRow()">Add Another Device</button>
                </section>

                <section class="form-popup-section">
                    <h3 class="form-popup-title">Assign Technical Support</h3>
                    <select class="form-popup-input" name="technical_support_id">
                        <option value="">Select Technical Support</option>
                        @foreach ($technicalSupports as $support)
                            <option value="{{ $support->employee_id }}">{{ $support->first_name }} {{ $support->last_name }}</option>
                        @endforeach
                    </select>
                </section>


                <button class="form-popup-button" type="submit">Submit</button>
            </form>
        </div>
    </div>
</div>

<script>
    function addNewDeviceRow() {
        let table = document.getElementById("equipmentTable").getElementsByTagName('tbody')[0];
        let rowCount = table.rows.length; // Get current row count
        let newRow = table.insertRow();

        newRow.innerHTML = `
            <td><input class="form-popup-input" type="text" name="equipment[${rowCount}][brand]" placeholder="Brand"></td>
            <td><input class="form-popup-input" type="text" name="equipment[${rowCount}][device]" placeholder="Device"></td>
            <td><input class="form-popup-input" type="text" name="equipment[${rowCount}][description]" placeholder="Description"></td>
            <td><input class="form-popup-input" type="text" name="equipment[${rowCount}][remarks]" placeholder="Remarks"></td>
        `;
    }

    // Attach the function to the button
    document.getElementById("addDeviceBtn").addEventListener("click", addNewDeviceRow);
</script>

