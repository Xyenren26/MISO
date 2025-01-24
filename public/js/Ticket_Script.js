// Open the ticket form modal
function openTicketFormModal() {
    toggleModal('ticketFormModal', true);
}

// Close the ticket form modal
function closeTicketFormModal() {
    toggleModal('ticketFormModal', false);
}

// Toggle modal visibility
function toggleModal(modalId, show) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = show ? 'block' : 'none';
    } else {
        console.warn(`Modal with ID "${modalId}" not found.`);
    }
}

// Update the ticket list UI dynamically
function updateTicketList(tickets) {
    const ticketListContainer = document.getElementById('ticket-list');
    if (!ticketListContainer) {
        console.error('Ticket list container not found.');
        return;
    }

    // Clear the current list
    ticketListContainer.innerHTML = '';

    // Populate new tickets
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

// Active filters
let activeFilters = {
    status: 'recent', // Default tab
    priority: null    // Default priority (all)
};

// Filter tickets by status
function filterTickets(status, event) {
    if (!event) return;

    // Update active status
    activeFilters.status = status;

    // Update active button styles
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active');
    });
    event.target.classList.add('active');

    // Fetch and render filtered tickets
    fetchAndRenderTickets();
}

// Filter tickets by priority
function filterByPriority(priority) {
    // Update active priority
    activeFilters.priority = priority;

    // Update dropdown button text
    const dropdownButton = document.querySelector('.dropdown-button');
    if (dropdownButton) {
        dropdownButton.innerHTML = `
            <i class="fas fa-filter"></i> ${priority ? capitalize(priority) : 'All Priorities'} <span class="arrow">&#x25BC;</span>
        `;
    }

    // Fetch and render filtered tickets
    fetchAndRenderTickets();
}

// Fetch and render tickets based on active filters
function fetchAndRenderTickets() {
    const ticketListContainer = document.getElementById('ticket-list');
    if (!ticketListContainer) {
        console.error('Ticket list container not found.');
        return;
    }

    // Fetch tickets with active filters
    const { status, priority } = activeFilters;
    const queryParams = new URLSearchParams({
        status,
        priority: priority || ''
    }).toString();

    fetch(`/tickets/filter?${queryParams}`)
        .then(response => response.text())
        .then(html => {
            ticketListContainer.innerHTML = html;
        })
        .catch(error => {
            console.error('Error fetching filtered tickets:', error);
            ticketListContainer.innerHTML = '<div class="error-message">Failed to load tickets. Please try again.</div>';
        });
}

// Utility function to capitalize strings
function capitalize(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
}

// Initialize filters and ticket list on DOMContentLoaded
document.addEventListener('DOMContentLoaded', fetchAndRenderTickets);
