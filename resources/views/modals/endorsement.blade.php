<style>

/* Modal Container */
#endorsementModal {
    max-width: 900px;
    margin: 20px auto;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 8px;
    background-color: #f9f9f9;
}

/* Header Section */
.modal-header {
    text-align: center;
    margin-bottom: 20px;
}

.modal-header img {
    width: 200px;
    height: auto;
    margin: 0 auto;
    display: block;
}

.modal-header h2 {
    background-color: #003067;
    color: white;
    padding: 15px;
    margin: 0;
    text-transform: uppercase;
}

.modal-header p {
    text-align: right;
    font-size: 14px;
    color: #333;
}

/* Form Section */
.modal-form-section {
    margin-bottom: 15px;
}

.modal-form-section label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

.modal-input-box {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.modal-input-box-date{
    width: 250%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
}


/* Two Column Layout */
.modal-two-column {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}

.modal-column {
    flex: 1;
    min-width: 250px;
    background: #ffffff;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 15px;
}

/* Checkbox Group */
.modal-checkbox-group {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.modal-checkbox-group div {
    display: flex;
    align-items: center;
    gap: 10px;
}

.modal-checkbox-group label {
    flex: 1;
    font-size: 16px;
}

.modal-checkbox-group input[type="text"] {
    flex: 2;
    padding: 5px;
    font-size: 14px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

/* Footer Section */
.modal-footer {
    margin-top: 20px;
    padding-top: 15px;
    border-top: 1px solid #ccc;
}

.modal-stacked-date-time {
    display: flex;
    gap: 100px;
    flex-wrap: wrap;
}

/* Responsive Design */
@media (max-width: 600px) {
    .modal-two-column {
        flex-direction: column;
    }

    .modal-stacked-date-time {
        flex-direction: column;
    }
}
</style>

<!-- Modal HTML Structure -->
<div id="endorsementModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close" onclick="closeEndorsementModal()">&times;</span>

        <!-- Header Section -->
        <div class="modal-header">
        <img src="{{ asset('images/SystemLogo.png') }}" alt="System Logo">
            <h2>Technical Endorsement</h2>
            <p><strong>MISO</strong><br>Management Information Systems Office</p>
        </div>

        <!-- Control No Section -->
        <div class="modal-form-section">
            <label for="control_no">Control No:</label>
            <input type="text" id="control_no" class="modal-input-box" placeholder="Enter Control No">
        </div>

        <!-- Department Section -->
        <div class="modal-form-section">
            <label for="department">Department/Office/Unit:</label>
            <input type="text" id="department" class="modal-input-box" placeholder="Enter Department/Office/Unit">
        </div>

        <!-- Concern Section -->
        <div class="modal-form-section">
            <h3>Concern</h3>
            <div class="modal-two-column">

                <!-- Left Column -->
                <div class="modal-column">
                    <h4>Internet/System/Void</h4>
                    <div class="modal-checkbox-group">
                        <div><input type="checkbox" id="system"><label for="system">IP ADDRESS</label><input type="text" placeholder="Details"></div>
                        <div><input type="checkbox" id="void"><label for="void">MAC ADDRESS</label><input type="text" placeholder="Details"></div>
                        <div><input type="checkbox" id="network"><label for="network">PING TEST</label><input type="text" placeholder="Details"></div>
                        <div><input type="checkbox" id="hardware"><label for="hardware">TRACET</label><input type="text" placeholder="Details"></div>
                        <div><input type="checkbox" id="software"><label for="software">NETWORK CABLE TEST</label><input type="text" placeholder="Details"></div>
                        <div><input type="checkbox" id="security"><label for="security">WEBSITE ACCESS</label><input type="text" placeholder="Details"></div>
                        <div><input type="checkbox" id="server"><label for="server">ROUTER / ACCESS POINT</label><input type="text" placeholder="Details"></div>
                        <div><input type="checkbox" id="void2"><label for="void2">VOID</label><input type="text" placeholder="Details"></div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="modal-column">
                    <h4>User Account</h4>
                    <div class="modal-checkbox-group">
                        <div><input type="checkbox" id="user_account"><label for="user_account">NEW DOMAIN ACCOUNT CREATION</label><input type="text" placeholder="Details"></div>
                        <div><input type="checkbox" id="other_account"><label for="other_account">WINDOWS ACCOUNT RESET</label><input type="text" placeholder="Details"></div>
                        <div><input type="checkbox" id="admin"><label for="admin">Admin</label><input type="text" placeholder="Details"></div>
                        <div><input type="checkbox" id="guest"><label for="guest">FILE SHARING / FILE SERVER</label><input type="text" placeholder="Details"></div>
                        <div><input type="checkbox" id="folder_creation"><label for="folder_creation">FOLDER CREATION</label><input type="text" placeholder="Details"></div>
                        <div><label for="office_folder_name">OFFICE / FOLDER NAME</label><input type="text" id="office_folder_name" placeholder="Details"></div>
                        <div><input type="checkbox" id="folder_access"><label for="folder_access">FOLDER ACCESS</label><input type="text" placeholder="Details"></div>
                        <div><label for="folder_name">FOLDER NAME</label><input type="text" id="folder_name" placeholder="Details"></div>
                        <div><label for="user_fullname">USER FULLNAME</label><input type="text" id="user_fullname" placeholder="Details"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Endorsed By Section -->
        <div class="modal-footer">
            <div class="modal-form-section">
                <label for="endorsed_by">Endorsed By:</label>
                <input type="text" id="endorsed_by" class="modal-input-box" placeholder="Enter Name">
            </div>
            <div class="modal-stacked-date-time">
                <div>
                    <label for="date">Date:</label>
                    <input type="date" id="date" class="modal-input-box-date">
                </div>
                <div>
                    <label for="time">Time:</label>
                    <input type="time" id="time" class="modal-input-box-date">
                </div>
            </div>
            <div class="modal-form-section">
                <label for="remarks">Remarks:</label>
                <input type="text" id="remarks" class="modal-input-box" placeholder="Enter Remarks">
            </div>
        </div>
        <!-- Endorsed By Section -->
        <div class="modal-footer">
            <div class="modal-form-section">
                <label for="endorsed_to">Endorsed To:</label>
                <input type="text" id="endorsed_to" class="modal-input-box" placeholder="Enter Name">
            </div>
            <div class="modal-stacked-date-time">
                <div>
                    <label for="date">Date:</label>
                    <input type="date" id="date" class="modal-input-box-date">
                </div>
                <div>
                    <label for="time">Time:</label>
                    <input type="time" id="time" class="modal-input-box-date">
                </div>
            </div>
            <div class="modal-form-section">
                <label for="remarks">Remarks:</label>
                <input type="text" id="remarks" class="modal-input-box" placeholder="Enter Remarks">
            </div>
        </div>
    </div>
</div>
