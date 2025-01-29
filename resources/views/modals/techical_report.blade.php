<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Technical Report Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .header img {
            height: 80px;
        }
        .header .title {
            font-size: 20px;
            font-weight: bold;
        }
        .form-header {
            text-align: center;
            font-size: 24px;
            text-transform: uppercase;
            margin-bottom: 20px;
            background-color: #003067;
            color: white;
            padding: 15px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .form-group input, .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .form-group textarea {
            resize: vertical;
            height: 80px;
        }
        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }
        .signature {
            flex: 1;
            margin-right: 20px;
        }
        .signature:last-child {
            margin-right: 0;
        }
        .signature label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .signature input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
    </style>
</head>
<body>

<div class="header">
    <img src="public/image/systemlogo.png" alt="System Logo">
    <div class="title">Management Information System Office</div>
</div>

<div class="form-header">Technical Report</div>

<form>
    <div class="form-group">
        <label for="date-time">Date and Time</label>
        <input type="datetime-local" id="date-time" name="date-time">
    </div>

    <div class="form-group">
        <label for="department">Department</label>
        <input type="text" id="department" name="department">
    </div>

    <div class="form-group">
        <label for="enduser">End User</label>
        <input type="text" id="enduser" name="enduser">
    </div>

    <div class="form-group">
        <label for="specification">Specification</label>
        <textarea id="specification" name="specification"></textarea>
    </div>

    <div class="form-group">
        <label for="problem">Problem</label>
        <textarea id="problem" name="problem"></textarea>
    </div>

    <div class="form-group">
        <label for="workdone">Work Done</label>
        <textarea id="workdone" name="workdone"></textarea>
    </div>

    <div class="form-group">
        <label for="findings">Findings</label>
        <textarea id="findings" name="findings"></textarea>
    </div>

    <div class="form-group">
        <label for="recommendation">Recommendation</label>
        <textarea id="recommendation" name="recommendation"></textarea>
    </div>

    <div class="signatures">
        <div class="signature">
            <label for="reported">Reported By</label>
            <input type="text" id="reported" name="reported">

            <label for="reported-date">Date and Time</label>
            <input type="datetime-local" id="reported-date" name="reported-date">
        </div>

        <div class="signature">
            <label for="inspected">Inspected By</label>
            <input type="text" id="inspected" name="inspected">

            <label for="inspected-date">Date and Time</label>
            <input type="datetime-local" id="inspected-date" name="inspected-date">
        </div>

        <div class="signature">
            <label for="noted">Noted By</label>
            <input type="text" id="noted" name="noted">

            <label for="noted-date">Date and Time</label>
            <input type="datetime-local" id="noted-date" name="noted-date">
        </div>
    </div>
</form>

</body>
</html>
