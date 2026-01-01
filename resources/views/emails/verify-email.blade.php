<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Verify Email Address</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 40px 0;
            color: #333333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo img {
            max-width: 200px;
            height: auto;
        }
        .content {
            line-height: 1.6;
            font-size: 16px;
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .button {
            display: inline-block;
            background-color: #007bff; /* Adjust color as needed */
            color: #ffffff !important;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            font-size: 16px;
        }
        .footer {
            margin-top: 30px;
            font-size: 14px;
            color: #666666;
            border-top: 1px solid #eeeeee;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            {{-- Assuming favicon.png is the logo based on file listing. Adjust path if strict logo exists --}}
            <img src="{{ asset('favicon.png') }}" alt="Global Trade Fairs Logo">
        </div>
        
        <div class="content">
            <p>Dear Sir or Madam,</p>
            
            <p>Thank you for registering with Global Trade Fairs.</p>
            
            <p>To complete your registration, please verify your email address by clicking the link below:</p>
            
            <div class="button-container">
                <a href="{{ $url }}" class="button">Verify Email Address</a>
            </div>
            
            <p>This step is required to confirm the accuracy of your contact information and to ensure proper account communication.</p>
            
            <p>If you did not initiate this registration, please disregard this message.</p>
            
            <p>Should you require any assistance, kindly reply to this email.</p>
            
            <p>Yours sincerely,<br>
            Global Trade Fairs</p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Global Trade Fairs. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
