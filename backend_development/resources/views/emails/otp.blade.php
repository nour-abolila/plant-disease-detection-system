<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thamara OTP Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0faf0;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #ffffff;
            border-radius: 15px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            padding: 40px 30px;
            text-align: center;
            position: relative;
        }

        .logo {
            font-size: 32px;
            font-weight: bold;
            color: #4CAF50;
            margin-bottom: 20px;
        }

        .logo span {
            font-size: 24px;
            vertical-align: middle;
        }

        h2 {
            color: #333;
            margin-bottom: 10px;
            font-size: 24px;
        }

        p {
            color: #555;
            font-size: 16px;
            margin-bottom: 25px;
        }

        .otp-code {
            display: inline-block;
            background-color: #4CAF50;
            color: #fff;
            font-size: 36px;
            font-weight: bold;
            padding: 15px 30px;
            border-radius: 12px;
            letter-spacing: 5px;
            margin-bottom: 25px;
        }

        .plants-icons {
            font-size: 28px;
            margin-bottom: 25px;
        }

        .footer {
            font-size: 14px;
            color: #888;
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="logo">Thamara</div>
        <div class="plants-icons">🌱🌿🌷🌿🌱</div>
        <h2>Email Verification Code</h2>
        <p>Use this code to verify your email address and activate your Thamara account.</p>
        <div class="otp-code">{{ $otp }}</div>
        <p>The code is valid for 10 minutes.</p>
        <div class="footer">
            &copy; {{ date('Y') }} Thamara. All rights reserved.
        </div>
    </div>
</body>

</html>
