@if(Auth::check() && Auth::user()->email_verified_at == null)
    @php
        $userEmail = Auth::user()->email; // Get user email for localStorage key
    @endphp
    <style>
        /* ✅ Email Verification Alert Box */
        #email-alert-box {
            background-color: #ffcc00; /* Yellow background */
            color: #333; /* Dark text */
            padding: 10px;
            font-size: 14px;
            text-align: center;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            width: 100%;
            margin-top: 5px; /* Space below navbar */
            position: relative;
        }

        /* ✅ Button */
        #trigger-verification-btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 16px;
            position: absolute;
            right: 32px;
            top: 5px;
            cursor: pointer;
        }

        #trigger-verification-btn:hover {
            background-color: #0056b3;
        }

        /* ✅ Close Button */
        .close-alert {
            cursor: pointer;
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }
    </style> 


    <div id="email-alert-box" id="email-alert-box" class="alert-box">
        <span class="alert-message">
            ⚠️ Hey there! Just a quick reminder to verify your email for a secure experience!
        </span>
        <button id="trigger-verification-btn" class="resend-email-btn" onclick="redirectToProfile()">Verify Now</button>
        <span class="close-alert" onclick="closeAlertBox()">&times;</span>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const emailAlertBox = document.getElementById("email-alert-box");
            const userEmail = "{{ $userEmail }}"; // Store user email from Blade to JS
            const storageKey = `emailAlertClosed_${userEmail}`; // Unique key for each user

            // ✅ Reset alert visibility on login
            localStorage.removeItem(storageKey); // Clear stored "closed" flag on new login

            window.closeAlertBox = function() {
                emailAlertBox.style.display = 'none';
                localStorage.setItem(storageKey, "true"); // Save closed state for this user
            };

            window.redirectToProfile = function() {
                window.location.href = "{{ route('profile.index') }}";
            };

            // ✅ Reset alert on logout
            const logoutForm = document.getElementById("logout-form");
            if (logoutForm) {
                logoutForm.addEventListener("submit", function () {
                    localStorage.removeItem(storageKey); // Remove storage flag when logging out
                });
            }
        });
    </script>
@endif