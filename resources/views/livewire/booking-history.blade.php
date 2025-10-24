<div>
    <!-- Header -->
    <div class="bg-white py-8 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">My Bookings</h1>
            <p class="text-gray-600">Track and manage your vehicle rentals</p>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="bg-gray-50 py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex space-x-1">
                <button wire:click="filterByStatus('')" 
                        class="px-4 py-2 text-sm font-medium rounded-md {{ $statusFilter === '' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }}">
                    All Bookings
                </button>
                <button wire:click="filterByStatus('pending')" 
                        class="px-4 py-2 text-sm font-medium rounded-md {{ $statusFilter === 'pending' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }}">
                    Pending
                </button>
                <button wire:click="filterByStatus('confirmed')" 
                        class="px-4 py-2 text-sm font-medium rounded-md {{ $statusFilter === 'confirmed' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }}">
                    Confirmed
                </button>
                <button wire:click="filterByStatus('completed')" 
                        class="px-4 py-2 text-sm font-medium rounded-md {{ $statusFilter === 'completed' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }}">
                    Completed
                </button>
                <button wire:click="filterByStatus('cancelled')" 
                        class="px-4 py-2 text-sm font-medium rounded-md {{ $statusFilter === 'cancelled' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }}">
                    Cancelled
                </button>
            </div>
        </div>
    </div>

    <!-- Bookings List -->
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($bookings->count() > 0)
                <div class="space-y-6">
                    @foreach($bookings as $booking)
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-4 mb-4">
                                        <img src="{{ $booking->vehicle->main_image ?: 'https://via.placeholder.com/100x60?text=Vehicle' }}" 
                                             alt="{{ $booking->vehicle->name }}" 
                                             class="w-20 h-12 object-cover rounded">
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $booking->vehicle->name }}</h3>
                                            <p class="text-sm text-gray-600">{{ ucfirst($booking->vehicle->type) }} • {{ $booking->vehicle->seats }} seats</p>
                                        </div>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                        <div>
                                            <span class="font-medium text-gray-700">Booking ID:</span>
                                            <span class="text-gray-900 font-mono">{{ $booking->booking_code }}</span>
                                        </div>
                                        <div>
                                            <span class="font-medium text-gray-700">Rental Period:</span>
                                            <span class="text-gray-900">{{ $booking->start_date->format('M d, Y') }} - {{ $booking->end_date->format('M d, Y') }}</span>
                                        </div>
                                        <div>
                                            <span class="font-medium text-gray-700">Total Amount:</span>
                                            <span class="text-gray-900 font-semibold">${{ number_format($booking->total_amount, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-4 md:mt-0 md:ml-6">
                                    <div class="flex items-center space-x-3">
                                        <!-- Status Badge -->
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                            @if($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($booking->status === 'confirmed') bg-green-100 text-green-800
                                            @elseif($booking->status === 'completed') bg-blue-100 text-blue-800
                                            @elseif($booking->status === 'cancelled') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                        
                                        <!-- Action Buttons -->
                                        @if($booking->status === 'pending')
                                            <a href="{{ route('booking.payment', $booking->booking_code) }}" 
                                               class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors text-sm">
                                                Complete Payment
                                            </a>
                                        @elseif($booking->status === 'confirmed')
                                            @if($booking->canBeCancelled())
                                                <button wire:click="cancelBooking({{ $booking->id }})" 
                                                        class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition-colors text-sm">
                                                    Cancel Booking
                                                </button>
                                            @endif
                                        @endif
                                        
                                        <a href="{{ route('bookings.show', $booking->booking_code) }}" 
                                           class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $bookings->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No bookings found</h3>
                    <p class="text-gray-600 mb-4">
                        @if($statusFilter)
                            No {{ $statusFilter }} bookings found.
                        @else
                            You haven't made any bookings yet.
                        @endif
                    </p>
                    <a href="{{ route('vehicles.index') }}" 
                       class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
                        Browse Vehicles
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
