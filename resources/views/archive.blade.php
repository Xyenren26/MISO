<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechTrack Archive</title>
    <link rel="icon" href="{{ asset('images/Systembrowserlogo.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('css/Audit_Logs_Style.css') }}">
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
            </div>

            <table class="audit-logs-table">
                <thead>
                    <tr>
                        <th>Control No</th>
                        <th>Name</th>
                        <th>Department</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Archived At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($archivedTickets as $ticket)
                        <tr>
                        <td>{{ ucfirst($ticket->control_no) }}</td>
                        <td>{{ ucfirst($ticket->name) }}</td>
                        <td>{{ ucfirst($ticket->department) }}</td>
                        <td>{{ ucfirst($ticket->priority) }}</td>
                        <td>{{ ucfirst($ticket->status) }}</td>
                        <td>{{ ucfirst($ticket->archived_at) }}</td>
                        </tr>
                    @endforeach
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
</body>
</html>