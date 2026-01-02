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
<header class="sticky top-0 z-50 bg-white/80 backdrop-blur border-b shadow-sm">
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
@if(Auth::check())
    <div class="flex items-center gap-3">

        <img
            src="{{ auth()->user()->profilepic
                ? asset('profilepics/' . auth()->user()->profilepic)
                : asset('profilepics/default.jpg') }}"
            class="w-8 h-8 rounded-full object-cover"
            alt="User"
        >

        <span class="text-sm text-gray-700">
            {{ auth()->user()->name }}
        </span>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button
               type="submit"
                   class="inline-flex items-center px-4 py-2 mt-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 transition">
                            Logout
            </button>

        </form>

    </div>
@else
    <a href="{{ route('login') }}"
       class="bg-primary text-white px-4 py-2 rounded-full text-sm font-medium hover:bg-primary-hover">
        Sign In
    </a>
@endif







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
            <div class="relative h-48 overflow-hidden">
                <img
    src="{{ asset($form->image ? 'uploads/'.$form->image : 'uploads/default.jpg') }}"
    class="w-full h-full object-cover
           transition-transform duration-500 ease-out
           group-hover:scale-110">


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

</body>
</html>
