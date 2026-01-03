<nav x-data="{ open: false, profileOpen: false }" class="sticky top-0 z-50 bg-white/80 backdrop-blur border-b shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 h-20 flex justify-between items-center gap-4">
        
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.dashboard') }}">
                <img src="{{ asset('favicon.png') }}" class="w-10 rounded-lg">
            </a>
            <h1 class="text-2xl font-bold">
                <span class="text-primary">GLOBAL</span> TRADE FAIRS
            </h1>
        </div>

        <!-- Navigation Links -->
        <nav class="hidden lg:flex items-center gap-6">
            <a href="{{ route('home') }}" class="text-gray-700 hover:text-primary transition-colors">Events</a>
            <a href="{{ route('tour.packages') }}" class="text-gray-700 hover:text-primary transition-colors">Tour Packages</a>
            <a href="{{ route('admin.dashboard') }}" class="text-primary font-semibold">Dashboard</a>
        </nav>

        <!-- Search (Optional for admin) -->
        <div class="hidden md:block w-[300px]"></div>

        <!-- Auth Section -->
        <div class="hidden lg:flex items-center gap-4">
            <!-- Profile Dropdown -->
            <div class="relative">
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
                    <!-- Online Status -->
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
                        <a href="{{ route('profile.edit') }}" 
                           class="w-full flex items-center justify-center gap-2 px-4 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-100 rounded-xl transition-all">
                            <span class="material-icons text-xl">person</span>
                            Profile Settings
                        </a>
                        
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
        </div>

        <!-- Mobile Menu Button -->
        <button @click="open = !open" class="lg:hidden p-2 text-gray-600 hover:bg-gray-100 rounded-full transition-colors">
            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': !open}" class="hidden lg:hidden border-t">
        <div class="px-4 py-3 space-y-1 bg-gray-50">
            <a href="{{ route('home') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary hover:bg-white transition">
                Events
            </a>
            <a href="{{ route('tour.packages') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary hover:bg-white transition">
                Tour Packages
            </a>
            <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-primary bg-white">
                Dashboard
            </a>
        </div>

        <!-- Responsive Settings -->
        <div class="pt-4 pb-3 border-t border-gray-200 bg-white">
            <div class="px-4 flex items-center gap-3">
                <img
                    src="{{ (auth()->user()->profilepic && auth()->user()->profilepic !== 'default.jpg')
                        ? asset('profilepics/' . auth()->user()->profilepic)
                        : asset('profilepics/user_avatar.png') }}"
                    class="w-10 h-10 rounded-full object-cover"
                    alt="User"
                >
                <div>
                    <div class="font-medium text-base text-gray-800">{{ auth()->user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ auth()->user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 px-4 space-y-1">
                <a href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary hover:bg-gray-50 transition">
                    Profile Settings
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-2 rounded-md text-base font-medium text-red-600 hover:bg-red-50 transition">
                        Sign Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: "#1a73e8",
                    "primary-hover": "#1557b0",
                },
            },
        },
    };
</script>
