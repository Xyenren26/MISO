<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechTrack Report and Analytics</title>
    <link rel="icon" href="{{ asset('images/Systembrowserlogo.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('css/Report_Style.css') }}">

    <!-- Include Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Include Chart.js for graph rendering -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Modal CSS -->
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fff;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 1000px;
            border-radius: 10px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .exportButton{
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        /* Responsive Design for Table */
        @media (max-width: 768px) {
            .performance-details table {
                font-size: 14px;
            }

            .performance-details th,
            .performance-details td {
                padding: 8px;
            }
        }

        /* Tablet & Smaller Screens */
        @media (max-width: 1024px) {
            .metrics-cards {
                grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); /* Smaller grid size */
            }

            .charts-container {
                flex-direction: column; /* Stack charts vertically */
                gap: 20px;
            }

            .performance-details {
                padding: 15px;
            }
        }

        /* Smartphones (≤ 768px) */
        @media (max-width: 768px) {
            
            .metrics-cards {
                display: grid;
                grid-template-columns: repeat(2, 1fr); /* Ensures exactly 2 columns */
                gap: 10px;
                width: 90%; /* Adjusts width to fit better on smartphones */
                margin: 0 auto; /* Centers the grid */
            }
            .metric-card {
                width: 100px;
            }


            .charts-container {
                flex-direction: column; /* Stack charts vertically */
                gap: 15px;
                width: 95%; /* Adjust width for better spacing */
                margin: 0 auto;
            }

            .performance-details {
                padding: 10px;
                width: 90%; /* Ensures better fit */
                margin: 0 auto; /* Centers the container */
            }

            /* Hide specific elements on smartphones */
            .hide-on-mobile,
            .donut-chart,
            .line-chart {
                display: none !important;
            }
        }

        /* Extra Small Screens (≤ 480px) */
        @media (max-width: 480px) {
            
            .metrics-cards {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 8px;
                width: 95%; /* Makes it more compact for smaller screens */
                margin: 0 auto;
            }

            .charts-container {
                width: 100%; /* Maximizes space for charts */
                gap: 10px;
            }

            .performance-details {
                width: 95%;
                padding: 8px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    @include('components.sidebar')
    <div class="main-content">
        @include('components.navbar')

        <!-- Key Metrics Summary Section -->
        <div class="key-metrics-summary">
            <h2>Key Metrics Summary (KPIs)</h2>
            <div class="metrics-cards">
                @php
                    $metrics = [
                        ['icon' => 'fas fa-ticket-alt', 'title' => 'Pending Tickets', 'count' => $pendingTickets],
                        ['icon' => 'fas fa-check-circle', 'title' => 'Closed Tickets', 'count' => $solvedTickets],
                        ['icon' => 'fas fa-share', 'title' => 'Endorsed Tickets', 'count' => $endorsedTickets],
                        ['icon' => 'fas fa-file-alt', 'title' => 'Technical Reports', 'count' => $technicalReports],
                    ];
                @endphp
                @foreach ($metrics as $metric)
                    <div class="metric-card">
                        <i class="{{ $metric['icon'] }}"></i>
                        <h3>{{ $metric['title'] }}</h3>
                        <p>{{ $metric['count'] ?? 0 }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Date Picker for Filtering -->
        <div class="date-filter">
            <label for="datePicker">Select Date:</label>
            <input type="month" id="datePicker" max="{{ date('Y-m') }}" value="{{ $selectedDate }}">
        </div>

        <!-- Metrics Charts -->
        <div class="charts-container">
            <div class="donut-chart"><canvas id="combinedMetricsChart"></canvas></div>
            <div class="line-chart"><canvas id="technicalSupportChart"></canvas></div>
        </div>

        <!-- Performance Details (Team Analytics) Section -->
        <div class="performance-details">
            <div style="display: flex; align-items: center; gap:10px; 
                background-color: #f5f5f5; padding: 10px 20px; border-radius: 5px;">
                <h2>Performance Details (Team Analytics)</h2>
                <a href="{{ url('/export-technician-performance?month=' . $selectedDate) }}" class="exportButton">Export CSV</a>
                <a id="openModalButton" class="exportButton">View Performance Details</a>
            </div>

            <table class="team-analytics-table">
                <thead>
                    <tr>
                        <th>Technician Name / ID</th>
                        <th>Tickets Assigned</th>
                        <th>Tickets Solved</th>
                        <th>Endorsed Tickets</th>
                        <th>Pull Out Device</th>
                        <th>Technical Reports</th>
                        <th>Rating</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($technicians as $technician)
                        <tr>
                            <td>{{ $technician->first_name }} {{ $technician->last_name }} / {{ $technician->employee_id }}</td>
                            <td>{{ $technician->tickets_assigned ?? 'None' }}</td>
                            <td>{{ $technician->tickets_solved ?? 'None' }}</td>
                            <td>{{ $technician->endorsed_tickets ?? 'None' }}</td>
                            <td>{{ $technician->pull_out ?? 'None' }}</td>
                            <td>{{ $technician->technical_reports ?? 'None' }}</td>
                            <td>{{ $technician->rating ?? 'No Rating' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="ticketModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <div class="ticket-table-container">
            <div class="ticket-table-wrapper">
                <h3>Ticket Summary Record</h3>
                
                <!-- Filters -->
                <div class="filters">
                    <label for="filter">Filter By:</label>
                    <select id="filter" onchange="toggleFilters()">
                        <option value="weekly">Weekly</option>
                        <option value="monthly">Monthly</option>
                        <option value="annually">Annually</option>
                    </select>
                    <select id="monthFilter" class="hidden">
                        <option value="01">January</option>
                        <option value="02">February</option>
                        <option value="03">March</option>
                        <option value="04">April</option>
                        <option value="05">May</option>
                        <option value="06">June</option>
                        <option value="07">July</option>
                        <option value="08">August</option>
                        <option value="09">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                    </select>
                    <input type="number" id="yearFilter" placeholder="Enter Year" class="hidden" min="2000" max="2099" value="{{ date('Y') }}">
                    <button onclick="fetchTickets()" class="filter-button">Apply Filter</button>
                    <button onclick="exportTickets()" class="export-button">Export</button>
                </div>

                <!--for export purpose -->
                <input type="hidden" id="userName" value="{{ auth()->user()->name }}">

                <!-- Table -->
                <div class="table-wrapper">
                    <table id="ticketTable">
                        <thead>
                            <tr>
                                <th>Control No</th>
                                <th>Date and Time</th>
                                <th>Employee Name</th>
                                <th>Department</th>
                                <th>Concern</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Remarks</th>
                                <th>Assign To</th>
                                <th>Rating</th>
                                <th>Rating Remark</th>
                                <th>Duration</th> <!-- New Column -->
                            </tr>
                        </thead>
                        <tbody id="ticketTableBody">
                            @foreach ($ticketRecords as $ticket)
                                <tr>
                                    <td class="px-6 py-4">{{ ucwords(strtolower($ticket->control_no)) }}</td>
                                    <td class="px-6 py-4">{{ ucwords(strtolower($ticket->date_time)) }}</td>
                                    <td class="px-6 py-4">{{ ucwords(strtolower($ticket->name)) }}</td>
                                    <td class="px-6 py-4">{{ ucwords(strtolower($ticket->department)) }}</td>
                                    <td class="px-6 py-4">{{ ucwords(strtolower($ticket->concern)) }}</td>
                                    <td class="px-6 py-4">{{ ucwords(strtolower($ticket->priority)) }}</td>
                                    <td class="px-6 py-4">{{ ucwords(strtolower($ticket->status)) }}</td>
                                    <td class="px-6 py-4">{{ ucwords(strtolower($ticket->remarks)) }}</td>
                                    <td class="px-6 py-4">{{ ucwords(strtolower($ticket->technical_support_name)) }}</td>
                                    <td class="px-6 py-4">{{ $ticket->rating_percentage ? $ticket->rating_percentage . '%' : 'N/A' }}</td>
                                    <td class="px-6 py-4">{{ ucwords(strtolower($ticket->remark ?? 'N/A')) }}</td>
                                    <td class="px-6 py-4">{{ $ticket->duration }}</td> <!-- Display Duration -->
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="pagination-container" id="pagination-container">
                    <div class="results-count">
                        @if ($ticketRecords->count() > 0)
                            Showing {{ $ticketRecords->firstItem() }} to {{ $ticketRecords->lastItem() }} of {{ $ticketRecords->total() }} results
                        @else
                            Showing 1 to 0 of 0 results
                        @endif
                    </div>

                    @if ($ticketRecords->hasPages())
                        <div class="pagination-buttons">
                            <ul class="flex space-x-2">
                                {{-- Previous Page Link --}}
                                <li class="{{ $ticketRecords->onFirstPage() ? 'disabled opacity-50' : '' }}">
                                    @if ($ticketRecords->onFirstPage())
                                        <span class="px-3 py-1">&lsaquo;</span>
                                    @else
                                        <a href="{{ $ticketRecords->previousPageUrl() }}" data-page="{{ $ticketRecords->currentPage() - 1 }}" class="px-3 py-1 border rounded">&lsaquo;</a>
                                    @endif
                                </li>

                                {{-- Page Numbers (show current page, one before, one after) --}}
                                @for ($i = max(1, $ticketRecords->currentPage() - 1); $i <= min($ticketRecords->lastPage(), $ticketRecords->currentPage() + 1); $i++)
                                    <li class="{{ $i == $ticketRecords->currentPage() ? 'active font-bold' : '' }}">
                                        @if ($i == $ticketRecords->currentPage())
                                            <span class="px-3 py-1 border rounded bg-gray-200">{{ $i }}</span>
                                        @else
                                            <a href="{{ $ticketRecords->url($i) }}" data-page="{{ $i }}" class="px-3 py-1 border rounded">{{ $i }}</a>
                                        @endif
                                    </li>
                                @endfor

                                {{-- Ellipsis for large page numbers --}}
                                @if ($ticketRecords->currentPage() < $ticketRecords->lastPage() - 2)
                                    <li><span>...</span></li>
                                    <li><a href="{{ $ticketRecords->url($ticketRecords->lastPage()) }}" data-page="{{ $ticketRecords->lastPage() }}" class="px-3 py-1 border rounded">{{ $ticketRecords->lastPage() }}</a></li>
                                @endif

                                {{-- Next Page Link --}}
                                <li class="{{ $ticketRecords->hasMorePages() ? '' : 'disabled opacity-50' }}">
                                    @if ($ticketRecords->hasMorePages())
                                        <a href="{{ $ticketRecords->nextPageUrl() }}" data-page="{{ $ticketRecords->currentPage() + 1 }}" class="px-3 py-1 border rounded">&rsaquo;</a>
                                    @else
                                        <span class="px-3 py-1">&rsaquo;</span>
                                    @endif
                                </li>
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Handle Date Picker Change
document.getElementById('datePicker').addEventListener('change', function() {
    const selectedDate = this.value;
    const url = new URL(window.location.href);
    url.searchParams.set('date', selectedDate);
    window.location.href = url.toString();
});

// Donut Chart
new Chart(document.getElementById('combinedMetricsChart').getContext('2d'), { 
    type: 'doughnut', 
    data: {
        labels: ['Pending', 'Closed', 'Endorsed', 'Technical-Report'],
        datasets: [{
            data: [{{ $pendingTickets }}, {{ $solvedTickets }}, {{ $endorsedTickets }}, {{ $technicalReports }}],
            backgroundColor: ['#003067', '#0073e6', '#28a745', '#ffc107'],
            hoverOffset: 20
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'right' } },
        cutout: '65%'
    }
});

// Technician Performance Chart
const ctx = document.getElementById('technicalSupportChart');

if (ctx) {
    let techData = @json($technicianChartData) || [];

    // Ensure data format is correct
    techData = techData.map(dataset => ({
        label: dataset.label || "Unknown Technician",
        data: dataset.data || Array(31).fill(0),
        borderColor: dataset.borderColor || "#000000",
        backgroundColor: dataset.backgroundColor || "rgba(0,0,0,0.2)",
        fill: true
    }));

    console.log("Processed Tech Data:", techData);

    // Handle no data case
    if (techData.length === 0 || techData.every(d => d.data.every(v => v === 0))) {
        techData = [{
            label: "No data available for technician performance chart.",
            data: Array(31).fill(0),
            borderColor: "#cccccc",
            backgroundColor: "rgba(200,200,200,0.5)",
            fill: true
        }];
        console.warn("No data available for technician performance chart.");
    }

    new Chart(ctx.getContext('2d'), {
        type: 'line',
        data: { labels: Array.from({length: 31}, (_, i) => i + 1), datasets: techData },
        options: {
            responsive: true,
            plugins: { legend: { position: 'top' } },
            scales: {
                x: { title: { display: true, text: 'Days of the Month' } },
                y: { title: { display: true, text: 'Tickets' }, beginAtZero: true }
            }
        }
    });
}

// Modal Script
const modal = document.getElementById("ticketModal");
const btn = document.getElementById("openModalButton");
const span = document.getElementsByClassName("close")[0];

btn.onclick = function() {
    modal.style.display = "block";
}

span.onclick = function() {
    modal.style.display = "none";
}

window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

function toggleFilters() {
    const filter = document.getElementById('filter').value;
    const monthFilter = document.getElementById('monthFilter');
    const yearFilter = document.getElementById('yearFilter');

    if (filter === 'monthly') {
        monthFilter.style.display = 'block';
        yearFilter.style.display = 'block';
    } else if (filter === 'annually') {
        monthFilter.style.display = 'none';
        yearFilter.style.display = 'block';
    } else {
        monthFilter.style.display = 'none';
        yearFilter.style.display = 'none';
    }
}

// Function to fetch and filter tickets based on the selected criteria
function fetchTickets(page = 1) {
    const filter = document.getElementById('filter').value;
    const month = document.getElementById('monthFilter').value;
    const year = document.getElementById('yearFilter').value;

    // Send an AJAX request to the server to fetch filtered tickets
    fetch(`/admin/fetch-tickets?filter=${filter}&month=${month}&year=${year}&page=${page}`)
        .then(response => response.json())
        .then(data => {
            updateTable(data.data); // Update the table with the paginated data
            updatePagination(data); // Update the pagination controls
        })
        .catch(error => console.error('Error fetching tickets:', error));
}

function capitalizeWords(str) {
    return str.toLowerCase().replace(/\b\w/g, char => char.toUpperCase());
}

function calculateDuration(timeIn, timeOut) {
    if (!timeIn || !timeOut) {
        return 'N/A'; // Return 'N/A' if either time_in or time_out is missing
    }

    // Convert time strings to Date objects
    const startTime = new Date(timeIn);
    const endTime = new Date(timeOut);

    // Calculate the difference in milliseconds
    const durationMs = endTime - startTime;

    // Convert milliseconds to seconds
    const durationSeconds = Math.floor(durationMs / 1000);

    // Calculate days, hours, minutes, and seconds
    const days = Math.floor(durationSeconds / (3600 * 24));
    const hours = Math.floor((durationSeconds % (3600 * 24)) / 3600);
    const minutes = Math.floor((durationSeconds % 3600) / 60);

    // Format the duration
    let duration = '';
    if (days > 0) {
        duration += `${days} day${days > 1 ? 's' : ''} `;
    }
    if (hours > 0) {
        duration += `${hours} hour${hours > 1 ? 's' : ''} `;
    }
    if (minutes > 0) {
        duration += `${minutes} minute${minutes > 1 ? 's' : ''}`;
    }

    // If duration is empty (e.g., less than a minute), return "0 minutes"
    return duration.trim() || '0 minutes';
}

// Function to update the table with the filtered tickets
function updateTable(tickets) {
    const tableBody = document.getElementById('ticketTableBody');
    tableBody.innerHTML = ''; // Clear the current table rows

    tickets.forEach(ticket => {
        const row = document.createElement('tr');
        row.className = 'hover:bg-gray-50 transition duration-200';
        row.innerHTML = `
            <td class="px-6 py-4">${ticket.control_no}</td>
            <td class="px-6 py-4">${ticket.date_time}</td>
            <td class="px-6 py-4">${ticket.name}</td>
            <td class="px-6 py-4">${ticket.department}</td>
            <td class="px-6 py-4">${ticket.concern}</td>
            <td class="px-6 py-4">${capitalizeWords(ticket.priority)}</td>
            <td class="px-6 py-4">${capitalizeWords(ticket.status)}</td>
            <td class="px-6 py-4">${ticket.remarks}</td>
            <td class="px-6 py-4">${ticket.technical_support_name}</td>
            <td class="px-6 py-4">${ticket.rating_percentage ? ticket.rating_percentage + '%' : 'N/A'}</td>
            <td class="px-6 py-4">${ticket.remark ?? 'N/A'}</td>
            <td class="px-6 py-4">${calculateDuration(ticket.time_in, ticket.time_out)}</td> <!-- Duration Column -->
        `;
        tableBody.appendChild(row);
    });
}

// Function to update the pagination controls
function updatePagination(data) {
    const paginationContainer = document.getElementById('pagination-container');
    if (!paginationContainer) return;

    const currentPage = data.current_page;
    const lastPage = data.last_page;

    let paginationHTML = `
        <div class="results-count">
            Showing ${data.from} to ${data.to} of ${data.total} results
        </div>
        <div class="pagination-buttons">
            <ul class="flex space-x-2">
    `;

    // Previous Page Button
    if (currentPage > 1) {
        paginationHTML += `
            <li>
                <a href="#" onclick="fetchTickets(${currentPage - 1})" class="px-3 py-1 border rounded">&lsaquo;</a>
            </li>
        `;
    } else {
        paginationHTML += `
            <li class="disabled opacity-50">
                <span class="px-3 py-1">&lsaquo;</span>
            </li>
        `;
    }

    // Page Numbers
    for (let i = 1; i <= lastPage; i++) {
        if (i === currentPage) {
            paginationHTML += `
                <li class="active font-bold">
                    <span class="px-3 py-1 border rounded bg-gray-200">${i}</span>
                </li>
            `;
        } else {
            paginationHTML += `
                <li>
                    <a href="#" onclick="fetchTickets(${i})" class="px-3 py-1 border rounded">${i}</a>
                </li>
            `;
        }
    }

    // Next Page Button
    if (currentPage < lastPage) {
        paginationHTML += `
            <li>
                <a href="#" onclick="fetchTickets(${currentPage + 1})" class="px-3 py-1 border rounded">&rsaquo;</a>
            </li>
        `;
    } else {
        paginationHTML += `
            <li class="disabled opacity-50">
                <span class="px-3 py-1">&rsaquo;</span>
            </li>
        `;
    }

    paginationHTML += `
            </ul>
        </div>
    `;

    paginationContainer.innerHTML = paginationHTML;
}

// Initialize the filters on page load
document.addEventListener('DOMContentLoaded', toggleFilters);
document.addEventListener('DOMContentLoaded', function () {
    fetchTickets(); // Fetch the first page of tickets
});

async function exportTickets() {
    const filter = document.getElementById('filter').value;
    const monthFilter = document.getElementById('monthFilter').value;
    const yearFilter = document.getElementById('yearFilter').value;
    const userName = document.getElementById('userName').value; // Get the user's name

    let fileName = '';
    if (filter === 'weekly') {
        const startDate = new Date();
        startDate.setDate(startDate.getDate() - startDate.getDay());
        const endDate = new Date();
        endDate.setDate(endDate.getDate() + (6 - endDate.getDay()));
        fileName = `weekly_report_${startDate.toISOString().split('T')[0]}_to_${endDate.toISOString().split('T')[0]}.csv`;
    } else if (filter === 'monthly') {
        fileName = `monthly_report_${monthFilter}_${yearFilter}.csv`;
    } else if (filter === 'annually') {
        fileName = `annual_report_${yearFilter}.csv`;
    }

    try {
        // Fetch all records from the server (ignoring pagination)
        const response = await fetch(`/admin/tickets/export?filter=${filter}&month=${monthFilter}&year=${yearFilter}`);
        const data = await response.json();

        if (!data.length) {
            alert('No records found to export.');
            return;
        }

        // Create CSV content
        let csvContent = "data:text/csv;charset=utf-8,";

        // Add headers
        const headers = Object.keys(data[0]);
        csvContent += headers.join(',') + '\n';

        // Add rows
        data.forEach(row => {
            const cols = headers.map(header => row[header]);
            csvContent += cols.join(',') + '\n';
        });

        // Trigger download
        const encodedUri = encodeURI(csvContent);
        const link = document.createElement('a');
        link.setAttribute('href', encodedUri);
        link.setAttribute('download', fileName);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    } catch (error) {
        console.error('Error exporting tickets:', error);
        alert('Failed to export tickets. Please try again.');
    }
}
</script>
</body>
</html>