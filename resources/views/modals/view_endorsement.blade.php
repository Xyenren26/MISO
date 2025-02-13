<style>
      #endorsementViewModal{
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
    }
</style>
<div id="endorsementViewModal" class="modal" style="display: none;">
    <div class="endorsed-modal-content">
        <span class="close" onclick="closeEndorsementViewModal()">&times;</span>

        <!-- Header Section -->
        <div class="modal-header">
            <img src="{{ asset('images/SystemLogo.png') }}" alt="System Logo">
            <h2>Technical Endorsement</h2>
            <p><strong>MISO</strong><br>Management Information Systems Office</p>
        </div>

        <!-- Control No Section -->
        <div class="modal-form-section">
            <label for="control_no">Control No:</label>
            <input type="text" id="ViewEndorsementcontrol_no" name="control_no" class="modal-input-box" readonly>
        </div>

        <!-- Department Section -->
        <div class="modal-form-section">
            <label for="department">Department/Office/Unit:</label>
            <input type="text" id="ViewEndorsementdepartment" name="department" class="modal-input-box" readonly>
        </div>

        <!-- Concern Section -->
        <div class="modal-form-section">
            <h3>Concern</h3>
            <div class="modal-two-column">

                <!-- Left Column - Network Issues -->
                <div class="modal-column">
                    <h4>Internet/System/Void</h4>
                    <div class="modal-checkbox-group">
                        @foreach(['IP ADDRESS', 'MAC ADDRESS', 'PING TEST', 'TRACET', 'NETWORK CABLE TEST', 'WEBSITE ACCESS', 'ROUTER / ACCESS POINT', 'VOID'] as $networkItem)
                            <div>
                                <input type="checkbox" name="network[]" value="{{ $networkItem }}">
                                <label>{{ $networkItem }}</label>
                                <input type="text" name="network_details[{{ $networkItem }}]" placeholder="Details">
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Right Column - User Account Issues -->
                <div class="modal-column">
                    <h4>User Account</h4>
                    <div class="modal-checkbox-group">
                        @foreach([
                            'NEW DOMAIN ACCOUNT CREATION', 
                            'WINDOWS ACCOUNT RESET', 
                            'ADMIN', 
                            'FILE SHARING / FILE SERVER', 
                            'FOLDER CREATION', 
                            'FOLDER ACCESS'
                        ] as $userAccountItem)
                        <div class="inline-group">
                            <div class="inline-header">
                                <input type="checkbox" name="user_account[]" value="{{ $userAccountItem }}">
                                <label>{{ $userAccountItem }}</label>
                                <input type="text" name="user_account_details[{{ $userAccountItem }}]" 
                                    placeholder="{{ $userAccountItem === 'FOLDER ACCESS' ? 'Folder Name' : 'Details' }}">
                            </div>

                            @if($userAccountItem === 'FOLDER ACCESS')
                                <div class="inline-extra">
                                    <input type="text" name="user_account_details[FOLDER ACCESS_USER]" placeholder="User Full Name">
                                </div>
                            @endif
                        </div>

                        @endforeach
                    </div>
                </div>

            </div>
        </div>

        <!-- Endorsed To Section -->
        <div class="modal-footer">
            <h3>Endorsed To</h3>
            <div class="modal-form-section">
                <label for="endorsed_to">Endorsed To:</label>
                <input type="text" id="endorsed_to" name="endorsed_to" class="modal-input-box" placeholder="Enter Name">
            </div>
            <div class="modal-stacked-date-time">
                <div>
                    <label for="endorsed_to_date">Date:</label>
                    <input type="date" name="endorsed_to_date" id="endorsed_to_date" class="modal-input-box-date" value="{{ date('Y-m-d') }}" disabled>
                </div>
                <div>
                    <label for="endorsed_to_time">Time:</label>
                    <input type="time" name="endorsed_to_time" id="endorsed_to_time" class="modal-input-box-date" value="{{ date('H:i') }}" readonly>
                </div>
            </div>
            <div class="modal-form-section">
                <label for="endorsed_to_remarks">Remarks:</label>
                <input type="text" id="endorsed_to_remarks" name="endorsed_to_remarks" class="modal-input-box" placeholder="Enter Remarks">
            </div>
        </div>
        <!-- Endorsed By Section -->
        <div class="modal-footer">
            <h3>Endorsed By</h3>
            <div class="modal-form-section">
                <label for="endorsed_by">Endorsed By:</label>
                <input type="text" id="endorsed_by" name="endorsed_by" class="modal-input-box" placeholder="Enter Name">
            </div>
            <div class="modal-stacked-date-time">
                <div>
                    <label for="endorsed_by_date">Date:</label>
                    <input type="date" name="endorsed_by_date" id="endorsed_by_date" class="modal-input-box-date" value="{{ date('Y-m-d') }}" readonly>
                </div>
                <div>
                    <label for="endorsed_by_time">Time:</label>
                    <input type="time" name="endorsed_by_time" id="endorsed_by_time" class="modal-input-box-date" value="{{ date('H:i') }}" readonly>
                </div>
            </div>
            <div class="modal-form-section">
                <label for="endorsed_by_remarks">Remarks:</label>
                <input type="text" id="endorsed_by_remarks" name="endorsed_by_remarks" class="modal-input-box" placeholder="Enter Remarks">
            </div>
        </div>


        <!-- Save Button -->
        <button type="submit" class="endorsementsave">Save</button>
    </div>
</div>
