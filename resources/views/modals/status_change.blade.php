<!-- CSRF Token Meta Tag (ensure this is inside the <head>) -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
    /* Modal background */
    .modal-update-status {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 999;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    /* Modal content */
    .modal-content-update-status {
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

    /* Loading Spinner */
    .loading-spinner {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        margin-top: 20px;
    }

    .spinner {
        border: 4px solid rgba(0, 0, 0, 0.1);
        border-left-color: #007BFF; /* Blue color */
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    .loading-spinner p {
        margin-top: 10px;
        font-size: 14px;
        color: #007BFF; /* Blue color */
    }

</style>

<!-- Confirmation Modal -->
<div id="confirmationModal" class="modal-update-status" style="display: none;">
    <div class="modal-content-update-status">
        <h2>Confirm Status Change</h2>
        <p class="triangle-text">
            I acknowledge that the device(s) listed under Form No. 
            <span class="form-no" id="modalFormNo"></span> have been successfully repaired and should now be marked as 
            <span class="repaired">"Repaired"</span>.
        </p>
        <button class="btn-submit" onclick="changeStatus()">Confirm</button>
        <button class="btn-cancel" onclick="closeModal()">Cancel</button>

        <!-- Spinner -->
        <div id="loadingSpinner" class="loading-spinner" style="display: none;">
            <div class="spinner"></div>
            <p>Updating status...</p>
        </div>
    </div>
</div>

<script>
    function changeStatus() {
        const spinner = document.getElementById("loadingSpinner");
        const submitButton = document.querySelector(".btn-submit");
        const cancelButton = document.querySelector(".btn-cancel");

        // Disable buttons and show spinner
        submitButton.disabled = true;
        cancelButton.disabled = true;
        spinner.style.display = "flex";

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
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the status.');
        })
        .finally(() => {
            // Re-enable buttons and hide spinner
            submitButton.disabled = false;
            cancelButton.disabled = false;
            spinner.style.display = "none";
        });
    }
</script>
