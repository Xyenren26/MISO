<!DOCTYPE html>
<html>
<head>
    <title>Technician Performance Report</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
    </style>
</head>
<body>
    <h2>Technician Performance Report - {{ $selectedMonth }}</h2>
    <table>
        <thead>
            <tr>
                <th>Technician Name / ID</th>
                <th>Tickets Assigned</th>
                <th>Tickets Solved</th>
                <th>Average Resolution Time</th>
                <th>Endorsed Tickets</th>
                <th>Pull Out Device</th>
                <th>Technical Reports Submitted</th>
                <th>Rating</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($technicians as $technician)
                <tr>
                    <td>{{ $technician->first_name }} {{ $technician->last_name }} / {{ $technician->employee_id }}</td>
                    <td>{{ $technician->tickets_assigned ?? 'None' }}</td>
                    <td>{{ $technician->tickets_solved ?? 'None' }}</td>
                    <td>{{ $technician->avg_resolution_time ? $technician->avg_resolution_time . ' hours' : 'None' }}</td>
                    <td>{{ $technician->endorsed_tickets ?? 'None' }}</td>
                    <td>{{ $technician->pull_out ?? 'None' }}</td>
                    <td>{{ $technician->technical_reports ?? 'None' }}</td>
                    <td>{{ $technician->ratings->avg('rating') ?? 'No Rating' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
