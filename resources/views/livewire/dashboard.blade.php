<div class="p-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Dashboard</h1>
        <p class="text-gray-600">Manage your bookings and track your rental history</p>
    </div>
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            <!-- Total Bookings -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Total Bookings</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $totalBookings }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Pending Bookings -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Pending</p>
                        <p class="text-3xl font-bold text-yellow-600">{{ $pendingBookings }}</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Confirmed Bookings -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Confirmed</p>
                        <p class="text-3xl font-bold text-green-600">{{ $confirmedBookings }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Draft Bookings -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Draft</p>
                        <p class="text-3xl font-bold text-gray-600">{{ $draftBookings }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Bookings -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-gray-50 to-blue-50 px-8 py-6 border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900">Recent Bookings</h2>
                <p class="text-gray-600 mt-1">Your latest bookings that are not completed</p>
            </div>

            @if($recentBookings->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($recentBookings as $booking)
                        <div class="p-8 hover:bg-gray-50 transition-colors duration-200">
                            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-6 mb-4">
                                        @if($booking->vehicle->getMainImageUrl())
                                            <img src="{{ $booking->vehicle->getMainImageUrl() }}" 
                                                 alt="{{ $booking->vehicle->name }}" 
                                                 class="w-24 h-16 object-cover rounded-xl shadow-lg">
                                        @else
                                            <img src="https://via.placeholder.com/120x80?text=Vehicle" 
                                                 alt="{{ $booking->vehicle->name }}" 
                                                 class="w-24 h-16 object-cover rounded-xl shadow-lg">
                                        @endif
                                        <div>
                                            <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $booking->vehicle->name }}</h3>
                                            <p class="text-gray-600">{{ ucfirst($booking->vehicle->type) }} • {{ $booking->vehicle->seats }} seats</p>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">
                                        <div>
                                            <p class="text-sm text-gray-500 mb-1">Booking ID</p>
                                            <p class="font-mono font-semibold text-gray-900">{{ $booking->booking_code }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500 mb-1">Rental Period</p>
                                            <p class="font-semibold text-gray-900">
                                                {{ \Carbon\Carbon::parse($booking->start_date)->format('M d, Y') }} - 
                                                {{ \Carbon\Carbon::parse($booking->end_date)->format('M d, Y') }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500 mb-1">Total Amount</p>
                                            <p class="text-2xl font-bold text-blue-600">${{ number_format($booking->total_amount, 2) }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-center space-x-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                            @if($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($booking->status === 'confirmed') bg-green-100 text-green-800
                                            @elseif($booking->status === 'draft') bg-gray-100 text-gray-800
                                            @else bg-blue-100 text-blue-800 @endif">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                        
                                        @if($booking->payment_reference)
                                            <span class="text-sm text-gray-500">
                                                Payment Ref: <span class="font-mono font-semibold">{{ $booking->payment_reference }}</span>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="mt-4 lg:mt-0 lg:ml-6">
                                    <div class="flex flex-col sm:flex-row gap-3">
                                        @if($booking->status === 'pending')
                                            <a href="{{ route('booking.create', $booking->vehicle->slug) }}?booking_id={{ $booking->id }}&step=3" 
                                               class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition-colors duration-200 font-semibold text-center">
                                                Complete Payment
                                            </a>
                                        @endif
                                        
                                        @if($booking->status === 'draft')
                                            <button class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors duration-200 font-semibold">
                                                Cancel Booking
                                            </button>
                                        @endif
                                        
                                        <a href="{{ route('vehicles.show', $booking->vehicle->slug) }}" 
                                           class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200 font-semibold text-center">
                                            View Vehicle
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="bg-gray-50 px-8 py-4">
                    {{ $recentBookings->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">No Recent Bookings</h3>
                    <p class="text-gray-600 mb-8">You don't have any active bookings at the moment.</p>
                    <a href="{{ route('vehicles.index') }}" 
                       class="bg-gradient-to-r from-blue-600 to-cyan-600 text-white px-8 py-3 rounded-xl hover:from-blue-700 hover:to-cyan-700 transition-all duration-300 font-bold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 inline-block">
                        Browse Vehicles
                    </a>
                </div>
            @endif
        </div>
</div>
