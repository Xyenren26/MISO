@if(Auth::check() && is_null(Auth::user()->email_verified_at))
<div id="email-verification-modal" class="modal">
    <div class="modal-content">
        <span class="close-modal" id="close-verification-modal">&times;</span>
        <h2>Security Warning</h2>
        <p>Your email is not verified. Please verify your email to ensure the security of your account.</p>
        <button id="resend-verification-email">Resend Verification Email</button>
    </div>
</div>

<style>
    .modal {
        display: block;
        position: fixed;
        z-index: 1000;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        width: 400px;
        background: white;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3);
        padding: 20px;
        border-radius: 8px;
        text-align: center;
    }

    .modal-content {
        position: relative;
    }

    .close-modal {
        position: absolute;
        top: 10px;
        right: 15px;
        font-size: 20px;
        cursor: pointer;
    }

    button {
        background: red;
        color: white;
        border: none;
        padding: 10px;
        cursor: pointer;
        border-radius: 5px;
        margin-top: 10px;
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const modal = document.getElementById("email-verification-modal");
        const closeModal = document.getElementById("close-verification-modal");
        const resendBtn = document.getElementById("resend-verification-email");

        closeModal.addEventListener("click", function () {
            modal.style.display = "none";
        });

        resendBtn.addEventListener("click", function () {
            fetch("{{ route('verification.resend') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector("meta[name='csrf-token']").getAttribute("content"),
                    "Accept": "application/json"
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Verification email sent. Check your inbox.");
                } else {
                    alert("Error sending verification email.");
                }
            })
            .catch(error => console.error("Error:", error));
        });
    });
</script>
@endif
