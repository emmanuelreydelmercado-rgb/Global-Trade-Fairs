<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                    <span class="material-icons text-primary">admin_panel_settings</span>
                    GLOBAL TRADE FAIRS <span class="text-primary">ADMIN</span>
                </h2>
                <p class="text-sm text-gray-500 mt-1">Manage payments and transaction records.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Tab Navigation --}}
            <div class="mb-6 border-b border-gray-200">
                <nav class="-mb-px flex space-x-8">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition">
                        Dashboard
                    </a>
                    <a href="{{ route('admin.payments') }}" 
                       class="border-primary text-primary whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
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

            {{-- Payment Records --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <span class="material-icons text-green-600">payments</span>
                        Payment Records
                    </h3>

                    @if($payments->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Package</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Razorpay Order ID</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Payment ID</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($payments as $payment)
                                        <tr>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                                #{{ $payment->id }}
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $payment->name }}
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $payment->email }}
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $payment->phone }}
                                            </td>
                                            <td class="px-4 py-4 text-sm text-gray-500">
                                                <div class="font-medium text-gray-900">{{ ucfirst($payment->package_type) }}</div>
                                                <div class="text-xs text-gray-500">{{ $payment->package_name }}</div>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm font-semibold text-green-600">
                                                ₹{{ number_format($payment->amount) }}
                                            </td>
                                            <td class="px-4 py-4 text-sm text-gray-500">
                                                <code class="text-xs bg-gray-100 px-2 py-1 rounded">{{ $payment->razorpay_order_id }}</code>
                                            </td>
                                            <td class="px-4 py-4 text-sm text-gray-500">
                                                <code class="text-xs bg-gray-100 px-2 py-1 rounded">{{ $payment->razorpay_payment_id }}</code>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                @if($payment->status == 'success')
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                        Success
                                                    </span>
                                                @elseif($payment->status == 'pending')
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        Pending
                                                    </span>
                                                @else
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                        Failed
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm">
                                                <a href="{{ route('payment.download', $payment->id) }}" 
                                                   class="inline-flex items-center px-3 py-1 text-white text-xs font-medium rounded hover:opacity-80"
                                                   style="background-color: #16a34a !important; color: white !important;">
                                                    <span class="material-icons text-xs mr-1">file_download</span>
                                                    Download PDF
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500">No payment records found.</p>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
