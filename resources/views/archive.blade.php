<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechTrack Archive</title>
    <link rel="icon" href="{{ asset('images/Systembrowserlogo.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('css/Archive_Style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<div class="container">
    @include('components.sidebar')

    <div class="main-content">
        @include('components.navbar')

        <section class="audit-logs">
            <h1>Archived Tickets</h1>

            <div class="filters">
                <form method="GET" action="{{ route('archive.index') }}">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by Control No, Name, or Department">
                    <button type="submit">Apply Filters</button>
                </form>
                <button id="exportCsv" class="export-button">
                    <i class="fas fa-file-csv"></i> Export to CSV
                </button>
            </div>

            <table class="audit-logs-table">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="checkAll"></th>
                        <th>Control No</th>
                        <th>Employee ID</th>
                        <th>Name</th>
                        <th>Department</th>
                        <th>Priority</th>
                        <th>Concern</th>
                        <th>Remarks</th>
                        <th>Technical Support ID</th>
                        <th>Technical Support Name</th>
                        <th>Status</th>
                        <th>Time In</th>
                        <th>Update At</th>
                        <th>Archived At</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($archivedTickets as $ticket)
                    <tr>
                        <td><input type="checkbox" class="ticket-checkbox" value="{{ $ticket->control_no }}"></td>
                        <td>{{ ucfirst($ticket->control_no) }}</td>
                        <td>{{ ucfirst($ticket->employee_id) }}</td>
                        <td>{{ ucfirst($ticket->name) }}</td>
                        <td>{{ ucfirst($ticket->department) }}</td>
                        <td>{{ ucfirst($ticket->priority) }}</td>
                        <td>{{ ucfirst($ticket->concern) }}</td>
                        <td>{{ ucfirst($ticket->remarks ?? 'N/A') }}</td>
                        <td>{{ ucfirst($ticket->technical_support_id ?? 'N/A') }}</td>
                        <td>{{ ucfirst($ticket->technical_support_name ?? 'N/A') }}</td>
                        <td>{{ ucfirst($ticket->status) }}</td>
                        <td>{{ $ticket->time_in ? ucfirst($ticket->time_in) : 'N/A' }}</td>
                        <td>{{ $ticket->time_out ? ucfirst($ticket->updated_at) : 'N/A' }}</td>
                        <td>{{ $ticket->archived_at ? ucfirst($ticket->archived_at) : 'N/A' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="14" class="no-results">No archived tickets found.</td>
                    </tr>
                @endforelse

                </tbody>
            </table>

            <div class="pagination-container">
                <div class="results-count">
                    @if ($archivedTickets->count() > 0)
                        Showing {{ $archivedTickets->firstItem() }} to {{ $archivedTickets->lastItem() }} of {{ $archivedTickets->total() }} results
                    @else
                        Showing 1 to 0 of 0 results
                    @endif
                </div>

                @if ($archivedTickets->hasPages()) 
                    <div class="pagination-buttons">
                        <ul>
                            <li class="{{ $archivedTickets->onFirstPage() ? 'disabled' : '' }}">
                                @if ($archivedTickets->onFirstPage())
                                    <span>&lsaquo;</span>
                                @else
                                    <a href="{{ $archivedTickets->previousPageUrl() }}">&lsaquo;</a>
                                @endif
                            </li>
                            @for ($i = max(1, $archivedTickets->currentPage() - 1); $i <= min($archivedTickets->lastPage(), $archivedTickets->currentPage() + 1); $i++)
                                <li class="{{ $i == $archivedTickets->currentPage() ? 'active' : '' }}">
                                    @if ($i == $archivedTickets->currentPage())
                                        <span>{{ $i }}</span>
                                    @else
                                        <a href="{{ $archivedTickets->url($i) }}">{{ $i }}</a>
                                    @endif
                                </li>
                            @endfor
                            <li class="{{ $archivedTickets->hasMorePages() ? '' : 'disabled' }}">
                                @if ($archivedTickets->hasMorePages())
                                    <a href="{{ $archivedTickets->nextPageUrl() }}">&rsaquo;</a>
                                @else
                                    <span>&rsaquo;</span>
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
document.getElementById('checkAll').addEventListener('change', function() {
    let checkboxes = document.querySelectorAll('.ticket-checkbox');
    checkboxes.forEach(checkbox => checkbox.checked = this.checked);
});

document.getElementById('exportCsv').addEventListener('click', function() {
    let selectedTickets = [];
    document.querySelectorAll('.ticket-checkbox:checked').forEach(checkbox => {
        selectedTickets.push(checkbox.value);
    });

    if (selectedTickets.length === 0) {
        alert('Please select at least one ticket to export.');
        return;
    }

    let url = "{{ route('archive.export') }}?tickets=" + selectedTickets.join(',');
    window.location.href = url;
});
</script>
</body>
</html>
