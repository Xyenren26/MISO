<style>
.form-popup-content-view {
    background-color: white;
    border: 2px solid #003067;
    padding: 20px;
    border-radius: 10px;
    margin-top: 300px;
    margin-left: auto;
    margin-right: auto;
    width: 60%;
    position: relative;
}

</style>
<div id="viewFormPopup" class="form-popup-container" style="display: none;">
    <div class="form-popup-content-view">
        <span class="form-popup-close-btn" onclick="closePopup('viewFormPopup')">×</span>
        <div class="form-popup-form-container">
            <header class="form-popup-header">
                <div class="form-popup-logo">
                    <img src="images/systemlogo.png" alt="Logo">
                </div>
                <h1>ICT Equipment Service Request Form</h1>
                <div class="form-popup-form-info">
                    <span>Form No.: <span id="viewFormNo"></span></span>
                </div>
            </header>

           <!-- Service Type (Radio Buttons) -->
            <section class="form-popup-section">
                <label>
                    <input type="radio" name="service_type" value="walk_in" required 
                        id="service_type_walk_in"> Walk-In
                </label>
                <label>
                    <input type="radio" name="service_type" value="pull_out" required 
                        id="service_type_pull_out"> Pull-Out
                </label>
            </section>


            <!-- General Information Section -->
            <section class="form-popup-section">
                <h3 class="form-popup-title">General Information</h3>
                <div class="form-popup-row">
                        <div class="form-popup-input-group">
                            <label class="form-popup-label">Employee Name:</label>
                            <input class="form-popup-input" id="viewName" readonly>
                        </div>
                        <div class="form-popup-input-group">
                            <label class="form-popup-label">Employee ID:</label>
                            <input class="form-popup-input" id="viewEmployee_ID" readonly>
                        </div>
                    </div>
                <div class="form-popup-input-group">
                    <label class="form-popup-label">Department / Office / Unit:</label>
                    <input class="form-popup-input" id="viewDepartment" readonly>
                </div>
                <div class="form-popup-checkbox-group">
                    <label class="form-popup-label">Condition of Equipment:</label>
                    <span id="viewCondition"></span>
                </div>
            </section>

            <!-- Equipment Description Section -->
            <section class="form-popup-section">
                <h3 class="form-popup-title">Equipment Description</h3>
                <table class="form-popup-table">
                    <thead>
                        <tr>
                            <th>Brand</th>
                            <th>Description</th>
                            <th>Motherboard</th>
                            <th>RAM</th>
                            <th>HDD</th>
                            <th>Accessories</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody id="viewEquipmentTable">
                        <!-- Dynamic rows will go here -->
                    </tbody>
                </table>
            </section>

            <!-- Technical Support Section -->
            <div class="form-popup-input-group">
                <label class="form-popup-label">Assigned Technical Support:</label>
                <input class="form-popup-input" id="viewTechnicalSupport" readonly>
            </div>
        </div>
    </div>
</div>

<!-- Add a close button to hide the popup -->
<script>
    function closePopup(popupId) {
        document.getElementById(popupId).style.display = 'none';
    }
</script>
