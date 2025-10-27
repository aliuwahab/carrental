<div>
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900 text-white overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.05"%3E%3Ccircle cx="30" cy="30" r="2"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-20"></div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-32">
            <div class="text-center lg:text-left lg:flex lg:items-center lg:justify-between">
                <div class="lg:w-1/2">
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold mb-6 leading-tight">
                        Find Your Perfect
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-cyan-400">
                            Ride
                        </span>
                    </h1>
                    <p class="text-xl lg:text-2xl mb-8 text-blue-100 max-w-2xl">
                        Premium car rental service with unbeatable prices and exceptional service. 
                        Book your next adventure today.
                    </p>
                    
                    <!-- Quick Stats -->
                    <div class="flex flex-wrap justify-center lg:justify-start gap-8 mb-8">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-400">500+</div>
                            <div class="text-sm text-blue-200">Happy Customers</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-400">50+</div>
                            <div class="text-sm text-blue-200">Vehicles</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-400">24/7</div>
                            <div class="text-sm text-blue-200">Support</div>
                        </div>
                    </div>
                </div>
                
                <!-- Search Form -->
                <div class="lg:w-1/2 lg:pl-12">
                    <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 lg:p-8 border border-white/20 shadow-2xl">
                        <h3 class="text-xl font-semibold mb-6 text-center">Search Available Cars</h3>
                        <form wire:submit="searchVehicles" class="space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-blue-100 mb-2">Pickup Date</label>
                                    <input type="date" wire:model.live="startDate" 
                                           min="{{ now()->format('Y-m-d') }}"
                                           wire:change="adjustEndDate"
                                           class="w-full px-4 py-3 bg-white/90 border border-white/30 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent text-gray-900">
                                    @error('startDate') <span class="text-red-300 text-sm">{{ $message }}</span> @enderror
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-blue-100 mb-2">Return Date</label>
                                    <input type="date" wire:model.live="endDate" 
                                           min="{{ $startDate ? \Carbon\Carbon::parse($startDate)->addDay()->format('Y-m-d') : now()->addDay()->format('Y-m-d') }}"
                                           class="w-full px-4 py-3 bg-white/90 border border-white/30 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent text-gray-900">
                                    @error('endDate') <span class="text-red-300 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-blue-100 mb-2">Vehicle Type</label>
                                    <select wire:model="vehicleType" 
                                            class="w-full px-4 py-3 bg-white/90 border border-white/30 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent text-gray-900">
                                        <option value="">All Types</option>
                                        <option value="economy">Economy</option>
                                        <option value="sedan">Sedan</option>
                                        <option value="suv">SUV</option>
                                        <option value="van">Van</option>
                                        <option value="luxury">Luxury</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-blue-100 mb-2">Price Range</label>
                                    <div class="flex space-x-2">
                                        <input type="number" wire:model="minPrice" placeholder="Min" 
                                               class="w-full px-4 py-3 bg-white/90 border border-white/30 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent text-gray-900">
                                        <input type="number" wire:model="maxPrice" placeholder="Max" 
                                               class="w-full px-4 py-3 bg-white/90 border border-white/30 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent text-gray-900">
                                    </div>
                                </div>
                            </div>
                            
                            <button type="submit" 
                                    class="w-full bg-gradient-to-r from-blue-600 to-cyan-600 text-white px-6 py-4 rounded-lg hover:from-blue-700 hover:to-cyan-700 transition-all duration-300 font-semibold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                <span class="flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                    Search Vehicles
                                </span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Search Results -->
    @if(count($availableVehicles) > 0)
        <section class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">Available Vehicles</h2>
                    <p class="text-lg text-gray-600">Found {{ count($availableVehicles) }} vehicles matching your criteria</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($availableVehicles as $vehicle)
                        <div class="group bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                            <div class="relative overflow-hidden">
                                @if($vehicle->getMainImageUrl())
                                    <img src="{{ $vehicle->getMainImageUrl() }}" 
                                         alt="{{ $vehicle->name }}" 
                                         class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <img src="https://via.placeholder.com/400x225?text=Vehicle+Image" 
                                         alt="{{ $vehicle->name }}" 
                                         class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                                @endif
                                <div class="absolute top-4 right-4">
                                    <span class="bg-white/90 backdrop-blur-sm text-gray-800 px-3 py-1 rounded-full text-sm font-medium">
                                        {{ ucfirst($vehicle->type) }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="p-6">
                                <div class="flex items-start justify-between mb-4">
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $vehicle->name }}</h3>
                                        <p class="text-gray-600 text-sm">{{ $vehicle->seats }} seats • {{ $vehicle->transmission }} • {{ $vehicle->fuel_type }}</p>
                                    </div>
                                </div>
                                
                                @if($vehicle->features)
                                    <div class="mb-4">
                                        <div class="flex flex-wrap gap-2">
                                            @foreach(array_slice($vehicle->features, 0, 3) as $feature)
                                                <span class="inline-block bg-blue-50 text-blue-700 text-xs px-2 py-1 rounded-full">
                                                    {{ $feature }}
                                                </span>
                                            @endforeach
                                            @if(count($vehicle->features) > 3)
                                                <span class="inline-block bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded-full">
                                                    +{{ count($vehicle->features) - 3 }} more
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                                
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="text-3xl font-bold text-blue-600">
                                            ${{ number_format($vehicle->currentRate?->daily_rate ?? 0, 2) }}
                                        </span>
                                        <span class="text-gray-500 text-sm">/day</span>
                                    </div>
                                    <a href="{{ route('vehicles.show', $vehicle->slug) }}" 
                                       class="bg-gradient-to-r from-blue-600 to-cyan-600 text-white px-6 py-3 rounded-lg hover:from-blue-700 hover:to-cyan-700 transition-all duration-300 font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                        View
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
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">Featured Vehicles</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">Discover our most popular rental options, carefully selected for comfort, reliability, and value</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($featuredVehicles as $vehicle)
                    <div class="group bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                        <div class="relative overflow-hidden">
                            @if($vehicle->getMainImageUrl())
                                <img src="{{ $vehicle->getMainImageUrl() }}" 
                                     alt="{{ $vehicle->name }}" 
                                     class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <img src="https://via.placeholder.com/400x225?text=Vehicle+Image" 
                                     alt="{{ $vehicle->name }}" 
                                     class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                            <div class="absolute top-4 right-4">
                                <span class="bg-white/90 backdrop-blur-sm text-gray-800 px-3 py-1 rounded-full text-sm font-medium">
                                    {{ ucfirst($vehicle->type) }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $vehicle->name }}</h3>
                                    <p class="text-gray-600 text-sm">{{ $vehicle->seats }} seats • {{ $vehicle->transmission }} • {{ $vehicle->fuel_type }}</p>
                                </div>
                            </div>
                            
                            @if($vehicle->features)
                                <div class="mb-4">
                                    <div class="flex flex-wrap gap-2">
                                        @foreach(array_slice($vehicle->features, 0, 3) as $feature)
                                            <span class="inline-block bg-blue-50 text-blue-700 text-xs px-2 py-1 rounded-full">
                                                {{ $feature }}
                                            </span>
                                        @endforeach
                                        @if(count($vehicle->features) > 3)
                                            <span class="inline-block bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded-full">
                                                +{{ count($vehicle->features) - 3 }} more
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="text-3xl font-bold text-blue-600">
                                        ${{ number_format($vehicle->currentRate?->daily_rate ?? 0, 2) }}
                                    </span>
                                    <span class="text-gray-500 text-sm">/day</span>
                                </div>
                                <a href="{{ route('vehicles.show', $vehicle->slug) }}" 
                                   class="bg-gradient-to-r from-blue-600 to-cyan-600 text-white px-6 py-3 rounded-lg hover:from-blue-700 hover:to-cyan-700 transition-all duration-300 font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                    View
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="text-center mt-12">
                <a href="{{ route('vehicles.index') }}" 
                   class="inline-flex items-center bg-gray-900 text-white px-8 py-4 rounded-lg hover:bg-gray-800 transition-all duration-300 font-semibold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <span>View All Vehicles</span>
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 bg-gradient-to-br from-gray-50 to-blue-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">Why Choose Rental Ghana?</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">We make car rental simple, affordable, and reliable with our comprehensive service offerings</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="group bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4 text-center">Best Prices</h3>
                    <p class="text-gray-600 text-center leading-relaxed">Competitive rates with no hidden fees. We guarantee the best value for your money with transparent pricing.</p>
                </div>
                
                <div class="group bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-500 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4 text-center">Quality Vehicles</h3>
                    <p class="text-gray-600 text-center leading-relaxed">Well-maintained cars for your safety and comfort. All vehicles undergo regular inspections and maintenance.</p>
                </div>
                
                <div class="group bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M12 2.25a9.75 9.75 0 100 19.5 9.75 9.75 0 000-19.5z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4 text-center">24/7 Support</h3>
                    <p class="text-gray-600 text-center leading-relaxed">Round-the-clock customer service. Our team is always ready to help you with any questions or concerns.</p>
                </div>
            </div>
            
            <!-- Additional Features -->
            <div class="mt-16 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h4 class="font-semibold text-gray-900 mb-1">Quick Booking</h4>
                    <p class="text-sm text-gray-600">Book in minutes</p>
                </div>
                
                <div class="text-center">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <h4 class="font-semibold text-gray-900 mb-1">Secure Payment</h4>
                    <p class="text-sm text-gray-600">Safe transactions</p>
                </div>
                
                <div class="text-center">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h4 class="font-semibold text-gray-900 mb-1">Flexible Dates</h4>
                    <p class="text-sm text-gray-600">Choose your dates</p>
                </div>
                
                <div class="text-center">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                    </div>
                    <h4 class="font-semibold text-gray-900 mb-1">Easy Contact</h4>
                    <p class="text-sm text-gray-600">Always available</p>
                </div>
            </div>
        </div>
    </section>
</div>
