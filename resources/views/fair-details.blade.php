<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>{{ $form->ExponName ?? 'Event Details' }} - Global Trade Fairs</title>
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
        body { font-family: 'Inter', sans-serif; }
        .info-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; }
        @media (max-width: 767px) {
            .info-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>

<body class="bg-[#f4f7fb] font-display min-h-screen flex flex-col" x-data="{ mobileMenuOpen: false }">

<!-- ================= HEADER ================= -->
<header class="sticky top-0 z-50 bg-white/80 backdrop-blur border-b shadow-sm">
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
            <a href="{{ route('tour.packages') }}" class="text-gray-700 hover:text-primary transition-colors">Tour Packages</a>
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

<!-- ================= CONTENT ================= -->
<main class="flex-grow max-w-7xl mx-auto px-4 py-10 w-full">
    
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        
        <!-- Hero Section -->
        <div class="bg-gradient-to-r from-blue-50 to-purple-50 p-8">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h1 class="text-3xl md:text-4xl font-extrabold text-primary mb-2">{{ $form->ExponName }}</h1>
                    <div class="text-gray-600">
                        Organizer: {{ $form->Orgname }} • Date: {{ $form->Date }}
                    </div>
                </div>
                <div class="bg-primary/10 text-primary px-4 py-2 rounded-lg font-semibold">
                    Hall: {{ $form->hallno ?? '—' }}
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6 md:p-8">
            <div class="grid md:grid-cols-2 gap-8">

                <!-- Left: Image -->
                <div>
                    <img src="{{ asset($form->image ? 'uploads/'.$form->image : 'uploads/default.jpg') }}" 
                         class="w-full rounded-xl shadow-lg object-cover max-h-96"
                         loading="lazy">
                    <div class="mt-4 text-sm text-gray-500">For event registration, use the button below if provided.</div>
                </div>

                <!-- Right: Details -->
                <div>
                    <div class="info-grid">

                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <div class="text-xs font-semibold text-primary uppercase mb-1">Venue</div>
                            <div class="text-sm font-medium text-gray-800">{{ $form->VenueName }}</div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <div class="text-xs font-semibold text-primary uppercase mb-1">City</div>
                            <div class="text-sm font-medium text-gray-800">{{ $form->city }}</div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <div class="text-xs font-semibold text-primary uppercase mb-1">Country</div>
                            <div class="text-sm font-medium text-gray-800">{{ $form->country ?? '—' }}</div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <div class="text-xs font-semibold text-primary uppercase mb-1">Date</div>
                            <div class="text-sm font-medium text-gray-800">{{ $form->Date }}</div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <div class="text-xs font-semibold text-primary uppercase mb-1">Organizer</div>
                            <div class="text-sm font-medium text-gray-800">{{ $form->Orgname }}</div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <div class="text-xs font-semibold text-primary uppercase mb-1">Phone</div>
                            <div class="text-sm font-medium text-gray-800">{{ $form->phone ?? '—' }}</div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <div class="text-xs font-semibold text-primary uppercase mb-1">Email</div>
                            <div class="text-sm font-medium text-gray-800 break-all">{{ $form->email ?? '—' }}</div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <div class="text-xs font-semibold text-primary uppercase mb-1">Hall No</div>
                            <div class="text-sm font-medium text-gray-800">{{ $form->hallno ?? '—' }}</div>
                        </div>

                        @if($form->reglink)
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 md:col-span-2">
                            <div class="text-xs font-semibold text-primary uppercase mb-2">Registration</div>
                            <a href="{{ $form->reglink }}" target="_blank" 
                               class="inline-flex items-center gap-2 bg-primary text-white px-4 py-2 rounded-lg font-semibold hover:bg-primary-hover transition-colors">
                                <span class="material-icons text-sm">open_in_new</span>
                                Open Registration Page
                            </a>
                        </div>
                        @endif

                    </div>

                    <!-- Buttons -->
                    <div class="mt-6 flex justify-end">
                        <a href="{{ route('home') }}" 
                           class="inline-flex items-center gap-2 px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition-colors">
                            <span class="material-icons">arrow_back</span>
                            Back to Events
                        </a>
                    </div>

                </div>

            </div>
        </div>

    </div>

</main>

<!-- ================= MOBILE APP NAVIGATION ================= -->
@include('partials.mobile-nav')


<style>
    /* Safe area padding for iPhones with home indicator */
    .pb-safe {
        padding-bottom: env(safe-area-inset-bottom, 12px);
    }
</style>

</body>
</html>
