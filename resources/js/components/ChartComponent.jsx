import React from 'react';  // Ensure React is imported first
import { Chart as ChartJS, ArcElement, Tooltip, Legend } from 'chart.js';
import { Doughnut } from 'react-chartjs-2';

// Register Chart.js components
ChartJS.register(ArcElement, Tooltip, Legend);

const ChartComponent = ({ data }) => {
  const chartData = {
    labels: ['Pending Tickets', 'Solved Tickets', 'Endorsed Tickets', 'Technical Reports', 'Devices in Repair', 'Repaired Devices'],
    datasets: [
      {
        data: [
          data.pendingTickets,
          data.solvedTickets,
          data.endorsedTickets,
          data.technicalReports,
          data.devicesInRepair,
          data.repairedDevices,
        ],
        backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'],
        hoverOffset: 20,
      },
    ],
  };

  const chartOptions = {
    responsive: true,
    plugins: {
      legend: {
        position: 'right',
        align: 'right',
        labels: {
          font: { size: 14 },
        },
      },
    },
  };

  return (
    <div>
      <h2>Key Metrics Overview</h2>
      <Doughnut data={chartData} options={chartOptions} />
    </div>
  );
};

export default ChartComponent;
