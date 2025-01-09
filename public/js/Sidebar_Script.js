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
