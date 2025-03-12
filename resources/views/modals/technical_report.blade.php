<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Technical Report Form</title>
    <style>
        #technicalModal{
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
        .modal-content-technical-report {
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
        .form-header {
            text-align: center;
            font-size: 24px;
            text-transform: uppercase;
            margin-bottom: 20px;
            background-color: #003067;
            color: white;
            padding: 15px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .form-group input, .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .form-group textarea {
            resize: vertical;
            height: 80px;
        }
        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }
        .signature {
            flex: 1;
            margin-right: 20px;
        }
        .signature:last-child {
            margin-right: 0;
        }
        .signature label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .signature input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .technicalsavebutton{
            margin-top: 20px;
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
    </style>
</head>
<body>


<!-- The Modal -->
<div id="technicalModal" style="display: none;">
    <div class="modal-content-technical-report">
        <span class="close" onclick="closeTechnicalReportModal()">&times;</span>
        <div class="form-header">Technical Report</div>
        <form id="technicalReportForm" action="{{ route('technical-reports.store') }}" method="POST">
            @csrf
            <input type="hidden" id="control_no" name="control_no">
            <input type="hidden" id="TRcontrol_no" name="TR_id">

            <!-- System Generated Date and Time -->
            <div class="form-group">
                <label for="date-time">Date and Time</label>
                <input type="datetime-local" id="date-time" name="date_time" required readonly>
            </div>

            <!-- Auto-fill Department & End User -->
            <div class="form-group">
                <label for="department">Department</label>
                <input type="text" id="TechnicalReportDepartment" name="department" required readonly>
            </div>
            <div class="form-group">
                <label for="enduser">End User</label>
                <input type="text" id="enduser" name="enduser" required readonly>
            </div>

            <div class="form-group">
                <label for="specification">Specification</label>
                <textarea id="specification" name="specification" required></textarea>
            </div>
            <div class="form-group">
                <label for="problem">Problem</label>
                <textarea id="problemtech" name="problem" required readonly></textarea>
            </div>
            <div class="form-group">
                <label for="workdone">Work Done</label>
                <textarea id="workdonetech" name="workdone" required readonly></textarea>
            </div>
            <div class="form-group">
                <label for="findings">Findings</label>
                <textarea id="findings" name="findings" required></textarea>
            </div>
            <div class="form-group">
                <label for="recommendation">Recommendation</label>
                <textarea id="recommendation" name="recommendation" required></textarea>
            </div>

            <!-- Signatures -->
            <div class="signatures">
                <div class="signature">
                    <label for="inspected">Inspected By</label>
                    <input type="text" id="inspectedtech" name="inspected_by" required readonly>
                    <label for="inspected-date">Date and Time</label>
                    <input type="datetime-local" id="inspected-date" name="inspected_date" required readonly>
                </div>
            </div>
            <button type="submit" class="technicalsavebutton">Save Report</button>
        </form>
    </div>
</div>

</body>
</html>
