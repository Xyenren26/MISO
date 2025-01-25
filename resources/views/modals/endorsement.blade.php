<!-- Modal -->
<div id="endorsementModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <!-- The original container content -->
        <div class="container">
            <!-- Header Section -->
            <div class="header">
                <img src="systemlogo.png" alt="System Logo">
                <h2>Technical Endorsement</h2>
                <p><strong>MISO</strong><br>Management Information Systems Office</p>
            </div>

            <!-- Control No Section -->
            <div class="form-section">
                <label for="control_no">Control No:</label>
                <input type="text" id="control_no" class="input-box" placeholder="Enter Control No">
            </div>

            <!-- Department Section -->
            <div class="form-section">
                <label for="department">Department/Office/Unit:</label>
                <input type="text" id="department" class="input-box" placeholder="Enter Department/Office/Unit">
            </div>

            <!-- Concern Section -->
            <div class="form-section">
                <h3>Concern</h3>
                <div class="two-column">
                    <!-- Left Column -->
                    <div class="column">
                        <h4>Internet/System/Void</h4>
                        <div class="checkbox-group">
                            <div><input type="checkbox" id="system"><label for="system">IP ADDRESS</label><input type="text" placeholder="Details"></div>
                            <div><input type="checkbox" id="void"><label for="void">MAC ADDRESS</label><input type="text" placeholder="Details"></div>
                            <div><input type="checkbox" id="network"><label for="network">PING TEST</label><input type="text" placeholder="Details"></div>
                            <div><input type="checkbox" id="hardware"><label for="hardware">TRACET</label><input type="text" placeholder="Details"></div>
                            <div><input type="checkbox" id="software"><label for="software">NETWORK CABLE TEST</label><input type="text" placeholder="Details"></div>
                            <div><input type="checkbox" id="security"><label for="security">WEBSITE ACCESS</label><input type="text" placeholder="Details"></div>
                            <div><input type="checkbox" id="server"><label for="server">ROUTER / ACCESS POINT</label><input type="text" placeholder="Details"></div>
                            <div><input type="checkbox" id="void"><label for="void">VOID</label><input type="text" placeholder="Details"></div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="column">
                        <h4>User Account</h4>
                        <div class="checkbox-group">
                            <div><input type="checkbox" id="user_account"><label for="user_account">NEW DOMAIN ACCOUNT CREATION</label><input type="text" placeholder="Details"></div>
                            <div><input type="checkbox" id="other_account"><label for="other_account">WINDOWS ACCOUNT RESET</label><input type="text" placeholder="Details"></div>
                            <div><input type="checkbox" id="admin"><label for="admin">Admin</label><input type="text" placeholder="Details"></div>
                            <div><input type="checkbox" id="guest"><label for="guest">FILE SHARING / FILE SERVER</label><input type="text" placeholder="Details"></div>
                            <div><input type="checkbox" id="access"><label for="access">FOLDER CREATION</label><input type="text" placeholder="Details"></div>
                            <div><label for="access">OFFICE / FOLDER NAME</label><input type="text" placeholder="Details"></div>
                            <div><input type="checkbox" id="access"><label for="access">FOLDER ACCESS</label><input type="text" placeholder="Details"></div>
                            <div><label for="access">FOLDER NAME</label><input type="text" placeholder="Details"></div>
                            <div><label for="access">USER FULLNAME</label><input type="text" placeholder="Details"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Endorsed By Section -->
            <div class="footer">
                <div class="form-section">
                    <label for="endorsed_by">Endorsed By:</label>
                    <input type="text" id="endorsed_by" class="input-box" placeholder="Enter Name">
                </div>
                <div class="stacked-date-time">
                    <div>
                        <label for="date">Date:</label>
                        <input type="date" id="date" class="input-box">
                    </div>
                    <div>
                        <label for="time">Time:</label>
                        <input type="time" id="time" class="input-box">
                    </div>
                </div>
                <div class="form-section">
                    <label for="remarks">Remarks:</label>
                    <input type="text" id="remarks" class="input-box" placeholder="Enter Remarks">
                </div>
            </div>
        </div>
    </div>
</div>