<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payment Slip - {{ $payment->id }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            font-size: 14px;
            padding: 20px;
        }
        .container {
            border: 2px solid #ddd;
            padding: 30px;
            position: relative;
        }
        .header-table {
            width: 100%;
            margin-bottom: 20px;
            border-bottom: 2px solid #eee;
            padding-bottom: 20px;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #1a73e8;
            margin: 0;
            text-transform: uppercase;
        }
        .company-details {
            font-size: 12px;
            color: #777;
            margin-top: 5px;
        }
        .receipt-title {
            text-align: right;
            font-size: 20px;
            font-weight: bold;
            color: #444;
            text-transform: uppercase;
        }
        .receipt-details {
            text-align: right;
            font-size: 12px;
            color: #555;
            margin-top: 5px;
        }
        .info-table {
            width: 100%;
            margin-bottom: 30px;
        }
        .info-label {
            font-weight: bold;
            color: #555;
            width: 120px;
        }
        .bill-to {
            margin-bottom: 20px;
        }
        .bill-to h3 {
            font-size: 14px;
            text-transform: uppercase;
            color: #777;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #ddd;
            padding: 12px;
            text-align: left;
            font-weight: bold;
            color: #444;
            text-transform: uppercase;
            font-size: 12px;
        }
        .items-table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }
        .items-table .amount-col {
            text-align: right;
        }
        .total-row td {
            border-top: 2px solid #333;
            border-bottom: none;
            font-weight: bold;
            font-size: 16px;
            padding-top: 15px;
        }
        .payment-status {
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-success {
            /* color: #22c55e;
            border: 1px solid #22c55e; */
            color: green;
        }
        .status-failed {
            color: #ef4444;
        }
        .footer-table {
            width: 100%;
            margin-top: 50px;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        .signature {
            text-align: right;
            padding-top: 40px;
        }
        .signature-line {
            border-top: 1px solid #333;
            display: inline-block;
            width: 200px;
            text-align: center;
            padding-top: 5px;
            font-size: 12px;
            font-weight: bold;
        }
        .terms {
            font-size: 10px;
            color: #999;
            text-align: center;
            margin-top: 20px;
        }
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 100px;
            color: rgba(0, 0, 0, 0.03);
            font-weight: bold;
            z-index: -1;
            pointer-events: none;
        }
    </style>
</head>
<body>

    <div class="container">
        
        <div class="watermark">PAID</div>

        <!-- Header Section -->
        <table class="header-table">
            <tr>
                <td valign="top">
                    <!-- Logo would go here -->
                    <div class="company-name">Global Trade Fairs</div>
                    <div class="company-details">
                        Powered by Reydel Mercado Online Services<br>
                        Email: support@globaltradefairs.com<br>
                        India
                    </div>
                </td>
                <td valign="top">
                    <div class="receipt-title">Payment Slip</div>
                    <div class="receipt-details">
                        <strong>Receipt #:</strong> GTF-{{ $payment->id }}<br>
                        <strong>Date:</strong> {{ $payment->created_at->format('d M Y') }}<br>
                        <strong>Transaction ID:</strong> {{ $payment->razorpay_payment_id }}
                    </div>
                </td>
            </tr>
        </table>

        <!-- Billed To Section -->
        <div class="bill-to">
            <h3>Billed To</h3>
            <table class="info-table">
                <tr>
                    <td class="info-label">Name:</td>
                    <td>{{ $payment->name }}</td>
                </tr>
                <tr>
                    <td class="info-label">Email:</td>
                    <td>{{ $payment->email }}</td>
                </tr>
                <tr>
                    <td class="info-label">Phone:</td>
                    <td>{{ $payment->phone }}</td>
                </tr>
            </table>
        </div>

        <!-- Line Items -->
        <table class="items-table">
            <thead>
                <tr>
                    <th width="60%">Description</th>
                    <th width="40%" class="amount-col">Amount (INR)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong>{{ $payment->package_name }}</strong><br>
                        <span style="font-size: 12px; color: #777;">{{ ucfirst($payment->package_type) }} Package</span>
                    </td>
                    <td class="amount-col">{{ number_format($payment->amount, 2) }}</td>
                </tr>
                
                @if($payment->breakdown && is_array($payment->breakdown))
                    @foreach($payment->breakdown as $item => $amount)
                    <tr>
                        <td style="padding-left: 20px; font-size: 12px; color: #666;">
                            • {{ $item }}
                        </td>
                        <td class="amount-col" style="font-size: 12px; color: #666;">
                            {{ number_format($amount, 2) }}
                        </td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td>Total Amount Paid</td>
                    <td class="amount-col">₹{{ number_format($payment->amount, 2) }}</td>
                </tr>
                <tr>
                    <td style="padding-top: 10px;">
                        Payment Status: <span class="payment-status status-success">{{ strtoupper($payment->status) }}</span>
                    </td>
                    <td></td>
                </tr>
            </tfoot>
        </table>

        <!-- Footer / Signature -->
        <table class="footer-table">
            <tr>
                <td width="50%" valign="bottom" style="font-size: 11px; color: #777;">
                    This is a computer generated receipt and does not require a physical signature.<br>
                    Thank you for your business.
                </td>
                <td width="50%" class="signature">
                    <div class="signature-line">
                        Authorized Signatory<br>
                        <span style="font-weight: normal; font-size: 10px;">Global Trade Fairs</span>
                    </div>
                </td>
            </tr>
        </table>

        <div class="terms">
            © {{ date('Y') }} Global Trade Fairs. All rights reserved.
        </div>

    </div>

</body>
</html>
