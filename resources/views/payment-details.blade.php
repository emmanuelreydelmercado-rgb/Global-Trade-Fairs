<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Payment Details - {{ $packageDetails['name'] }}</title>
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>

    <!-- Razorpay Checkout -->
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#1a73e8",
                        "primary-hover": "#1557b0",
                    },
                    fontFamily: {
                        display: ["Inter", "sans-serif"],
                    },
                },
            },
        };
    </script>
</head>

<body class="bg-[#f4f7fb] font-display min-h-screen flex flex-col">

<!-- HEADER -->
<header class="sticky top-0 z-50 bg-white/80 backdrop-blur border-b shadow-sm">
    <div class="max-w-7xl mx-auto px-4 h-20 flex justify-between items-center">
        <div class="flex items-center gap-3">
            <img src="{{ asset('favicon.png') }}" class="w-10 rounded-lg">
            <h1 class="text-2xl font-bold">
                <span class="text-primary">GLOBAL</span> TRADE FAIRS
            </h1>
        </div>
        <a href="{{ route('tour.packages') }}" class="text-gray-700 hover:text-primary transition-colors flex items-center gap-2">
            <span class="material-icons">arrow_back</span>
            Back to Packages
        </a>
    </div>
</header>

<!-- MAIN CONTENT -->
<main class="flex-grow max-w-5xl mx-auto px-4 py-10 w-full">

    <div class="text-center mb-10">
        <h1 class="text-4xl font-extrabold text-gray-800 mb-3">Complete Your Purchase</h1>
        <p class="text-gray-600">Review your package details and proceed to payment</p>
    </div>

    <div class="grid md:grid-cols-5 gap-8">

        <!-- LEFT: Package Details (60%) -->
        <div class="md:col-span-3">
            
            <!-- Package Info Card -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
                <div class="flex items-center gap-3 mb-6">
                    @if($packageType == 'basic')
                        <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center">
                            <span class="material-icons text-white text-3xl">location_city</span>
                        </div>
                    @elseif($packageType == 'pro')
                        <div class="w-16 h-16 bg-gradient-to-br from-primary to-purple-600 rounded-2xl flex items-center justify-center">
                            <span class="material-icons text-white text-3xl">public</span>
                        </div>
                    @else
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-indigo-700 rounded-2xl flex items-center justify-center">
                            <span class="material-icons text-white text-3xl">emoji_events</span>
                        </div>
                    @endif
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">{{ $packageDetails['name'] }}</h2>
                        <p class="text-sm text-gray-500 uppercase tracking-wide">{{ ucfirst($packageType) }} Package</p>
                    </div>
                </div>

                <!-- Features -->
                <div class="mb-6">
                    <h3 class="font-semibold text-gray-700 mb-3">Package Includes:</h3>
                    <ul class="space-y-2">
                        @foreach($packageDetails['features'] as $feature)
                        <li class="flex items-start gap-2 text-sm text-gray-600">
                            <span class="material-icons text-green-500 flex-shrink-0" style="font-size: 18px;">check_circle</span>
                            <span>{{ $feature }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Price Breakdown -->
                <div class="border-t pt-6">
                    <h3 class="font-semibold text-gray-700 mb-4">Price Breakdown</h3>
                    <div class="space-y-3">
                        @foreach($packageDetails['breakdown'] as $item => $amount)
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-600">{{ $item }}</span>
                            <span class="font-semibold text-gray-800">₹{{ number_format($amount) }}</span>
                        </div>
                        @endforeach
                        
                        <div class="border-t pt-3 mt-3 flex justify-between items-center">
                            <span class="text-lg font-bold text-gray-800">Total Amount</span>
                            <span class="text-2xl font-extrabold text-primary">₹{{ number_format($packageDetails['amount']) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Security Badge -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 flex items-center gap-3">
                <span class="material-icons text-blue-600">lock</span>
                <div class="text-sm">
                    <p class="font-semibold text-blue-900">Secure Payment</p>
                    <p class="text-blue-700">Your payment information is encrypted and secure</p>
                </div>
            </div>
        </div>

        <!-- RIGHT: User Details Form (40%) -->
        <div class="md:col-span-2">
            <div class="bg-white rounded-2xl shadow-lg p-8 sticky top-24">
                <h3 class="text-xl font-bold text-gray-800 mb-6">Your Details</h3>
                
                <form id="paymentForm">
                    <input type="hidden" name="package_type" value="{{ $packageType }}">
                    
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name</label>
                        <input 
                            type="text" 
                            name="name" 
                            required
                            placeholder="Enter your full name"
                            class="w-full px-4 py-3 rounded-lg border focus:ring-2 focus:ring-primary focus:border-primary"
                        >
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                        <input 
                            type="email" 
                            name="email" 
                            required
                            placeholder="your.email@example.com"
                            class="w-full px-4 py-3 rounded-lg border focus:ring-2 focus:ring-primary focus:border-primary"
                        >
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Phone Number</label>
                        <input 
                            type="tel" 
                            name="phone" 
                            required
                            placeholder="+91 9876543210"
                            class="w-full px-4 py-3 rounded-lg border focus:ring-2 focus:ring-primary focus:border-primary"
                        >
                    </div>

                    <button 
                        type="submit" 
                        id="payButton"
                        class="w-full py-4 bg-gradient-to-r from-primary to-purple-600 text-white font-bold rounded-xl
                               transition-all duration-300 hover:shadow-lg hover:shadow-primary/50
                               flex items-center justify-center gap-2 group"
                    >
                        <span class="material-icons">payment</span>
                        <span>Proceed to Pay ₹{{ number_format($packageDetails['amount']) }}</span>
                    </button>

                    <p class="text-xs text-center text-gray-500 mt-4">
                        By proceeding, you agree to our Terms & Conditions
                    </p>
                </form>

                <!-- Razorpay Logo -->
                <div class="mt-6 pt-6 border-t text-center">
                    <p class="text-xs text-gray-500 mb-2">Secured by</p>
                    <img src="https://razorpay.com/assets/razorpay-glyph.svg" alt="Razorpay" class="h-6 mx-auto">
                </div>
            </div>
        </div>

    </div>

</main>

<!-- FOOTER -->
<footer class="bg-primary text-white mt-16 py-6">
    <div class="max-w-7xl mx-auto px-6 text-center text-sm">
        © 2025 Global Trade Fairs — Powered by Reydel Mercado Online services
    </div>
</footer>

<script>
    document.getElementById('paymentForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const button = document.getElementById('payButton');
        button.disabled = true;
        button.innerHTML = '<span class="material-icons animate-spin">refresh</span> Processing...';

        const formData = new FormData(this);
        
        try {
            // Create order
            const response = await fetch('{{ route("payment.create") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(Object.fromEntries(formData))
            });

            const data = await response.json();

            if (!data.success) {
                throw new Error(data.message);
            }

            // Initialize Razorpay
            const options = {
                key: data.key,
                amount: data.amount * 100,
                currency: 'INR',
                name: 'Global Trade Fairs',
                description: '{{ $packageDetails["name"] }}',
                image: '{{ asset("favicon.png") }}',
                order_id: data.order_id,
                handler: async function(response) {
                    try {
                        console.log('Payment successful, verifying...', response);
                        
                        // Verify payment
                        const verifyResponse = await fetch('{{ route("payment.verify") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                razorpay_payment_id: response.razorpay_payment_id,
                                razorpay_order_id: response.razorpay_order_id,
                                razorpay_signature: response.razorpay_signature,
                                payment_id: data.payment_id
                            })
                        });

                        const verifyData = await verifyResponse.json();
                        console.log('Verification response:', verifyData);
                        
                        if (verifyData.success) {
                            console.log('Redirecting to:', verifyData.redirect_url);
                            window.location.href = verifyData.redirect_url;
                        } else {
                            console.error('Verification failed');
                            window.location.href = verifyData.redirect_url || '{{ route("payment.failure") }}';
                        }
                    } catch (error) {
                        console.error('Verification error:', error);
                        alert('Verification failed: ' + error.message);
                        window.location.href = '{{ route("payment.failure") }}';
                    }
                },
                prefill: {
                    name: formData.get('name'),
                    email: formData.get('email'),
                    contact: formData.get('phone')
                },
                theme: {
                    color: '#1a73e8'
                },
                modal: {
                    ondismiss: function() {
                        button.disabled = false;
                        button.innerHTML = '<span class="material-icons">payment</span><span>Proceed to Pay ₹{{ number_format($packageDetails["amount"]) }}</span>';
                    }
                }
            };

            const razorpay = new Razorpay(options);
            razorpay.on('payment.failed', function(response) {
                window.location.href = '{{ route("payment.failure") }}';
            });
            razorpay.open();

        } catch (error) {
            alert('Error: ' + error.message);
            button.disabled = false;
            button.innerHTML = '<span class="material-icons">payment</span><span>Proceed to Pay ₹{{ number_format($packageDetails["amount"]) }}</span>';
        }
    });
</script>

</body>
</html>
