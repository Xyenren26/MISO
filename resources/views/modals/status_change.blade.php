<!-- CSRF Token Meta Tag (ensure this is inside the <head>) -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
    /* Modal background */
    .modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
    }

    /* Modal content */
    .modal-content {
        background: white;
        padding: 20px;
        width: 50%;
        border-radius: 10px;
        text-align: center;
    }

    /* Triangle-like centered text */
    .triangle-text {
        font-size: 18px;
        font-weight: bold;
        text-align: center;
        line-height: 1.5;
    }

    /* Color for Form No. (red) */
    .form-no {
        color: red;
    }

    /* Color for Repaired (green) */
    .repaired {
        color: green;
    }
    /* Submit Button (Blue Color) */
    .btn-submit {
        padding: 12px 24px;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.2s ease;
        background-color: #007BFF; /* Blue */
        color: white;
    }

    /* Submit Button hover effect */
    .btn-submit:hover {
        background-color: #0056b3;
        transform: scale(1.05); /* Slightly grow the button */
    }

    /* Cancel Button (Muted Orange) */
    .btn-cancel {
        padding: 12px 24px;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
        background-color: #f39c12; /* Muted Orange */
        color: white;
    }

    /* Cancel Button hover effect */
    .btn-cancel:hover {
        background-color: #e67e22;
    }

</style>

<!-- Confirmation Modal -->
<div id="confirmationModal" class="modal" style="display: none;">
    <div class="modal-content">
        <h2>Confirm Status Change</h2>
        <p class="triangle-text">
            I acknowledge that the device(s) listed under Form No. 
            <span class="form-no" id="modalFormNo"></span> have been successfully repaired and should now be marked as 
            <span class="repaired">"Repaired"</span>.
        </p>
        <button class="btn-submit" onclick="changeStatus()">Confirm</button>
        <button class="btn-cancel" onclick="closeModal()">Cancel</button>
    </div>
</div>

<script>
    function changeStatus() {
        fetch(`/update-status/${currentFormNo}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({}) // No need to send status, it's handled in the controller
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Status updated successfully!');
                location.reload(); // Refresh to reflect changes
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => console.error('Error:', error));

        closeModal();
    }
</script>
