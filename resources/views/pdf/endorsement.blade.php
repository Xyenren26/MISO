<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Technical Endorsement</title>
    <style>
        /* Global Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #ffffff; /* White background for PDF */
            color: #003067;
            margin: 0;
            padding: 20px;
        }

        .endorsed-modal-content {
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 8px;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Header Section */
        .modal-header {
            text-align: center;
            border-bottom: 2px solid #003067;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        .modal-header h2 {
            font-size: 24px;
            margin: 10px 0;
            color: #003067;
        }

        .modal-header p {
            font-size: 16px;
            color: #555;
            margin: 5px 0;
        }

        /* Form Sections */
        .modal-form-section {
            margin-bottom: 20px;
        }

        .modal-form-section label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #003067;
            font-size: 14px;
        }

        .modal-input-box, .modal-input-box-date {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            color: #555;
            background-color: #f9f9f9;
            box-sizing: border-box;
        }

        .modal-input-box:disabled, .modal-input-box-date:disabled {
            background-color: #e9ecef;
            color: #777;
        }

        /* Two-Column Layout */
        .modal-two-column {
            display: table;
            width: 100%;
        }

        .modal-column {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 10px;
        }

        .modal-column:last-child {
            padding-right: 0;
        }

        /* Checkbox Group */
        .modal-checkbox-group {
            margin-bottom: 15px;
        }

        .checkbox-row {
            margin-bottom: 10px;
        }

        .checkbox-row label {
            display: inline-block;
            min-width: 150px;
            color: #003067;
            font-size: 14px;
        }

        .checkbox-row input[type="text"] {
            width: calc(100% - 160px);
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            color: #555;
            background-color: #f9f9f9;
        }

        /* Footer Sections */
        .modal-footer {
            border-top: 2px solid #003067;
            padding-top: 20px;
            margin-top: 20px;
        }

        .modal-footer h3 {
            font-size: 18px;
            margin-bottom: 15px;
            color: #003067;
        }

        .modal-stacked-date-time {
            margin-bottom: 15px;
        }

        .modal-stacked-date-time div {
            display: inline-block;
            width: 48%;
            margin-right: 2%;
        }

        .modal-stacked-date-time div:last-child {
            margin-right: 0;
        }

        /* Approval Details Section */
        .form-popup-section {
            margin-top: 20px;
        }

        .form-popup-title {
            font-size: 18px;
            margin-bottom: 15px;
            color: #003067;
            border-bottom: 2px solid #003067;
            padding-bottom: 8px;
        }

        .form-popup-input-group {
            margin-bottom: 15px;
        }

        .form-popup-input-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #003067;
            font-size: 14px;
        }

        .form-popup-input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            color: #555;
            background-color: #f9f9f9;
        }

        .form-popup-input:disabled {
            background-color: #e9ecef;
            color: #777;
        }

        /* Technical Support Division Head */
        .form-popup-section h3 {
            font-size: 18px;
            margin-bottom: 15px;
            color: #003067;
            border-bottom: 2px solid #003067;
            padding-bottom: 8px;
        }

        .form-popup-input-group-approval {
            margin-bottom: 15px;
        }

        .form-popup-input-group-approval label {
            font-weight: bold;
            color: #003067;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="endorsed-modal-content">
        <!-- Header Section -->
        <div class="modal-header">
            <h2>Technical Endorsement</h2>
            <p><strong>MISO</strong><br>Management Information Systems Office</p>
        </div>

        <!-- Control No Section -->
        <div class="modal-form-section">
            <label for="control_no">Control No:</label>
            <input type="text" value="{{ $endorsement->control_no }}" class="modal-input-box" readonly disabled>
        </div>

        <!-- Department Section -->
        <div class="modal-form-section">
            <label for="department">Department/Office/Unit:</label>
            <input type="text" value="{{ $endorsement->department }}" class="modal-input-box" readonly disabled>
        </div>

        <!-- Concern Section -->
        <div class="modal-form-section">
            <h3>Concern</h3>
            <div class="modal-two-column">
                <!-- Left Column - Network Issues -->
                <div class="modal-column">
                    <div class="modal-checkbox-group">
                        @if(!empty($network))
                            @foreach($network as $item)
                                <div class="checkbox-row">
                                    <label>{{ $item }}</label>
                                    <input type="text" value="{{ $network_details[$item] ?? '' }}" readonly>
                                </div>
                            @endforeach
                        @else
                            <div class="checkbox-row">
                                <label>No network data available</label>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Endorsed To Section -->
        <div class="modal-footer">
            <h3>Endorsed To</h3>
            <div class="modal-form-section">
                <label for="endorsed_to">Endorsed To:</label>
                <input type="text" value="{{ $endorsement->endorsed_to }}" class="modal-input-box" disabled>
            </div>
            <div class="modal-stacked-date-time">
                <div>
                    <label for="endorsed_to_date">Date:</label>
                    <input type="date" value="{{ $endorsement->endorsed_to_date }}" class="modal-input-box-date" readonly disabled>
                </div>
                <div>
                    <label for="endorsed_to_time">Time:</label>
                    <input type="time" value="{{ $endorsement->endorsed_to_time }}" class="modal-input-box-date" readonly disabled>
                </div>
            </div>
            <div class="modal-form-section">
                <label for="endorsed_to_remarks">Remarks:</label>
                <input type="text" value="{{ $endorsement->endorsed_to_remarks }}" class="modal-input-box" disabled>
            </div>
        </div>

        <!-- Endorsed By Section -->
        <div class="modal-footer">
            <h3>Endorsed By</h3>
            <div class="modal-form-section">
                <label for="endorsed_by">Endorsed By:</label>
                <input type="text" value="{{ $endorsement->endorsed_by }}" class="modal-input-box" readonly disabled>
            </div>
            <div class="modal-stacked-date-time">
                <div>
                    <label for="endorsed_by_date">Date:</label>
                    <input type="date" value="{{ $endorsement->endorsed_by_date }}" class="modal-input-box-date" readonly disabled>
                </div>
                <div>
                    <label for="endorsed_by_time">Time:</label>
                    <input type="time" value="{{ $endorsement->endorsed_by_time }}" class="modal-input-box-date" readonly disabled>
                </div>
            </div>
            <div class="modal-form-section">
                <label for="endorsed_by_remarks">Work Done:</label>
                <input type="text" value="{{ $endorsement->endorsed_by_remarks }}" class="modal-input-box" readonly disabled>
            </div>
        </div>

        <!-- Approval Details Section -->
        <section class="form-popup-section">
            <h3 class="form-popup-title">Approval Details</h3>
            <div class="form-popup-input-group">
                <label class="form-popup-label">Noted By:</label>
                <input class="form-popup-input" value="{{ $approve['name'] }}" readonly disabled>
            </div>
            <div class="form-popup-input-group">
                <label class="form-popup-label">Approval Date:</label>
                <input class="form-popup-input" value="{{ $approve['approve_date'] }}" readonly disabled>
            </div>
        </section>

        <!-- Technical Support Division Head -->
        <section class="form-popup-section">
            <h3>Technical Support Division (MISO) Head</h3>
            <div class="form-popup-input-group-approval">
                <label>Department Head:</label>
                <input class="form-popup-input" value="Mr. Cecilio V. Demano" readonly>
            </div>
        </section>
    </div>
</body>
</html>