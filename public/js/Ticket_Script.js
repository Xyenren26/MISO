// Open the ticket form modal
function openTicketFormModal() {
    document.getElementById('ticketFormModal').style.display = 'block';
}

// Close the ticket form modal
function closeTicketFormModal() {
    document.getElementById('ticketFormModal').style.display = 'none';
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
function filterTickets(status, event = null, priority = null) {
    // Determine the active tab if an event is provided
    if (event) {
        // Remove the active class from all tab buttons
        document.querySelectorAll('.tab-button').forEach(button => {
            button.classList.remove('active');
        });

        // Add the active class to the clicked tab
        event.target.classList.add('active');
    }

    // If no status is provided, use the active tab's status
    const activeTab = document.querySelector('.tab-button.active');
    if (!status && activeTab) {
        status = activeTab.dataset.status; // Assuming tabs have a 'data-status' attribute
    }

    if (!status) {
        console.error('No active tab or status detected.');
        return;
    }

    // Build the URL for the AJAX request with status and optional priority
    const url = `/filter-tickets/${status}` + (priority ? `?priority=${priority}` : '');

    // Send AJAX request to filter tickets by status (and priority if provided)
    fetch(url, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
        }
    })
        .then(response => response.json())
        .then(data => {
            // Update the ticket list with the new filtered tickets
            document.getElementById('ticket-list').innerHTML = data.html;

            // Update pagination (if pagination is included in the response)
            const paginationContainer = document.querySelector('.pagination-container');
            if (paginationContainer) {
                paginationContainer.innerHTML = data.pagination;
            }
        })
        .catch(error => console.error('Error fetching tickets:', error));
}

// Function to filter tickets by priority within the active tab
function filterByPriority(priority) {
    const activeTab = document.querySelector('.tab-button.active');
    if (activeTab) {
        const status = activeTab.dataset.status; // Assuming tabs have a 'data-status' attribute
        filterTickets(status, null, priority);
    } else {
        console.error('No active tab detected.');
    }
}
