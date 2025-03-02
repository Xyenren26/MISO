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
                    <td class="{{ getStatusClass($ticket->status) }}">
                        @if ($ticket->isRemarksDone && !$ticket->isApproved && $ticket->existsInModels)
                            <span style="color: red; font-weight: bold; font-size:15px;">Waiting for Admin Approval</span>
                        @elseif (($ticket->status !== 'completed' && $ticket->status !== 'in-progress'&& $ticket->status !== 'endorsed')  && $ticket->isRemarksDone && !$ticket->isApproved && !$ticket->formfillup)
                            <span style="color: red; font-weight: bold; font-size:15px;">Form is Required to fill up</span>
                        @elseif (($ticket->status !== 'completed' && $ticket->status !== 'in-progress')  && $ticket->isRemarksDone && !$ticket->isApproved && $ticket->formfillup)
                            <span style="color: red; font-weight: bold; font-size:15px;">Form is Required to fill up</span>
                        @elseif (
                                ($ticket->isRemarksDone && $ticket->isApproved && $ticket->existsInModels && $ticket->formfillup && !$ticket->isRated && $ticket->status !== 'pull-out') 
                                || 
                                ($ticket->isRemarksDone && $ticket->status === 'completed' && !$ticket->isRated)
                                ||
                                ($ticket->isRemarksDone && $ticket->isApproved && $ticket->existsInModels && $ticket->formfillup && !$ticket->isRated && $ticket->status === 'pull-out' && $ticket->isRepaired) 
                            )
                            <span style="color: orange; font-weight: bold; font-size:15px;">User is rating your service</span>
                        @elseif ($ticket->isRemarksDone && $ticket->isApproved && $ticket->existsInModels && $ticket->formfillup && !$ticket->isRated && $ticket->status === 'pull-out' && !$ticket->isRepaired) 
                            <span style="color: red; font-weight: bold; font-size:15px;">Mark it as repaired</span>
                        @else
                            {{ $ticket->status === 'pull-out' ? 'Equipment Handover' : ucfirst($ticket->status) }}
                        @endif
                    </td>


                    <td>
                        <div class="button-container">
                            <button class="action-button" onclick="showTicketDetails('{{ $ticket->control_no }}')">
                                <i class="fas fa-eye"></i>
                            </button>

                            @if ($ticket->status == 'technical-report')
                                <button class="action-button" onclick="checkTechnicalReport('{{ $ticket->control_no }}')">
                                    <i class="fas fa-file-alt"></i>
                                </button>
                            @elseif ($ticket->status == 'endorsed')
                                @php
                                    $endorsement = \App\Models\Endorsement::where('ticket_id', $ticket->control_no)->first();
                                    $isSubmitted = $endorsement && $endorsement->endorsed_to;
                                @endphp

                                @if ($isSubmitted)
                                    <button class="action-button" onclick="openViewEndorsementModal('{{ $ticket->control_no }}')">
                                        <i class="fas fa-book"></i>
                                    </button>
                                @else
                                    <button class="action-button" onclick="openEndorsementModal('{{ $ticket->control_no }}')">
                                        <i class="fas fa-file-alt"></i>
                                    </button>
                                @endif
                            @elseif ($ticket->status == 'pull-out')
                                <button class="action-button" onclick="checkAndOpenPopup('{{ $ticket->control_no }}')">
                                    <i class="fas fa-laptop"></i>
                                </button>
                            @elseif ($ticket->status == 'deployment')                
                                <button class="action-button" onclick="checkDeploymentAndOpenPopup('{{ $ticket->control_no }}')">
                                    <i class="fas fa-laptop"></i>
                                </button>
                            @else
                                @if (!$ticket->isRemarksDone)
                                    <button class="action-button" onclick="openRemarksModal('{{ $ticket->control_no }}')" 
                                            id="remarks-btn-{{ $ticket->control_no }}">
                                        <i class="fas fa-sticky-note"></i>
                                    </button>
                                @endif
                            @endif

                            @if (!$ticket->isRemarksDone)
                                <button class="action-button" id="chat-btn-{{ $ticket->control_no }}" onclick="sendMessageTechnical('{{ $ticket->control_no }}')">
                                        <i class="fas fa-comments"></i> 
                                </button>

                                    @if (!$ticket->isAssistDone)
                                        <button class="action-button" onclick="showAssistModal('{{ $ticket->control_no }}')" 
                                                id="assist-btn-{{ $ticket->control_no }}">
                                            <i class="fas fa-handshake"></i> 
                                        </button>
                                    @endif
                            @endif
                            @if ($ticket->isRemarksDone && !$ticket->isApproved && auth()->user()->account_type === 'administrator' && $ticket->existsInModels)
                                <button class="action-button" onclick="approveTicket('{{ $ticket->control_no }}')">
                                    <i class="fas fa-check-circle"></i>
                                </button>
                            @endif
                            
                            @if (
                                Auth::user()->employee_id === $ticket->technical_support_id &&  // Only assigned technical support
                                $ticket->isRemarksDone && 
                                $ticket->isApproved && 
                                $ticket->existsInModels && 
                                $ticket->formfillup && 
                                !$ticket->isRated && 
                                $ticket->status === 'pull-out' && 
                                !$ticket->isRepaired
                            )
                                <button class="status-button" 
                                    onclick="openConfirmationModal('{{ $ticket->serviceRequest->form_no ?? '' }}')"
                                    @if(optional($ticket->serviceRequest)->status === 'repaired') disabled @endif>
                                    <i class="fas fa-sync-alt"></i>
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

@include('modals.view')
@include('modals.assist')
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
    function sendMessageTechnical(ticketId) {
    console.log('Ticket ID:', ticketId); // Debugging: Check the ticketId value

    fetch(`/send-message-technical/${ticketId}`, {
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

