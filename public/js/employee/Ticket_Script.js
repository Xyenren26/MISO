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

// Active filters
let activeFilters = {
    status: 'recent', // Default tab
    priority: null,   // Default priority (all)
    search: ''        // Default search value
};

// Filter tickets by status
function filterTickets(status, event) {
    if (!event) return;

    activeFilters.status = status;

    // Update active button styles
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active');
    });
    event.target.classList.add('active');

    fetchAndRenderTickets();
}

// Filter tickets by priority
function filterByPriority(priority) {
    activeFilters.priority = priority;
    
    const dropdownButton = document.querySelector('.dropdown-button');
    if (dropdownButton) {
        dropdownButton.innerHTML = `
            <i class="fas fa-filter"></i> ${priority ? capitalize(priority) : 'All Priorities'} <span class="arrow">&#x25BC;</span>
        `;
    }

    fetchAndRenderTickets();
}

// Handle search input and update ticket list dynamically
function handleSearchInput() {
    activeFilters.search = document.getElementById("ticketSearch").value.trim();
    fetchAndRenderTickets();
}

// Fetch and render tickets based on active filters & pagination
function fetchAndRenderTickets(page = 1) {
    const ticketListContainer = document.getElementById('ticket-list');
    if (!ticketListContainer) {
        console.error('Ticket list container not found.');
        return;
    }

    const { status, priority, search } = activeFilters;
    let queryParams = new URLSearchParams({
        status: status || '',
        priority: priority || '',
        search: search || '',
        page: page // Pass the page number
    }).toString();

    fetch(`/employee/filter?${queryParams}`, {
        method: "GET",
        headers: { "X-Requested-With": "XMLHttpRequest" }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error("Failed to fetch tickets");
        }
        return response.text();
    })
    .then(html => {
        ticketListContainer.innerHTML = html;
        attachPaginationListeners(); // Reattach pagination events after updating content
    })
    .catch(error => {
        console.error('Error fetching filtered tickets:', error);
        ticketListContainer.innerHTML = '<div class="error-message">Failed to load tickets. Please try again.</div>';
    });
}

// Attach event listeners to pagination links dynamically
function attachPaginationListeners() {
    document.querySelectorAll('.pagination-container a').forEach(link => {
        link.addEventListener('click', function (event) {
            event.preventDefault(); // Prevent full page reload
            
            const url = new URL(this.href);
            const page = url.searchParams.get("page"); // Extract page number
            
            if (page) {
                fetchAndRenderTickets(page);
            }
        });
    });
}

// Utility function to capitalize strings
function capitalize(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
}

// Attach event listeners on page load
window.addEventListener('DOMContentLoaded', () => {
    document.getElementById("ticketSearch").addEventListener("input", handleSearchInput);
    fetchAndRenderTickets(); // Initial load
    attachPaginationListeners(); // Attach pagination listeners
});
