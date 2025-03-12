function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const toggleIcon = document.getElementById('sidebar-toggle-icon');
    const mainContent = document.querySelector('.main-content'); // Ensure this is targeted correctly

    // Toggle the "minimized" class on sidebar
    sidebar.classList.toggle('minimized');

    // Change margin-left of the main content when the sidebar is minimized or maximized
    if (sidebar.classList.contains('minimized')) {
        mainContent.style.marginLeft = '60px';  // Smaller margin when sidebar is minimized
    } else {
        mainContent.style.marginLeft = '250px';  // Larger margin when sidebar is expanded
    }

    // Change the direction of the icon
    if (sidebar.classList.contains("minimized")) {
        toggleIcon.classList.remove("fa-arrow-left");
        toggleIcon.classList.add("fa-arrow-right");
    } else {
        toggleIcon.classList.remove("fa-arrow-right");
        toggleIcon.classList.add("fa-arrow-left");
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const requestSupportButton = document.getElementById('requestSupportButton');
    const ticketMessage = document.getElementById('ticketMessage');

    // Fetch data from the server
    fetch('/check-pending-tickets') // Replace with your endpoint
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.hasPendingTickets) {
                // If there are pending tickets, disable the button and show the message
                requestSupportButton.disabled = true; // Disable the button
                requestSupportButton.classList.add('disabled-button'); // Add a class for styling
                ticketMessage.style.display = 'block';
                ticketMessage.textContent = 'You cannot request support until your pending tickets are resolved.';
            } else {
                // If no pending tickets, enable the button and hide the message
                requestSupportButton.disabled = false; // Enable the button
                requestSupportButton.classList.remove('disabled-button'); // Remove the class for styling
                ticketMessage.style.display = 'none';
            }
        })
        .catch(error => {
            console.error('Error fetching ticket data:', error);
            ticketMessage.style.display = 'block';
            ticketMessage.textContent = 'An error occurred while checking your tickets. Please try again later.';
        });
});