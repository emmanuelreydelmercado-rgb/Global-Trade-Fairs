<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>My Wishlist - Global Trade Fairs</title>
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">
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

    @include('partials.google-analytics')
</head>

<body class="bg-[#f4f7fb] dark:bg-gray-900 font-display min-h-screen flex flex-col">

<!-- ================= HEADER ================= -->
<header class="sticky top-0 z-50 bg-white/80 dark:bg-gray-900/80 backdrop-blur border-b dark:border-gray-800 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 h-20 flex justify-between items-center">

        <div class="flex items-center gap-3">
            <img src="{{ asset('favicon.png') }}" class="w-10 rounded-lg">
            <h1 class="text-2xl font-bold">
                <span class="text-primary">GLOBAL</span> <span class="dark:text-white">TRADE FAIRS</span>
            </h1>
        </div>

        <!-- Back Button -->
        <a href="{{ route('home') }}" class="flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-full hover:bg-primary-hover transition-colors">
            <span class="material-icons text-sm">arrow_back</span>
            <span class="hidden md:inline">Back to Events</span>
        </a>

    </div>
</header>

<!-- ================= CONTENT ================= -->
<main class="flex-grow max-w-7xl mx-auto px-4 py-10 w-full">

    <div class="mb-8">
        <h2 class="text-3xl font-extrabold dark:text-white flex items-center gap-3">
            <span class="material-icons text-red-500 text-4xl">favorite</span>
            My Wishlist
        </h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
            Events you've saved for later
        </p>
    </div>

    <!-- Wishlist Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">

        @forelse($wishlists as $wishlist)
            @php
                $form = $wishlist->form;
            @endphp
            <div
                class="group bg-white dark:bg-gray-800 rounded-xl border dark:border-gray-700 overflow-hidden flex flex-col
                       transition-all duration-300 ease-out
                       hover:-translate-y-2 hover:shadow-2xl"
            >

                <!-- IMAGE -->
                <div class="relative h-48 overflow-hidden bg-gray-100 dark:bg-gray-700">
                    <img
                        src="{{ asset($form->image ? 'uploads/'.$form->image : 'uploads/default.jpg') }}"
                        class="w-full h-full object-contain
                               transition-transform duration-500 ease-out
                               group-hover:scale-105">

                    <!-- Remove Button -->
                    <button 
                        onclick="removeFromWishlist({{ $form->id }})"
                        class="absolute top-3 right-3 w-9 h-9 rounded-full bg-red-500 text-white flex items-center justify-center
                               shadow-md hover:shadow-lg transition-all duration-300 hover:scale-110"
                    >
                        <span class="material-icons text-xl">close</span>
                    </button>

                    @php
                        $eventDate = \Carbon\Carbon::parse($form->Date);
                    @endphp

                    @if($eventDate->isToday())
                        <span
                            class="absolute top-3 left-3 bg-red-600 text-white
                                   px-2 py-1 text-xs font-bold rounded shadow
                                   animate-pulse">
                            Happening Today
                        </span>
                    @elseif($eventDate->isFuture())
                        <span
                            class="absolute top-3 left-3 bg-white/90 dark:bg-gray-800/90 backdrop-blur
                                   px-2 py-1 text-xs font-bold rounded shadow dark:text-white">
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

                    <div class="text-sm text-gray-600 dark:text-gray-300 space-y-2 flex-grow">
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
            <!-- Empty State -->
            <div class="col-span-full flex flex-col items-center justify-center py-20">
                <span class="material-icons text-gray-300 dark:text-gray-600 text-8xl mb-4">favorite_border</span>
                <h3 class="text-2xl font-bold text-gray-400 dark:text-gray-500 mb-2">Your wishlist is empty</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-6">Start adding events you love!</p>
                <a href="{{ route('home') }}" class="px-6 py-3 bg-primary text-white rounded-full hover:bg-primary-hover transition-colors">
                    Explore Events
                </a>
            </div>
        @endforelse
    </div>

</main>

<!-- ================= FOOTER ================= -->
<footer class="bg-primary text-white mt-16">
    <div class="max-w-7xl mx-auto px-6 py-12 text-center">
        <p class="text-sm">© 2025 Global Trade Fairs — Powered by Reydel Mercado Online Services</p>
    </div>
</footer>

<!-- Mobile Bottom Navigation -->
@include('partials.mobile-nav')

<!-- Chatbot -->
@include('partials.chatbot')

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    async function removeFromWishlist(formId) {
        // Show beautiful confirmation dialog
        const result = await Swal.fire({
            title: '💔 Remove from Wishlist?',
            text: "This event will be removed from your saved items",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '✓ Yes, Remove It',
            cancelButtonText: '✗ Cancel',
            reverseButtons: true,
            backdrop: `
                rgba(0,0,0,0.4)
                left top
                no-repeat
            `,
            customClass: {
                popup: 'rounded-2xl',
                title: 'text-xl font-bold',
                confirmButton: 'rounded-lg px-6 py-2.5 font-semibold',
                cancelButton: 'rounded-lg px-6 py-2.5 font-semibold'
            }
        });

        // If user clicked cancel, return
        if (!result.isConfirmed) return;

        try {
            const response = await fetch('{{ route("wishlist.toggle") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ form_id: formId })
            });

            const data = await response.json();
            if (data.success) {
                // Show success message
                await Swal.fire({
                    title: '✓ Removed!',
                    text: 'Event removed from your wishlist',
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false,
                    customClass: {
                        popup: 'rounded-2xl'
                    }
                });
                // Reload page
                location.reload();
            }
        } catch (error) {
            console.error('Error removing from wishlist:', error);
            Swal.fire({
                title: '❌ Error!',
                text: 'Failed to remove. Please try again.',
                icon: 'error',
                confirmButtonColor: '#1a73e8',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-lg px-6 py-2.5 font-semibold'
                }
            });
        }
    }
</script>

</body>
</html>
