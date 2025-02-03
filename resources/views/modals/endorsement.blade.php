<style>
#endorsementModal{
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.4);
}
/* Modal Container */
.endorsed-modal-content {
    max-width: 900px;
    margin: 20px auto;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 8px;
    background-color: #f9f9f9;
    z-index: 2; /* Set a high z-index value */
    position: relative; /* Make sure z-index works */
    top: 50%; /* Center vertically */
    transform: translate(0%, -20%); /* Offset to truly center the modal */
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
    width: 96%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.modal-input-box-date{
    width: 325%;
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
    gap: 363px;
    flex-wrap: wrap;
}

.endorsementsave {
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

.endorsementsave:hover {
    background-color: #0056b3;
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
<form id="endorsementForm" action="{{ route('endorsements.store') }}" method="POST">
    @csrf
<div id="endorsementModal" class="modal" style="display: none;">
    <div class="endorsed-modal-content">
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
                <input type="text" id="control_no" name="control_no" class="modal-input-box" readonly>
            </div>

            <!-- Department Section -->
            <div class="modal-form-section">
            <label for="department">Department/Office/Unit:</label>
            <input type="text" id="department" name="department" class="modal-input-box" value="{{ $ticket->department ?? '' }}" readonly>
            </div>

            <!-- Concern Section -->
            <div class="modal-form-section">
                <h3>Concern</h3>
                <div class="modal-two-column">

                    <!-- Left Column -->
                <div class="modal-column">
                    <h4>Internet/System/Void</h4>
                    <div class="modal-checkbox-group">
                        <div>
                            <input type="checkbox" id="system">
                            <label for="system">IP ADDRESS</label>
                            <input type="text" id="ip_address" placeholder="Details">
                        </div>
                        <div>
                            <input type="checkbox" id="void">
                            <label for="void">MAC ADDRESS</label>
                            <input type="text" id="void_input" placeholder="Details">
                        </div>
                        <div>
                            <input type="checkbox" id="network">
                            <label for="network">PING TEST</label>
                            <input type="text" id="network_input" placeholder="Details">
                        </div>
                        <div>
                            <input type="checkbox" id="hardware">
                            <label for="hardware">TRACET</label>
                            <input type="text" id="hardware_input" placeholder="Details">
                        </div>
                        <div>
                            <input type="checkbox" id="software">
                            <label for="software">NETWORK CABLE TEST</label>
                            <input type="text" id="software_input" placeholder="Details">
                        </div>
                        <div>
                            <input type="checkbox" id="security">
                            <label for="security">WEBSITE ACCESS</label>
                            <input type="text" id="security_input" placeholder="Details">
                        </div>
                        <div>
                            <input type="checkbox" id="server">
                            <label for="server">ROUTER / ACCESS POINT</label>
                            <input type="text" id="server_input" placeholder="Details">
                        </div>
                        <div>
                            <input type="checkbox" id="void2">
                            <label for="void2">VOID</label>
                            <input type="text" id="void2_input" placeholder="Details">
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="modal-column">
                    <h4>User Account</h4>
                    <div class="modal-checkbox-group">
                        <div>
                            <input type="checkbox" id="user_account">
                            <label for="user_account">NEW DOMAIN ACCOUNT CREATION</label>
                            <input type="text" id="user_account_input" placeholder="Details">
                        </div>
                        <div>
                            <input type="checkbox" id="other_account">
                            <label for="other_account">WINDOWS ACCOUNT RESET</label>
                            <input type="text" id="other_account_input" placeholder="Details">
                        </div>
                        <div>
                            <input type="checkbox" id="admin">
                            <label for="admin">ADMIN</label>
                            <input type="text" id="admin_input" placeholder="Details">
                        </div>
                        <div>
                            <input type="checkbox" id="guest">
                            <label for="guest">FILE SHARING / FILE SERVER</label>
                            <input type="text" id="guest_input" placeholder="Details">
                        </div>
                        <div>
                            <input type="checkbox" id="folder_creation">
                            <label for="folder_creation">FOLDER CREATION</label>
                        </div>
                        <div>
                            <label for="office_folder_name">OFFICE / FOLDER NAME</label>
                            <input type="text" id="office_folder_name" placeholder="Details">
                        </div>
                        <div>
                            <input type="checkbox" id="folder_access">
                            <label for="folder_access">FOLDER ACCESS</label>
                        </div>
                        <div>
                            <label for="folder_name">FOLDER NAME</label>
                            <input type="text" id="folder_name" placeholder="Details">
                        </div>
                        <div>
                            <label for="user_fullname">USER FULLNAME</label>
                            <input type="text" id="user_fullname" placeholder="Details">
                        </div>
                    </div>
                </div>
            </div>
        </div>

       <!-- Endorsed To Section -->
       <div class="modal-footer">
                <h3>Endorsed To</h3>
                <div class="modal-form-section">
                    <label for="endorsed_to">Endorsed By:</label>
                    <input type="text" id="endorsed_to" class="modal-input-box" placeholder="Enter Name">
                </div>
                <div class="modal-stacked-date-time">
                    <div>
                        <label for="endorsed_to_date">Date:</label>
                        <input type="date" id="endorsed_to_date" class="modal-input-box-date">
                    </div>
                    <div>
                        <label for="endorsed_to_time">Time:</label>
                        <input type="time" id="endorsed_to_time" class="modal-input-box-date">
                    </div>
                </div>
                <div class="modal-form-section">
                    <label for="endorsed_to_remarks">Remarks:</label>
                    <input type="text" id="endorsed_to_remarks" class="modal-input-box" placeholder="Enter Remarks">
                </div>
            </div>

            <!-- Endorsed By Section -->
            <div class="modal-footer">
                <h3>Endorsed By</h3>
                <div class="modal-form-section">
                <label for="technical_division">Technical Division:</label>
                <input type="text" id="technical_division" name="technical_division" class="modal-input-box" value="{{ $ticket->technical_support_name ?? '' }}" readonly>
                </div>
                <div class="modal-stacked-date-time">
                    <div>
                        <label for="date">Date:</label>
                        <input type="date" id="date" name="date" class="modal-input-box" value="{{ date('Y-m-d') }}" readonly>
                    </div>
                    <div>
                        <label for="time">Time:</label>
                        <input type="time" id="time" name="time" class="modal-input-box" value="{{ date('H:i') }}" readonly>
                    </div>
                </div>
                <div class="modal-form-section">
                    <label for="remarks">Remarks:</label>
                    <input id="remarks" name="remarks" class="modal-input-box" value ="{{ $ticket->remarks ?? '' }}" readonly>
                </div>
            </div>

            <button type="submit" class="endorsementsave">Save</button>
    </div>
</div>
</form>
<script>
    
document.getElementById('endorsementForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch('{{ route('endorsements.store') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData,
    })
    .then((response) => response.json())
    .then((data) => {
        if (data.success) {
            alert(data.message);
            closeEndorsementModal();
        } else {
            alert('Failed to save data. Please try again.');
        }
    })
    .catch((error) => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
});

</script>
