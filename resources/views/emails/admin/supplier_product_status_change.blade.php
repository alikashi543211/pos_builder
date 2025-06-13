<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier Profile Updated</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border: 1px solid #ddd;
            padding: 20px;
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 1px solid #ddd;
        }
        .header h1 {
            margin: 0;
            color: #333;
        }
        .content {
            padding: 20px 0;
            color: #555;
            line-height: 1.6;
        }
        .content h2 {
            margin-top: 0;
            color: #333;
        }
        .button-container {
            text-align: center;
            padding-top: 20px;
        }
        .button-container a {
            background-color: #28a745;
            color: #ffffff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
        }
        .footer {
            padding-top: 20px;
            text-align: center;
            font-size: 12px;
            color: #999;
        }
        @media only screen and (max-width: 600px) {
            .email-container {
                padding: 10px;
            }
            .header h1, .content h2 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>Product Status Updated</h1>
        </div>
        <div class="content">
            <h2>Dear {{ @$adminDetail->FullName }},</h2>
            <p>We hope this email finds you well.</p>
            <p>You have recently updated the status of supplier profile. The changes were made to ensure that supplier profile is accurate and up-to-date, reflecting the latest information about supplier business.</p>
            <h3>Details:</h3>
            <ul>
                <li><b>Supplier Name</b>&nbsp;&nbsp;{{ @$supplierDetail->supplier_public_name }}</li>
                <li><b>Previous Status</b>&nbsp;&nbsp;{{ @$previousStatus }}</li>
                <li><b>Updated Status</b>&nbsp;&nbsp;{{ $supplierDetail->getRawOriginal('status') }}</li>
            </ul>
            <p><b>Notes:</b> {{ @$supplierDetail->status_change_notes }}</p>
            {{-- <p>If you have any questions or need further clarification regarding these changes, please do not hesitate to contact us. We are here to assist you and ensure that your experience with us remains smooth and beneficial.</p> --}}
        </div>
        {{-- <div class="button-container">
            <a href="[Link to Profile]" target="_blank">View Your Profile</a>
        </div>
        <div class="footer">
            <p>Thank you for your continued partnership and for being a valued supplier.</p>
        </div> --}}
    </div>
</body>
</html>
