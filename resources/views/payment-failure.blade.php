<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Payment Failed - Global Trade Fairs</title>
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>
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

    @include('partials.google-analytics')
</head>

<body class="bg-[#f4f7fb] font-display min-h-screen flex items-center justify-center p-4">

    <div class="max-w-2xl w-full text-center">
        
        <!-- Error Icon -->
        <div class="inline-flex items-center justify-center w-24 h-24 bg-red-100 rounded-full mb-6">
            <span class="material-icons text-red-600" style="font-size: 64px;">cancel</span>
        </div>

        <h1 class="text-4xl font-extrabold text-gray-800 mb-3">Payment Failed</h1>
        <p class="text-lg text-gray-600 mb-8">Unfortunately, we couldn't process your payment</p>

        <!-- Error Card -->
        <div class="bg-white rounded-2xl shadow-xl p-8 mb-6">
            <div class="bg-red-50 border border-red-200 rounded-lg p-6 mb-6">
                <div class="flex items-start gap-3">
                    <span class="material-icons text-red-600 flex-shrink-0">error_outline</span>
                    <div class="text-left text-sm text-red-900">
                        <p class="font-semibold mb-2">Common reasons for payment failure:</p>
                        <ul class="space-y-1 text-red-800">
                            <li>• Insufficient funds in your account</li>
                            <li>• Incorrect card details or CVV</li>
                            <li>• Payment cancelled by user</li>
                            <li>• Bank declined the transaction</li>
                            <li>• Network connectivity issues</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="border-t pt-6">
                <p class="text-gray-600 mb-4">If the amount was deducted from your account, it will be refunded within 5-7 business days.</p>
                <p class="text-sm text-gray-500">For assistance, please contact our support team.</p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4">
            <a href="{{ route('tour.packages') }}" 
               class="flex-1 py-3 bg-primary text-white rounded-xl font-semibold
                      hover:bg-primary-hover transition-all duration-300 inline-flex items-center justify-center gap-2">
                <span class="material-icons">refresh</span>
                <span>Try Again</span>
            </a>
            <a href="{{ route('home') }}" 
               class="flex-1 py-3 border-2 border-gray-300 text-gray-700 rounded-xl font-semibold
                      hover:bg-gray-50 transition-all duration-300">
                Go to Home
            </a>
        </div>

        <!-- Support Info -->
        <div class="mt-8 text-sm text-gray-600">
            <p>Need help? Contact us at <a href="mailto:support@globaltradefairs.com" class="text-primary font-semibold">support@globaltradefairs.com</a></p>
        </div>
    </div>

</body>
</html>
