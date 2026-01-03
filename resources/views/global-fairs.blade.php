<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Global Trade Fairs</title>
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
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
    <!-- amCharts 5 -->
<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/map.js"></script>
<script src="https://cdn.amcharts.com/lib/5/geodata/worldLow.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

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
    <div class="flex items-center gap-4">
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
                        src="{{ auth()->user()->profilepic
                            ? asset('profilepics/' . auth()->user()->profilepic)
                            : asset('profilepics/profpic.png') }}"
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
                                src="{{ auth()->user()->profilepic
                                    ? asset('profilepics/' . auth()->user()->profilepic)
                                    : asset('profilepics/profpic.png') }}"
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
            x-data="{ x: 0, y: 0 }"
            @mousemove="
                // Disable tilt on mobile for smooth scrolling
                if (window.innerWidth < 1024) return;
                const r = $el.getBoundingClientRect();
                x = ((event.clientX - r.left) / r.width - 0.5) * 10;
                y = ((event.clientY - r.top) / r.height - 0.5) * -10;
            "
            @mouseleave="x = 0; y = 0"
            class="group bg-white rounded-xl border overflow-hidden flex flex-col
                   transition-all duration-300 ease-out
                   hover:-translate-y-2 hover:shadow-2xl
                   will-change-transform"
            :style="`transform: perspective(1000px) rotateX(${y}deg) rotateY(${x}deg)`"
        >

            <!-- IMAGE -->
            <div class="relative h-48 overflow-hidden bg-gray-100">
                <img
    src="{{ asset($form->image ? 'uploads/'.$form->image : 'uploads/default.jpg') }}"
    class="w-full h-full object-contain
           transition-transform duration-500 ease-out
           group-hover:scale-105">


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
                            <li>✅ Access to exclusive tour packages</li>
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
<div class="fixed bottom-0 left-0 w-full bg-white/90 backdrop-blur-lg border-t border-gray-200 z-[100] grid grid-cols-4 gap-1 px-2 py-3 md:hidden shadow-[0_-5px_20px_rgba(0,0,0,0.05)] pb-safe">
    
    <a href="{{ route('home') }}" class="flex flex-col items-center justify-center gap-1 group">
        <div class="p-1 rounded-xl transition-all duration-300 {{ request()->routeIs('home') ? 'bg-primary/10 text-primary' : 'text-gray-400 group-hover:text-gray-600' }}">
            <span class="material-icons text-2xl transition-transform group-active:scale-90">home</span>
        </div>
        <span class="text-[10px] font-semibold {{ request()->routeIs('home') ? 'text-primary' : 'text-gray-400' }}">Home</span>
    </a>

    <button @click="window.scrollTo({top: 0, behavior: 'smooth'}); document.querySelector('input[name=search]').focus()" 
            class="flex flex-col items-center justify-center gap-1 group relative">
        <div class="absolute -top-8 bg-primary text-white w-12 h-12 rounded-full shadow-lg shadow-primary/30 flex items-center justify-center border-4 border-[#f4f7fb] transform transition-transform group-active:scale-90">
            <span class="material-icons text-2xl">search</span>
        </div>
        <div class="h-6"></div> <!-- Spacer for the floating button -->
        <span class="text-[10px] font-semibold text-gray-400 mt-1">Search</span>
    </button>

    <a href="{{ route('tour.packages') }}" class="flex flex-col items-center justify-center gap-1 group">
        <div class="p-1 rounded-xl transition-all duration-300 {{ request()->routeIs('tour.packages') ? 'bg-primary/10 text-primary' : 'text-gray-400 group-hover:text-gray-600' }}">
            <span class="material-icons text-2xl transition-transform group-active:scale-90">flight</span>
        </div>
        <span class="text-[10px] font-semibold {{ request()->routeIs('tour.packages') ? 'text-primary' : 'text-gray-400' }}">Tours</span>
    </a>

    @auth
        <div class="relative flex flex-col items-center justify-center gap-1" x-data="{ open: false }">
            <button @click="open = !open" class="flex flex-col items-center justify-center gap-1 group w-full">
                <div class="p-0.5 rounded-full border-2 transition-all duration-300 {{ Auth::check() ? 'border-primary' : 'border-transparent' }}">
                     <img src="{{ auth()->user()->profilepic ? asset('profilepics/' . auth()->user()->profilepic) : asset('profilepics/profpic.png') }}" 
                         class="w-6 h-6 rounded-full object-cover">
                </div>
                <span class="text-[10px] font-semibold text-gray-400">Profile</span>
            </button>

            <!-- Popover Menu -->
            <div x-show="open" 
                 @click.outside="open = false" 
                 class="absolute bottom-20 right-2 w-56 bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden transform origin-bottom-right"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                 style="display: none;">
                 
                 <div class="p-4 bg-gray-50 border-b">
                    <p class="text-sm font-bold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                 </div>
                 
                 <div class="p-2">
                     <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 text-red-600 text-sm font-medium hover:bg-red-50 rounded-xl transition-colors">
                            <span class="material-icons text-[18px]">logout</span>
                            Sign Out
                        </button>
                    </form>
                 </div>
            </div>
        </div>
    @else
        <a href="{{ route('login') }}" class="flex flex-col items-center justify-center gap-1 group">
            <div class="p-1 rounded-xl transition-all duration-300 text-gray-400 group-hover:text-gray-600">
                <span class="material-icons text-2xl transition-transform group-active:scale-90">account_circle</span>
            </div>
            <span class="text-[10px] font-semibold text-gray-400">Login</span>
        </a>
    @endauth

</div>

<style>
    /* Safe area padding for iPhones with home indicator */
    .pb-safe {
        padding-bottom: env(safe-area-inset-bottom, 12px);
    }
</style>

</body>
</html>
