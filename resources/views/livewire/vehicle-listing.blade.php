<div>
    <!-- Header -->
    <div class="bg-gradient-to-r from-slate-900 via-blue-900 to-slate-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl lg:text-5xl font-bold mb-4">Available Vehicles</h1>
                <p class="text-xl text-blue-100 mb-8">{{ $totalVehicles }} vehicles available for your selected dates</p>
                
                <!-- Quick Stats -->
                <div class="flex flex-wrap justify-center gap-8 mb-8">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-400">{{ $totalVehicles }}</div>
                        <div class="text-sm text-blue-200">Available Cars</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-400">5</div>
                        <div class="text-sm text-blue-200">Vehicle Types</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-400">24/7</div>
                        <div class="text-sm text-blue-200">Support</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow-lg -mt-8 relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Filter & Search</h3>
                <form wire:submit="searchVehicles" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pickup Date</label>
                        <input type="date" wire:model.live="startDate" 
                               min="{{ now()->format('Y-m-d') }}"
                               wire:change="adjustEndDate"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('startDate') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Return Date</label>
                        <input type="date" wire:model.live="endDate" 
                               min="{{ $startDate ? \Carbon\Carbon::parse($startDate)->addDay()->format('Y-m-d') : now()->addDay()->format('Y-m-d') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('endDate') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Vehicle Type</label>
                        <select wire:model="vehicleType" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">All Types</option>
                            <option value="economy">Economy</option>
                            <option value="sedan">Sedan</option>
                            <option value="suv">SUV</option>
                            <option value="van">Van</option>
                            <option value="luxury">Luxury</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Min Price</label>
                        <input type="number" wire:model="minPrice" placeholder="$0" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Max Price</label>
                        <input type="number" wire:model="maxPrice" placeholder="$1000" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    
                    <div class="flex items-end">
                        <button type="submit" 
                                class="w-full bg-gradient-to-r from-blue-600 to-cyan-600 text-white px-6 py-3 rounded-lg hover:from-blue-700 hover:to-cyan-700 transition-all duration-300 font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <span class="flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Search
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Sort Options -->
    <div class="bg-gray-50 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-sm font-medium text-gray-700">Sort by:</span>
                    <div class="flex flex-wrap gap-2">
                        <button wire:click="sortBy('name')" 
                                class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ $sortBy === 'name' ? 'bg-blue-600 text-white shadow-lg' : 'bg-white text-gray-700 hover:bg-blue-50 hover:text-blue-600 border border-gray-200' }}">
                            Name
                            @if($sortBy === 'name')
                                <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </button>
                        <button wire:click="sortBy('price')" 
                                class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ $sortBy === 'price' ? 'bg-blue-600 text-white shadow-lg' : 'bg-white text-gray-700 hover:bg-blue-50 hover:text-blue-600 border border-gray-200' }}">
                            Price
                            @if($sortBy === 'price')
                                <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </button>
                        <button wire:click="sortBy('type')" 
                                class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ $sortBy === 'type' ? 'bg-blue-600 text-white shadow-lg' : 'bg-white text-gray-700 hover:bg-blue-50 hover:text-blue-600 border border-gray-200' }}">
                            Type
                            @if($sortBy === 'type')
                                <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </button>
                        <button wire:click="sortBy('seats')" 
                                class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ $sortBy === 'seats' ? 'bg-blue-600 text-white shadow-lg' : 'bg-white text-gray-700 hover:bg-blue-50 hover:text-blue-600 border border-gray-200' }}">
                            Seats
                            @if($sortBy === 'seats')
                                <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </button>
                    </div>
                </div>
                
                <div class="flex items-center gap-4">
                    <div class="text-sm text-gray-600">
                        Showing {{ $vehicles->count() }} of {{ $totalVehicles }} vehicles
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-600">View:</span>
                        <button class="p-2 rounded-lg bg-white border border-gray-200 hover:bg-blue-50 hover:border-blue-300 transition-colors">
                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                            </svg>
                        </button>
                        <button class="p-2 rounded-lg bg-blue-600 text-white border border-blue-600 hover:bg-blue-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Vehicle Grid -->
    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($vehicles->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                    @foreach($vehicles as $vehicle)
                        <div class="group bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100">
                            <div class="relative overflow-hidden">
                                @if($vehicle->getFirstMedia('main_image'))
                                    <img src="{{ $vehicle->getFirstMedia('main_image')->getUrl() }}" 
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
                                <div class="absolute bottom-4 left-4">
                                    <div class="flex items-center space-x-2 text-white">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="text-sm font-medium">Available</span>
                                    </div>
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

                <!-- Pagination -->
                <div class="mt-12 flex justify-center">
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-4">
                        {{ $vehicles->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-16">
                    <div class="w-32 h-32 bg-gradient-to-br from-blue-100 to-cyan-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-16 h-16 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.29-1.009-5.824-2.709M15 6.291A7.962 7.962 0 0012 5c-2.34 0-4.29 1.009-5.824 2.709"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">No vehicles found</h3>
                    <p class="text-lg text-gray-600 mb-8 max-w-md mx-auto">We couldn't find any vehicles matching your search criteria. Try adjusting your filters or dates.</p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <button wire:click="$set('vehicleType', '')" 
                                wire:click="$set('minPrice', '')" 
                                wire:click="$set('maxPrice', '')"
                                class="bg-gradient-to-r from-blue-600 to-cyan-600 text-white px-8 py-3 rounded-lg hover:from-blue-700 hover:to-cyan-700 transition-all duration-300 font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            Clear Filters
                        </button>
                        <a href="{{ route('home') }}" 
                           class="bg-gray-100 text-gray-700 px-8 py-3 rounded-lg hover:bg-gray-200 transition-all duration-300 font-semibold">
                            Back to Home
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
