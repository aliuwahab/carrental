<div>
    <!-- Breadcrumb -->
    <div class="bg-gradient-to-r from-gray-50 to-blue-50 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="flex items-center space-x-2 text-sm" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-800 font-medium">Home</a>
                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
                <a href="{{ route('vehicles.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">Vehicles</a>
                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
                <span class="text-gray-600 font-medium">{{ $vehicle->name }}</span>
            </nav>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-12">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-12">
            <!-- Vehicle Images -->
            <div class="space-y-6">
                @php
                    $mainImageUrl = $this->getMainImageUrl();
                    $galleryUrls = $this->getGalleryUrls();
                    $allImageUrls = collect();
                    
                    if ($mainImageUrl) {
                        $allImageUrls->push($mainImageUrl);
                    }
                    
                    $allImageUrls = $allImageUrls->merge($galleryUrls);
                @endphp
                
                <!-- Main Image -->
                <div class="relative group">
                    <div class="aspect-w-16 aspect-h-9 overflow-hidden rounded-xl lg:rounded-2xl shadow-xl lg:shadow-2xl">
                        @if($allImageUrls->count() > 0)
                            <img src="{{ $allImageUrls[$selectedImageIndex] }}" 
                                 alt="{{ $vehicle->name }}" 
                                 class="w-full h-64 sm:h-80 lg:h-96 object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <img src="https://via.placeholder.com/800x450?text=Vehicle+Image" 
                                 alt="{{ $vehicle->name }}" 
                                 class="w-full h-64 sm:h-80 lg:h-96 object-cover group-hover:scale-105 transition-transform duration-500">
                        @endif
                    </div>
                    <div class="absolute top-4 right-4">
                        <span class="bg-white/90 backdrop-blur-sm text-gray-800 px-4 py-2 rounded-full text-sm font-semibold shadow-lg">
                            {{ ucfirst($vehicle->type) }}
                        </span>
                    </div>
                    <div class="absolute bottom-4 left-4">
                        <div class="flex items-center space-x-2 text-white bg-black/50 backdrop-blur-sm px-3 py-2 rounded-lg">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-sm font-medium">Available Now</span>
                        </div>
                    </div>
                    
                    @if($allImageUrls->count() > 1)
                        <!-- Navigation arrows -->
                        <button wire:click="selectImage({{ $selectedImageIndex > 0 ? $selectedImageIndex - 1 : $allImageUrls->count() - 1 }})" 
                                class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white p-2 rounded-full transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </button>
                        <button wire:click="selectImage({{ $selectedImageIndex < $allImageUrls->count() - 1 ? $selectedImageIndex + 1 : 0 }})" 
                                class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white p-2 rounded-full transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                    @endif
                </div>
                
                <!-- Image Gallery Thumbnails -->
                @if($allImageUrls->count() > 1)
                    <div class="grid grid-cols-3 sm:grid-cols-4 gap-2 sm:gap-3">
                        @foreach($allImageUrls as $index => $imageUrl)
                            <button wire:click="selectImage({{ $index }})" 
                                    class="aspect-w-16 aspect-h-9 overflow-hidden rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 {{ $selectedImageIndex === $index ? 'ring-2 ring-blue-500 shadow-xl' : '' }}">
                                <img src="{{ $imageUrl }}" 
                                     alt="{{ $vehicle->name }}" 
                                     class="w-full h-16 sm:h-20 object-cover hover:scale-105 transition-transform duration-300">
                            </button>
                        @endforeach
                    </div>
                @endif
                
                <!-- Features Grid -->
                @if($vehicle->features)
                    <div class="bg-white rounded-xl lg:rounded-2xl shadow-lg border border-gray-100 p-4 lg:p-6">
                        <h3 class="text-lg lg:text-xl font-bold text-gray-900 mb-4 lg:mb-6 flex items-center">
                            <svg class="w-5 h-5 lg:w-6 lg:h-6 text-blue-600 mr-2 lg:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Vehicle Features
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 lg:gap-4">
                            @foreach($vehicle->features as $feature)
                                <div class="flex items-center space-x-2 lg:space-x-3 p-2 lg:p-3 bg-blue-50 rounded-lg">
                                    <svg class="w-4 h-4 lg:w-5 lg:h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-xs lg:text-sm font-medium text-gray-700">{{ $feature }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Vehicle Details & Booking -->
            <div class="space-y-4 lg:space-y-6">
                <!-- Vehicle Info -->
                <div class="bg-white rounded-xl lg:rounded-2xl shadow-lg border border-gray-100 p-4 lg:p-8">
                    <div class="mb-4 lg:mb-6">
                        <h1 class="text-2xl lg:text-4xl font-bold text-gray-900 mb-3">{{ $vehicle->name }}</h1>
                        <div class="flex flex-col sm:flex-row sm:items-center space-y-2 sm:space-y-0 sm:space-x-4 text-base lg:text-lg text-gray-600 mb-4">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 lg:w-5 lg:h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                {{ ucfirst($vehicle->type) }}
                            </span>
                            <span class="flex items-center">
                                <svg class="w-4 h-4 lg:w-5 lg:h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                {{ $vehicle->seats }} seats
                            </span>
                        </div>
                        
                        @if($vehicle->description)
                            <p class="text-gray-700 text-base lg:text-lg leading-relaxed">{{ $vehicle->description }}</p>
                        @endif
                    </div>

                    <!-- Vehicle Specifications -->
                    <div class="bg-gray-50 rounded-lg lg:rounded-xl p-4 lg:p-6 mb-4 lg:mb-6">
                        <h3 class="text-base lg:text-lg font-semibold text-gray-900 mb-3 lg:mb-4">Specifications</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 lg:gap-4">
                            <div class="flex items-center justify-between py-2 lg:py-3 border-b border-gray-200">
                                <span class="text-sm lg:text-base font-medium text-gray-700">Transmission:</span>
                                <span class="text-sm lg:text-base text-gray-900 font-semibold">{{ ucfirst($vehicle->transmission) }}</span>
                            </div>
                            <div class="flex items-center justify-between py-2 lg:py-3 border-b border-gray-200">
                                <span class="text-sm lg:text-base font-medium text-gray-700">Fuel Type:</span>
                                <span class="text-sm lg:text-base text-gray-900 font-semibold">{{ $vehicle->fuel_type }}</span>
                            </div>
                            <div class="flex items-center justify-between py-2 lg:py-3 border-b border-gray-200">
                                <span class="text-sm lg:text-base font-medium text-gray-700">Seats:</span>
                                <span class="text-sm lg:text-base text-gray-900 font-semibold">{{ $vehicle->seats }}</span>
                            </div>
                            <div class="flex items-center justify-between py-2 lg:py-3 border-b border-gray-200">
                                <span class="text-sm lg:text-base font-medium text-gray-700">Daily Rate:</span>
                                <span class="text-blue-600 font-bold text-base lg:text-lg">${{ number_format($vehicle->currentRate?->daily_rate ?? 0, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Booking Section -->
                <div class="bg-white rounded-xl lg:rounded-2xl shadow-lg border border-gray-100 p-4 lg:p-8">
                    <h3 class="text-lg lg:text-2xl font-bold text-gray-900 mb-4 lg:mb-6 flex items-center">
                        <svg class="w-5 h-5 lg:w-6 lg:h-6 text-blue-600 mr-2 lg:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Select Rental Dates
                    </h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6 mb-4 lg:mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 lg:mb-3">Pickup Date</label>
                            <input type="date" wire:model.live="startDate" 
                                   min="{{ now()->format('Y-m-d') }}"
                                   wire:change="adjustEndDate"
                                   class="w-full px-3 lg:px-4 py-2 lg:py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-base lg:text-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 lg:mb-3">Return Date</label>
                            <input type="date" wire:model.live="endDate" 
                                   min="{{ $startDate ? \Carbon\Carbon::parse($startDate)->addDay()->format('Y-m-d') : now()->addDay()->format('Y-m-d') }}"
                                   class="w-full px-3 lg:px-4 py-2 lg:py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-base lg:text-lg">
                        </div>
                    </div>

                    <!-- Availability Status -->
                    @if($startDate && $endDate)
                        <div class="mb-4 lg:mb-6">
                            @if($isAvailable)
                                <div class="flex items-center space-x-2 lg:space-x-3 p-3 lg:p-4 bg-green-50 border border-green-200 rounded-lg lg:rounded-xl">
                                    <svg class="w-5 h-5 lg:w-6 lg:h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-sm lg:text-base font-semibold text-green-800">Available for selected dates</span>
                                </div>
                            @else
                                <div class="flex items-center space-x-2 lg:space-x-3 p-3 lg:p-4 bg-red-50 border border-red-200 rounded-lg lg:rounded-xl">
                                    <svg class="w-5 h-5 lg:w-6 lg:h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-sm lg:text-base font-semibold text-red-800">Not available for selected dates</span>
                                </div>
                            @endif
                        </div>

                        <!-- Price Calculation -->
                        @if($isAvailable && $totalPrice > 0)
                            <div class="bg-gradient-to-r from-blue-50 to-cyan-50 rounded-lg lg:rounded-xl p-4 lg:p-6 mb-4 lg:mb-6 border border-blue-200">
                                <h4 class="text-base lg:text-lg font-semibold text-gray-900 mb-3 lg:mb-4">Price Breakdown</h4>
                                <div class="space-y-2 lg:space-y-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm lg:text-base text-gray-700">Daily Rate:</span>
                                        <span class="text-sm lg:text-base font-semibold text-gray-900">${{ number_format($vehicle->currentRate?->daily_rate ?? 0, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm lg:text-base text-gray-700">Rental Days:</span>
                                        <span class="text-sm lg:text-base font-semibold text-gray-900">{{ $rentalDays }} days</span>
                                    </div>
                                    <div class="border-t border-blue-200 pt-2 lg:pt-3">
                                        <div class="flex justify-between items-center">
                                            <span class="text-lg lg:text-xl font-bold text-gray-900">Total Amount:</span>
                                            <span class="text-2xl lg:text-3xl font-bold text-blue-600">${{ number_format($totalPrice, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif

                    <!-- Book Now Button -->
                    <button wire:click="bookNow" 
                            class="w-full bg-gradient-to-r from-blue-600 to-cyan-600 text-white py-3 lg:py-4 px-6 lg:px-8 rounded-lg lg:rounded-xl hover:from-blue-700 hover:to-cyan-700 transition-all duration-300 font-bold text-lg lg:text-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 {{ !$isAvailable ? 'opacity-50 cursor-not-allowed' : '' }}"
                            {{ !$isAvailable ? 'disabled' : '' }}>
                        <span class="flex items-center justify-center">
                            @if($isAvailable)
                                <svg class="w-5 h-5 lg:w-6 lg:h-6 mr-2 lg:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Book This Vehicle
                            @else
                                <svg class="w-5 h-5 lg:w-6 lg:h-6 mr-2 lg:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                                Not Available
                            @endif
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
