<x-app-layout>
    <!-- DASHBOARD VERSION 3 -->
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                    <span class="material-icons text-primary">admin_panel_settings</span>
                    GLOBAL TRADE FAIRS <span class="text-primary">ADMIN</span>
                </h2>
                <p class="text-sm text-gray-500 mt-1">Manage events, approvals, and system settings.</p>
            </div>
            
            <div class="flex items-center gap-3">
                <div class="relative">
                    <input type="text" id="dashboardSearch" placeholder="Search events..." 
                           class="pl-9 pr-9 py-2 bg-gray-50 border border-gray-200 rounded-md text-sm focus:ring-primary focus:border-primary w-64 transition-all">
                    <button id="clearSearch" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 hidden">
                        <span class="material-icons text-sm">cancel</span>
                    </button>
                </div>

                <a href="{{ route('event.form') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:opacity-80 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150"
                   style="background-color: #2563eb !important;">
                    <span class="material-icons text-sm mr-2">add_circle</span>
                    Create New Event
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Tab Navigation --}}
            <div class="mb-6 border-b border-gray-200">
                <nav class="-mb-px flex space-x-8">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="border-primary text-primary whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Event Details
                    </a>
                    <a href="{{ route('admin.payments') }}" 
                       class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition">
                        Payment
                    </a>
                </nav>
            </div>
            
            {{-- Success Message --}}
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Google Analytics Stats Widget --}}
            <div class="bg-gradient-to-r from-primary to-blue-700 rounded-2xl shadow-xl p-6 mb-6 text-white">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-2xl font-bold flex items-center gap-2">
                            <span class="material-icons">analytics</span>
                            Website Analytics
                        </h3>
                        <p class="text-blue-100 text-sm mt-1">Real-time visitor statistics</p>
                    </div>
                    <a href="https://analytics.google.com" target="_blank" 
                       class="bg-white/20 hover:bg-white/30 px-4 py-2 rounded-lg text-sm font-semibold transition flex items-center gap-2">
                        <span class="material-icons text-sm">open_in_new</span>
                        View Full Report
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    {{-- Today's Visitors --}}
                    <div class="bg-white/10 backdrop-blur rounded-xl p-4 hover:bg-white/20 transition">
                        <div class="flex items-center justify-between mb-2">
                            <span class="material-icons text-3xl">people</span>
                            <span class="text-xs bg-green-500 px-2 py-1 rounded-full text-white">Live</span>
                        </div>
                        <p class="text-3xl font-bold" id="todayVisitors">-</p>
                        <p class="text-sm text-blue-100">Today's Visitors</p>
                    </div>

                    {{-- Active Users --}}
                    <div class="bg-white/10 backdrop-blur rounded-xl p-4 hover:bg-white/20 transition">
                        <div class="flex items-center justify-between mb-2">
                            <span class="material-icons text-3xl">visibility</span>
                            <span class="text-xs bg-red-500 px-2 py-1 rounded-full text-white animate-pulse">Now</span>
                        </div>
                        <p class="text-3xl font-bold" id="activeUsers">-</p>
                        <p class="text-sm text-blue-100">Active Users</p>
                    </div>

                    {{-- Total Page Views --}}
                    <div class="bg-white/10 backdrop-blur rounded-xl p-4 hover:bg-white/20 transition">
                        <div class="flex items-center justify-between mb-2">
                            <span class="material-icons text-3xl">pageview</span>
                        </div>
                        <p class="text-3xl font-bold" id="pageViews">-</p>
                        <p class="text-sm text-blue-100">Page Views Today</p>
                    </div>

                    {{-- Avg Session Duration --}}
                    <div class="bg-white/10 backdrop-blur rounded-xl p-4 hover:bg-white/20 transition">
                        <div class="flex items-center justify-between mb-2">
                            <span class="material-icons text-3xl">schedule</span>
                        </div>
                        <p class="text-3xl font-bold" id="avgDuration">-</p>
                        <p class="text-sm text-blue-100">Avg. Duration</p>
                    </div>
                </div>

                {{-- Quick Instructions --}}
            </div>

            {{-- Pending Events --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        Pending Events (Awaiting Approval)
                    </h3>
                    
                    @php
                        $pendingForms = \App\Models\Form::where('status', 'pending')->orderBy('id', 'desc')->get();
                    @endphp

                    @if($pendingForms->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Event</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Organizer</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">City</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Venue</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($pendingForms as $form)
                                        <tr>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $form->ExponName }}
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $form->Orgname }}
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $form->city }}
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $form->VenueName }}
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $form->Date }}
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Pending
                                                </span>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm">
                                                <div class="flex items-center">
                                                    <a href="{{ route('approve.form', $form->id) }}" 
                                                       class="inline-flex items-center px-4 py-1 text-white text-xs font-medium rounded hover:opacity-80"
                                                       style="background-color: #16a34a !important; color: white !important; margin-right: 10px !important;">
                                                        Approve
                                                    </a>
                                                    <a href="{{ route('disapprove.form', $form->id) }}" 
                                                       class="inline-flex items-center px-4 py-1 bg-red-600 text-white text-xs font-medium rounded hover:bg-red-700"
                                                       style="margin-right: 10px !important;">
                                                        Reject
                                                    </a>
                                                    <a href="{{ route('form.edit', $form->id) }}" 
                                                       class="inline-flex items-center px-4 py-1 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700"
                                                       style="margin-right: 10px !important;">
                                                        Update
                                                    </a>
                                                    <button type="button" 
                                                            onclick="showPreview(JSON.parse(this.getAttribute('data-form')))"
                                                            data-form="{{ json_encode($form) }}"
                                                            class="inline-flex items-center px-3 py-1 text-white text-xs font-medium rounded hover:opacity-80"
                                                            style="background-color: #4b5563 !important; color: white !important;">
                                                        <span class="material-icons text-xs mr-1">visibility</span>
                                                        View
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500">No pending events to review.</p>
                    @endif
                </div>
            </div>

            {{-- Approved Events --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        Approved Events (Visible on Global Fairs)
                    </h3>
                    
                    @php
                        $approvedForms = \App\Models\Form::where('status', 'approved')->orderBy('id', 'desc')->get();
                    @endphp

                    @if($approvedForms->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Event</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Organizer</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">City</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($approvedForms as $form)
                                        <tr>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $form->ExponName }}
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $form->Orgname }}
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $form->city }}
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $form->Date }}
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                    Approved
                                                </span>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm">
                                                <div class="flex items-center">
                                                    <a href="{{ route('form.edit', $form->id) }}" 
                                                       class="inline-flex items-center px-4 py-1 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700"
                                                       style="margin-right: 10px !important;">
                                                        Update
                                                    </a>
                                                    <a href="{{ route('disapprove.form', $form->id) }}" 
                                                       class="inline-flex items-center px-4 py-1 bg-red-600 text-white text-xs font-medium rounded hover:bg-red-700"
                                                       style="margin-right: 10px !important;">
                                                        Reject
                                                    </a>
                                                    <button type="button" 
                                                            onclick="showPreview(JSON.parse(this.getAttribute('data-form')))"
                                                            data-form="{{ json_encode($form) }}"
                                                            class="inline-flex items-center px-3 py-1 text-white text-xs font-medium rounded hover:opacity-80"
                                                            style="background-color: #4b5563 !important; color: white !important;">
                                                        <span class="material-icons text-xs mr-1">visibility</span>
                                                        View
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500">No approved events yet.</p>
                    @endif
                </div>
            </div>

        </div>
    </div>

    {{-- Data Preview Modal with Clean Design --}}
    <div id="previewModal" class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center py-8 px-4 z-50 hidden">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-6xl max-h-[80vh] flex flex-col relative">
            
            <!-- Close Button (X) in Upper Right -->
            <button onclick="hidePreview()" 
                    class="absolute top-3 right-3 z-50 text-blue-600 hover:text-blue-800 transition-all cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            
            <!-- Header Section (Fixed) -->
            <div class="bg-gray-50 px-6 py-4 border-b flex-shrink-0 relative">
                
                <!-- Hall Badge - Upper Left -->
                <div class="absolute top-4 left-6">
                    <span id="prevHallBadge" class="inline-block px-3 py-1 bg-blue-100 text-blue-600 text-xs font-semibold rounded"></span>
                </div>
                
                <!-- Title - Center -->
                <div class="text-center px-20">
                    <h1 id="prevExponName" class="text-3xl font-bold text-blue-600 mb-2"></h1>
                    <div class="text-sm text-gray-600">
                        Organizer: <span id="prevOrgname" class="font-medium"></span> • Date: <span id="prevDate"></span>
                    </div>
                </div>
                
            </div>

            <!-- Content (Scrollable) -->
            <div class="overflow-y-auto flex-1 p-4">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    
                    <!-- Left: Image (1 column) -->
                    <div class="lg:col-span-1">
                        <img id="prevImage" src="" alt="Event Image" 
                             class="w-full rounded-lg object-contain max-h-60 hidden">
                        <div id="prevNoImage" class="w-full h-60 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400 hidden">
                            <div class="text-center">
                                <span class="material-icons text-5xl mb-2">image_not_supported</span>
                                <p class="text-xs">No image provided</p>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 italic mt-3 text-center">
                            For event registration, use the button below if provided.
                        </p>
                    </div>

                    <!-- Right: Details (2 columns) -->
                    <div class="lg:col-span-2">
                        <div class="grid grid-cols-2 gap-2">
                            
                            <!-- Venue -->
                            <div class="bg-white p-3 rounded-lg border border-blue-100 shadow-sm hover:shadow-md transition-shadow">
                                <p class="text-xs font-semibold text-blue-600 uppercase tracking-wider mb-1">Venue</p>
                                <p id="prevVenueName" class="text-sm text-gray-900 font-medium"></p>
                            </div>

                            <!-- City -->
                            <div class="bg-white p-3 rounded-lg border border-blue-100 shadow-sm hover:shadow-md transition-shadow">
                                <p class="text-xs font-semibold text-blue-600 uppercase tracking-wider mb-1">City</p>
                                <p id="prevCity" class="text-sm text-gray-900 font-medium"></p>
                            </div>

                            <!-- Country -->
                            <div class="bg-white p-3 rounded-lg border border-blue-100 shadow-sm hover:shadow-md transition-shadow">
                                <p class="text-xs font-semibold text-blue-600 uppercase tracking-wider mb-1">Country</p>
                                <p id="prevCountry" class="text-sm text-gray-900 font-medium"></p>
                            </div>

                            <!-- Date -->
                            <div class="bg-white p-3 rounded-lg border border-blue-100 shadow-sm hover:shadow-md transition-shadow">
                                <p class="text-xs font-semibold text-blue-600 uppercase tracking-wider mb-1">Date</p>
                                <p id="prevDateDetail" class="text-sm text-gray-900 font-medium"></p>
                            </div>

                            <!-- Organizer -->
                            <div class="bg-white p-3 rounded-lg border border-blue-100 shadow-sm hover:shadow-md transition-shadow">
                                <p class="text-xs font-semibold text-blue-600 uppercase tracking-wider mb-1">Organizer</p>
                                <p id="prevOrgnameDetail" class="text-sm text-gray-900 font-medium"></p>
                            </div>

                            <!-- Phone -->
                            <div class="bg-white p-3 rounded-lg border border-blue-100 shadow-sm hover:shadow-md transition-shadow">
                                <p class="text-xs font-semibold text-blue-600 uppercase tracking-wider mb-1">Phone</p>
                                <p id="prevPhone" class="text-sm text-gray-900 font-medium"></p>
                            </div>

                            <!-- Email -->
                            <div class="bg-white p-3 rounded-lg border border-blue-100 shadow-sm hover:shadow-md transition-shadow">
                                <p class="text-xs font-semibold text-blue-600 uppercase tracking-wider mb-1">Email</p>
                                <p id="prevEmail" class="text-sm text-gray-900 font-medium break-all"></p>
                            </div>

                            <!-- Hall No -->
                            <div class="bg-white p-3 rounded-lg border border-blue-100 shadow-sm hover:shadow-md transition-shadow">
                                <p class="text-xs font-semibold text-blue-600 uppercase tracking-wider mb-1">Hall No</p>
                                <p id="prevHallno" class="text-sm text-gray-900 font-medium"></p>
                            </div>

                            <!-- Registration (Full Width) -->
                            <div id="prevReglinkContainer" class="col-span-2 bg-white p-4 rounded-xl border border-blue-100 shadow-sm hover:shadow-md transition-shadow hidden">
                                <p class="text-xs font-semibold text-blue-600 uppercase tracking-wider mb-2">Registration</p>
                                <a id="prevReglink" href="#" target="_blank" 
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700 transition">
                                    <span class="material-icons text-sm mr-2">open_in_new</span>
                                    Open Registration Page
                                </a>
                            </div>
                            
                        </div>

                        <!-- Back Button -->
                        <div class="mt-4 flex justify-end">
                            <button onclick="hidePreview()" 
                                    class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded hover:bg-gray-700 transition">
                                <span class="material-icons text-sm mr-2">arrow_back</span>
                                Back
                            </button>
                        </div>
                    </div>

                </div>
            <!-- End Scrollable Content -->
        </div>
    </div>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        #previewModal {
            font-family: 'Poppins', sans-serif;
        }
    </style>

    <script>
        function showPreview(data) {
            // Header fields
            document.getElementById('prevExponName').innerText = data.ExponName || 'N/A';
            document.getElementById('prevOrgname').innerText = data.Orgname || 'N/A';
            document.getElementById('prevDate').innerText = data.Date || 'N/A';
            
            // Hall badge in header
            const hallBadge = document.getElementById('prevHallBadge');
            if (data.hallno) {
                hallBadge.innerText = 'Hall ' + data.hallno;
            } else {
                hallBadge.innerText = '';
            }
            
            // Detail fields
            document.getElementById('prevVenueName').innerText = data.VenueName || '—';
            document.getElementById('prevCity').innerText = data.city || '—';
            document.getElementById('prevCountry').innerText = data.country || '—';
            document.getElementById('prevDateDetail').innerText = data.Date || '—';
            document.getElementById('prevOrgnameDetail').innerText = data.Orgname || '—';
            document.getElementById('prevPhone').innerText = data.phone || '—';
            document.getElementById('prevEmail').innerText = data.email || '—';
            document.getElementById('prevHallno').innerText = data.hallno || '—';
            
            // Registration link
            const regLinkContainer = document.getElementById('prevReglinkContainer');
            const regLink = document.getElementById('prevReglink');
            if (data.reglink) {
                regLink.href = data.reglink;
                regLinkContainer.classList.remove('hidden');
            } else {
                regLinkContainer.classList.add('hidden');
            }

            // Image handling
            const img = document.getElementById('prevImage');
            const noImg = document.getElementById('prevNoImage');
            if (data.image) {
                img.src = '/uploads/' + data.image;
                img.classList.remove('hidden');
                noImg.classList.add('hidden');
            } else {
                img.classList.add('hidden');
                noImg.classList.remove('hidden');
            }

            document.getElementById('previewModal').classList.remove('hidden');
        }

        function hidePreview() {
            document.getElementById('previewModal').classList.add('hidden');
        }

        // Close on escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                hidePreview();
            }
        });

        // Close on clicking outside the modal
        document.getElementById('previewModal').addEventListener('click', function(event) {
            if (event.target === this) {
                hidePreview();
            }
        });

        // Dashboard Search Logic
        const searchInput = document.getElementById('dashboardSearch');
        const clearBtn = document.getElementById('clearSearch');
        const pendingRows = document.querySelectorAll('tbody:first-of-type tr:not(#noPendingResults)');
        const approvedRows = document.querySelectorAll('tbody:last-of-type tr:not(#noApprovedResults)');
        const noPending = document.getElementById('noPendingResults');
        const noApproved = document.getElementById('noApprovedResults');

        function filterTable(rows, query, noResultEl) {
            let hasMatch = false;
            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                const isMatch = text.includes(query.toLowerCase());
                row.classList.toggle('hidden', !isMatch);
                if (isMatch) hasMatch = true;
            });
            if (noResultEl) {
                noResultEl.classList.toggle('hidden', hasMatch || query === '');
            }
        }

        searchInput.addEventListener('input', (e) => {
            const query = e.target.value;
            clearBtn.classList.toggle('hidden', query === '');
            
            filterTable(pendingRows, query, noPending);
            filterTable(approvedRows, query, noApproved);
        });

        clearBtn.addEventListener('click', () => {
            searchInput.value = '';
            clearBtn.classList.add('hidden');
            filterTable(pendingRows, '', noPending);
            filterTable(approvedRows, '', noApproved);
            searchInput.focus();
        });

        // -------------------------
        // GOOGLE ANALYTICS DATA
        // -------------------------
        async function fetchAnalyticsData() {
            try {
                const response = await fetch('{{ route("analytics.dashboard.stats") }}');
                const result = await response.json();
                
                if (result.success && result.data) {
                    const data = result.data;
                    
                    // Update stats
                    document.getElementById('todayVisitors').textContent = 
                        data.today_visitors ? data.today_visitors.toLocaleString() : '0';
                    
                    document.getElementById('activeUsers').textContent = 
                        data.active_users ? data.active_users.toLocaleString() : '0';
                    
                    document.getElementById('pageViews').textContent = 
                        data.page_views ? data.page_views.toLocaleString() : '0';
                    
                    document.getElementById('avgDuration').textContent = 
                        data.avg_duration || '0m 0s';
                } else {
                    console.error('Analytics API error:', result.error);
                }
            } catch (error) {
                console.error('Failed to fetch analytics:', error);
            }
        }

        // Fetch data on page load
        fetchAnalyticsData();

        // Auto-refresh every 30 seconds
        setInterval(fetchAnalyticsData, 30000);
    </script>
</x-app-layout>
