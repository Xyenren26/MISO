<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechTrack Audit Logs</title>
    <link rel="icon" href="{{ asset('images/Systembrowserlogo.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('css/Audit_Logs_Style.css') }}">
    <!-- Include Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<div class="container">
    <!-- Sidebar -->
    @include('components.sidebar')
    @include('components.navbar')
    <!-- Main Content Area -->
    <div class="main-content">

        <!-- Audit Logs Section -->
        <section class="audit-logs">
            <h1>Audit Logs</h1>

            <!-- Filters Section -->
            <div class="filters">
                <form action="{{ route('audit_logs') }}" method="GET">
                    <label for="date-range">Date Range:</label>
                    <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" placeholder="Start Date">
                    <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" placeholder="End Date">

                    <label for="action-type">Action Type:</label>
                    <select name="action_type">
                        <option value="">All</option>
                        <option value="created" {{ request('action_type') == 'created' ? 'selected' : '' }}>Created</option>
                        <option value="updated" {{ request('action_type') == 'updated' ? 'selected' : '' }}>Updated</option>
                        <option value="endorsed" {{ request('action_type') == 'archived' ? 'selected' : '' }}>Archived</option>
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
                        <th>Ticket ID / Form No.</th>
                        <th>Remarks / Details</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($auditLogs as $log) <!-- Use $auditLogs instead of $logs -->
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
                                    @elseif ($log->action_type == 'archived')
                                        🔴
                                    @endif
                                </span>
                                {{ ucfirst($log->action_type) }}
                            </td>
                            <td>{{ $log->user ? $log->user->first_name . ' ' . $log->user->last_name : 'N/A' }}</td>
                            <td>{{ $log->ticket_or_device_id }}</td>
                            <td>{{ $log->remarks }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="no-results">No archived tickets found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="pagination-container">
                <div class="results-count">
                    @if ($auditLogs->count() > 0)
                        Showing {{ $auditLogs->firstItem() }} to {{ $auditLogs->lastItem() }} of {{ $auditLogs->total() }} results
                    @else
                        Showing 1 to 0 of 0 results
                    @endif
                </div>

                @if ($auditLogs->hasPages()) 
                    <div class="pagination-buttons">
                        <ul class="flex space-x-2">
                            {{-- Previous Page Link --}}
                            <li class="{{ $auditLogs->onFirstPage() ? 'disabled opacity-50' : '' }}">
                                @if ($auditLogs->onFirstPage())
                                    <span class="px-3 py-1">&lsaquo;</span>
                                @else
                                    <a href="{{ $auditLogs->previousPageUrl() }}" data-page="{{ $auditLogs->currentPage() - 1 }}" class="px-3 py-1 border rounded">&lsaquo;</a>
                                @endif
                            </li>

                            {{-- Page Numbers (show current page, one before, one after) --}}
                            @for ($i = max(1, $auditLogs->currentPage() - 1); $i <= min($auditLogs->lastPage(), $auditLogs->currentPage() + 1); $i++)
                                <li class="{{ $i == $auditLogs->currentPage() ? 'active font-bold' : '' }}">
                                    @if ($i == $auditLogs->currentPage())
                                        <span class="px-3 py-1 border rounded bg-gray-200">{{ $i }}</span>
                                    @else
                                        <a href="{{ $auditLogs->url($i) }}" data-page="{{ $i }}" class="px-3 py-1 border rounded">{{ $i }}</a>
                                    @endif
                                </li>
                            @endfor

                            {{-- Ellipsis for large page numbers --}}
                            @if ($auditLogs->currentPage() < $auditLogs->lastPage() - 2)
                                <li><span>...</span></li>
                                <li><a href="{{ $auditLogs->url($auditLogs->lastPage()) }}" data-page="{{ $auditLogs->lastPage() }}" class="px-3 py-1 border rounded">{{ $auditLogs->lastPage() }}</a></li>
                            @endif

                            {{-- Next Page Link --}}
                            <li class="{{ $auditLogs->hasMorePages() ? '' : 'disabled opacity-50' }}">
                                @if ($auditLogs->hasMorePages())
                                    <a href="{{ $auditLogs->nextPageUrl() }}" data-page="{{ $auditLogs->currentPage() + 1 }}" class="px-3 py-1 border rounded">&rsaquo;</a>
                                @else
                                    <span class="px-3 py-1">&rsaquo;</span>
                                @endif
                            </li>
                        </ul>
                    </div>
                @endif
            </div>


        </section>
    </div>
</div>

<script src="{{ asset('js/Audit_logs_Script.js') }}"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    let today = new Date().toISOString().split("T")[0];

    let startDate = document.getElementById("start_date");
    let endDate = document.getElementById("end_date");

    // Disable future dates for start_date and end_date
    startDate.setAttribute("max", today);
    endDate.setAttribute("max", today);

    // Ensure end_date is not earlier than start_date
    startDate.addEventListener("change", function () {
        endDate.setAttribute("min", startDate.value);
    });

    endDate.addEventListener("change", function () {
        if (endDate.value < startDate.value) {
            endDate.value = startDate.value; // Reset end_date if it's before start_date
        }
    });
});
</script>
</body>
</html>
