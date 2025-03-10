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
            case 'deployment':
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
                    <th>Date and Time</th>
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
                        <td>{{ $ticket->created_at }}</td>
                        <td>{{ ucwords(strtolower($ticket->name)) }}</td>
                        <td>{{ ucwords(strtolower($ticket->department)) }}</td>
                        <td>{{ ucwords(strtolower($ticket->concern)) }}</td>
                        <td class="{{ getPriorityClass($ticket->priority) }}">{{ ucfirst($ticket->priority) }}</td>
                        <td class="{{ getStatusClass($ticket->status) }}">
                        @if ($ticket->isRemarksDone && !$ticket->isApproved && $ticket->existsInModels)
                            <span style="color: red; font-weight: bold; font-size:15px;">Processing</span>
                        @elseif (($ticket->status !== 'completed' && $ticket->status !== 'in-progress'&& $ticket->status !== 'endorsed')  && $ticket->isRemarksDone && !$ticket->isApproved && !$ticket->formfillup)
                            <span style="color: red; font-weight: bold; font-size:15px;">Processing</span>
                        @elseif (($ticket->status !== 'completed' && $ticket->status !== 'in-progress')  && $ticket->isRemarksDone && !$ticket->isApproved && $ticket->formfillup)
                            <span style="color: red; font-weight: bold; font-size:15px;">Processing</span>
                        @elseif (
                                ($ticket->isRemarksDone && $ticket->isApproved && $ticket->existsInModels && $ticket->formfillup && !$ticket->isRated && $ticket->status !== 'pull-out') 
                                || 
                                ($ticket->isRemarksDone && $ticket->status === 'completed' && !$ticket->isRated)
                                ||
                                ($ticket->isRemarksDone && $ticket->isApproved && $ticket->existsInModels && $ticket->formfillup && !$ticket->isRated && $ticket->status === 'pull-out' && $ticket->isRepaired) 
                            )
                            <span style="color: orange; font-weight: bold; font-size:15px;">Please Rate Technical Service</span>
                        @elseif ($ticket->isRemarksDone && $ticket->isApproved && $ticket->existsInModels && $ticket->formfillup && !$ticket->isRated && $ticket->status === 'pull-out' && !$ticket->isRepaired) 
                            <span style="color: red; font-weight: bold; font-size:15px;">Processing</span>
                            @else
                                @switch($ticket->status)
                                    @case('pull-out')
                                    @case('deployment')
                                    @case('endorsed')
                                    @case('technical-report')
                                        Closed
                                        @break
                                    @default
                                        {{ ucfirst($ticket->status) }}
                                @endswitch
                            @endif
                    </td>
                        <td>
                            <div class="button-container">
                                <button class="action-button" onclick="showTicketDetails('{{ $ticket->control_no }}')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            @if ($ticket->status == 'technical-report' && $ticket->isRemarksDone && $ticket->isApproved && $ticket->existsInModels && $ticket->formfillup && $ticket->isRated)
                                <button class="action-button" onclick="checkTechnicalReport('{{ $ticket->control_no }}')">
                                    <i class="fas fa-file-alt"></i>
                                </button>
                            @elseif ($ticket->status == 'endorsed' && $ticket->isRemarksDone && $ticket->isApproved && $ticket->existsInModels && $ticket->formfillup && $ticket->isRated)
                                    <button class="action-button" onclick="openViewEndorsementModal('{{ $ticket->control_no }}')">
                                        <i class="fas fa-book"></i>
                                    </button>
                            @elseif ($ticket->status == 'pull-out' && $ticket->isRemarksDone && $ticket->isApproved && $ticket->existsInModels && $ticket->formfillup && $ticket->isRated) 
                                <button class="action-button" onclick="checkAndOpenPopup('{{ $ticket->control_no }}')">
                                    <i class="fas fa-laptop"></i>
                                </button>
                            @elseif ($ticket->status == 'deployment' && $ticket->isRemarksDone && $ticket->isApproved && $ticket->existsInModels && $ticket->formfillup && $ticket->isRated)               
                                <button class="action-button" onclick="openDeploymentView('{{ $ticket->control_no }}')">
                                    <i class="fas fa-laptop"></i>
                                </button>
                            @endif


                                @if (!$ticket->isRated)
                                    <button class="action-button" id="chat-btn-{{ $ticket->control_no }}" onclick="sendMessage('{{ $ticket->control_no }}')">
                                            <i class="fas fa-comments"></i> 
                                    </button>
                                @endif
                                @if (
                                ($ticket->isRemarksDone && $ticket->isApproved && $ticket->existsInModels && $ticket->formfillup && !$ticket->isRated && $ticket->status !== 'pull-out') 
                                || 
                                ($ticket->isRemarksDone && $ticket->status === 'completed' && !$ticket->isRated)
                                ||
                                ($ticket->isRemarksDone && $ticket->isApproved && $ticket->existsInModels && $ticket->formfillup && !$ticket->isRated && $ticket->status === 'pull-out' && $ticket->isRepaired) 
                            )
                                    <button class="status-button btn btn-primary" onclick="showRating('{{ $ticket->control_no }}', '{{ $ticket->technical_support_id }}', '{{ $ticket->technical_support_name }}')">
                                        <i class="fas fa-star"></i> 
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
                        <a href="{{ $tickets->previousPageUrl() }}" data-page="{{ $tickets->currentPage() - 1 }}">&lsaquo;</a>
                    @endif
                </li>

                {{-- Page Numbers (show current page, one before, one after) --}}
                @for ($i = max(1, $tickets->currentPage() - 1); $i <= min($tickets->lastPage(), $tickets->currentPage() + 1); $i++)
                    <li class="{{ $i == $tickets->currentPage() ? 'active' : '' }}">
                        @if ($i == $tickets->currentPage())
                            <span>{{ $i }}</span>
                        @else
                            <a href="{{ $tickets->url($i) }}" data-page="{{ $i }}">{{ $i }}</a>
                        @endif
                    </li>
                @endfor

                {{-- Ellipsis for large page numbers --}}
                @if ($tickets->currentPage() < $tickets->lastPage() - 2)
                    <li><span>...</span></li>
                    <li><a href="{{ $tickets->url($tickets->lastPage()) }}" data-page="{{ $tickets->lastPage() }}">{{ $tickets->lastPage() }}</a></li>
                @endif

                {{-- Next Page Link --}}
                <li class="{{ $tickets->hasMorePages() ? '' : 'disabled' }}">
                    @if ($tickets->hasMorePages())
                        <a href="{{ $tickets->nextPageUrl() }}" data-page="{{ $tickets->currentPage() + 1 }}">&rsaquo;</a>
                    @else
                        <span>&rsaquo;</span>
                    @endif
                </li>
            </ul>
        </div>
    @endif
</div>

@include('modals.rating')
@include('modals.view')
@include('modals.remarks')
@include('modals.status_change')
@include('modals.new_device_deployment')
@include('modals.view_deployment')
@include('modals.technical_report')
@include('modals.view_endorsement')
@include('modals.view_device')
@include('modals.technical_report_view')
@include('modals.new_device_form')
@include('modals.endorsement')


<script src="{{ asset('js/Ticket_Components_Script.js') }}" defer></script>
<script>
 // Show modal function
 function showRating(controlNo, techId, techName) {
        // Set the ticket and technical support details
        document.getElementById("ticketControlNo").textContent = controlNo;
        document.getElementById("technicalSupportName").textContent = techName;
        document.getElementById("technicalSupportName").setAttribute("data-tech-id", techId);

        // Reset the star rating
        resetStars();

        // Show the modal
        document.getElementById("modalOverlay").style.display = "block";
        document.getElementById("ratingModal").style.display = "block";

        // Initialize star rating logic
        initializeStarRating();
    }


function hideRating(ticketId) {
    document.getElementById("modalOverlay").style.display = "none";
    document.getElementById("ratingModal").style.display = "none";
}

function sendMessage(ticketId) {
    console.log('Ticket ID:', ticketId); // Debugging: Check the ticketId value

    fetch(`/send-message/${ticketId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
    })
    .then(response => {
        if (!response.ok) {
            return response.text().then(text => { throw new Error(text) });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert('Message sent successfully!');
            if (data.redirectUrl) {
                window.location.href = data.redirectUrl;
            }
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Check the console for details.');
    });
}
</script>
