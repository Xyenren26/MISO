function openTicketFormModal() {
    const modal = document.getElementById('ticketFormModal');
    modal.style.display = 'block'; // Show the modal
}

function closeTicketFormModal() {
    const modal = document.getElementById('ticketFormModal');
    modal.style.display = 'none'; // Hide the modal
}

function filterTickets(status, event) {
    // Prevent default action for the click event
    event.preventDefault();

    // Remove 'active' class from all tab buttons
    const buttons = document.querySelectorAll('.tab-button');
    buttons.forEach(button => button.classList.remove('active'));

    // Add 'active' class to the clicked button
    event.target.classList.add('active');

    // Send AJAX request to the server to filter tickets based on the status
    fetch(`/tickets/filter/${status}`)
        .then(response => response.json())
        .then(data => {
            // Replace the content of the ticket list with the updated data
            document.getElementById('ticket-list').innerHTML = data.html;
        })
        .catch(error => {
            console.error('Error:', error);
        });
}
function filterTickets(status, event) {
    // Prevent default action for the click event
    event.preventDefault();

    // Remove 'active' class from all tab buttons
    const buttons = document.querySelectorAll('.tab-button');
    buttons.forEach(button => button.classList.remove('active'));

    // Add 'active' class to the clicked button
    event.target.classList.add('active');

    // Send AJAX request to the server to filter tickets based on the status
    fetch(`/tickets/filter/${status}`)
        .then(response => response.json())
        .then(data => {
            // Replace the content of the ticket list with the updated data
            document.getElementById('ticket-list').innerHTML = data.html;
        })
        .catch(error => {
            console.error('Error:', error);
        });
}




function updateTicketList(tickets) {
    const ticketListContainer = document.getElementById('ticket-list'); // Adjust with your container ID
    ticketListContainer.innerHTML = ''; // Clear current list

    tickets.forEach(ticket => {
        const ticketItem = document.createElement('div');
        ticketItem.classList.add('ticket-item');
        ticketItem.innerHTML = `
            <div class="ticket-id">${ticket.id}</div>
            <div class="ticket-status">${ticket.status}</div>
            <div class="ticket-description">${ticket.description}</div>
        `;
        ticketListContainer.appendChild(ticketItem);
    });
}

