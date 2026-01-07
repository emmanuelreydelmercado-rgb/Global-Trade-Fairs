<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Expo Tour Packages - Global Trade Fairs</title>
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            darkMode: 'class',
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

    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 20px rgba(26, 115, 232, 0.3); }
            50% { box-shadow: 0 0 40px rgba(26, 115, 232, 0.6); }
        }

        @keyframes shimmer {
            0% { background-position: -1000px 0; }
            100% { background-position: 1000px 0; }
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        .animate-pulse-glow {
            animation: pulse-glow 2s ease-in-out infinite;
        }

        .shimmer-effect {
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.8), transparent);
            background-size: 1000px 100%;
            animation: shimmer 3s infinite;
        }

        .gradient-border {
            position: relative;
            background: linear-gradient(white, white) padding-box,
                        linear-gradient(135deg, #1a73e8, #86efac, #a855f7) border-box;
            border: 3px solid transparent;
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
    </style>

    @include('partials.google-analytics')
</head>

<body class="bg-[#f4f7fb] font-display min-h-screen flex flex-col">

<!-- ================= HEADER ================= -->
<header class="sticky top-0 z-50 bg-white/80 backdrop-blur border-b shadow-sm" x-data="{ mobileMenuOpen: false }">
    <div class="max-w-7xl mx-auto px-4 h-20 flex justify-between items-center gap-4">

        <div class="flex items-center gap-3">
            <img src="{{ asset('favicon.png') }}" class="w-10 rounded-lg">
            <h1 class="text-2xl font-bold">
                <span class="text-primary">GLOBAL</span> TRADE FAIRS
            </h1>
        </div>

        <!-- Navigation -->
        <nav class="hidden lg:flex items-center gap-6">
            <a href="{{ route('home') }}" class="text-gray-700 hover:text-primary transition-colors">Events</a>
            <a href="{{ route('tour.packages') }}" class="text-primary font-semibold">Tour Packages</a>
        </nav>

        <!-- Search -->
        <form action="{{ route('home') }}" method="GET" class="hidden md:block w-[420px] relative">
            <span class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search Events, Organizers, or Venues..."
                class="w-full pl-10 pr-4 py-2 rounded-full border focus:ring-primary focus:border-primary">
        </form>

    
    {{-- Auth Section --}}
    <div class="hidden lg:flex items-center gap-4">
        @if(Auth::check())
            <!-- Profile Dropdown -->
            <div class="relative" x-data="{ profileOpen: false }">
                <!-- Trigger: Profile Image -->
                <button 
                    @click="profileOpen = !profileOpen" 
                    @click.outside="profileOpen = false"
                    class="relative flex items-center gap-2 focus:outline-none transition-transform hover:scale-105"
                >
                    <img
                        src="{{ (auth()->user()->profilepic && auth()->user()->profilepic !== 'default.jpg')
                            ? asset('profilepics/' . auth()->user()->profilepic)
                            : asset('profilepics/user_avatar.png') }}"
                        onerror="this.src='{{ asset('profilepics/user_avatar.png') }}'"
                        class="w-10 h-10 rounded-full object-cover border-2 border-white ring-2 ring-blue-500 shadow-md"
                        alt="User Profile"
                    >
                    <!-- Lil Green Circle (Online Status) -->
                    <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></span>
                </button>

                <!-- Dropdown Menu -->
                <div 
                    x-show="profileOpen"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                    x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                    class="absolute right-0 mt-3 w-72 bg-white rounded-2xl shadow-xl border border-gray-100 z-50 overflow-hidden"
                    style="display: none;"
                >
                    <!-- User Info Section -->
                    <div class="flex flex-col items-center p-6 border-b border-gray-50 bg-gradient-to-b from-white to-gray-50/50">
                        <div class="relative mb-3">
                            <img
                                src="{{ (auth()->user()->profilepic && auth()->user()->profilepic !== 'default.jpg')
                                    ? asset('profilepics/' . auth()->user()->profilepic)
                                    : asset('profilepics/user_avatar.png') }}"
                                onerror="this.src='{{ asset('profilepics/user_avatar.png') }}'"
                                class="w-20 h-20 rounded-full object-cover shadow-lg border-4 border-white"
                                alt="User"
                            >
                            <span class="absolute bottom-1 right-1 w-4 h-4 bg-green-500 border-2 border-white rounded-full"></span>
                        </div>
                        
                        <h3 class="font-bold text-gray-800 text-lg text-center leading-tight">
                            {{ auth()->user()->name }}
                        </h3>
                        <p class="text-xs text-gray-500 text-center mt-1 font-medium bg-gray-100 px-3 py-1 rounded-full">
                            {{ auth()->user()->email }}
                        </p>
                    </div>

                    <!-- Actions -->
                    <div class="p-2 bg-gray-50/30">
                        <!-- You can add more menu items here later -->
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button
                                type="submit"
                                class="w-full flex items-center justify-center gap-2 px-4 py-3 text-sm font-semibold text-red-600 hover:bg-red-50 rounded-xl transition-all group"
                            >
                                <span class="material-icons text-xl group-hover:-translate-x-1 transition-transform">logout</span>
                                Sign Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @else
            <a href="{{ route('login') }}"
            class="bg-primary text-white px-4 py-2 rounded-full text-sm font-medium hover:bg-primary-hover">
                Sign In
            </a>
        @endif

        <!-- Mobile Menu Button (Hidden in favor of Bottom Nav) -->
        <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden hidden p-2 text-gray-600 hover:bg-gray-100 rounded-full transition-colors">
            <span class="material-icons" x-text="mobileMenuOpen ? 'close' : 'menu'">menu</span>
        </button>
    </div>




    </div>
</header>

<!-- ================= HERO SECTION ================= -->
<section class="relative overflow-hidden bg-gradient-to-br from-blue-50 via-white to-purple-50 py-20">
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-primary/10 rounded-full blur-3xl animate-float"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-purple-500/10 rounded-full blur-3xl animate-float" style="animation-delay: 1s;"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 text-center relative z-10">
        <span class="inline-block px-4 py-2 bg-primary/10 text-primary rounded-full text-sm font-semibold mb-6 animate-pulse">
            🌍 Explore Global Trade Fairs
        </span>
        
        <h1 class="text-5xl md:text-6xl font-extrabold mb-6 bg-gradient-to-r from-primary via-purple-600 to-primary bg-clip-text text-transparent">
            Expo Tour Packages
        </h1>
        
        <p class="text-xl text-gray-600 max-w-2xl mx-auto mb-8">
            Choose the perfect expo trip package that fits your business needs. Travel to trade fairs across India, Asia, or the entire world with our expertly curated packages.
        </p>

        <div class="flex justify-center gap-4 flex-wrap">
            <div class="flex items-center gap-2 text-gray-700">
                <span class="material-icons text-green-500">verified</span>
                <span class="text-sm">All-Inclusive Packages</span>
            </div>
            <div class="flex items-center gap-2 text-gray-700">
                <span class="material-icons text-green-500">verified</span>
                <span class="text-sm">Expert Guidance</span>
            </div>
            <div class="flex items-center gap-2 text-gray-700">
                <span class="material-icons text-green-500">verified</span>
                <span class="text-sm">Hassle-Free Travel</span>
            </div>
        </div>
    </div>
</section>

<!-- ================= PRICING CARDS ================= -->
<main class="flex-grow max-w-7xl mx-auto px-4 py-16 w-full">
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-12">

        <!-- ========== BASIC PLAN ========== -->
        <div
            x-data="{ 
                x: 0, 
                y: 0,
                isHovered: false 
            }"
            @mouseenter="isHovered = true"
            @mouseleave="isHovered = false; x = 0; y = 0"
            @mousemove="
                const r = $el.getBoundingClientRect();
                x = ((event.clientX - r.left) / r.width - 0.5) * 8;
                y = ((event.clientY - r.top) / r.height - 0.5) * -8;
            "
            class="group bg-white rounded-2xl border-2 overflow-hidden flex flex-col
                   transition-all duration-500 ease-out
                   hover:-translate-y-3 hover:shadow-2xl
                   will-change-transform relative"
            :style="`transform: perspective(1000px) rotateX(${y}deg) rotateY(${x}deg) scale(${isHovered ? 1.02 : 1})`"
        >
            <!-- Plan Badge -->
            <div class="absolute top-6 right-6 z-10">
                <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-500 text-white text-xs font-bold rounded-full shadow-lg
                             transition-transform duration-300 group-hover:scale-110">
                    <span class="material-icons" style="font-size: 14px;">flight_takeoff</span>
                    STARTER
                </span>
            </div>

            <!-- Header Image -->
            <div class="h-56 relative overflow-hidden">
                <img 
                    src="{{ asset('uploads/india-trade-fair.png') }}" 
                    alt="India Trade Fair"
                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                
                <!-- Overlay gradient -->
                <div class="absolute inset-0 bg-gradient-to-br from-green-500/60 via-green-600/50 to-emerald-700/60"></div>
                
                <!-- Icon overlay -->
                <div class="absolute inset-0 flex items-center justify-center">
                    <span class="material-icons text-white drop-shadow-2xl transition-transform duration-500 group-hover:scale-125 group-hover:rotate-12" 
                          style="font-size: 64px;">
                        location_city
                    </span>
                </div>
            </div>

            <!-- Content -->
            <div class="p-8 flex flex-col flex-grow">
                <h3 class="text-2xl font-extrabold text-gray-800 mb-2">
                    Basic Plan
                </h3>
                <p class="text-sm text-gray-500 mb-6">
                    Perfect for exploring trade fairs within India
                </p>

                <!-- Price -->
                <div class="mb-8">
                    <div class="flex items-baseline gap-2">
                        <span class="text-4xl font-black text-primary">₹50,000</span>
                        <span class="text-gray-500">/trip</span>
                    </div>
                </div>

                <!-- Features -->
                <ul class="space-y-3 mb-8 flex-grow">
                    <li class="flex items-start gap-3 text-sm text-gray-700">
                        <span class="material-icons text-green-500 flex-shrink-0" style="font-size: 20px;">check_circle</span>
                        <span>Trade fairs across <strong>India</strong></span>
                    </li>
                    <li class="flex items-start gap-3 text-sm text-gray-700">
                        <span class="material-icons text-green-500 flex-shrink-0" style="font-size: 20px;">check_circle</span>
                        <span>Domestic flight & accommodation</span>
                    </li>
                    <li class="flex items-start gap-3 text-sm text-gray-700">
                        <span class="material-icons text-green-500 flex-shrink-0" style="font-size: 20px;">check_circle</span>
                        <span>Event registration assistance</span>
                    </li>
                    <li class="flex items-start gap-3 text-sm text-gray-700">
                        <span class="material-icons text-green-500 flex-shrink-0" style="font-size: 20px;">check_circle</span>
                        <span>Local transport</span>
                    </li>
                    <li class="flex items-start gap-3 text-sm text-gray-700">
                        <span class="material-icons text-green-500 flex-shrink-0" style="font-size: 20px;">check_circle</span>
                        <span>24/7 support during trip</span>
                    </li>
                </ul>

                <!-- CTA Button -->
                <a
                    href="{{ route('payment.details', 'basic') }}"
                    class="w-full py-4 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-bold rounded-xl
                           transition-all duration-300
                           hover:shadow-lg hover:shadow-green-500/50
                           transform hover:-translate-y-1
                           flex items-center justify-center gap-2
                           group/btn"
                >
                    <span>BUY NOW</span>
                    <span class="material-icons transition-transform group-hover/btn:translate-x-1">arrow_forward</span>
                </a>
            </div>
        </div>

        <!-- ========== PRO PLAN (FEATURED) ========== -->
        <div
            x-data="{ 
                x: 0, 
                y: 0,
                isHovered: false 
            }"
            @mouseenter="isHovered = true"
            @mouseleave="isHovered = false; x = 0; y = 0"
            @mousemove="
                const r = $el.getBoundingClientRect();
                x = ((event.clientX - r.left) / r.width - 0.5) * 8;
                y = ((event.clientY - r.top) / r.height - 0.5) * -8;
            "
            class="group gradient-border rounded-2xl overflow-hidden flex flex-col
                   transition-all duration-500 ease-out
                   hover:-translate-y-3 hover:shadow-2xl animate-pulse-glow
                   will-change-transform relative
                   md:scale-105"
            :style="`transform: perspective(1000px) rotateX(${y}deg) rotateY(${x}deg) scale(${isHovered ? 1.05 : 1.02})`"
        >
            <!-- Popular Badge -->
            <div class="absolute -top-4 left-1/2 -translate-x-1/2 z-10">
                <span class="inline-flex items-center gap-1 px-6 py-2 bg-gradient-to-r from-primary to-purple-600 text-white text-sm font-bold rounded-full shadow-xl
                             transition-transform duration-300 group-hover:scale-110 animate-pulse">
                    <span class="material-icons" style="font-size: 16px;">stars</span>
                    MOST POPULAR
                </span>
            </div>

            <!-- Header Image -->
            <div class="h-56 relative overflow-hidden">
                <img 
                    src="{{ asset('uploads/asia-trade-expo.png') }}" 
                    alt="Asian Trade Expo"
                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                
                <!-- Overlay gradient with shimmer -->
                <div class="absolute inset-0 bg-gradient-to-br from-primary/70 via-blue-600/60 to-purple-600/70"></div>
                <div class="absolute inset-0 shimmer-effect opacity-20"></div>
                
                <!-- Icon overlay -->
                <div class="absolute inset-0 flex items-center justify-center">
                    <span class="material-icons text-white drop-shadow-2xl transition-transform duration-500 group-hover:scale-125 group-hover:rotate-12" 
                          style="font-size: 64px;">
                        public
                    </span>
                </div>
            </div>

            <!-- Content -->
            <div class="p-8 flex flex-col flex-grow bg-white">
                <h3 class="text-2xl font-extrabold text-gray-800 mb-2">
                    Pro Plan
                </h3>
                <p class="text-sm text-gray-500 mb-6">
                    Expand your reach across all of Asia
                </p>

                <!-- Price -->
                <div class="mb-8">
                    <div class="flex items-baseline gap-2">
                        <span class="text-4xl font-black bg-gradient-to-r from-primary to-purple-600 bg-clip-text text-transparent">₹2,00,000</span>
                        <span class="text-gray-500">/trip</span>
                    </div>
                </div>

                <!-- Features -->
                <ul class="space-y-3 mb-8 flex-grow">
                    <li class="flex items-start gap-3 text-sm text-gray-700">
                        <span class="material-icons text-primary flex-shrink-0" style="font-size: 20px;">check_circle</span>
                        <span>Trade fairs across <strong>All Asia</strong></span>
                    </li>
                    <li class="flex items-start gap-3 text-sm text-gray-700">
                        <span class="material-icons text-primary flex-shrink-0" style="font-size: 20px;">check_circle</span>
                        <span>International flights & 4-star hotels</span>
                    </li>
                    <li class="flex items-start gap-3 text-sm text-gray-700">
                        <span class="material-icons text-primary flex-shrink-0" style="font-size: 20px;">check_circle</span>
                        <span>VIP event registration & networking</span>
                    </li>
                    <li class="flex items-start gap-3 text-sm text-gray-700">
                        <span class="material-icons text-primary flex-shrink-0" style="font-size: 20px;">check_circle</span>
                        <span>Premium transport & meals</span>
                    </li>
                    <li class="flex items-start gap-3 text-sm text-gray-700">
                        <span class="material-icons text-primary flex-shrink-0" style="font-size: 20px;">check_circle</span>
                        <span>Dedicated trip coordinator</span>
                    </li>
                    <li class="flex items-start gap-3 text-sm text-gray-700">
                        <span class="material-icons text-primary flex-shrink-0" style="font-size: 20px;">check_circle</span>
                        <span>Visa assistance included</span>
                    </li>
                </ul>

                <!-- CTA Button -->
                <a
                    href="{{ route('payment.details', 'pro') }}"
                    class="w-full py-4 bg-gradient-to-r from-primary to-purple-600 text-white font-bold rounded-xl
                           transition-all duration-300
                           hover:shadow-lg hover:shadow-primary/50
                           transform hover:-translate-y-1
                           flex items-center justify-center gap-2
                           group/btn relative overflow-hidden"
                >
                    <span class="absolute inset-0 shimmer-effect opacity-0 group-hover/btn:opacity-30"></span>
                    <span class="relative">BUY NOW</span>
                    <span class="material-icons transition-transform group-hover/btn:translate-x-1 relative">arrow_forward</span>
                </a>
            </div>
        </div>

        <!-- ========== EXPERT PLAN ========== -->
        <div
            x-data="{ 
                x: 0, 
                y: 0,
                isHovered: false 
            }"
            @mouseenter="isHovered = true"
            @mouseleave="isHovered = false; x = 0; y = 0"
            @mousemove="
                const r = $el.getBoundingClientRect();
                x = ((event.clientX - r.left) / r.width - 0.5) * 8;
                y = ((event.clientY - r.top) / r.height - 0.5) * -8;
            "
            class="group bg-white rounded-2xl border-2 overflow-hidden flex flex-col
                   transition-all duration-500 ease-out
                   hover:-translate-y-3 hover:shadow-2xl
                   will-change-transform relative"
            :style="`transform: perspective(1000px) rotateX(${y}deg) rotateY(${x}deg) scale(${isHovered ? 1.02 : 1})`"
        >
            <!-- Plan Badge -->
            <div class="absolute top-6 right-6 z-10">
                <span class="inline-flex items-center gap-1 px-3 py-1 bg-purple-600 text-white text-xs font-bold rounded-full shadow-lg
                             transition-transform duration-300 group-hover:scale-110">
                    <span class="material-icons" style="font-size: 14px;">workspace_premium</span>
                    PREMIUM
                </span>
            </div>

            <!-- Header Image -->
            <div class="h-56 relative overflow-hidden">
                <img 
                    src="{{ asset('uploads/europe-trade-expo.png') }}" 
                    alt="Europe Trade Expo"
                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                
                <!-- Overlay gradient -->
                <div class="absolute inset-0 bg-gradient-to-br from-purple-500/70 via-purple-600/60 to-indigo-700/70"></div>
                
                <!-- Icon overlay -->
                <div class="absolute inset-0 flex items-center justify-center">
                    <span class="material-icons text-white drop-shadow-2xl transition-transform duration-500 group-hover:scale-125 group-hover:rotate-12" 
                          style="font-size: 64px;">
                        emoji_events
                    </span>
                </div>
            </div>

            <!-- Content -->
            <div class="p-8 flex flex-col flex-grow">
                <h3 class="text-2xl font-extrabold text-gray-800 mb-2">
                    Expert Plan
                </h3>
                <p class="text-sm text-gray-500 mb-6">
                    Ultimate global experience including Europe
                </p>

                <!-- Price -->
                <div class="mb-8">
                    <div class="flex items-baseline gap-2">
                        <span class="text-4xl font-black text-purple-600">₹8,00,000</span>
                        <span class="text-gray-500">/trip</span>
                    </div>
                </div>

                <!-- Features -->
                <ul class="space-y-3 mb-8 flex-grow">
                    <li class="flex items-start gap-3 text-sm text-gray-700">
                        <span class="material-icons text-purple-600 flex-shrink-0" style="font-size: 20px;">check_circle</span>
                        <span>Trade fairs <strong>Worldwide (Europe focus)</strong></span>
                    </li>
                    <li class="flex items-start gap-3 text-sm text-gray-700">
                        <span class="material-icons text-purple-600 flex-shrink-0" style="font-size: 20px;">check_circle</span>
                        <span>Business class flights & 5-star hotels</span>
                    </li>
                    <li class="flex items-start gap-3 text-sm text-gray-700">
                        <span class="material-icons text-purple-600 flex-shrink-0" style="font-size: 20px;">check_circle</span>
                        <span>Exclusive VIP access & private meetings</span>
                    </li>
                    <li class="flex items-start gap-3 text-sm text-gray-700">
                        <span class="material-icons text-purple-600 flex-shrink-0" style="font-size: 20px;">check_circle</span>
                        <span>Luxury transport & fine dining</span>
                    </li>
                    <li class="flex items-start gap-3 text-sm text-gray-700">
                        <span class="material-icons text-purple-600 flex-shrink-0" style="font-size: 20px;">check_circle</span>
                        <span>Personal concierge service</span>
                    </li>
                    <li class="flex items-start gap-3 text-sm text-gray-700">
                        <span class="material-icons text-purple-600 flex-shrink-0" style="font-size: 20px;">check_circle</span>
                        <span>Complete visa & documentation support</span>
                    </li>
                    <li class="flex items-start gap-3 text-sm text-gray-700">
                        <span class="material-icons text-purple-600 flex-shrink-0" style="font-size: 20px;">check_circle</span>
                        <span>Cultural tours & business insights</span>
                    </li>
                </ul>

                <!-- CTA Button -->
                <a
                    href="{{ route('payment.details', 'expert') }}"
                    class="w-full py-4 bg-gradient-to-r from-purple-600 to-indigo-700 text-white font-bold rounded-xl
                           transition-all duration-300
                           hover:shadow-lg hover:shadow-purple-600/50
                           transform hover:-translate-y-1
                           flex items-center justify-center gap-2
                           group/btn"
                >
                    <span>BUY NOW</span>
                    <span class="material-icons transition-transform group-hover/btn:translate-x-1">arrow_forward</span>
                </a>
            </div>
        </div>

    </div>

    <!-- Additional Info Section -->
    <div class="mt-20 bg-white rounded-2xl shadow-lg p-8 md:p-12">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-extrabold text-gray-800 mb-4">Why Choose Our Expo Packages?</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">
                We handle everything from flights to accommodation, event registration to local transport, so you can focus on building business relationships.
            </p>
        </div>

        <div class="grid md:grid-cols-4 gap-8">
            <div class="text-center group">
                <div class="w-16 h-16 bg-gradient-to-br from-primary to-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4
                            transition-transform duration-300 group-hover:scale-110 group-hover:rotate-6">
                    <span class="material-icons text-white text-3xl">verified_user</span>
                </div>
                <h3 class="font-bold text-gray-800 mb-2">Trusted Service</h3>
                <p class="text-sm text-gray-600">15+ years of experience</p>
            </div>

            <div class="text-center group">
                <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-4
                            transition-transform duration-300 group-hover:scale-110 group-hover:rotate-6">
                    <span class="material-icons text-white text-3xl">language</span>
                </div>
                <h3 class="font-bold text-gray-800 mb-2">Global Reach</h3>
                <p class="text-sm text-gray-600">50+ countries covered</p>
            </div>

            <div class="text-center group">
                <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-4
                            transition-transform duration-300 group-hover:scale-110 group-hover:rotate-6">
                    <span class="material-icons text-white text-3xl">support_agent</span>
                </div>
                <h3 class="font-bold text-gray-800 mb-2">24/7 Support</h3>
                <p class="text-sm text-gray-600">Always here to help</p>
            </div>

            <div class="text-center group">
                <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl flex items-center justify-center mx-auto mb-4
                            transition-transform duration-300 group-hover:scale-110 group-hover:rotate-6">
                    <span class="material-icons text-white text-3xl">receipt_long</span>
                </div>
                <h3 class="font-bold text-gray-800 mb-2">No Hidden Costs</h3>
                <p class="text-sm text-gray-600">Transparent pricing</p>
            </div>
        </div>
    </div>

</main>

<!-- ================= FOOTER ================= -->
<footer class="bg-primary text-white mt-16">
    <div class="max-w-7xl mx-auto px-6 py-12 grid md:grid-cols-3 gap-8">

        <div>
            <h3 class="font-bold text-lg flex items-center gap-2">
                <span class="material-icons">public</span> GLOBAL TRADE FAIRS
            </h3>
            <p class="text-sm mt-3 text-blue-100">
                Connecting businesses globally through world-class exhibitions.
            </p>
        </div>

        <div>
            <h4 class="font-semibold mb-3">Quick Links</h4>
            <ul class="space-y-2 text-sm">
                <li><a href="{{ route('home') }}">Events</a></li>
                <li><a href="#">Tour Packages</a></li>
                <li><a href="#">About Us</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
        </div>

        <div>
            <h4 class="font-semibold mb-3">Newsletter</h4>
            <form class="flex">
                <input type="email" placeholder="Your email"
                       class="w-full px-3 py-2 text-gray-900 rounded-l">
                <button class="bg-gray-900 px-4 py-2 rounded-r">
                    Subscribe
                </button>
            </form>
        </div>
    </div>

    <div class="border-t border-blue-300 text-center text-sm py-5 text-blue-100">
        © 2025 Global Trade Fairs — Powered by Reydel Mercado Online services
    </div>
</footer>

<!-- ================= MOBILE APP NAVIGATION ================= -->
@include('partials.mobile-nav')

{{-- AI Chatbot Widget --}}
@include('partials.chatbot')



<style>
    /* Safe area padding for iPhones with home indicator */
    .pb-safe {
        padding-bottom: env(safe-area-inset-bottom, 12px);
    }
</style>

</body>
</html>
