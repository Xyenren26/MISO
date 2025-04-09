<!DOCTYPE html>
<html>
<head>
    <title>Ticket Approval Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            text-align: center;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .logo {
            width: 300px;
            margin: 20px auto;
            display: block;
        }
        h2 {
            color: #333;
        }
        p {
            color: #555;
            font-size: 16px;
            line-height: 1.5;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #777;
        }
        .download-btn {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #003067;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .download-btn:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }
    </style>
    <script>
        function downloadModalAsPDFTechnical() {
            const controlNo = '{{ $ticketId }}';
            window.location.href = `/technical-report/download/${controlNo}`;
        }

        function downloadModalAsPDF() {
            const controlNo = '{{ $ticketId }}';
            window.location.href = `/endorsement/download/${controlNo}`;
        }
    </script>
</head>
<body>
<div class="container">
    <img src="https://i.imgur.com/aQrIRgy.png" alt="System Logo" class="logo">
    <p>Dear User,</p>
    <p>Your ticket with Control No: <strong>{{ $ticketId }}</strong> has been approved.</p>
    <p><strong>Status:</strong> {{ $status }}</p>
    <p>Approved by: <strong>{{ $approvedBy }}</strong></p>
    <p>Date Approved: <strong>{{ $approveDate }}</strong></p>


    @if($status === 'technical-report' && $technicalReportId)
        <a href="{{ route('technical_report.download', ['id' => $technicalReportId]) }}"
        style="display: inline-block; padding: 10px 20px; background-color: #003067; color: white; text-decoration: none; border-radius: 5px;">
            Download Technical Report PDF
        </a>
    @elseif($status === 'endorsed' && $endorsementControlNo)
        <a href="{{ route('endorsement.download', ['id' => $endorsementControlNo]) }}"
        style="display: inline-block; padding: 10px 20px; background-color: #003067; color: white; text-decoration: none; border-radius: 5px;">
            Download Endorsement PDF
        </a>
    @endif




    <p class="footer">Regards, <br> <strong>{{ config('app.name') }}</strong></p>
</div>
</body>
</html>
