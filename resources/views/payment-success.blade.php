<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Payment Successful - Global Trade Fairs</title>
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>
    <style>
        @media print {
            body {
                background: white !important;
            }
            .print\\:hidden {
                display: none !important;
            }
            .max-w-2xl {
                max-width: 100% !important;
            }
        }
    </style>
    
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: { primary: "#1a73e8" },
                    fontFamily: { display: ["Inter", "sans-serif"] },
                },
            },
        };
    </script>
</head>

<body class="bg-[#f4f7fb] font-display min-h-screen flex items-center justify-center p-4">

    <div class="max-w-2xl w-full">
        
        <!-- Success Animation -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-green-100 rounded-full mb-6">
                <span class="material-icons text-green-600" style="font-size: 64px;">check_circle</span>
            </div>
            <h1 class="text-4xl font-extrabold text-gray-800 mb-3">Payment Successful!</h1>
            <p class="text-lg text-gray-600">Thank you for your purchase</p>
        </div>

        <!-- Payment Details Card -->
        <div class="bg-white rounded-2xl shadow-xl p-8 mb-6">
            <div class="border-b pb-6 mb-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Transaction Details</h2>
                
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500 mb-1">Transaction ID</p>
                        <p class="font-semibold text-gray-800 break-all">{{ $payment->razorpay_payment_id }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 mb-1">Order ID</p>
                        <p class="font-semibold text-gray-800">{{ $payment->id }}</p>
                    </div>
                </div>
            </div>

            <div class="border-b pb-6 mb-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Package Details</h2>
                <p class="text-2xl font-bold text-primary mb-2">{{ $payment->package_name }}</p>
                <p class="text-gray-600 mb-4">{{ ucfirst($payment->package_type) }} Package</p>
                
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-600 mb-2">Amount Paid</p>
                    <p class="text-3xl font-extrabold text-green-600">₹{{ number_format($payment->amount) }}</p>
                </div>
            </div>

            <div>
                <h2 class="text-xl font-bold text-gray-800 mb-4">Customer Information</h2>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Name:</span>
                        <span class="font-semibold text-gray-800">{{ $payment->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Email:</span>
                        <span class="font-semibold text-gray-800">{{ $payment->email }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Phone:</span>
                        <span class="font-semibold text-gray-800">{{ $payment->phone }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Box -->
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-6">
            <div class="flex items-start gap-3">
                <span class="material-icons text-blue-600 flex-shrink-0">info</span>
                <div class="text-sm text-blue-900">
                    <p class="font-semibold mb-2">What's Next?</p>
                    <ul class="space-y-1 text-blue-800">
                        <li>• A confirmation email has been sent to {{ $payment->email }}</li>
                        <li>• Our team will contact you within 24-48 hours</li>
                        <li>• You'll receive detailed trip itinerary soon</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col gap-4">
            <!-- Download Report Button -->
            <a 
                href="{{ route('payment.download', $payment->id) }}"
                class="w-full py-4 bg-green-600 text-white rounded-xl font-semibold
                       hover:bg-green-700 transition-all duration-300
                       flex items-center justify-center gap-2 shadow-lg print:hidden">
                <span class="material-icons">file_download</span>
                <span>Download Report / Save as PDF</span>
            </a>
            
            <div class="flex gap-4">
                <a href="{{ route('tour.packages') }}" 
                   class="flex-1 py-3 border-2 border-primary text-primary rounded-xl font-semibold text-center
                          hover:bg-primary hover:text-white transition-all duration-300 print:hidden">
                    Browse More Packages
                </a>
                <a href="{{ route('home') }}" 
                   class="flex-1 py-3 bg-primary text-white rounded-xl font-semibold text-center
                          hover:bg-primary-hover transition-all duration-300 print:hidden">
                    Go to Home
                </a>
            </div>
        </div>
    </div>

</body>
</html>
