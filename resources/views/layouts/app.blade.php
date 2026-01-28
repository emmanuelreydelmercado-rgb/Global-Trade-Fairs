<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- SEO Meta Tags -->
        <title>@yield('title', 'Global Trade Fairs - Find Trade Shows & Exhibitions Worldwide')</title>
        <meta name="description" content="@yield('description', 'Discover upcoming trade fairs, exhibitions, and expos worldwide. Hardware, technology, manufacturing events and more. Find the perfect trade show for your business.')">
        <meta name="keywords" content="@yield('keywords', 'trade fairs, exhibitions, expos, trade shows, hardware expo, tech events, manufacturing events, business events, B2B events')">
        <meta name="author" content="Global Trade Fairs">
        <meta name="robots" content="index, follow">
        
        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="@yield('og_type', 'website')">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:title" content="@yield('og_title', 'Global Trade Fairs')">
        <meta property="og:description" content="@yield('og_description', 'Find trade shows and exhibitions worldwide')">
        <meta property="og:image" content="@yield('og_image', asset('favicon.png'))">
        <meta property="og:site_name" content="Global Trade Fairs">
        
        <!-- Twitter Card -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:url" content="{{ url()->current() }}">
        <meta name="twitter:title" content="@yield('twitter_title', 'Global Trade Fairs')">
        <meta name="twitter:description" content="@yield('twitter_description', 'Find trade shows worldwide')">
        <meta name="twitter:image" content="@yield('twitter_image', asset('favicon.png'))">
        
        <!-- Canonical URL -->
        <link rel="canonical" href="{{ url()->current() }}">
        
        <!-- Favicon -->
        <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>

        <!-- Tailwind CSS CDN -->
        <script src="https://cdn.tailwindcss.com"></script>

        @include('partials.google-analytics')

        <!-- Google AdSense -->
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-7636580147246668"
                crossorigin="anonymous"></script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
