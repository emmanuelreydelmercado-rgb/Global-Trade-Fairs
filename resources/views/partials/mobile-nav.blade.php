<!-- ================= MOBILE APP NAVIGATION ================= -->
<div x-data="{ 
    settingsOpen: false, 
    theme: localStorage.getItem('theme') || 'system',
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
    }
}" x-init="initTheme()" 
class="fixed bottom-0 left-0 w-full bg-white/90 dark:bg-gray-900/90 backdrop-blur-lg border-t border-gray-200 dark:border-gray-800 z-[100] grid grid-cols-4 gap-1 px-2 py-3 md:hidden shadow-[0_-5px_20px_rgba(0,0,0,0.05)] pb-safe">
    
    <!-- 1. Home -->
    <a href="{{ route('home') }}" class="flex flex-col items-center justify-center gap-1 group">
        <div class="p-1 rounded-xl transition-all duration-300 {{ request()->routeIs('home') ? 'bg-primary/10 text-primary' : 'text-gray-400 dark:text-gray-500 group-hover:text-gray-600 dark:group-hover:text-gray-300' }}">
            <span class="material-icons text-2xl transition-transform group-active:scale-90">home</span>
        </div>
        <span class="text-[10px] font-semibold {{ request()->routeIs('home') ? 'text-primary' : 'text-gray-400 dark:text-gray-500' }}">Home</span>
    </a>

    <!-- 2. Tours -->
    <a href="{{ route('tour.packages') }}" class="flex flex-col items-center justify-center gap-1 group">
        <div class="p-1 rounded-xl transition-all duration-300 {{ request()->routeIs('tour.packages') ? 'bg-primary/10 text-primary' : 'text-gray-400 dark:text-gray-500 group-hover:text-gray-600 dark:group-hover:text-gray-300' }}">
            <span class="material-icons text-2xl transition-transform group-active:scale-90">flight</span>
        </div>
        <span class="text-[10px] font-semibold {{ request()->routeIs('tour.packages') ? 'text-primary' : 'text-gray-400 dark:text-gray-500' }}">Tours</span>
    </a>

    <!-- 3. Chat -->
    <button onclick="document.getElementById('chatbot-toggle').click()" class="flex flex-col items-center justify-center gap-1 group">
        <div class="p-1 rounded-xl transition-all duration-300 text-gray-400 dark:text-gray-500 group-hover:text-gray-600 dark:group-hover:text-gray-300">
            <span class="material-icons text-2xl transition-transform group-active:scale-90">chat_bubble</span>
        </div>
        <span class="text-[10px] font-semibold text-gray-400 dark:text-gray-500">Chat</span>
    </button>

    <!-- 4. Settings -->
    <button @click="settingsOpen = true" class="flex flex-col items-center justify-center gap-1 group">
        <div class="p-1 rounded-xl transition-all duration-300 {{ $open ?? false ? 'bg-primary/10 text-primary' : 'text-gray-400 dark:text-gray-500 group-hover:text-gray-600 dark:group-hover:text-gray-300' }}">
            <span class="material-icons text-2xl transition-transform group-active:scale-90">settings</span>
        </div>
        <span class="text-[10px] font-semibold text-gray-400 dark:text-gray-500">Settings</span>
    </button>

    <!-- SETTINGS MODAL -->
    <div 
        x-show="settingsOpen"
        class="fixed inset-0 z-[110]"
        style="display: none;"
    >
        <!-- Backdrop -->
        <div 
            x-show="settingsOpen"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="settingsOpen = false"
            class="absolute inset-0 bg-black/50 backdrop-blur-sm"
        ></div>

        <!-- Sheet -->
        <div 
            x-show="settingsOpen"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="translate-y-full"
            x-transition:enter-end="translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="translate-y-0"
            x-transition:leave-end="translate-y-full"
            class="absolute bottom-0 left-0 w-full bg-white dark:bg-gray-900 rounded-t-3xl shadow-2xl overflow-hidden max-h-[85vh] flex flex-col"
        >
            <!-- Handle -->
            <div class="w-full flex justify-center pt-3 pb-1 relative" @click="settingsOpen = false">
                <div class="w-12 h-1.5 bg-gray-300 dark:bg-gray-700 rounded-full"></div>
            </div>

            <!-- Content -->
            <div class="p-6 overflow-y-auto relative">
                <!-- Close Button (X) -->
                <button @click="settingsOpen = false" class="absolute top-0 right-0 p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 bg-gray-100 dark:bg-gray-800 rounded-full transition-colors">
                    <span class="material-icons text-xl">close</span>
                </button>

                <h2 class="text-2xl font-bold mb-6 dark:text-white mt-2">Settings</h2>

                <!-- 1. Profile Section -->
                <div class="mb-8">
                    @auth
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-2xl p-4 flex items-center gap-4">
                            <img src="{{ (auth()->user()->profilepic && auth()->user()->profilepic !== 'default.jpg') ? asset('profilepics/' . auth()->user()->profilepic) : asset('profilepics/user_avatar.png') }}" 
                                 onerror="this.src='{{ asset('profilepics/user_avatar.png') }}'"
                                 class="w-14 h-14 rounded-full object-cover border-2 border-white dark:border-gray-700">
                            <div class="flex-grow">
                                <h3 class="font-bold text-lg dark:text-white">{{ auth()->user()->name }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</p>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="mt-3">
                            @csrf
                            <button type="submit" class="w-full py-3 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 font-semibold flex items-center justify-center gap-2">
                                <span class="material-icons">logout</span> Sign Out
                            </button>
                        </form>
                    @else
                        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-2xl p-6 text-center">
                            <div class="w-16 h-16 bg-blue-100 dark:bg-blue-800 rounded-full flex items-center justify-center mx-auto mb-3 text-3xl">👤</div>
                            <h3 class="font-bold text-lg mb-1 dark:text-white">Guest User</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Login to access your profile and bookings</p>
                            <a href="{{ route('login') }}" class="block w-full py-3 bg-primary text-white font-bold rounded-xl shadow-lg shadow-blue-500/30">
                                Login / Sign Up
                            </a>
                        </div>
                    @endauth
                </div>

                <!-- 2. Wishlist Section -->
                @auth
                <div class="mb-8">
                    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-3">My Favorites</h3>
                    <a href="{{ route('wishlist.index') }}" class="flex items-center justify-between p-4 bg-pink-50 dark:bg-pink-900/20 rounded-2xl hover:bg-pink-100 dark:hover:bg-pink-900/30 transition-colors">
                        <div class="flex items-center gap-3">
                            <span class="material-icons text-2xl text-red-500">favorite</span>
                            <div>
                                <h4 class="font-bold text-gray-800 dark:text-white">Wishlist</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400">View saved events</p>
                            </div>
                        </div>
                        <span class="material-icons text-gray-400">chevron_right</span>
                    </a>
                </div>
                @endauth

                <!-- 3. Preferences -->
                <div>
                    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Preferences</h3>
                    
                    <!-- Dark Mode Toggle -->
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-2xl p-1 flex relative">
                        <!-- Sliding Background -->
                        <div class="absolute top-1 bottom-1 rounded-xl bg-white dark:bg-gray-700 shadow-sm transition-all duration-300 w-1/3"
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
</div>

<style>
    /* Hide floating chatbot button on mobile since we have it in nav */
    @media (max-width: 768px) {
        #chatbot-toggle {
            display: none !important;
        }
    }
    
    /* Dark mode basics */
    .dark body {
        background-color: #111827;
        color: #f3f4f6;
    }
    .dark .bg-white {
        background-color: #1f2937;
    }
    .dark .text-gray-800 {
        color: #f3f4f6;
    }
     /* Safe area padding for iPhones with home indicator */
    .pb-safe {
        padding-bottom: env(safe-area-inset-bottom, 12px);
    }
</style>
