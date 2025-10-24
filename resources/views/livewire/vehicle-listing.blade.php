<div>
    <!-- Header -->
    <div class="bg-white py-8 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Available Vehicles</h1>
            <p class="text-gray-600">{{ $totalVehicles }} vehicles available for your selected dates</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-gray-50 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form wire:submit="searchVehicles" class="grid grid-cols-1 md:grid-cols-6 gap-4">
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
                    <label class="block text-sm font-medium text-gray-700 mb-2">Min Price</label>
                    <input type="number" wire:model="minPrice" placeholder="$0" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Max Price</label>
                    <input type="number" wire:model="maxPrice" placeholder="$1000" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="flex items-end">
                    <button type="submit" 
                            class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
                        Search
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Sort Options -->
    <div class="bg-white py-4 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600">Sort by:</span>
                    <button wire:click="sortBy('name')" 
                            class="text-sm {{ $sortBy === 'name' ? 'text-blue-600 font-semibold' : 'text-gray-600 hover:text-blue-600' }}">
                        Name
                        @if($sortBy === 'name')
                            <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </button>
                    <button wire:click="sortBy('price')" 
                            class="text-sm {{ $sortBy === 'price' ? 'text-blue-600 font-semibold' : 'text-gray-600 hover:text-blue-600' }}">
                        Price
                        @if($sortBy === 'price')
                            <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </button>
                    <button wire:click="sortBy('type')" 
                            class="text-sm {{ $sortBy === 'type' ? 'text-blue-600 font-semibold' : 'text-gray-600 hover:text-blue-600' }}">
                        Type
                        @if($sortBy === 'type')
                            <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </button>
                    <button wire:click="sortBy('seats')" 
                            class="text-sm {{ $sortBy === 'seats' ? 'text-blue-600 font-semibold' : 'text-gray-600 hover:text-blue-600' }}">
                        Seats
                        @if($sortBy === 'seats')
                            <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </button>
                </div>
                
                <div class="text-sm text-gray-600">
                    Showing {{ $vehicles->count() }} of {{ $totalVehicles }} vehicles
                </div>
            </div>
        </div>
    </div>

    <!-- Vehicle Grid -->
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($vehicles->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($vehicles as $vehicle)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow">
                            <div class="aspect-w-16 aspect-h-9">
                                <img src="{{ $vehicle->main_image ?: 'https://via.placeholder.com/400x225?text=Vehicle+Image' }}" 
                                     alt="{{ $vehicle->name }}" 
                                     class="w-full h-48 object-cover">
                            </div>
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $vehicle->name }}</h3>
                                <p class="text-gray-600 mb-2">{{ ucfirst($vehicle->type) }} • {{ $vehicle->seats }} seats</p>
                                <p class="text-sm text-gray-500 mb-4">{{ $vehicle->transmission }} • {{ $vehicle->fuel_type }}</p>
                                
                                @if($vehicle->features)
                                    <div class="mb-4">
                                        <div class="flex flex-wrap gap-1">
                                            @foreach(array_slice($vehicle->features, 0, 3) as $feature)
                                                <span class="inline-block bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded">
                                                    {{ $feature }}
                                                </span>
                                            @endforeach
                                            @if(count($vehicle->features) > 3)
                                                <span class="inline-block bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded">
                                                    +{{ count($vehicle->features) - 3 }} more
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                                
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="text-2xl font-bold text-blue-600">
                                            ${{ number_format($vehicle->currentRate?->daily_rate ?? 0, 2) }}
                                        </span>
                                        <span class="text-sm text-gray-500">/day</span>
                                    </div>
                                    <a href="{{ route('vehicles.show', $vehicle->slug) }}" 
                                       class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors text-sm">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $vehicles->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.29-1.009-5.824-2.709M15 6.291A7.962 7.962 0 0012 5c-2.34 0-4.29 1.009-5.824 2.709"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No vehicles found</h3>
                    <p class="text-gray-600 mb-4">Try adjusting your search criteria or dates</p>
                    <button wire:click="$set('vehicleType', '')" 
                            wire:click="$set('minPrice', '')" 
                            wire:click="$set('maxPrice', '')"
                            class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
                        Clear Filters
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>
