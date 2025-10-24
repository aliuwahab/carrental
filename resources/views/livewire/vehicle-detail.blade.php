<div>
    <!-- Breadcrumb -->
    <div class="bg-gray-50 py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-4">
                    <li>
                        <a href="{{ route('home') }}" class="text-gray-400 hover:text-gray-500">Home</a>
                    </li>
                    <li>
                        <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </li>
                    <li>
                        <a href="{{ route('vehicles.index') }}" class="text-gray-400 hover:text-gray-500">Vehicles</a>
                    </li>
                    <li>
                        <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </li>
                    <li>
                        <span class="text-gray-500">{{ $vehicle->name }}</span>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Vehicle Images -->
            <div>
                <div class="aspect-w-16 aspect-h-9 mb-4">
                    <img src="{{ $vehicle->main_image ?: 'https://via.placeholder.com/800x450?text=Vehicle+Image' }}" 
                         alt="{{ $vehicle->name }}" 
                         class="w-full h-96 object-cover rounded-lg">
                </div>
                
                <!-- Features Grid -->
                @if($vehicle->features)
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Features</h3>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach($vehicle->features as $feature)
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-sm text-gray-700">{{ $feature }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Vehicle Details & Booking -->
            <div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $vehicle->name }}</h1>
                    <p class="text-lg text-gray-600 mb-4">{{ ucfirst($vehicle->type) }} • {{ $vehicle->seats }} seats</p>
                    
                    @if($vehicle->description)
                        <p class="text-gray-700 mb-6">{{ $vehicle->description }}</p>
                    @endif

                    <!-- Vehicle Specifications -->
                    <div class="grid grid-cols-2 gap-4 mb-6 text-sm">
                        <div>
                            <span class="font-medium text-gray-700">Transmission:</span>
                            <span class="text-gray-900">{{ ucfirst($vehicle->transmission) }}</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700">Fuel Type:</span>
                            <span class="text-gray-900">{{ $vehicle->fuel_type }}</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700">Seats:</span>
                            <span class="text-gray-900">{{ $vehicle->seats }}</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700">Daily Rate:</span>
                            <span class="text-gray-900 font-semibold">${{ number_format($vehicle->currentRate?->daily_rate ?? 0, 2) }}</span>
                        </div>
                    </div>

                    <!-- Date Selection -->
                    <div class="border-t pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Select Rental Dates</h3>
                        
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pickup Date</label>
                                <input type="date" wire:model="startDate" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Return Date</label>
                                <input type="date" wire:model="endDate" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>

                        <!-- Availability Status -->
                        @if($startDate && $endDate)
                            <div class="mb-4">
                                @if($isAvailable)
                                    <div class="flex items-center space-x-2 text-green-600">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="font-medium">Available for selected dates</span>
                                    </div>
                                @else
                                    <div class="flex items-center space-x-2 text-red-600">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="font-medium">Not available for selected dates</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Price Calculation -->
                            @if($isAvailable && $totalPrice > 0)
                                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-gray-700">Daily Rate:</span>
                                        <span class="font-medium">${{ number_format($vehicle->currentRate?->daily_rate ?? 0, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-gray-700">Rental Days:</span>
                                        <span class="font-medium">{{ $rentalDays }} days</span>
                                    </div>
                                    <div class="border-t pt-2">
                                        <div class="flex justify-between items-center">
                                            <span class="text-lg font-semibold text-gray-900">Total Amount:</span>
                                            <span class="text-2xl font-bold text-blue-600">${{ number_format($totalPrice, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif

                        <!-- Book Now Button -->
                        <button wire:click="bookNow" 
                                class="w-full bg-blue-600 text-white py-3 px-6 rounded-md hover:bg-blue-700 transition-colors font-semibold text-lg {{ !$isAvailable ? 'opacity-50 cursor-not-allowed' : '' }}"
                                {{ !$isAvailable ? 'disabled' : '' }}>
                            @if($isAvailable)
                                Book Now
                            @else
                                Not Available
                            @endif
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
