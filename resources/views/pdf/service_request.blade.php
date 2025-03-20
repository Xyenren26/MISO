<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ICT Equipment Service Request Form</title>
    <style>
        /* Global Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #ffffff; /* White background for PDF */
            color: #333;
        }

        .form-popup-content-view {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 30px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        /* Header Section */
        .form-popup-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-popup-header h1 {
            color: #003067;
            font-size: 24px;
            margin: 10px 0;
            font-weight: 600;
        }

        .form-popup-form-info_no {
            margin-top: 10px;
        }

        .form-popup-form-info_no label {
            font-weight: bold;
            color: #003067;
        }

        .form-popup-form-info_no input {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background: #f9f9f9;
            font-size: 14px;
            width: 200px;
            text-align: center;
        }

        /* QR Code */
        .qr-code-container {
            position: absolute;
            top: 20px;
            left: 20px;
        }

        .qr-code {
            width: 100px;
            height: auto;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 5px;
            background: #fff;
        }

        /* Top Section: Rating and Status */
        .top-container {
            margin-bottom: 20px;
            margin-top: 90px; /* Space for QR code */
        }

        .rating-container {
            float: left;
            width: 50%;
        }

        .rating-container label {
            font-weight: bold;
            color: #003067;
        }

        .star-rating {
            color: #FFD700;
            font-size: 20px;
        }

        .status-container {
            float: right;
            width: 40%;
            text-align: right;
        }

        .status-container label {
            font-weight: bold;
            color: #003067;
        }

        .status-container input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background: #f9f9f9;
            font-size: 14px;
            text-align: center;
            text-transform: uppercase;
        }

        /* Clear floats */
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }

        /* Sections */
        .form-popup-section {
            margin-bottom: 25px;
        }

        .form-popup-section h3 {
            color: #003067;
            font-size: 18px;
            margin-bottom: 10px;
            border-bottom: 2px solid #003067;
            padding-bottom: 5px;
            font-weight: 600;
        }

        .form-popup-row {
            margin-bottom: 15px;
        }

        .form-popup-input-group {
            margin-bottom: 15px;
        }

        .form-popup-input-group label {
            font-weight: bold;
            color: #003067;
            display: block;
            margin-bottom: 5px;
        }

        .form-popup-input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background: #f9f9f9;
            font-size: 14px;
        }

        .form-popup-checkbox-group label {
            margin-right: 15px;
            font-weight: normal;
        }

        /* Table */
        .form-popup-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .form-popup-table th,
        .form-popup-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .form-popup-table th {
            background-color: #003067;
            color: #fff;
            font-weight: 600;
        }

        .form-popup-table td {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="form-popup-content-view">
        <!-- QR Code -->
        <div class="qr-code-container">
            <img id="qrCodeImage" class="qr-code" src="{{ $qrCodeBase64 }}" alt="QR Code">
        </div>

        <!-- Header -->
        <header class="form-popup-header">
            <h1>ICT Equipment Service Request Form</h1>
            <div class="form-popup-form-info_no">
                <label for="viewFormNoService">Form No.:</label>
                <input type="text" value="{{ $serviceRequest->form_no }}" readonly>
            </div>
        </header>

        <!-- Rating and Status -->
        <div class="top-container clearfix">
            <div class="rating-container">
                <label>Rating:</label>
                <div id="starRatingTechnical" class="star-rating">{{ $rating }}
                    @php
                        $ratingValue = (int)$rating;
                        echo str_repeat('*', $ratingValue) . str_repeat('', 5 - $ratingValue);
                    @endphp
                </div>
            </div>
            <div class="status-container">
                <label>Status:</label>
                <input type="text" value="{{ $serviceRequest->status }}" readonly>
            </div>
        </div>

        <!-- Service Type -->
        <section class="form-popup-section">
            <h3>Service Type</h3>
            <div class="form-popup-checkbox-group">
                <label><input type="radio" name="service_type" @if($serviceRequest->service_type === 'walk_in') checked @endif disabled> Walk-In</label>
                <label><input type="radio" name="service_type" @if($serviceRequest->service_type === 'pull_out') checked @endif disabled> Pull-Out</label>
            </div>
        </section>

        <!-- General Information -->
        <section class="form-popup-section">
            <h3>General Information</h3>
            <div class="form-popup-row">
                <div class="form-popup-input-group">
                    <label>Employee Name:</label>
                    <input class="form-popup-input" value="{{ $serviceRequest->name }}" readonly>
                </div>
                <div class="form-popup-input-group">
                    <label>Employee ID:</label>
                    <input class="form-popup-input" value="{{ $serviceRequest->employee_id }}" readonly>
                </div>
            </div>
            <div class="form-popup-input-group">
                <label>Department / Office / Unit:</label>
                <input class="form-popup-input" value="{{ $serviceRequest->department }}" readonly>
            </div>
            <div class="form-popup-checkbox-group">
                <label>Condition of Equipment:</label>
                <label><input type="radio" name="condition" @if($serviceRequest->condition === 'working') checked @endif disabled> Working</label>
                <label><input type="radio" name="condition" @if($serviceRequest->condition === 'not-working') checked @endif disabled> Not Working</label>
                <label><input type="radio" name="condition" @if($serviceRequest->condition === 'needs-repair') checked @endif disabled> Needs Repair</label>
            </div>
        </section>

        <!-- Equipment Description -->
        <section class="form-popup-section">
            <h3>Equipment Description</h3>
            <table class="form-popup-table">
                <thead>
                    <tr>
                        <th>Brand</th>
                        <th>Device</th>
                        <th>Description</th>
                        <th>Serial Number</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($equipmentDescriptions as $equipment)
                        <tr>
                            <td>{{ $equipment->brand }}</td>
                            <td>{{ $equipment->device }}</td>
                            <td>{{ $equipment->description }}</td>
                            <td>{{ $equipment->serial_no }}</td>
                            <td>{{ $equipment->remarks }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>

        <!-- Technical Support -->
        <div class="form-popup-input-group">
            <label>Assigned Technical Support:</label>
            <input class="form-popup-input" value="{{ $name->name }}" readonly>
        </div>

        <!-- Approval Details -->
        <section class="form-popup-section">
            <h3>Approval Details</h3>
            <div class="form-popup-input-group-approval">
                <label>Noted By:</label>
                <input class="form-popup-input" value="{{ $approve->name }}" readonly>
            </div>
            <div class="form-popup-input-group-approval">
                <label>Approval Date:</label>
                <input class="form-popup-input" value="{{ $approve->approve_date }}" readonly>
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