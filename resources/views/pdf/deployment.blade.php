<!DOCTYPE html>
<html>
<head>
    <title>Deployment PDF</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Header */
        .header {
            text-align: center;
            padding: 20px;
            background-color: #f2f2f2;
            border-bottom: 1px solid #ddd;
        }

        .header h1 {
            margin: 0;
            font-size: 1.5em;
        }

        .header h2 {
            margin: 5px 0 0;
            font-size: 1em;
            color: #555;
        }

        /* Title */
        .title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin: 20px 0;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        /* Section Title */
        h3.section-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin: 20px 0 10px;
        }

        /* Footer */
        .footer {
            text-align: center;
            padding: 10px;
            background-color: #f2f2f2;
            border-top: 1px solid #ddd;
            margin-top: auto;
        }

        .footer p {
            margin: 0;
            font-size: 0.9em;
            color: #555;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>Techtrack: An Electronic Service Monitoring and Management System</h1>
        <h2>MISO Technical Support Division</h2>
    </div>

    <!-- Title -->
    <div class="title">
        New Device Deployment Details
    </div>

    <!-- Deployment Details Table -->
    <table>
        <tr>
            <th>Control Number</th>
            <td>{{ $control_number }}</td>
        </tr>
        <tr>
            <th>Purpose</th>
            <td>{{ $purpose }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>{{ $status }}</td>
        </tr>
        <tr>
            <th>Components</th>
            <td>{{ $components }}</td>
        </tr>
        <tr>
            <th>Software</th>
            <td>{{ $software }}</td>
        </tr>
        <tr>
            <th>Brand Name</th>
            <td>{{ $brand_name }}</td>
        </tr>
        <tr>
            <th>Specification</th>
            <td>{{ $specification }}</td>
        </tr>
        <tr>
            <th>Received By</th>
            <td>{{ $received_by }}</td>
        </tr>
        <tr>
            <th>Received Date</th>
            <td>{{ $received_date }}</td>
        </tr>
        <tr>
            <th>Issued By</th>
            <td>{{ $issued_by }}</td>
        </tr>
        <tr>
            <th>Issued Date</th>
            <td>{{ $issued_date }}</td>
        </tr>
        <tr>
            <th>Noted By</th>
            <td>{{ $noted_by }}</td>
        </tr>
        <tr>
            <th>Noted Date</th>
            <td>{{ $noted_date }}</td>
        </tr>
    </table>

    <!-- Equipment Items Section -->
    <h3 class="section-title">Equipment Items</h3>
    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th>Serial Number</th>
                <th>Quantity</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($equipmentItems as $item)
                <tr>
                    <td>{{ $item->description }}</td>
                    <td>{{ $item->serial_number }}</td>
                    <td>{{ $item->quantity }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p>All Rights Reserved. &copy; Techtrack</p>
    </div>
</body>
</html>