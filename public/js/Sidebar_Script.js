function toggleSidebar() {
    const sidebar = document.getElementById("sidebar");
    sidebar.classList.toggle("minimized");

    const toggleIcon = document.getElementById("sidebar-toggle-icon");

    // Change the arrow direction based on sidebar state
    if (sidebar.classList.contains("minimized")) {
        toggleIcon.classList.remove("fa-arrow-left");
        toggleIcon.classList.add("fa-arrow-right");
    } else {
        toggleIcon.classList.remove("fa-arrow-right");
        toggleIcon.classList.add("fa-arrow-left");
    }
}
