<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechTrack</title>
    <link rel="icon" href="{{ asset('images/Systembrowserlogo.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('css/Home_Style.css') }}">
    
    <!-- Include Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <!-- Include Chart.js for graph rendering -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<div class="container">
    <!-- Sidebar -->
    @include('components.sidebar')

    <!-- Main Content Area -->
    <div class="main-content">
        <!-- Navbar -->
        @include('components.navbar')
       <!-- Modal for Creating a New Ticket -->
        <div id="ticketFormModal" class="modal" style="display: none;">
            <div class="modal-content">
                <!-- Ensure data is passed to the component correctly -->
                @include('components.ticket-form', ['technicalSupports' => $technicalSupports, 'formattedControlNo' => $formattedControlNo])

            </div>
        </div>
<!-- to me huwag mong galawain lagi mo kasing namomove-->
<script src="{{ asset('js/Home_Script.js') }}"></script>
<script>
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

</script>
</body>
</html>
