// Labels and colors
const labels = ['Urgent', 'Semi-Urgent', 'Non-Urgent'];
const backgroundColors = ['#FF0000', '#FFA500', '#008000']; // Red, Orange, Green
const borderColors = ['#FF0000', '#FFA500', '#008000']; // Red, Orange, Green

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
            labels: ['In Repairs', 'Repaired'],
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
                label: 'Technical Report Tickets',
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
