const ctx = document.getElementById('ticketPerformanceGraph').getContext('2d');
const days = Array.from({ length: 31 }, (_, i) => `Day ${i + 1}`); // Days of the month

const ticketPerformanceGraph = new Chart(ctx, {
    type: 'line',
    data: {
        labels: days,
        datasets: [
            {
                label: 'Solved Tickets',
                data: [5, 10, 8, 15, 20, 10, 12, 18, 16, 25, 30, 28, 18, 20, 22, 26, 30, 32, 25, 28, 30, 35, 40, 42, 38, 44, 50, 52, 55, 58, 60],
                borderColor: '#66b3ff',
                backgroundColor: 'rgba(102, 179, 255, 0.2)',
                tension: 0.3,
                fill: true
            },
            {
                label: 'Technical Reports',
                data: [2, 4, 6, 8, 10, 9, 11, 13, 15, 17, 19, 22, 20, 23, 25, 28, 27, 30, 32, 35, 36, 38, 40, 41, 45, 47, 50, 53, 55, 58, 60],
                borderColor: '#ff9999',
                backgroundColor: 'rgba(255, 153, 153, 0.2)',
                tension: 0.3,
                fill: true
            }
        ]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Number of Reports'
                }
            },
            x: {
                title: {
                    display: true,
                    text: 'Days of the Month'
                }
            }
        }
    }
});
