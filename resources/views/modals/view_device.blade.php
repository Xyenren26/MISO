<div id="viewFormPopup" class="form-popup-container" style="display: none;">
    <div class="form-popup-content">
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

            <!-- General Information Section -->
            <section class="form-popup-section">
                <h3 class="form-popup-title">General Information</h3>
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
                            <th>Parts</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody id="viewEquipmentTable">
                        <!-- Data will be inserted dynamically -->
                    </tbody>
                </table>
            </section>
        </div>
    </div>
</div>
