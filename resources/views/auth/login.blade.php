<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Login - Global Trade Fairs</title>
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
</head>

<body class="bg-[#f4f7fb] dark:bg-gray-900 font-display min-h-screen flex flex-col">

<!-- ================= HEADER ================= -->
<header class="sticky top-0 z-50 bg-white/80 dark:bg-gray-800/80 backdrop-blur border-b dark:border-gray-700 shadow-sm">
    <div class="max-w-7xl mx-auto px-3 sm:px-4 h-16 sm:h-20 grid grid-cols-3 items-center gap-2">

        <!-- Logo (Left) -->
        <div class="flex items-center gap-1.5 sm:gap-3">
            <img src="{{ asset('favicon.png') }}" class="w-8 sm:w-10 rounded-lg">
            <h1 class="text-sm sm:text-lg md:text-2xl font-bold">
                <span class="text-primary">GLOBAL</span>
                <span class="dark:text-white hidden sm:inline"> TRADE FAIRS</span>
                <span class="dark:text-white sm:hidden"> TF</span>
            </h1>
        </div>

        <!-- Center Text (Center) -->
        <div class="flex justify-center">
            <h2 class="text-base sm:text-lg md:text-xl font-bold text-gray-700 dark:text-gray-300">LOGIN</h2>
        </div>

        <!-- Settings Dropdown (Right) -->
        <div class="flex justify-end">
        <div class="relative" x-data="{ 
            settingsOpen: false,
            theme: localStorage.getItem('theme') || 'light',
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
        }" x-init="initTheme()">
            <button 
                @click="settingsOpen = !settingsOpen" 
                @click.outside="settingsOpen = false"
                class="p-1.5 sm:p-2 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full transition-colors"
            >
                <span class="material-icons text-xl sm:text-2xl">settings</span>
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
                class="absolute right-0 mt-2 sm:mt-3 w-72 sm:w-80 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 z-50 overflow-hidden"
                style="display: none;"
            >
                <div class="p-5">
                    <h3 class="text-lg font-bold mb-4 text-gray-800 dark:text-white">Settings</h3>

                    <!-- Dark Mode -->
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
        </div>
    </div>
</header>

<!-- ================= LOGIN FORM ================= -->
<main class="flex-grow flex items-center justify-center px-3 sm:px-4 py-4 sm:py-6">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border dark:border-gray-700 p-4 sm:p-6 w-full max-w-md">
        <div class="text-center mb-3 sm:mb-4">
            <h3 class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-white">Welcome Back</h3>
            <p class="text-sm sm:text-base text-gray-500 dark:text-gray-400 mt-1 sm:mt-2">Sign in to your account</p>
        </div>

        @if($errors->any())
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-600 dark:text-red-400 px-4 py-3 rounded-lg mb-4">
                {{ $errors->first() }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-600 dark:text-red-400 px-4 py-3 rounded-lg mb-4">
                {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-600 dark:text-green-400 px-4 py-3 rounded-lg mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" enctype="multipart/form-data" class="space-y-3">
            @csrf

            <div>
                <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                <input type="email" name="email" required autofocus value="{{ old('email') }}"
                    class="w-full px-3 py-2.5 sm:py-2 text-sm sm:text-base rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent">
            </div>

            <div>
                <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Profile Picture (optional)</label>
                <input type="file" name="profilepic" accept="image/*"
                    class="w-full px-2 sm:px-3 py-1.5 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-xs sm:text-sm file:mr-2 sm:file:mr-3 file:py-1 sm:file:py-1.5 file:px-2 sm:file:px-3 file:text-xs sm:file:text-sm file:rounded-lg file:border-0 file:bg-primary file:text-white hover:file:bg-primary-hover">
                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Updates your account photo</div>
            </div>

            <div>
                <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password</label>
                <input type="password" name="password" required
                    class="w-full px-3 py-2.5 sm:py-2 text-sm sm:text-base rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent">
            </div>

            <div class="flex items-center">
                <input type="checkbox" id="remember_me" name="remember" class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-primary border-gray-300 rounded focus:ring-primary">
                <label for="remember_me" class="ml-2 text-xs sm:text-sm text-gray-600 dark:text-gray-400">Remember me</label>
            </div>

            <button type="submit" class="w-full bg-primary hover:bg-primary-hover text-white text-sm sm:text-base font-semibold py-2.5 sm:py-3 rounded-lg transition-colors mt-3 sm:mt-4 active:scale-98 touch-manipulation">
                Log in
            </button>
        </form>

        <!-- OR Divider -->
        <div class="flex items-center my-3 sm:my-4">
            <hr class="flex-grow border-gray-300 dark:border-gray-600">
            <span class="px-3 sm:px-4 text-xs sm:text-sm text-gray-500 dark:text-gray-400">OR</span>
            <hr class="flex-grow border-gray-300 dark:border-gray-600">
        </div>

        <!-- Google Sign-In -->
        <a href="{{ route('auth.google.login') }}" class="flex items-center justify-center gap-2 w-full border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm sm:text-base font-semibold py-2.5 sm:py-3 rounded-lg transition-colors active:scale-98 touch-manipulation">
            <svg class="w-4 h-4 sm:w-5 sm:h-5" viewBox="0 0 48 48">
                <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"></path>
                <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"></path>
                <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"></path>
                <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"></path>
            </svg>
            Sign in with Google
        </a>

        <div class="flex flex-col sm:flex-row justify-between items-center gap-2 sm:gap-0 mt-3 sm:mt-4 text-xs sm:text-sm">
            <a href="{{ route('register') }}" class="text-primary hover:underline">Create account</a>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-gray-500 dark:text-gray-400 hover:underline">Forgot password?</a>
            @endif
        </div>
    </div>
</main>

</body>
</html>
