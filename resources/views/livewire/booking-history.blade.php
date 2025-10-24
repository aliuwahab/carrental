<div>
    <!-- Header -->
    <div class="bg-gradient-to-r from-slate-900 via-blue-900 to-slate-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl lg:text-5xl font-bold mb-4">My Bookings</h1>
                <p class="text-xl text-blue-100 mb-8">Track and manage your vehicle rentals</p>
                
                <!-- Quick Stats -->
                <div class="flex flex-wrap justify-center gap-8">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-400">{{ $bookings->total() }}</div>
                        <div class="text-sm text-blue-200">Total Bookings</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-400">{{ $bookings->where('status', 'confirmed')->count() }}</div>
                        <div class="text-sm text-blue-200">Confirmed</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-400">{{ $bookings->where('status', 'completed')->count() }}</div>
                        <div class="text-sm text-blue-200">Completed</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="bg-white shadow-lg -mt-8 relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Filter Bookings</h3>
                <div class="flex flex-wrap gap-3">
                    <button wire:click="filterByStatus('')" 
                            class="px-6 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ $statusFilter === '' ? 'bg-blue-600 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-blue-50 hover:text-blue-600' }}">
                        All Bookings
                    </button>
                    <button wire:click="filterByStatus('pending')" 
                            class="px-6 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ $statusFilter === 'pending' ? 'bg-yellow-600 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-yellow-50 hover:text-yellow-600' }}">
                        Pending
                    </button>
                    <button wire:click="filterByStatus('confirmed')" 
                            class="px-6 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ $statusFilter === 'confirmed' ? 'bg-green-600 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-green-50 hover:text-green-600' }}">
                        Confirmed
                    </button>
                    <button wire:click="filterByStatus('completed')" 
                            class="px-6 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ $statusFilter === 'completed' ? 'bg-blue-600 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-blue-50 hover:text-blue-600' }}">
                        Completed
                    </button>
                    <button wire:click="filterByStatus('cancelled')" 
                            class="px-6 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ $statusFilter === 'cancelled' ? 'bg-red-600 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-red-50 hover:text-red-600' }}">
                        Cancelled
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bookings List -->
    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($bookings->count() > 0)
                <div class="space-y-8">
                    @foreach($bookings as $booking)
                        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-300">
                            <div class="p-8">
                                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-6 mb-6">
                                            @if($booking->vehicle->getMainImageUrl())
                                                <img src="{{ $booking->vehicle->getMainImageUrl() }}" 
                                                     alt="{{ $booking->vehicle->name }}" 
                                                     class="w-32 h-20 object-cover rounded-xl shadow-lg">
                                            @else
                                                <img src="https://via.placeholder.com/120x80?text=Vehicle" 
                                                     alt="{{ $booking->vehicle->name }}" 
                                                     class="w-32 h-20 object-cover rounded-xl shadow-lg">
                                            @endif
                                            <div>
                                                <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $booking->vehicle->name }}</h3>
                                                <p class="text-gray-600 text-lg">{{ ucfirst($booking->vehicle->type) }} • {{ $booking->vehicle->seats }} seats</p>
                                            </div>
                                        </div>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                            <div class="bg-gray-50 rounded-xl p-4">
                                                <div class="flex items-center space-x-3 mb-2">
                                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                                    </svg>
                                                    <span class="font-semibold text-gray-700">Booking ID</span>
                                                </div>
                                                <p class="text-gray-900 font-mono text-lg">{{ $booking->booking_code }}</p>
                                            </div>
                                            
                                            <div class="bg-gray-50 rounded-xl p-4">
                                                <div class="flex items-center space-x-3 mb-2">
                                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                    <span class="font-semibold text-gray-700">Rental Period</span>
                                                </div>
                                                <p class="text-gray-900 text-lg">{{ $booking->start_date->format('M d, Y') }} - {{ $booking->end_date->format('M d, Y') }}</p>
                                            </div>
                                            
                                            <div class="bg-gray-50 rounded-xl p-4">
                                                <div class="flex items-center space-x-3 mb-2">
                                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                                    </svg>
                                                    <span class="font-semibold text-gray-700">Total Amount</span>
                                                </div>
                                                <p class="text-gray-900 font-bold text-2xl text-blue-600">${{ number_format($booking->total_amount, 2) }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-6 lg:mt-0 lg:ml-8">
                                        <div class="flex flex-col space-y-4">
                                            <!-- Status Badge -->
                                            <span class="inline-flex items-center px-6 py-3 rounded-xl text-lg font-bold
                                                @if($booking->status === 'pending') bg-yellow-100 text-yellow-800 border border-yellow-200
                                                @elseif($booking->status === 'confirmed') bg-green-100 text-green-800 border border-green-200
                                                @elseif($booking->status === 'completed') bg-blue-100 text-blue-800 border border-blue-200
                                                @elseif($booking->status === 'cancelled') bg-red-100 text-red-800 border border-red-200
                                                @else bg-gray-100 text-gray-800 border border-gray-200 @endif">
                                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    @if($booking->status === 'pending')
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                    @elseif($booking->status === 'confirmed')
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                    @elseif($booking->status === 'completed')
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                    @elseif($booking->status === 'cancelled')
                                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                    @endif
                                                </svg>
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                            
                                            <!-- Action Buttons -->
                                            <div class="flex flex-col space-y-3">
                                                @if($booking->status === 'pending')
                                                    <a href="{{ route('booking.payment', $booking->booking_code) }}" 
                                                       class="bg-gradient-to-r from-blue-600 to-cyan-600 text-white px-6 py-3 rounded-xl hover:from-blue-700 hover:to-cyan-700 transition-all duration-300 font-bold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-center">
                                                        Complete Payment
                                                    </a>
                                                @elseif($booking->status === 'confirmed')
                                                    @if($booking->canBeCancelled())
                                                        <button wire:click="cancelBooking({{ $booking->id }})" 
                                                                class="bg-gradient-to-r from-red-600 to-pink-600 text-white px-6 py-3 rounded-xl hover:from-red-700 hover:to-pink-700 transition-all duration-300 font-bold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                                            Cancel Booking
                                                        </button>
                                                    @endif
                                                @endif
                                                
                                                <a href="{{ route('bookings.show', $booking->booking_code) }}" 
                                                   class="bg-gray-100 text-gray-700 px-6 py-3 rounded-xl hover:bg-gray-200 transition-all duration-300 font-semibold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-center">
                                                    View
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-12 flex justify-center">
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-4">
                        {{ $bookings->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-16">
                    <div class="w-32 h-32 bg-gradient-to-br from-blue-100 to-cyan-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-16 h-16 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">No bookings found</h3>
                    <p class="text-lg text-gray-600 mb-8 max-w-md mx-auto">
                        @if($statusFilter)
                            No {{ $statusFilter }} bookings found.
                        @else
                            You haven't made any bookings yet.
                        @endif
                    </p>
                    <a href="{{ route('vehicles.index') }}" 
                       class="bg-gradient-to-r from-blue-600 to-cyan-600 text-white px-8 py-4 rounded-xl hover:from-blue-700 hover:to-cyan-700 transition-all duration-300 font-bold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        Browse Vehicles
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
