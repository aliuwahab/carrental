<div>
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold mb-6">
                    Find Your Perfect Ride
                </h1>
                <p class="text-xl md:text-2xl mb-8 text-blue-100">
                    Premium car rental service with unbeatable prices and exceptional service
                </p>
                
                <!-- Search Form -->
                <div class="bg-white rounded-lg p-6 shadow-xl max-w-4xl mx-auto">
                    <form wire:submit="searchVehicles" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pickup Date</label>
                            <input type="date" wire:model="startDate" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('startDate') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Return Date</label>
                            <input type="date" wire:model="endDate" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('endDate') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Vehicle Type</label>
                            <select wire:model="vehicleType" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">All Types</option>
                                <option value="economy">Economy</option>
                                <option value="sedan">Sedan</option>
                                <option value="suv">SUV</option>
                                <option value="van">Van</option>
                                <option value="luxury">Luxury</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Price Range</label>
                            <div class="flex space-x-2">
                                <input type="number" wire:model="minPrice" placeholder="Min" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <input type="number" wire:model="maxPrice" placeholder="Max" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                        
                        <div class="flex items-end">
                            <button type="submit" 
                                    class="w-full bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition-colors">
                                Search Vehicles
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Search Results -->
    @if(count($availableVehicles) > 0)
        <section class="py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-8">Available Vehicles</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($availableVehicles as $vehicle)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow">
                            <div class="aspect-w-16 aspect-h-9">
                                <img src="{{ $vehicle->main_image ?: 'https://via.placeholder.com/400x225?text=Vehicle+Image' }}" 
                                     alt="{{ $vehicle->name }}" 
                                     class="w-full h-48 object-cover">
                            </div>
                            <div class="p-6">
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $vehicle->name }}</h3>
                                <p class="text-gray-600 mb-4">{{ ucfirst($vehicle->type) }} • {{ $vehicle->seats }} seats</p>
                                <div class="flex items-center justify-between">
                                    <span class="text-2xl font-bold text-blue-600">
                                        ${{ number_format($vehicle->currentRate?->daily_rate ?? 0, 2) }}/day
                                    </span>
                                    <a href="{{ route('vehicles.show', $vehicle->slug) }}" 
                                       class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Featured Vehicles -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Featured Vehicles</h2>
                <p class="text-gray-600">Discover our most popular rental options</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($featuredVehicles as $vehicle)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow">
                        <div class="aspect-w-16 aspect-h-9">
                            <img src="{{ $vehicle->main_image ?: 'https://via.placeholder.com/400x225?text=Vehicle+Image' }}" 
                                 alt="{{ $vehicle->name }}" 
                                 class="w-full h-48 object-cover">
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $vehicle->name }}</h3>
                            <p class="text-gray-600 mb-4">{{ ucfirst($vehicle->type) }} • {{ $vehicle->seats }} seats</p>
                            <div class="flex items-center justify-between">
                                <span class="text-2xl font-bold text-blue-600">
                                    ${{ number_format($vehicle->currentRate?->daily_rate ?? 0, 2) }}/day
                                </span>
                                <a href="{{ route('vehicles.show', $vehicle->slug) }}" 
                                   class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Why Choose QuickRental?</h2>
                <p class="text-gray-600">We make car rental simple, affordable, and reliable</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Best Prices</h3>
                    <p class="text-gray-600">Competitive rates with no hidden fees</p>
                </div>
                
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Quality Vehicles</h3>
                    <p class="text-gray-600">Well-maintained cars for your safety and comfort</p>
                </div>
                
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M12 2.25a9.75 9.75 0 100 19.5 9.75 9.75 0 000-19.5z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">24/7 Support</h3>
                    <p class="text-gray-600">Round-the-clock customer service</p>
                </div>
            </div>
        </div>
    </section>
</div>
