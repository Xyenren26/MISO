<!DOCTYPE html>
<html>
<head>
    <title>Deployment PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        .title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
        }

        .section-title {
            font-weight: bold;
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <div class="title">
        New Device Deployment Details
    </div>

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
    <h3>Equipment Items</h3>
    <table border="1">
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
    

</body>
</html>
