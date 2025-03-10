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
    search: '',       // Default search value
    fromDate: '',     // Default start date
    toDate: ''        // Default end date
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

// Handle date filter inputs
function handleDateFilter() {
    activeFilters.fromDate = document.getElementById("fromDate").value;
    activeFilters.toDate = document.getElementById("toDate").value;
    fetchAndRenderTickets();
}

// Fetch and render tickets based on active filters
function fetchAndRenderTickets(page = 1) {
    const ticketListContainer = document.getElementById('ticket-list');
    if (!ticketListContainer) {
        console.error('Ticket list container not found.');
        return;
    }

    const { status, priority, search, fromDate, toDate } = activeFilters;
    let queryParams = new URLSearchParams({
        status: status || '',
        priority: priority || '',
        search: search || '',
        fromDate: fromDate || '',
        toDate: toDate || '',
        page: page // Pass the page number
    }).toString();

    fetch(`/tickets/filter?${queryParams}`, {
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
    document.getElementById("fromDate").addEventListener("change", handleDateFilter);
    document.getElementById("toDate").addEventListener("change", handleDateFilter);
    setupDateFilters(); // Apply date restrictions
    fetchAndRenderTickets(); // Initial load
    attachPaginationListeners(); // Attach pagination listeners
});

// Date filtering logic
function setupDateFilters() {
    const fromDateInput = document.getElementById("fromDate");
    const toDateInput = document.getElementById("toDate");

    // Get today's date in YYYY-MM-DD format
    const today = new Date().toISOString().split("T")[0];

    // Prevent selecting future dates
    fromDateInput.setAttribute("max", today);
    toDateInput.setAttribute("max", today);

    // When 'From' date changes
    fromDateInput.addEventListener("change", function () {
        const fromDateValue = fromDateInput.value;

        // Set 'To' date minimum to selected 'From' date
        toDateInput.setAttribute("min", fromDateValue);

        // Auto-correct 'To' date if it's before 'From' date
        if (toDateInput.value && toDateInput.value < fromDateValue) {
            toDateInput.value = fromDateValue;
        }

        // Apply filter automatically
        handleDateFilter();
    });

    // When 'To' date changes
    toDateInput.addEventListener("change", function () {
        if (toDateInput.value < fromDateInput.value) {
            alert("The 'To' date cannot be before the 'From' date.");
            toDateInput.value = fromDateInput.value;
        }

        // Apply filter automatically
        handleDateFilter();
    });
}

function archiveTicket(controlNo) {
    if (confirm("Are you sure you want to archive this ticket?")) {
        fetch(`/tickets/${controlNo}/archive`, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                "Content-Type": "application/json"
            },
            body: JSON.stringify({})
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            location.reload(); // Refresh the page after archiving
        })
        .catch(error => console.error("Error:", error));
    }
}