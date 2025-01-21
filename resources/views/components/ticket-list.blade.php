@php
    function getPriorityClass($priority) {
        switch (strtolower($priority)) {
            case 'urgent':
                return 'priority-urgent';
            case 'semi-urgent':
                return 'priority-semi-urgent';
            case 'non-urgent':
                return 'priority-non-urgent';
            default:
                return '';
        }
    }

    function getStatusClass($status) {
        switch (strtolower($status)) {
            case 'endorsed':
                return 'status-endorsed';
            case 'completed':
                return 'status-completed';
            case 'in-progress':
                return 'status-in-progress';
            case 'technical-report':
                return 'status-technical-report';
            default:
                return '';
        }
    }
@endphp

<table class="tickets-table">
    <thead>
        <tr>
            <th>Control No</th>
            <th>Name</th>
            <th>Department</th>
            <th>Concern</th>
            <th>Priority</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($tickets as $ticket)
            <tr>
                <td>{{ $ticket->control_no }}</td>
                <td>{{ $ticket->name }}</td>
                <td>{{ $ticket->department }}</td>
                <td>{{ $ticket->concern }}</td>
                <td class="{{ getPriorityClass($ticket->priority) }}">{{ ucfirst($ticket->priority) }}</td>
                <td class="{{ getStatusClass($ticket->status) }}">{{ ucfirst($ticket->status) }}</td>
                <td>
                    <button class="action-button">View</button>
                    <button class="action-button">Remarks</button>
                </td>
            </tr>
        @empty
            <tr class="no-records-row">
                <td colspan="7">
                    <div class="no-records">NO RECORDS FOUND</div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

<!-- Pagination Section -->
<div class="pagination-container">
    @if ($tickets->hasPages())
        <div class="pagination-buttons">
            {{ $tickets->appends(request()->input())->links('pagination::bootstrap-4') }}
        </div>
        <div class="results-count">
            Showing {{ $tickets->firstItem() }} to {{ $tickets->lastItem() }} of {{ $tickets->total() }} results
        </div>
    @endif
</div>
