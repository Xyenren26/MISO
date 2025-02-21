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
            case 'pull-out':
                return 'status-pull-out';     

            case 'in-progress':
                return 'status-in-progress';
            case 'technical-report':
                return 'status-technical-report';
            default:
                return '';
        }
    }
@endphp
<link rel="stylesheet" href="{{ asset('css/ticket_components_Style.css') }}">
<meta name="csrf-token" content="{{ csrf_token() }}">

<div id="ticket-container">
    @if ($tickets->isNotEmpty())
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
                        <td>{{ ucwords(strtolower($ticket->name)) }}</td>
                        <td>{{ ucwords(strtolower($ticket->department)) }}</td>
                        <td>{{ ucwords(strtolower($ticket->concern)) }}</td>
                        <td class="{{ getPriorityClass($ticket->priority) }}">{{ ucfirst($ticket->priority) }}</td>
                        <td class="{{ getStatusClass($ticket->status) }}">{{ ucfirst($ticket->status) }}</td>
                        <td>
                            <div class="button-container">
                                <button class="action-button" onclick="showTicketDetails('{{ $ticket->control_no }}')">
                                    <i class="fas fa-eye"></i>
                                </button>
                                @if ($ticket->status == 'pull-out' && \App\Models\ServiceRequest::where('ticket_id', $ticket->control_no)->exists())
                                    <button class="action-button" onclick="trackDevice('{{ $ticket->control_no }}')">
                                        <i class="fas fa-map-marker-alt"></i> <!-- Track Icon -->
                                    </button>
                                @endif


                               <!-- Tracking Status Modal -->
                                <div id="trackModal" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 20px; border-radius: 10px; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);">
                                    <h3>Device Tracking</h3>
                                    <p id="trackingStatus">Fetching status...</p>
                                    <button class="Trackbutton" onclick="closeTrackModal()">Close</button>
                                </div>


                                
                                @if (!$ticket->isRemarksDone)
                                    <button class="action-button" id="chat-btn-{{ $ticket->control_no }}">
                                        <i class="fas fa-comments"></i> 
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-records">NO RECORDS FOUND</div>
    @endif
</div>


<div class="pagination-container">
    <div class="results-count">
        @if ($tickets->count() > 0)
            Showing {{ $tickets->firstItem() }} to {{ $tickets->lastItem() }} of {{ $tickets->total() }} results
        @else
            Showing 1 to 0 of 0 results
        @endif
    </div>

    @if ($tickets->hasPages()) 
        <div class="pagination-buttons">
            <ul>
                {{-- Previous Page Link --}}
                <li class="{{ $tickets->onFirstPage() ? 'disabled' : '' }}">
                    @if ($tickets->onFirstPage())
                        <span>&lsaquo;</span>
                    @else
                        <a href="{{ $tickets->previousPageUrl() }}" data-page="{{ $tickets->currentPage() - 1 }}">&lsaquo; </a>
                    @endif
                </li>

                {{-- Page Numbers --}}
                @foreach ($tickets->links()->elements as $element)
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            <li class="{{ $page == $tickets->currentPage() ? 'active' : '' }}">
                                @if ($page == $tickets->currentPage())
                                    <span>{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}" data-page="{{ $page }}">{{ $page }}</a>
                                @endif
                            </li>
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                <li class="{{ $tickets->hasMorePages() ? '' : 'disabled' }}">
                    @if ($tickets->hasMorePages())
                        <a href="{{ $tickets->nextPageUrl() }}" data-page="{{ $tickets->currentPage() + 1 }}"> &rsaquo;</a>
                    @else
                        <span> &rsaquo;</span>
                    @endif
                </li>
            </ul>
        </div>
    @endif
</div>

@include('modals.view')

<script src="{{ asset('js/Ticket_Components_Script.js') }}" defer></script>
<script>
function trackDevice(ticketId) {
    fetch(`/track-device-status/${ticketId}`)
        .then(response => response.json()) // Ensure JSON response
        .then(data => {
            if (data.service_type === 'pull_out') {
                let statusText = data.status === 'in-repairs' 
                    ? '🛠 Your device is currently in repairs.' 
                    : '✅ Your device has been repaired.';
                
                document.getElementById('trackingStatus').textContent = statusText;
                document.getElementById('trackModal').style.display = 'block';
            } else {
                alert('The devices is currently processing.');
            }
        })
        .catch(error => {
            console.error('Error fetching status:', error);
            alert('Something went wrong. Please check the console for details.');
        });
}


// Close Tracking Modal
function closeTrackModal() {
    document.getElementById('trackModal').style.display = 'none';
}
</script>



