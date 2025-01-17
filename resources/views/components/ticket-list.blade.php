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
        @foreach ($tickets as $ticket)
            <tr>
                <td>{{ $ticket->control_no }}</td>
                <td>{{ $ticket->name }}</td>
                <td>{{ $ticket->department }}</td>
                <td>{{ $ticket->concern }}</td>
                <td>{{ ucfirst($ticket->priority) }}</td>
                <td>{{ ucfirst($ticket->status) }}</td>
                <td>
                    <button class="action-button">View</button>
                    <button class="action-button">Remarks</button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
