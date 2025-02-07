<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Request PDF</title>
    <style>
    /* Style for In and Out New Form */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
}

h1 {
    text-align: center;
    margin-top: 20px;
    font-size: 2em;
}

h3 {
    margin-top: 20px;
    font-size: 1.5em;
}

/* Form Info */
.form-popup-form-info {
    text-align: center;
    font-size: 1.2em;
    color: #555;
    margin: 15px 0;
}

/* Form Section */
.form-popup-section {
    margin: 20px 15%;
}

.form-popup-row {
    display: flex;
    gap: 20px;
    margin-bottom: 15px;
}

.form-popup-input-group {
    flex: 1;
    margin-bottom: 10px;
}

.form-popup-label {
    font-weight: bold;
    display: block;
    margin-bottom: 5px;
}

.form-popup-input {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 1em;
}

/* Table Styling */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th, td {
    padding: 12px 15px;
    border: 1px solid #ddd;
    text-align: center;
}

th {
    background-color: #f2f2f2;
    font-weight: bold;
}

td {
    font-size: 1em;
}

td input[type="checkbox"] {
    width: 20px;
    height: 20px;
    margin: 0 auto;
}

/* Button Styling */
.form-popup-button {
    width: 100%;
    padding: 10px;
    background-color: #007BFF;
    color: white;
    font-weight: bold;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: 0.3s;
}

.form-popup-button:hover {
    background-color: #0056b3;
}

</style>

</head>
<body>
<h1>ICT Equipment Service Request Form</h1>

<div class="form-popup-form-info">
    <span><strong>Form No.:</strong> {{ $form_no }}</span>
</div>

<!-- General Information Section -->
<section class="form-popup-section">
    <h3>General Information</h3>
    
    <div class="form-popup-row">
        <div class="form-popup-input-group">
            <label class="form-popup-label">Employee Name:</label>
            <input class="form-popup-input" value="{{ $name }}" readonly>
        </div>
        
        <div class="form-popup-input-group">
            <label class="form-popup-label">Employee ID:</label>
            <input class="form-popup-input" value="{{ $employee_id }}" readonly>
        </div>
    </div>

    <div class="form-popup-input-group">
        <label class="form-popup-label">Department / Office / Unit:</label>
        <input class="form-popup-input" value="{{ $department }}" readonly>
    </div>

    <div class="form-popup-input-group">
        <label class="form-popup-label">Condition:</label>
        <input class="form-popup-input" value="{{ $condition }}" readonly>
    </div>
</section>

<!-- Equipment Details -->
<h3>Equipment Details</h3>
<table>
    <thead>
        <tr>
            <th>Brand</th>
            <th>Description</th>
            <th>Motherboard</th>
            <th>RAM</th>
            <th>HDD</th>
            <th>Accessories</th>
            <th>Remarks</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($equipment_descriptions as $equipment)
        <tr>
            <td>{{ $equipment['brand'] }}</td>
            <td>{{ $equipment['equipment_type'] }}</td>
            
            @foreach (['Motherboard', 'RAM', 'HDD', 'Accessories'] as $part)
                <td>
                    @if (in_array($part, $equipment['equipment_parts']))
                        [X] <!-- Checked checkbox -->
                    @else
                        [ ] <!-- Unchecked checkbox -->
                    @endif
                </td>
            @endforeach
            
            <td>{{ $equipment['remarks'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-- Technical Support -->
<div class="form-popup-input-group">
    <label class="form-popup-label">Technical Support:</label>
    <input class="form-popup-input" value="{{ $technical_support }}" readonly>
</div>

</body>
</html>