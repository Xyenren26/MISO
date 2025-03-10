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

    function toggleModal(modalId, show) {
        let modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = show ? 'block' : 'none';
        } else {
            console.error('Modal not found:', modalId);
        }
    }

    function openTicketFormModal() {
        toggleModal('ticketFormModal', true);
    }

    function closeTicketFormModal() {
        toggleModal('ticketFormModal', false);
    }

    // Function to open modal
    function openModalHome(modalId) {
        document.getElementById(modalId).style.display = "block";
    }

    // Function to close modal
    function closeModal(modalId) {
        document.getElementById(modalId).style.display = "none";
    }

    // Close modal when clicking outside of it
    window.onclick = function(event) {
      if (event.target.classList.contains('modal')) {
        event.target.style.display = "none";
      }
    }
    // Slideshow Functionality
    let slideIndex = 0;
    const slidespower = document.querySelector('.slides');
    const totalSlides = slidespower.children.length;

    function showSlide(index) {
        if (index >= totalSlides) slideIndex = 0;
        if (index < 0) slideIndex = totalSlides - 1;
        slidespower.style.transform = `translateX(${-slideIndex * 100}%)`;
    }

    function nextSlide() {
        slideIndex++;
        showSlide(slideIndex);
    }

    function prevSlide() {
        slideIndex--;
        showSlide(slideIndex);
    }

    // Auto-play the slideshow (optional)
    setInterval(nextSlide, 5000);

    // Calendar Functionality (Basic Example)
    const calendarElement = document.getElementById('calendar');
    const date = new Date();
    const month = date.toLocaleString('default', { month: 'long' });
    const year = date.getFullYear();


    