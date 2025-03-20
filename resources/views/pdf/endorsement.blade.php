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
            background-color: #f4f7fa;
            color: #003067;
            margin: 0;
            padding: 20px;
        }

        .endorsed-modal-content {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .modal-header {
            text-align: center;
            border-bottom: 2px solid #003067;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        .modal-header h2 {
            font-size: 28px;
            margin: 10px 0;
            color: #003067;
        }

        .modal-header p {
            font-size: 16px;
            color: #555;
        }

        .rating-container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
        }

        .rating-container label {
            font-weight: bold;
            margin-right: 10px;
        }

        #starRatingTechnical {
            color: #ffc107;
            font-size: 24px;
        }

        .modal-form-section {
            margin-bottom: 20px;
        }

        .modal-form-section label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #003067;
        }

        .modal-input-box, .modal-input-box-date {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            color: #555;
            background-color: #f9f9f9;
            transition: border-color 0.3s ease;
        }

        .modal-input-box:disabled, .modal-input-box-date:disabled {
            background-color: #e9ecef;
            color: #777;
        }

        .modal-input-box:focus, .modal-input-box-date:focus {
            border-color: #003067;
            outline: none;
        }

        .modal-two-column {
            display: flex;
            gap: 20px;
        }

        .modal-column {
            flex: 1;
        }

        .modal-checkbox-group div {
            display: flex;
            align-items: center;
            gap: 10px; /* Space between checkbox, label, and input */
            margin-bottom: 10px;
        }

        .modal-checkbox-group input[type="checkbox"] {
            margin-right: 10px;
        }

        .modal-checkbox-group label {
            min-width: 100px; /* Adjust as needed */
        }

        .modal-checkbox-group input[type="text"] {
            flex: 1; /* Allow the input to take up remaining space */
        }

        .modal-footer {
            border-top: 2px solid #003067;
            padding-top: 20px;
            margin-top: 20px;
        }

        .modal-footer h3 {
            font-size: 20px;
            margin-bottom: 15px;
            color: #003067;
        }

        .modal-stacked-date-time {
            display: flex;
            gap: 20px;
        }

        .modal-stacked-date-time div {
            flex: 1;
        }

        .form-popup-section {
            margin-top: 20px;
        }

        .form-popup-title {
            font-size: 20px;
            margin-bottom: 15px;
            color: #003067;
        }

        .form-popup-input-group {
            margin-bottom: 15px;
        }

        .form-popup-input-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #003067;
        }

        .form-popup-input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            color: #555;
            background-color: #f9f9f9;
            transition: border-color 0.3s ease;
        }

        .form-popup-input:disabled {
            background-color: #e9ecef;
            color: #777;
        }

        .form-popup-input-group-approval {
            margin-top: 20px;
        }

        .form-popup-input-group-approval label {
            font-weight: bold;
            color: #003067;
        }
        .modal-checkbox-group {
            display: flex;
            flex-direction: column;
            gap: 10px; /* Space between rows */
        }

        .checkbox-row {
            display: flex;
            align-items: center;
            gap: 10px; /* Space between checkbox, label, and input */
        }

        .checkbox-row label {
            min-width: 150px; /* Adjust this value to align all labels */
            text-align: left; /* Align text to the left */
        }

        .checkbox-row input[type="text"] {
            flex: 1; /* Allow the input to take up remaining space */
            min-width: 200px; /* Adjust this value as needed */
        }
        

        /* Animation for Input Focus */
        @keyframes inputFocus {
            0% { transform: scale(1); }
            50% { transform: scale(1.02); }
            100% { transform: scale(1); }
        }

        .modal-input-box:focus, .modal-input-box-date:focus, .form-popup-input:focus {
            animation: inputFocus 0.3s ease;
        }
    </style>
</head>
<body>
    <div class="endorsed-modal-content">
        <!-- Header Section -->
        <div class="modal-header">
            <div class="rating-container">
                <label class="form-popup-label">Rating:</label>
                <div id="starRatingTechnical">{{ $rating }}</div>
            </div>
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
                        @foreach($network as $item)
                            <div class="checkbox-row">
                                <label>{{ $item }}</label>
                                <input type="text" value="{{ $network_details[$item] ?? '' }}" readonly>
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