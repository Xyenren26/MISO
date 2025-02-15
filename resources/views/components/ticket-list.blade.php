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
                <td>{{ ucfirst(strtolower($ticket->concern)) }}</td>

                    <td class="{{ getPriorityClass($ticket->priority) }}">{{ ucfirst($ticket->priority) }}</td>
                    <td class="{{ getStatusClass($ticket->status) }}">{{ ucfirst($ticket->status) }}</td>
                    <td>
                    <div class="button-container">
                        <!-- View Ticket Details -->
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
                                    <i class="fas fa-folder-open"></i>
                                </button>
                            @endif
                        @elseif ($ticket->status == 'pull-out')
                            <button class="action-button" onclick="checkAndOpenPopup('{{ $ticket->control_no }}')">
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
                            <button class="action-button" id="chat-btn-{{ $ticket->control_no }}">
                                <i class="fas fa-comments"></i> 
                            </button>

                            @if (!$ticket->isAssistDone)
                                <button class="action-button" onclick="showAssistModal('{{ $ticket->control_no }}')" 
                                        id="assist-btn-{{ $ticket->control_no }}">
                                    <i class="fas fa-handshake"></i> 
                                </button>
                            @endif
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

@include('modals.view')
@include('modals.assist')
@include('modals.remarks')
@include('modals.technical_report')
@include('modals.view_endorsement')
@include('modals.view_device')
@include('modals.technical_report_view')
@include('modals.new_device_form')
@include('modals.endorsement')

<script src="{{ asset('js/Ticket_Components_Script.js') }}" defer></script>


