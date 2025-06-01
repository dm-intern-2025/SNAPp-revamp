<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Account Password</title>
    <style>
        /* Basic reset and body styles */
        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fa;
            color: #333;
        }

        .email-container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* Flux UI: Header */
        .email-header {
            text-align: center;
            padding-bottom: 20px;
        }

        .email-header h1 {
            color: #3f51b5;
            font-size: 28px;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .email-header p {
            font-size: 16px;
            color: #555;
            margin: 0;
        }

        /* Flux UI: Content */
        .email-body {
            font-size: 16px;
            color: #333;
            line-height: 1.5;
        }

        .password-box {
            background-color: #f1f5f9;
            padding: 15px;
            text-align: center;
            border-radius: 6px;
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
            margin-top: 20px;
        }

        .email-body p {
            margin-bottom: 20px;
        }

        /* Flux UI: Button */
        .cta-btn {
            display: inline-block;
            padding: 12px 30px;
            background-color: #3f51b5;
            color: white;
            text-decoration: none;
            font-size: 16px;
            border-radius: 6px;
            margin-top: 20px;
        }

        /* Footer */
        .email-footer {
            text-align: center;
            font-size: 12px;
            color: #aaa;
            margin-top: 40px;
        }

        .email-footer p {
            margin: 0;
        }

        @media (max-width: 600px) {
            .email-container {
                padding: 20px;
            }

            .email-header h1 {
                font-size: 24px;
            }

            .password-box {
                font-size: 16px;
                padding: 12px;
            }
        }
    </style>
</head>
<body>

    <div class="email-container">
        <!-- Header Section -->
        <div class="email-header">
            <h1>Welcome to Our Service!</h1>
            <p>We're excited to have you with us. Here's your generated password.</p>
        </div>

        <!-- Body Section -->
        <div class="email-body">
            <p>Your generated password is:</p>
            <div class="password-box">
                {{ $password }}
            </div>
            <p>For security purposes, please change your password once you log in.</p>
            <a href="{{ config('app.url') }}" class="cta-btn">Login Now</a>
        </div>

        <!-- Footer Section -->
        <div class="email-footer">
            <p>If you didn't request this, please ignore this email.</p>
        </div>
    </div>

</body>
</html>
