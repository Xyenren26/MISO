document.addEventListener("DOMContentLoaded", function() {
    const texts = [
        "Computer, printer, and UPS evaluation, repair, and maintenance.",
        "Basic troubleshooting of cables and network connections.",
        "Windows OS, MS Office, and app installation.",
        "Assist in internet and network troubleshooting.",
        "Support in other technical tasks."
    ];

    let index = 0;
    const textElement = document.getElementById("slideshowText");

    function updateText() {
        textElement.style.transition = "transform 0.8s ease-in-out, opacity 0.8s ease-in-out";
        textElement.style.transform = "translateY(20px)"; // Slide down before fade out
        textElement.style.opacity = 0; // Fade out
        
        setTimeout(() => {
            textElement.innerText = texts[index]; // Update text
            textElement.style.transform = "translateY(-20px)"; // Slide up effect

            setTimeout(() => {
                textElement.style.transition = "transform 0.6s cubic-bezier(0.5, -0.4, 0.3, 1.4), opacity 0.8s ease-in-out"; 
                textElement.style.transform = "translateY(0)"; // Bounce effect
                textElement.style.opacity = 1; // Fade in
            }, 50);

            index = (index + 1) % texts.length; // Cycle through texts
        }, 500);
    }

    setInterval(updateText, 7000); // Change text every 5 seconds
    updateText(); // Initialize first text
});

    //<!-- JavaScript for Toggle -->
    function toggleCompletedTickets() {
        var panel = document.getElementById("completedTicketsPanel");
        panel.style.display = (panel.style.display === "none" || panel.style.display === "") ? "block" : "none";
    }
