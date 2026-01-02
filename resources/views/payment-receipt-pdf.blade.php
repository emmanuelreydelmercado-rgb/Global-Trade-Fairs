<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payment Receipt - {{ $payment->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #1a73e8;
        }
        .header h1 {
            color: #1a73e8;
            margin: 0;
            font-size: 28px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .section {
            margin: 25px 0;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 8px;
        }
        .section h2 {
            margin: 0 0 15px 0;
            color: #1a73e8;
            font-size: 18px;
            border-bottom: 2px solid #e0e0e0;
            padding-bottom: 8px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e0e0e0;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .label {
            font-weight: bold;
            color: #666;
        }
        .value {
            color: #333;
        }
        .amount {
            font-size: 32px;
            font-weight: bold;
            color: #22c55e;
            text-align: center;
            margin: 20px 0;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #999;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }
        .success-badge {
            background: #22c55e;
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            display: inline-block;
            margin: 10px 0;
            font-size: 14px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>GLOBAL TRADE FAIRS</h1>
        <p>Payment Receipt</p>
        <div class="success-badge">✓ PAYMENT SUCCESSFUL</div>
    </div>

    <div class="section">
        <h2>Transaction Details</h2>
        <div class="info-row">
            <span class="label">Transaction ID:</span>
            <span class="value">{{ $payment->razorpay_payment_id }}</span>
        </div>
        <div class="info-row">
            <span class="label">Order ID:</span>
            <span class="value">{{ $payment->id }}</span>
        </div>
        <div class="info-row">
            <span class="label">Date:</span>
            <span class="value">{{ $payment->created_at->format('d M Y, h:i A') }}</span>
        </div>
        <div class="info-row">
            <span class="label">Status:</span>
            <span class="value" style="color: #22c55e; font-weight: bold;">{{ strtoupper($payment->status) }}</span>
        </div>
    </div>

    <div class="section">
        <h2>Package Details</h2>
        <div class="info-row">
            <span class="label">Package Name:</span>
            <span class="value">{{ $payment->package_name }}</span>
        </div>
        <div class="info-row">
            <span class="label">Package Type:</span>
            <span class="value">{{ ucfirst($payment->package_type) }} Package</span>
        </div>
    </div>

    <div class="section">
        <h2>Amount Paid</h2>
        <div class="amount">₹{{ number_format($payment->amount) }}</div>
        
        @if($payment->breakdown)
        <h3 style="margin-top: 20px; color: #666; font-size: 14px;">Price Breakdown:</h3>
        @foreach($payment->breakdown as $item => $amount)
        <div class="info-row">
            <span class="label">{{ $item }}:</span>
            <span class="value">₹{{ number_format($amount) }}</span>
        </div>
        @endforeach
        @endif
    </div>

    <div class="section">
        <h2>Customer Information</h2>
        <div class="info-row">
            <span class="label">Name:</span>
            <span class="value">{{ $payment->name }}</span>
        </div>
        <div class="info-row">
            <span class="label">Email:</span>
            <span class="value">{{ $payment->email }}</span>
        </div>
        <div class="info-row">
            <span class="label">Phone:</span>
            <span class="value">{{ $payment->phone }}</span>
        </div>
    </div>

    <div class="footer">
        <p><strong>Thank you for your purchase!</strong></p>
        <p>For any queries, please contact us at support@globaltradefairs.com</p>
        <p style="margin-top: 15px;">© 2025 Global Trade Fairs - Powered by Reydel Mercado Online Services</p>
    </div>

</body>
</html>
