<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit Logs</title>
    <link rel="stylesheet" href="{{ asset('css/Audit_Logs_Style.css') }}">
    <!-- Include Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<div class="container">
    <!-- Sidebar -->
    @include('components.sidebar')

    <!-- Main Content Area -->
    <div class="main-content">
        <!-- Navbar -->
        @include('components.navbar')

        <!-- Audit Logs Section -->
        <section class="audit-logs">
            <h1>Audit Logs</h1>

            <!-- Filters Section -->
            <div class="filters">
                <form action="{{ route('audit_logs') }}" method="GET">
                    <label for="date-range">Date Range:</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" placeholder="Start Date">
                    <input type="date" name="end_date" value="{{ request('end_date') }}" placeholder="End Date">

                    <label for="action-type">Action Type:</label>
                    <select name="action_type">
                        <option value="">All</option>
                        <option value="created" {{ request('action_type') == 'created' ? 'selected' : '' }}>Created</option>
                        <option value="updated" {{ request('action_type') == 'updated' ? 'selected' : '' }}>Updated</option>
                        <option value="endorsed" {{ request('action_type') == 'endorsed' ? 'selected' : '' }}>Endorsed</option>
                        <option value="unrepairable" {{ request('action_type') == 'unrepairable' ? 'selected' : '' }}>Marked Unrepairable</option>
                    </select>

                    <label for="user">User:</label>
                    <select name="user">
                        <option value="">All</option>
                        <option value="end_user" {{ request('user') == 'end_user' ? 'selected' : '' }}>End User</option>
                        <option value="technical_support" {{ request('user') == 'technical_support' ? 'selected' : '' }}>Technical Support</option>
                    </select>


                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by Ticket ID, Device ID, or User Name">
                    <button type="submit">Apply Filters</button>
                </form>
            </div>

            <!-- Audit Logs Table -->
            <table class="audit-logs-table">
                <thead>
                    <tr>
                        <th>Date & Time</th>
                        <th>Action Performed</th>
                        <th>Performed By</th>
                        <th>Ticket ID / Device ID</th>
                        <th>Remarks / Details</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($auditLogs as $log) <!-- Use $auditLogs instead of $logs -->
                        <tr>
                            <td>{{ $log->date_time }}</td>
                            <td>
                                <span class="action-icon {{ $log->action_type }}">
                                    @if ($log->action_type == 'created')
                                        🟢
                                    @elseif ($log->action_type == 'updated')
                                        🔵
                                    @elseif ($log->action_type == 'endorsed')
                                        🟠
                                    @elseif ($log->action_type == 'unrepairable')
                                        🔴
                                    @endif
                                </span>
                                {{ ucfirst($log->action_type) }}
                            </td>
                            <td>{{ $log->user ? $log->user->first_name . ' ' . $log->user->last_name : 'N/A' }}</td>
                            <td>{{ $log->ticket_or_device_id }}</td>
                            <td>{{ $log->remarks }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="pagination flex justify-center mt-4 text-sm">
                {{ $auditLogs->links() }}
            </div>

        </section>
    </div>
</div>

<script src="{{ asset('js/Audit_logs_Script.js') }}"></script>
</body>
</html>
