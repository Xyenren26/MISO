<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Request PDF</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #f9f9f9;
        }

        /* Header */
        .header {
            text-align: center;
            padding: 20px;
            background-color: #003067; /* Theme color */
            color: #fff;
            border-bottom: 3px solid #ffa500; /* Accent color */
        }

        .header h1 {
            margin: 0;
            font-size: 1.8em;
            font-weight: bold;
        }

        .header h2 {
            margin: 5px 0 0;
            font-size: 1.2em;
            color: #ffa500; /* Accent color */
        }

        .titleName h2 {
            text-align: center;
            margin: 20px 0;
            font-size: 1.8em;
            color: #003067; /* Theme color */
            font-weight: bold;
        }

        /* Form Info */
        .form-popup-form-info {
            text-align: center;
            font-size: 1.1em;
            color: #003067; /* Theme color */
            margin: 15px 0;
            font-weight: bold;
        }

        /* Form Section */
        .form-popup-section {
            margin: 20px 15%;
        }

        .form-popup-row {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
        }

        .form-popup-input-group {
            flex: 1;
            margin-bottom: 10px;
        }

        .form-popup-label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            color: #003067; /* Theme color */
        }

        .form-popup-input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1em;
            background-color: #f9f9f9;
            color: #333;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #003067; /* Theme color */
            color: #fff;
            font-weight: bold;
        }

        td {
            font-size: 0.9em;
            color: #333;
        }

        /* Warning Message */
        .warning-message {
            background-color: #fff3cd;
            border: 1px solid #ffeeba;
            color: #856404;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            font-size: 0.9em;
        }

        /* Footer */
        .footer {
            text-align: center;
            padding: 10px;
            background-color: #003067; /* Theme color */
            border-top: 3px solid #ffa500; /* Accent color */
            margin-top: auto;
            color: #fff;
        }

        .footer p {
            margin: 0;
            font-size: 0.9em;
        }

        /* Accent Color for Headings */
        h3 {
            color: #003067; /* Theme color */
            border-bottom: 2px solid #ffa500; /* Accent color */
            padding-bottom: 5px;
            font-size: 1.4em;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>Techtrack: An Electronic Service Monitoring and Management System</h1>
        <h2>MISO Technical Support Division</h2>
    </div>

    <!-- Body Content -->
    <div class="titleName">
        <h2>ICT Equipment Service Request Form</h2>
    </div>

    <div class="form-popup-form-info">
        <span><strong>Form No.:</strong> {{ $form_no }}</span>
    </div>

    <!-- General Information Section -->
    <section class="form-popup-section">
        <h3>General Information</h3>
        
        <div class="form-popup-row">
            <div class="form-popup-input-group">
                <label class="form-popup-label">Employee Name:</label>
                <input class="form-popup-input" value="{{ $name }}" readonly>
            </div>
            
            <div class="form-popup-input-group">
                <label class="form-popup-label">Employee ID:</label>
                <input class="form-popup-input" value="{{ $employee_id }}" readonly>
            </div>
        </div>

        <div class="form-popup-input-group">
            <label class="form-popup-label">Department / Office / Unit:</label>
            <input class="form-popup-input" value="{{ $department }}" readonly>
        </div>

        <div class="form-popup-input-group">
            <label class="form-popup-label">Condition:</label>
            <input class="form-popup-input" value="{{ ucfirst($condition) }}" readonly>
        </div>
    </section>

    <!-- Equipment Details -->
    <h3>Equipment Details</h3>
    <table>
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
            @foreach ($equipment_descriptions as $equipment)
            <tr>
                <td>{{ $equipment['brand'] }}</td>
                <td>{{ $equipment['device'] }}</td>
                <td>{{ $equipment['description'] }}</td>
                <td>{{ $equipment['serial_no'] }}</td>
                <td>{{ $equipment['remarks'] }}</td>
            </tr>

            <!-- Display warning message if serial_no appears more than 5 times -->
            @if ($equipment['warning_message'])
                <tr>
                    <td colspan="5" class="warning-message">
                        {{ $equipment['warning_message'] }}
                    </td>
                </tr>
            @endif
            @endforeach
        </tbody>
    </table>

    <!-- Technical Support -->
    <div class="form-popup-input-group">
        <label class="form-popup-label">Technical Support:</label>
        <input class="form-popup-input" value="{{ $technical_support }}" readonly>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>All Rights Reserved. &copy; Techtrack</p>
    </div>
</body>
</html>