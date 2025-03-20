<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Technical Report</title>
    <style>
        /* Global Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
            background-color: #ffffff; /* White background for PDF */
        }

        .modal-content-technical-report {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: #fff;
        }

        /* Header */
        .form-header {
            font-size: 24px;
            font-weight: 600;
            color: #003067;
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #003067;
            padding-bottom: 10px;
        }

        /* Rating Container */
        .rating-container {
            margin-bottom: 20px;
            text-align: left;
        }

        .rating-container label {
            font-weight: bold;
            color: #003067;
        }

        #starRatingTechnical {
            font-size: 20px;
            color: #FFD700; /* Gold color for stars */
        }

        /* Form Groups */
        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            font-weight: bold;
            color: #003067;
            display: block;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background: #f9f9f9;
            font-size: 14px;
            box-sizing: border-box;
        }

        .form-group textarea {
            resize: none; /* Disable resizing for PDF */
        }

        /* Approval and Inspection Sections */
        .form-popup-section {
            margin-top: 20px;
            clear: both; /* Clear floats */
        }

        .form-popup-title {
            font-size: 20px;
            font-weight: 600;
            color: #003067;
            margin-bottom: 15px;
            border-bottom: 2px solid #003067;
            padding-bottom: 8px;
        }

        .form-popup-input-group {
            margin-bottom: 15px;
        }

        .form-popup-input-group label {
            font-weight: bold;
            color: #003067;
            display: block;
            margin-bottom: 5px;
        }

        .form-popup-input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background: #f9f9f9;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="modal-content-technical-report">
        <!-- Rating -->
        <div class="rating-container">
            <label>Rating:</label>
            <div id="starRatingTechnical" class="star-rating">{{ $rating }}
                @php
                    $ratingValue = (int)$rating;
                    echo str_repeat('★', $ratingValue) . str_repeat('☆', 5 - $ratingValue);
                @endphp
            </div>
        </div>

        <!-- Header -->
        <div class="form-header">Technical Report Details</div>

        <!-- Non-Editable Fields -->
        <div class="form-group">
            <label>Technical Report No.</label>
            <input type="text" value="{{ $technicalReport->TR_id }}" readonly disabled>
        </div>

        <div class="form-group">
            <label>Date and Time</label>
            <input type="datetime-local" value="{{ $technicalReport->date_time }}" readonly disabled>
        </div>

        <div class="form-group">
            <label>Department</label>
            <input type="text" value="{{ $technicalReport->department }}" readonly disabled>
        </div>

        <div class="form-group">
            <label>End User</label>
            <input type="text" value="{{ $technicalReport->enduser }}" readonly disabled>
        </div>

        <div class="form-group">
            <label>Problem</label>
            <textarea readonly disabled>{{ $technicalReport->problem }}</textarea>
        </div>

        <div class="form-group">
            <label>Work Done</label>
            <textarea readonly disabled>{{ $technicalReport->workdone }}</textarea>
        </div>

        <!-- Editable Fields -->
        <div class="form-group">
            <label>Specification</label>
            <textarea readonly>{{ $technicalReport->specification }}</textarea>
        </div>

        <div class="form-group">
            <label>Findings</label>
            <textarea readonly>{{ $technicalReport->findings }}</textarea>
        </div>

        <div class="form-group">
            <label>Recommendation</label>
            <textarea readonly>{{ $technicalReport->recommendation }}</textarea>
        </div>

        <!-- Inspection Section -->
        <section class="form-popup-section">
            <h3 class="form-popup-title">Inspection Details</h3>
            <div class="form-popup-input-group">
                <label class="form-popup-label">Inspected By:</label>
                <input class="form-popup-input" value="{{ $technicalReport->inspected_by }}" readonly disabled>
            </div>
            <div class="form-popup-input-group">
                <label class="form-popup-label">Inspected Date:</label>
                <input class="form-popup-input" value="{{ $technicalReport->inspected_date }}" readonly disabled>
            </div>
        </section>

        <!-- Approval Section -->
        <section class="form-popup-section">
            <h3 class="form-popup-title">Approval Details</h3>
            <div class="form-popup-input-group">
                <label class="form-popup-label">Noted By:</label>
                <input class="form-popup-input" value="{{ $approve->name }}" readonly disabled>
            </div>
            <div class="form-popup-input-group">
                <label class="form-popup-label">Approval Date:</label>
                <input class="form-popup-input" value="{{ $approve->approve_date }}" readonly disabled>
            </div>
        </section>

        <!-- Technical Support Division Head -->
        <section class="form-popup-section">
            <h3>Technical Support Division (MISO) Head</h3>
            <div class="form-popup-input-group">
                <label>Department Head:</label>
                <input class="form-popup-input" value="Mr. Cecilio V. Demano" readonly>
            </div>
        </section>
    </div>
</body>
</html>