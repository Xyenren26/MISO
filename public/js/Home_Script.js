// Labels and colors
const labels = ['Urgent', 'High', 'Medium', 'Low']; // Updated priority levels
const backgroundColors = ['#FF0000', '#FFA500', '#FFFF00', '#008000']; // Red, Orange, Yellow, Green
const borderColors = ['#FF0000', '#FFA500', '#FFFF00', '#008000']; // Red, Orange, Yellow, Green

// Initialize charts
createVerticalBarChart(document.getElementById('pendingTicketGraph'), pendingData, labels, backgroundColors, borderColors);
createVerticalBarChart(document.getElementById('solvedTicketGraph'), solvedData, labels, backgroundColors, borderColors);
createVerticalBarChart(document.getElementById('endorsedTicketGraph'), endorsedData, labels, backgroundColors, borderColors);
createVerticalBarChart(document.getElementById('technicalReportGraph'), technicalReportData, labels, backgroundColors, borderColors);

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

// Function to initialize a vertical bar chart
function createVerticalBarChart(ctx, data, labels, backgroundColors, borderColors) {
    new Chart(ctx, {
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
            maintainAspectRatio: false, // Allow resizing without preserving aspect ratio
            indexAxis: 'y', // Makes the bar chart vertical
            x: {
                beginAtZero: true,  // Start the y-axis at zero
                ticks: {
                    stepSize: 1,  // Ensure the ticks are whole numbers
                    precision: 0  // No decimals, whole numbers only
                }
            }
        }        
    });
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
function fetchTickets() {
    console.log("Fetching tickets..."); // Debugging line
    const filter = document.getElementById('filter').value;
    const month = document.getElementById('monthFilter').value;
    const year = document.getElementById('yearFilter').value;

    // Send an AJAX request to the server to fetch filtered tickets
    fetch(`/fetch-tickets?filter=${filter}&month=${month}&year=${year}`)
        .then(response => response.json())
        .then(data => {
            updateTable(data);
        })
        .catch(error => console.error('Error fetching tickets:', error));
}

function capitalizeWords(str) {
    return str.toLowerCase().replace(/\b\w/g, char => char.toUpperCase());
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
            <td class="px-6 py-4">${ticket.rating_percentage ? ticket.rating_percentage + '%' : 'N/A'}</td>
            <td class="px-6 py-4">${ticket.remark ?? 'N/A'}</td>
        `;
        tableBody.appendChild(row);
    });
}

// Initialize the filters on page load
document.addEventListener('DOMContentLoaded', toggleFilters);

function exportTickets() {
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
        fileName = `${userName}_weekly_report_${startDate.toISOString().split('T')[0]}_to_${endDate.toISOString().split('T')[0]}.csv`;
    } else if (filter === 'monthly') {
        fileName = `${userName}_monthly_report_${monthFilter}_${yearFilter}.csv`;
    } else if (filter === 'annually') {
        fileName = `${userName}_annual_report_${yearFilter}.csv`;
    }

    const rows = document.querySelectorAll('#ticketTableBody tr');
    let csvContent = "data:text/csv;charset=utf-8,";

    // Add headers
    const headers = Array.from(document.querySelectorAll('#ticketTable thead th')).map(header => header.innerText);
    csvContent += headers.join(',') + '\n';

    // Add rows
    rows.forEach(row => {
        const cols = Array.from(row.querySelectorAll('td')).map(col => col.innerText);
        csvContent += cols.join(',') + '\n';
    });

    // Create a link and trigger the download
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement('a');
    link.setAttribute('href', encodedUri);
    link.setAttribute('download', fileName);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}