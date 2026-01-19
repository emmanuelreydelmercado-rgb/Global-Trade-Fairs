<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Global Trade Fairs</title>
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#1a73e8">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
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
    <!-- amCharts 5 -->
<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/map.js"></script>
<script src="https://cdn.amcharts.com/lib/5/geodata/worldLow.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

    <style>
        /* Custom Scrollbar for Share Dropdown */
        .share-dropdown-scroll::-webkit-scrollbar {
            width: 6px;
        }
        
        .share-dropdown-scroll::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        .share-dropdown-scroll::-webkit-scrollbar-thumb {
            background: #1a73e8;
            border-radius: 10px;
        }
        
        .share-dropdown-scroll::-webkit-scrollbar-thumb:hover {
            background: #1557b0;
        }
    </style>

    @include('partials.google-analytics')
</head>

<body class="bg-[#f4f7fb] font-display min-h-screen flex flex-col">


<!-- ================= HEADER ================= -->
<header class="sticky top-0 z-50 bg-white/80 backdrop-blur border-b shadow-sm" x-data="{ mobileMenuOpen: false }">
    <div class="max-w-7xl mx-auto px-4 h-20 flex justify-between items-center">

        <div class="flex items-center gap-3">
            <img src="{{ asset('favicon.png') }}" class="w-10 rounded-lg">
            <h1 class="text-2xl font-bold">
                <span class="text-primary">GLOBAL</span> TRADE FAIRS
            </h1>
        </div>

        <!-- Navigation -->
        <nav class="hidden lg:flex items-center gap-6">
            <a href="{{ route('home') }}" class="text-primary font-semibold">Events</a>
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
        <!-- Settings Dropdown (FOR EVERYONE) -->
        <div class="relative" x-data="{ 
            settingsOpen: false,
            theme: localStorage.getItem('theme') || 'light',
            installPrompt: null,
            init() {
                this.initTheme();
                this.initPwa();
            },
            initTheme() {
                this.applyTheme();
                this.$watch('theme', () => {
                    localStorage.setItem('theme', this.theme);
                    this.applyTheme();
                });
            },
            applyTheme() {
                if (this.theme === 'dark' || (this.theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            },
            initPwa() {
                window.addEventListener('beforeinstallprompt', (e) => {
                    e.preventDefault();
                    this.installPrompt = e;
                    console.log('PWA Install Triggered');
                });
            },
            async installApp() {
                if (!this.installPrompt) return;
                this.installPrompt.prompt();
                const { outcome } = await this.installPrompt.userChoice;
                if (outcome === 'accepted') {
                    this.installPrompt = null;
                }
            }
        }" x-init="init()">
            <button 
                @click="settingsOpen = !settingsOpen" 
                @click.outside="settingsOpen = false"
                class="p-2 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-full transition-colors relative"
            >
                <span class="material-icons">settings</span>
            </button>

            <!-- Settings Dropdown -->
            <div 
                x-show="settingsOpen"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                class="absolute right-0 mt-3 w-80 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 z-50 overflow-hidden"
                style="display: none;"
            >
                <div class="p-5">
                    <h3 class="text-lg font-bold mb-4 text-gray-800 dark:text-white">Settings</h3>

                    <!-- Wishlist Link (ONLY FOR AUTHENTICATED USERS) -->
                    @auth
                    <a href="{{ route('wishlist.index') }}" class="flex items-center gap-3 p-3 bg-pink-50 dark:bg-pink-900/20 rounded-xl hover:bg-pink-100 dark:hover:bg-pink-900/30 transition-colors mb-4">
                        <span class="material-icons text-red-500 text-2xl">favorite</span>
                        <div>
                            <h4 class="font-bold text-gray-800 dark:text-white">Wishlist</h4>
                            <p class="text-xs text-gray-500 dark:text-gray-400">View Liked Events</p>
                        </div>
                    </a>
                    @else
                    <!-- Login Prompt for Guests -->
                    <a href="{{ route('login') }}" class="flex items-center gap-3 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-xl hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors mb-4">
                        <span class="material-icons text-primary text-2xl">login</span>
                        <div>
                            <h4 class="font-bold text-gray-800 dark:text-white">Login to Save Events</h4>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Create a wishlist</p>
                        </div>
                    </a>
                    @endauth
                    
                    <!-- PWA Install Button -->
                    <div x-show="installPrompt" class="mb-4">
                        <button @click="installApp()" class="w-full flex items-center gap-3 p-3 bg-green-50 dark:bg-green-900/20 rounded-xl hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors text-left">
                            <span class="material-icons text-green-600 text-2xl">download</span>
                            <div>
                                <h4 class="font-bold text-gray-800 dark:text-white">Install App</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Add to Home Screen</p>
                            </div>
                        </button>
                    </div>

                    <!-- Dark Mode (FOR EVERYONE) -->
                    <div>
                        <h4 class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase mb-2">Theme</h4>
                        <div class="bg-gray-100 dark:bg-gray-700 rounded-xl p-1 flex relative">
                            <div class="absolute top-1 bottom-1 rounded-lg bg-white dark:bg-gray-600 shadow-sm transition-all duration-300 w-1/3"
                                 :style="theme === 'light' ? 'left: 4px' : (theme === 'dark' ? 'left: 66%' : 'left: 33%')">
                            </div>

                            <button @click="theme = 'light'" class="relative z-10 w-1/3 py-2 text-sm font-medium transition-colors"
                                :class="theme === 'light' ? 'text-primary' : 'text-gray-500 dark:text-gray-400'">
                                Light
                            </button>
                            <button @click="theme = 'system'" class="relative z-10 w-1/3 py-2 text-sm font-medium transition-colors"
                                :class="theme === 'system' ? 'text-primary' : 'text-gray-500 dark:text-gray-400'">
                                System
                            </button>
                            <button @click="theme = 'dark'" class="relative z-10 w-1/3 py-2 text-sm font-medium transition-colors"
                                :class="theme === 'dark' ? 'text-primary' : 'text-gray-500 dark:text-gray-400'">
                                Dark
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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
                    class="absolute right-0 mt-3 w-72 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 z-50 overflow-hidden"
                    style="display: none;"
                >
                    <!-- User Info Section -->
                    <div class="flex flex-col items-center p-6 border-b border-gray-50 dark:border-gray-700 bg-gradient-to-b from-white to-gray-50/50 dark:from-gray-800 dark:to-gray-800/50">
                        <div class="relative mb-3">
                            <img
                                src="{{ (auth()->user()->profilepic && auth()->user()->profilepic !== 'default.jpg')
                                    ? asset('profilepics/' . auth()->user()->profilepic)
                                    : asset('profilepics/user_avatar.png') }}"
                                onerror="this.src='{{ asset('profilepics/user_avatar.png') }}'"
                                class="w-20 h-20 rounded-full object-cover shadow-lg border-4 border-white dark:border-gray-700"
                                alt="User"
                            >
                            <span class="absolute bottom-1 right-1 w-4 h-4 bg-green-500 border-2 border-white dark:border-gray-700 rounded-full"></span>
                        </div>
                        
                        <h3 class="font-bold text-gray-800 dark:text-white text-lg text-center leading-tight">
                            {{ auth()->user()->name }}
                        </h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 text-center mt-1 font-medium bg-gray-100 dark:bg-gray-700 px-3 py-1 rounded-full">
                            {{ auth()->user()->email }}
                        </p>
                    </div>

                    <!-- Actions -->
                    <div class="p-2 bg-gray-50/30 dark:bg-gray-900/30">
                        <!-- You can add more menu items here later -->
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button
                                type="submit"
                                class="w-full flex items-center justify-center gap-2 px-4 py-3 text-sm font-semibold text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-xl transition-all group"
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

    <!-- Mobile Menu -->
    <div 
        x-show="mobileMenuOpen" 
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="lg:hidden bg-white border-t p-4 shadow-lg absolute w-full left-0 top-20 flex flex-col gap-4" 
        style="display: none;"
    >
        <a href="{{ route('home') }}" class="text-lg font-semibold text-gray-800 hover:text-primary">Events</a>
        <a href="{{ route('tour.packages') }}" class="text-lg font-semibold text-gray-800 hover:text-primary">Tour Packages</a>
        
        <!-- Mobile Search -->
        <form action="{{ route('home') }}" method="GET" class="relative">
            <span class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search..."
                class="w-full pl-10 pr-4 py-2 rounded-lg border focus:ring-primary focus:border-primary">
        </form>
    </div>
</header>

<!-- ================= CONTENT ================= -->
<main class="flex-grow max-w-7xl mx-auto px-4 py-10 w-full">

    <!-- Title + Sort -->
    <div class="flex flex-col sm:flex-row justify-between mb-8">
        <div>
            <h2 class="text-3xl font-extrabold">Global Events</h2>
            <p class="text-sm text-gray-500 mt-1">
                Discover the best trade fairs happening around the globe.
            </p>
        </div>
        
        <!-- Mobile Search Bar (Only visible on mobile) -->
        <div class="block md:hidden w-full mt-4 mb-2">
            <form action="{{ route('home') }}" method="GET" class="relative">
                <span class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                <input
                    id="mobile-search-input"
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search Events..."
                    class="w-full pl-10 pr-4 py-3 rounded-xl border-gray-200 shadow-sm focus:ring-primary focus:border-primary text-sm">
            </form>
        </div>

        <div class="mt-4 sm:mt-0 flex gap-3 items-center">
            
            <a href="{{ request('filter') == 'live' ? route('home') : route('home', ['filter' => 'live']) }}" 
               class="px-4 py-2 text-sm font-semibold rounded-md shadow-sm transition-colors duration-200 
                      {{ request('filter') == 'live' ? 'bg-red-600 text-white hover:bg-red-700' : 'bg-white text-gray-700 border hover:bg-gray-50' }}">
                {{ request('filter') == 'live' ? 'Show All Events' : 'Live Events' }}
                @if(request('filter') != 'live')
                    <span class="ml-2 w-2 h-2 inline-block rounded-full bg-red-500 animate-pulse"></span>
                @endif
            </a>

            <form method="GET">
                <input type="hidden" name="search" value="{{ request('search') }}">
                @if(request('filter'))
                    <input type="hidden" name="filter" value="{{ request('filter') }}">
                @endif
                <select name="sort"
                        onchange="this.form.submit()"
                        class="pl-3 pr-10 py-2 text-sm border rounded-md shadow-sm cursor-pointer">
                    <option value="">Default</option>
                    <option value="date" {{ request('sort') == 'date' ? 'selected' : '' }}>Date</option>
                    <option value="city" {{ request('sort') == 'city' ? 'selected' : '' }}>City</option>
                </select>
            </form>
        </div>
    </div>

    <!-- ================= GRID ================= -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">

        @forelse($forms as $form)
        <div
            x-data="{ 
                x: 0, 
                y: 0,
                isLiked: {{ in_array($form->id, $wishlistFormIds) ? 'true' : 'false' }},
                shareOpen: false,
                showToast: false,
                baseTransform: '',
                handleMove(e) {
                    if (window.innerWidth < 1024) return;
                    const r = $el.getBoundingClientRect();
                    this.x = ((e.clientX - r.left) / r.width - 0.5) * 10;
                    this.y = ((e.clientY - r.top) / r.height - 0.5) * -10;
                },
                async toggleLike() {
                    @auth
                        try {
                            const response = await fetch('{{ route("wishlist.toggle") }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({ form_id: {{ $form->id }} })
                            });
                            const data = await response.json();
                            if (data.success) {
                                this.isLiked = data.action === 'added';
                            }
                        } catch (error) {
                            console.error('Error toggling wishlist:', error);
                        }
                    @else
                        Swal.fire({
                            icon: 'info',
                            title: 'Login Required',
                            text: 'Please Login to like and save events to your wishlist!',
                            showCancelButton: true,
                            confirmButtonText: '🔐 Login Now',
                            cancelButtonText: 'Cancel',
                            confirmButtonColor: '#1a73e8',
                            cancelButtonColor: '#6b7280',
                            reverseButtons: true
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '{{ route("login") }}';
                            }
                        });
                    @endauth
                },
                shareEvent(platform) {
                    const url = '{{ route('fair.details', $form->id) }}';
                    const text = '{{ addslashes($form->ExponName) }} - {{ addslashes($form->city) }}';
                    
                    let shareUrl = '';
                    if (platform === 'whatsapp') {
                        shareUrl = `https://wa.me/?text=${encodeURIComponent(text + ' ' + url)}`;
                    } else if (platform === 'facebook') {
                        shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`;
                    } else if (platform === 'twitter') {
                        shareUrl = `https://twitter.com/intent/tweet?text=${encodeURIComponent(text)}&url=${encodeURIComponent(url)}`;
                    } else if (platform === 'copy') {
                        navigator.clipboard.writeText(url);
                        this.showToast = true;
                        setTimeout(() => { this.showToast = false; }, 2000);
                        this.shareOpen = false;
                        return;
                    }
                    
                    if (shareUrl) {
                        window.open(shareUrl, '_blank', 'width=600,height=400');
                    }
                    this.shareOpen = false;
                }
            }"
            @mousemove="handleMove($event)"
            @mouseleave="x = 0; y = 0"
            class="group bg-white rounded-xl border overflow-visible flex flex-col
                   transition-all duration-300 ease-out
                   hover:-translate-y-2 hover:shadow-2xl
                   will-change-transform"
            :style="`transform: perspective(1000px) rotateX(${y}deg) rotateY(${x}deg)`"

        >

            <!-- IMAGE -->
            <div class="relative h-48 overflow-visible bg-gray-100">
                <div class="absolute inset-0 overflow-hidden">
                    <img
                        src="{{ asset($form->image ? 'uploads/'.$form->image : 'uploads/default.jpg') }}"
                        class="w-full h-full object-contain
                               transition-transform duration-500 ease-out
                               group-hover:scale-105">
                </div>

                <!-- Like & Share Icons (Top Left) -->
                <div class="absolute top-3 left-3 flex gap-2 z-10">
                    <!-- Like Button -->
                    <button 
                        @click="toggleLike()"
                        class="w-9 h-9 rounded-full bg-white/90 backdrop-blur flex items-center justify-center
                               shadow-md hover:shadow-lg transition-all duration-300 hover:scale-110"
                        :class="isLiked ? 'text-red-500' : 'text-gray-600'"
                    >
                        <span class="material-icons text-xl" x-text="isLiked ? 'favorite' : 'favorite_border'">favorite_border</span>
                    </button>

                    <!-- Share Button -->
                    <div class="relative overflow-visible">
                        <button 
                            @click="shareOpen = !shareOpen"
                            class="w-9 h-9 rounded-full bg-white/90 backdrop-blur flex items-center justify-center
                                   shadow-md hover:shadow-lg transition-all duration-300 hover:scale-110 text-gray-600"
                        >
                            <span class="material-icons text-xl">share</span>
                        </button>

                        <!-- Share Dropdown -->
                        <div 
                            x-show="shareOpen"
                            @click.outside="shareOpen = false"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            class="absolute left-0 top-full mt-2 w-44 bg-white rounded-lg shadow-xl border z-[100]"
                            style="display: none;"
                        >
                            <button @click="shareEvent('whatsapp')" class="w-full px-4 py-2.5 text-left text-sm hover:bg-gray-50 flex items-center gap-3 transition-colors">
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="#25D366">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                </svg>
                                <span class="font-medium">WhatsApp</span>
                            </button>
                            <button @click="shareEvent('facebook')" class="w-full px-4 py-2.5 text-left text-sm hover:bg-gray-50 flex items-center gap-3 transition-colors">
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="#1877F2">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                                <span class="font-medium">Facebook</span>
                            </button>
                            <button @click="shareEvent('twitter')" class="w-full px-4 py-2.5 text-left text-sm hover:bg-gray-50 flex items-center gap-3 transition-colors">
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="#000000">
                                    <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                </svg>
                                <span class="font-medium">Twitter</span>
                            </button>
                            <button @click="shareEvent('copy')" class="w-full px-4 py-2.5 text-left text-sm hover:bg-gray-50 flex items-center gap-3 transition-colors">
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="#6B7280" stroke-width="2">
                                    <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                                    <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                                </svg>
                                <span class="font-medium">Copy Link</span>
                            </button>
                        </div>
                    </div>
                </div>

                @php
                    $eventDate = \Carbon\Carbon::parse($form->Date);
                @endphp

                @if($eventDate->isToday())
                    <span
                        class="absolute top-3 right-3 bg-red-600 text-white
                               px-2 py-1 text-xs font-bold rounded shadow
                               animate-pulse
                               transition-transform duration-300
                               group-hover:scale-110">
                        Happening Today
                    </span>
                @elseif($eventDate->isFuture())
                    <span
                        class="absolute top-3 right-3 bg-white/90 backdrop-blur
                               px-2 py-1 text-xs font-bold rounded shadow
                               transition-all duration-300
                               group-hover:bg-primary
                               group-hover:text-white
                               group-hover:scale-110">
                        Upcoming
                    </span>
                @endif
            </div>

            <!-- CONTENT -->
            <div class="p-5 flex flex-col flex-grow">
                <h3
                    class="text-lg font-bold text-primary mb-3
                           transition-colors duration-300
                           group-hover:text-primary-hover">
                    {{ $form->ExponName }}
                </h3>

                <div class="text-sm text-gray-600 space-y-2 flex-grow">
                    <div class="flex items-center gap-2">
                        <span class="material-icons text-sm text-primary">location_on</span>
                        {{ $form->VenueName }}
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="material-icons text-sm text-primary">location_city</span>
                        {{ $form->city }}
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="material-icons text-sm text-primary">calendar_today</span>
                        {{ $form->Date }}
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="material-icons text-sm text-primary">business</span>
                        {{ $form->Orgname }}
                    </div>
                </div>

                <a
                    href="{{ route('fair.details', $form->id) }}"
                    class="mt-5 text-center py-2 border border-primary
                           text-primary rounded-lg
                           transition-all duration-300
                           hover:bg-primary hover:text-white
                           group-hover:shadow-md">
                    View Details
                </a>
            </div>

            <!-- Toast Notification -->
            <div 
                x-show="showToast"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 -translate-y-4"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-4"
                class="fixed top-24 left-1/2 transform -translate-x-1/2 z-[200] 
                       bg-white dark:bg-gray-800 text-gray-800 dark:text-white
                       px-5 py-3 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 
                       flex items-center gap-2"
                style="display: none;"
            >
                <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
                <span class="text-sm font-medium">Link Copied!</span>
            </div>
        </div>
        @empty
            <p class="col-span-full text-center text-gray-500">
                No matching trade fairs found.
            </p>
        @endforelse
    </div>

    <!-- Pagination (if needed later) -->
    <div class="mt-12 flex justify-center">
    {{ $forms->withQueryString()->links() }}
</div>

 

</main>
<!-- ================= MAP ANALYTICS ================= -->
<section class="max-w-7xl mx-auto px-4 mb-20">
    <div class="bg-white rounded-xl shadow-lg p-6">

        <h2 class="text-2xl font-bold mb-6 text-gray-800">
            Global Trade Section Maps & Analytics
        </h2>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

            <!-- Map -->
            <div>
                <div id="worldMap" class="w-full h-[420px]"></div>
            </div>

            <!-- Stats / Chart placeholder -->
            <div class="flex flex-col justify-center">
                <div class="grid grid-cols-3 gap-4 text-center">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-3xl font-bold text-primary">{{ $totalEvents ?? 0 }}</p>
                        <p class="text-sm text-gray-500">Total Events</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-3xl font-bold text-green-600">{{ $totalCountries ?? 0 }}</p>
                        <p class="text-sm text-gray-500">Countries</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-3xl font-bold text-purple-600">{{ $avgPerCountry ?? 0 }}</p>
                        <p class="text-sm text-gray-500">Avg / Country</p>
                    </div>
                </div>

                <!-- Color Legend -->
                <div class="mt-8 p-4 bg-gray-50 rounded-lg">
                    <h3 class="text-sm font-semibold mb-3 text-gray-700">Event Distribution</h3>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-gray-600">Low</span>
                            <div class="flex gap-1">
                                <div class="w-8 h-4 rounded" style="background-color: #bbf7d0"></div>
                                <div class="w-8 h-4 rounded" style="background-color: #86efac"></div>
                                <div class="w-8 h-4 rounded" style="background-color: #4ade80"></div>
                                <div class="w-8 h-4 rounded" style="background-color: #22c55e"></div>
                                <div class="w-8 h-4 rounded" style="background-color: #15803d"></div>
                            </div>
                            <span class="text-gray-600">High</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
<script>
am5.ready(function () {

    var root = am5.Root.new("worldMap");
    root.setThemes([am5themes_Animated.new(root)]);

    var chart = root.container.children.push(
        am5map.MapChart.new(root, {
            projection: am5map.geoMercator()
        })
    );

    var polygonSeries = chart.series.push(
        am5map.MapPolygonSeries.new(root, {
            geoJSON: am5geodata_worldLow
        })
    );

    polygonSeries.mapPolygons.template.setAll({
        tooltipText: "{name}: {value} events",
        interactive: true,
        fill: am5.color(0x3b82f6)  // Blue default color
    });

    polygonSeries.mapPolygons.template.states.create("hover", {
        fill: am5.color(0x86efac)  // Green on hover
    });

    // Use real data from Laravel backend
    var mapData = @json($mapData ?? []);
    
    polygonSeries.data.setAll(mapData);

    // Set up heat rules for color coding based on event count (green shades)
    polygonSeries.set("heatRules", [{
        target: polygonSeries.mapPolygons.template,
        min: am5.color(0xbbf7d0),  // Light green for low event counts
        max: am5.color(0x15803d),  // Dark green for high event counts
        dataField: "value",
        key: "fill"
    }]);

    // Add country labels for better UX
    var labelSeries = chart.series.push(
        am5map.MapPointSeries.new(root, {})
    );

    labelSeries.bullets.push(function() {
        return am5.Bullet.new(root, {
            sprite: am5.Label.new(root, {
                text: "{name}",
                fontSize: 10,
                fill: am5.color(0x374151),
                centerX: am5.p50,
                centerY: am5.p50,
                populateText: true
            })
        });
    });

    // Only add labels for countries with events
    var labelData = mapData.map(function(item) {
        return {
            geometry: { type: "Point", coordinates: [0, 0] },
            name: item.name,
            id: item.id
        };
    });

});
</script>



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
                <li><a href="#">About Us</a></li>
                <li><a href="#">Contact Organizers</a></li>
                <li><a href="#">Privacy Policy</a></li>
                <li><a href="#">Terms of Service</a></li>
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

<!-- SweetAlert2 for beautiful popups -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Login Prompt for Guest Users -->
@guest
<script>
    // Check if user has already seen the popup in this session
    if (!sessionStorage.getItem('loginPromptShown')) {
        // Show popup after 7 seconds
        setTimeout(function() {
            Swal.fire({
                title: '🎯 Unlock Full Access!',
                imageUrl: '{{ asset("favicon.png") }}',
                imageWidth: 80,
                imageHeight: 80,
                imageAlt: 'Global Trade Fairs Logo',
                html: `
                    <div style="text-align: center;">
                        <p style="font-size: 16px; color: #555; margin-bottom: 20px;">
                            Login to get full access to all features including:
                        </p>
                        <ul style="text-align: left; display: inline-block; color: #666;">
                            <li>✅ Lookthrough all events</li>
                            <li>✅ Personalized recommendations</li>
                            <li>✅ Event reminders & notifications</li>
                            <li>✅ Access to exclusive tour packages and AI</li>
                        </ul>
                    </div>
                `,

                showCancelButton: true,
                confirmButtonText: '🔐 Login Now',
                cancelButtonText: 'Maybe Later',
                confirmButtonColor: '#1a73e8',
                cancelButtonColor: '#6b7280',
                reverseButtons: true,
                backdrop: `
                    rgba(0,0,0,0.4)
                    left top
                    no-repeat
                `,
                customClass: {
                    popup: 'animated-popup',
                    title: 'popup-title',
                    confirmButton: 'popup-confirm-btn',
                    cancelButton: 'popup-cancel-btn'
                },
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect to login page
                    window.location.href = "{{ route('login') }}";
                }
            });

            // Mark that popup has been shown in this session
            sessionStorage.setItem('loginPromptShown', 'true');
        }, 7000); // 7 seconds delay
    }
</script>

<style>
    .animated-popup {
        border-radius: 20px !important;
        padding: 30px !important;
    }
    .popup-title {
        font-size: 28px !important;
        font-weight: 700 !important;
        color: #1a73e8 !important;
    }
    .popup-confirm-btn {
        padding: 12px 30px !important;
        font-weight: 600 !important;
        border-radius: 10px !important;
        transition: all 0.3s ease !important;
    }
    .popup-confirm-btn:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 8px 16px rgba(26, 115, 232, 0.3) !important;
    }
    .popup-cancel-btn {
        padding: 12px 30px !important;
        font-weight: 600 !important;
        border-radius: 10px !important;
    }
</style>
@endguest


<!-- ================= MOBILE APP NAVIGATION ================= -->
<!-- ================= MOBILE APP NAVIGATION ================= -->
@include('partials.mobile-nav')


<style>
    /* Safe area padding for iPhones with home indicator */
    .pb-safe {
        padding-bottom: env(safe-area-inset-bottom, 12px);
    }
</style>

{{-- AI Chatbot Widget --}}
@include('partials.chatbot')

<script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/sw.js')
                .then(registration => {
                    console.log('ServiceWorker registration successful');
                })
                .catch(err => {
                    console.log('ServiceWorker registration failed: ', err);
                });
        });
    }
</script>

</body>
</html>
