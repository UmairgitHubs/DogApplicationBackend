<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset OTP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background-color: #58A509;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }

        .content {
            background-color: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 8px 8px;
        }

        .otp-code {
            background-color: #58A509;
            color: white;
            font-size: 32px;
            font-weight: bold;
            text-align: center;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            letter-spacing: 5px;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 14px;
        }

        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>DogTranslation.com</h1>
        <p>Password Reset Request</p>
    </div>

    <div class="content">
        <h2>Hello {{ $user->name }}!</h2>

        <p>We received a request to reset your password for your DogTranslation.com account. To proceed with the password reset, please use the following verification code:</p>

        <div class="otp-code">
            {{ $otp }}
        </div>

        <div class="warning">
            <p><strong>Security Notice:</strong></p>
            <ul>
                <li>This code will expire in 15 minutes</li>
                <li>Do not share this code with anyone</li>
                <li>If you didn't request this password reset, please ignore this email</li>
                <li>Your account remains secure if you don't take any action</li>
            </ul>
        </div>

        <p>If you have any questions or concerns, please contact our support team immediately.</p>

        <p>Best regards,<br>The DogTranslation.com Team</p>
    </div>

    <div class="footer">
        <p>This is an automated email. Please do not reply to this message.</p>
        <p>If you didn't request this password reset, please contact support.</p>
    </div>
</body>

</html>