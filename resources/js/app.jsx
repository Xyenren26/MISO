import React, { StrictMode } from 'react';
import ReactDOM from 'react-dom/client'; // Make sure this is the correct import
import ChartComponent from './components/ChartComponent';

const data = {
  pendingTickets: 15,
  solvedTickets: 50,
  endorsedTickets: 10,
  technicalReports: 25,
  devicesInRepair: 7,
  repairedDevices: 30,
};

const targetDiv = document.getElementById('combined-chart');
if (targetDiv) {
  const root = ReactDOM.createRoot(targetDiv);
  root.render(
    <StrictMode>
      <ChartComponent data={data} />
    </StrictMode>
  );
}
