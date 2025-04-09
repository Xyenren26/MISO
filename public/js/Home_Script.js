// Labels and colors
const labels = ['Urgent', 'High', 'Medium', 'Low']; // Updated priority levels
const backgroundColors = ['#FF0000', '#FFA500', '#FFFF00', '#008000']; // Red, Orange, Yellow, Green
const borderColors = ['#FF0000', '#FFA500', '#FFFF00', '#008000']; // Red, Orange, Yellow, Green

// Initialize charts
function initializeCharts() {
    // Create charts without storing their instances
    createVerticalBarChart(document.getElementById('pendingTicketGraph'), pendingData, labels, backgroundColors, borderColors);
    createVerticalBarChart(document.getElementById('solvedTicketGraph'), solvedData, labels, backgroundColors, borderColors);
    createVerticalBarChart(document.getElementById('endorsedTicketGraph'), endorsedData, labels, backgroundColors, borderColors);
    createVerticalBarChart(document.getElementById('technicalReportGraph'), technicalReportData, labels, backgroundColors, borderColors);
}

// Function to create a vertical bar chart
function createVerticalBarChart(ctx, data, labels, backgroundColors, borderColors) {
    return new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Ticket Status',
                data: data,
                backgroundColor: backgroundColors,
                borderColor: borderColors,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y',
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        precision: 0
                    }
                }
            }
        }
    });
}

// Function to update a chart
function updateChart(chartId, newData) {
    const chart = Chart.getChart(chartId); // Get the chart instance by its ID
    if (!chart || !chart.data || !chart.data.datasets) {
        console.error('Chart is not properly initialized.');
        return;
    }
    chart.data.datasets[0].data = newData; // Update the dataset
    chart.update(); // Re-render the chart
}

// Fetch ticket data and update charts and counts
function fetchTicketData() {
    const selectedMonth = document.getElementById('monthPicker').value;

    fetch(`/fetch-ticket-data?month=${selectedMonth}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {

        // Map fetched data to the chart's data structure
        const pendingData = mapPriorityData(data['in-progress']);
        const solvedData = mapPriorityData(data['completed']);
        const endorsedData = mapPriorityData(data['endorsed']);
        const technicalReportData = mapPriorityData(data['technical-report']);

        // Update the charts with new data
        updateChart('pendingTicketGraph', pendingData);
        updateChart('solvedTicketGraph', solvedData);
        updateChart('endorsedTicketGraph', endorsedData);
        updateChart('technicalReportGraph', technicalReportData);

        // Update the total counts for each ticket category
        updateTotalCount('pendingTicketCount', data['in-progress']);
        updateTotalCount('solvedTicketCount', data['completed']);
        updateTotalCount('endorsedTicketCount', data['endorsed']);
        updateTotalCount('technicalReportCount', data['technical-report']);
    })
    .catch(error => console.error('Error fetching ticket data:', error));
}

// Function to map priority data to the chart's data structure
function mapPriorityData(data) {
    const mappedData = [0, 0, 0, 0]; // Initialize with zeros for [Urgent, High, Medium, Low]

    if (data) {
        for (const [priority, count] of Object.entries(data)) {
            const index = labels.map(label => label.toLowerCase()).indexOf(priority.toLowerCase());
            if (index !== -1) {
                mappedData[index] = count; // Use the actual count from the data
            }
        }
    }

    return mappedData;
}

// Function to update the total count for a ticket category
function updateTotalCount(elementId, data) {
    const countElement = document.getElementById(elementId);
    if (countElement && data) {
        // Calculate the total count by summing all priority counts
        const totalCount = Object.values(data).reduce((sum, count) => sum + count, 0);
        countElement.textContent = totalCount; // Update the count display
    }
}

// Initialize charts and fetch data when the page loads
document.addEventListener('DOMContentLoaded', function () {
    initializeCharts(); // Initialize charts first
    fetchTicketData(); // Fetch data immediately
    setInterval(fetchTicketData, 5000); // Fetch data every 5 seconds
});

const ctx2 = document.getElementById('deviceManagementGraph').getContext('2d');

// Check if there's any data (inRepairs and repaired) before rendering the chart
if (inRepairs === 0 && repaired === 0) {
    // Display a message if there are no records
    document.querySelector('.graph-container-donut').innerHTML = '<h3>No Device Management Records</h3>';
} else {
    // Render the chart if there are records
    const deviceManagementGraph = new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['Pending Repairs', 'Repaired'],
            datasets: [{
                data: [inRepairs, repaired],
                backgroundColor: ['#FF6347', '#32CD32'],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.label + ': ' + tooltipItem.raw;
                        }
                    }
                }
            }
        }
    });
}


document.addEventListener('DOMContentLoaded', function () {
    const monthPicker = document.getElementById('monthPicker');
    const currentDate = new Date();
    const maxMonth = currentDate.toISOString().slice(0, 7); // Format: YYYY-MM
    monthPicker.setAttribute('max', maxMonth);
});

function restrictFutureMonth(event) {
    const selectedMonth = event.target.value;
    const maxMonth = event.target.getAttribute('max');
    if (selectedMonth > maxMonth) {
        event.target.value = maxMonth; // Revert to the max month if the user types a future month
    }
}

function updatePerformanceGraph() {
    const selectedMonth = document.getElementById('monthPicker').value;
    console.log("Selected Month: " + selectedMonth);

    // Submit the form to reload the page with the new data
    const form = document.getElementById('monthForm');
    form.submit();
}

// Function to format the number dynamically based on reports count
function formatNumber(count) {
    if (count >= 1000000) {
        return (count / 1000000).toFixed(1) + 'M';
    } else if (count >= 1000) {
        return (count / 1000).toFixed(1) + 'K';
    }
    return count;
}

// Labels for the days of the month (1 to 31)
const daysOfMonth = Array.from({ length: 31 }, (_, i) => `Day ${i + 1}`);

// Create the Personal Performance Graph (line chart for solved and technical report tickets)
const ctxPerformance = document.getElementById('ticketPerformanceGraph').getContext('2d');
const ticketPerformanceGraph = new Chart(ctxPerformance, {
    type: 'line',
    data: {
        labels: daysOfMonth,
        datasets: [
            {
                label: 'Solved Tickets',
                data: solvedDataByDay, // Data for solved tickets by day
                borderColor: '#4CAF50',
                backgroundColor: 'rgba(76, 175, 80, 0.2)',
                fill: true,
                tension: 0.1,
            },
            {
                label: 'Pending Tickets',
                data: technicalReportDataByDay, // Data for technical report tickets by day
                borderColor: '#FF5722',
                backgroundColor: 'rgba(255, 87, 34, 0.2)',
                fill: true,
                tension: 0.1,
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top'
            },
            tooltip: {
                callbacks: {
                    label: function(tooltipItem) {
                        return `${tooltipItem.dataset.label}: ${tooltipItem.raw} tickets`;
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true, // Start Y-axis at 0
                ticks: {
                    stepSize: 1, // Step size of 1 for integer values
                    callback: function(value) {
                        return value; // Show only integers without decimals
                    }
                }
            }
        }
    }
});

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
    fetch(`/fetch-tickets?filter=${filter}&month=${month}&year=${year}&page=${page}`)
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
            <td class="px-6 py-4">${ticket.remarks ?? 'N/A'}</td>
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
    const exportBtn = document.querySelector('.export-button');
    try {
        // Set loading state
        exportBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generating...';
        exportBtn.disabled = true;

        // Get filter values
        const filter = document.getElementById('filter').value;
        const month = document.getElementById('monthFilter').value;
        const year = document.getElementById('yearFilter').value;

        // Fetch PDF
        const response = await fetch(`/tickets/export?filter=${filter}&month=${month}&year=${year}`);
        
        if (!response.ok) {
            throw new Error(`Server error: ${response.status}`);
        }

        const { pdf, passwords, download_name } = await response.json();

        // Download PDF
        const link = document.createElement('a');
        link.href = pdf;
        link.download = download_name;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

        // Show passwords modal
        showPasswordModal({
            openPassword: passwords.open,
            //ownerPassword: passwords.owner,
            expiresAt: passwords.expires_at
        });

    } catch (error) {
        console.error('Export failed:', error);
        alert('Export failed: ' + error.message);
    } finally {
        if (exportBtn) {
            exportBtn.innerHTML = 'Export';
            exportBtn.disabled = false;
        }
    }
}

function showPasswordModal({ openPassword, expiresAt }) { //include ownerPassword for testing
    // Remove existing modal if present
    const existingModal = document.getElementById('passwordModal');
    if (existingModal) existingModal.remove();

    // Create modal
    const modal = document.createElement('div');
    modal.id = 'passwordModal';
    modal.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.7);
        z-index: 1000;
        display: flex;
        justify-content: center;
        align-items: center;
    `;

    modal.innerHTML = `
        <div style="
            background: white;
            padding: 2rem;
            border-radius: 8px;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        ">
            <h3 style="margin-top: 0; color: #003067;">Document Passwords</h3>
            <p><strong>Open Password:</strong> <span id="openPass">${openPassword}</span></p>
            
            <p><em>Ticket list Record should be pass until: ${new Date(expiresAt).toLocaleString()}</em></p>
            <div style="margin-top: 1.5rem; display: flex; gap: 1rem;">
                <button onclick="copyPassword('openPass')" style="
                    padding: 8px 16px;
                    background: #003067;
                    color: white;
                    border: none;
                    border-radius: 4px;
                    cursor: pointer;
                ">Copy Open</button>
                
            </div>
            <div style="margin-top: 2rem;">
                <button onclick="closeModal()" style="
                    padding: 8px 16px;
                    background: #4CAF50;
                    color: white;
                    border: none;
                    border-radius: 4px;
                    cursor: pointer;
                ">Close</button>
            </div>
        </div>
    `;
    //<p><strong>Owner Password:</strong> <span id="ownerPass">${ownerPassword}</span></p> remove include it for testing
    
    /*<button onclick="copyPassword('ownerPass')" style="
                    padding: 8px 16px;
                    background: #3a5a87;
                    color: white;
                    border: none;
                    border-radius: 4px;
                    cursor: pointer;
                ">Copy Owner</button>
                */
    document.body.appendChild(modal);
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    const modal = document.getElementById('passwordModal');
    if (modal) {
        modal.remove();
        document.body.style.overflow = '';
    }
}

function copyPassword(elementId) {
    const element = document.getElementById(elementId);
    if (!element) return;

    navigator.clipboard.writeText(element.textContent)
        .then(() => {
            const originalText = element.textContent;
            element.textContent = 'Copied!';
            element.style.color = '#4CAF50';
            setTimeout(() => {
                element.textContent = originalText;
                element.style.color = '';
            }, 2000);
        })
        .catch(err => {
            console.error('Copy failed:', err);
            alert('Please copy manually: ' + element.textContent);
        });
}