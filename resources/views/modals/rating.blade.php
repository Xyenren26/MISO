<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Star Rating Modal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        /* Modal Overlay */
        #modalOverlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }

        /* Modal Container */
        #ratingModal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 1001;
            width: 400px;
            max-width: 90%;
        }

        #modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        #modal-header h5 {
            margin: 0;
        }

        #modal-header .btn-minimize {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
        }

        .modal-body {
            margin-bottom: 15px;
        }

        .star-rating {
            font-size: 24px;
            color: #ddd;
            cursor: pointer;
        }

        .star-rating .star {
            transition: color 0.2s;
        }

        .star-rating .star.hover,
        .star-rating .star.selected {
            color: #ffcc00;
        }

        .modal-footer {
            text-align: right;
        }

        .modal-footer button {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-left: 10px;
        }

        .modal-footer .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .modal-footer .btn-success {
            background: #28a745;
            color: white;
        }
    </style>
</head>
<body>

<!-- Background Overlay -->
<div class="modal-overlay" id="modalOverlay"></div>

<!-- Rating Modal -->
<div class="modal-container" id="ratingModal">
    <div class="modal-header bg-primary text-white" id="modal-header">
        <h5 class="modal-title">Rate Technical Support</h5>
        <div>
            <button type="button" class="btn-minimize" onclick="hideRating()">✖</button>
        </div>
    </div>
    <div class="modal-body text-center">
        <p><strong>Control No:</strong> <span id="ticketControlNo"></span></p>
        <p><strong>Technical Support:</strong> <span id="technicalSupportName"></span></p>
        <p>Please rate the technical support for this request:</p>
        <div class="star-rating">
            <i class="fas fa-star star" data-value="1"></i>
            <i class="fas fa-star star" data-value="2"></i>
            <i class="fas fa-star star" data-value="3"></i>
            <i class="fas fa-star star" data-value="4"></i>
            <i class="fas fa-star star" data-value="5"></i>
        </div>
        <input type="hidden" id="ratingValue">

        <!-- Remark Field -->
        <div class="form-group mt-3">
            <label for="remark"><strong>Remarks:</strong></label>
            <textarea class="form-control" id="remark" rows="3" placeholder="Add any additional feedback or comments..."></textarea>
        </div>
    </div>
    <div class="modal-footer text-center">
        <button type="button" class="btn btn-secondary" onclick="hideRating()">Cancel</button>
        <button type="button" class="btn btn-success" onclick="submitRating()">Submit</button>
    </div>
</div>

<!-- JavaScript for Star Rating and Modal Toggle -->
<script>
    // Reset stars to default state
    function resetStars() {
        const stars = document.querySelectorAll(".star-rating .star");
        stars.forEach(star => {
            star.classList.remove("selected", "hover");
        });
        document.getElementById("ratingValue").value = "";
    }

    // Star rating logic
    function initializeStarRating() {
        const stars = document.querySelectorAll(".star-rating .star");
        const ratingValue = document.getElementById("ratingValue");

        stars.forEach(star => {
            star.addEventListener("click", () => {
                const value = star.getAttribute("data-value");
                ratingValue.value = value;

                stars.forEach(s => {
                    s.classList.remove("selected");
                    if (s.getAttribute("data-value") <= value) {
                        s.classList.add("selected");
                    }
                });
            });

            star.addEventListener("mouseover", () => {
                const value = star.getAttribute("data-value");

                stars.forEach(s => {
                    s.classList.remove("hover");
                    if (s.getAttribute("data-value") <= value) {
                        s.classList.add("hover");
                    }
                });
            });

            star.addEventListener("mouseout", () => {
                stars.forEach(s => s.classList.remove("hover"));
            });
        });
    }

    function submitRating() {
        let rating = document.getElementById("ratingValue").value;
        let ticketId = document.getElementById("ticketControlNo").textContent;
        let techId = document.getElementById("technicalSupportName").getAttribute("data-tech-id");
        let remark = document.getElementById("remark").value; // Get remark value

        if (!rating || rating === "0") {
            alert("Please select a rating before submitting.");
            return;
        }

        fetch("/submit-rating", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                control_no: ticketId,
                technical_support_id: techId,
                rating: rating,
                remark: remark // Include remark in the request
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Rating submitted successfully!");
                hideRating();
                location.reload();
            } else {
                alert("Error submitting rating. Please try again.");
            }
        })
        .catch(error => console.error("Error:", error));
    }
</script>