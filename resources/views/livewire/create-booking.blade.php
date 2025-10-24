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
                        <a href="{{ route('vehicles.show', $vehicle->slug) }}" class="text-gray-400 hover:text-gray-500">{{ $vehicle->name }}</a>
                    </li>
                    <li>
                        <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </li>
                    <li>
                        <span class="text-gray-500">Book Now</span>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Progress Steps -->
        <div class="mb-8">
            <div class="flex items-center justify-center space-x-8">
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-full {{ $step >= 1 ? 'bg-blue-600 text-white' : 'bg-gray-300 text-gray-600' }} flex items-center justify-center text-sm font-semibold">
                        1
                    </div>
                    <span class="ml-2 text-sm font-medium {{ $step >= 1 ? 'text-blue-600' : 'text-gray-500' }}">Review</span>
                </div>
                <div class="w-16 h-0.5 {{ $step >= 2 ? 'bg-blue-600' : 'bg-gray-300' }}"></div>
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-full {{ $step >= 2 ? 'bg-blue-600 text-white' : 'bg-gray-300 text-gray-600' }} flex items-center justify-center text-sm font-semibold">
                        2
                    </div>
                    <span class="ml-2 text-sm font-medium {{ $step >= 2 ? 'text-blue-600' : 'text-gray-500' }}">Terms</span>
                </div>
                <div class="w-16 h-0.5 {{ $step >= 3 ? 'bg-blue-600' : 'bg-gray-300' }}"></div>
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-full {{ $step >= 3 ? 'bg-blue-600 text-white' : 'bg-gray-300 text-gray-600' }} flex items-center justify-center text-sm font-semibold">
                        3
                    </div>
                    <span class="ml-2 text-sm font-medium {{ $step >= 3 ? 'text-blue-600' : 'text-gray-500' }}">Payment</span>
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md">
                {{ session('success') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md">
                {{ session('error') }}
            </div>
        @endif

        <!-- Step 1: Review Booking -->
        @if($step === 1)
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-cyan-600 text-white p-8">
                    <h2 class="text-3xl font-bold mb-2">Review Your Booking</h2>
                    <p class="text-blue-100">Please review your vehicle selection and rental details</p>
                </div>
                
                <div class="p-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Vehicle Details -->
                        <div class="bg-gray-50 rounded-xl p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                                <svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                Vehicle Details
                            </h3>
                            <div class="flex items-center space-x-4 mb-6">
                                <img src="{{ $vehicle->main_image ?: 'https://via.placeholder.com/120x80?text=Vehicle' }}" 
                                     alt="{{ $vehicle->name }}" 
                                     class="w-32 h-20 object-cover rounded-lg shadow-lg">
                                <div>
                                    <h4 class="text-xl font-bold text-gray-900">{{ $vehicle->name }}</h4>
                                    <p class="text-gray-600">{{ ucfirst($vehicle->type) }} • {{ $vehicle->seats }} seats</p>
                                </div>
                            </div>
                            
                            <div class="space-y-3">
                                <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                    <span class="text-gray-600 font-medium">Transmission:</span>
                                    <span class="text-gray-900 font-semibold">{{ ucfirst($vehicle->transmission) }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                    <span class="text-gray-600 font-medium">Fuel Type:</span>
                                    <span class="text-gray-900 font-semibold">{{ $vehicle->fuel_type }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2">
                                    <span class="text-gray-600 font-medium">Daily Rate:</span>
                                    <span class="text-blue-600 font-bold text-lg">${{ number_format($vehicle->currentRate?->daily_rate ?? 0, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Booking Details -->
                        <div class="bg-gray-50 rounded-xl p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                                <svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Rental Details
                            </h3>
                            
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-3">Pickup Date</label>
                                    <input type="date" wire:model.live="startDate" 
                                           min="{{ now()->format('Y-m-d') }}"
                                           wire:change="adjustEndDate"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-lg">
                                    @error('startDate') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-3">Return Date</label>
                                    <input type="date" wire:model.live="endDate" 
                                           min="{{ $startDate ? \Carbon\Carbon::parse($startDate)->addDay()->format('Y-m-d') : now()->addDay()->format('Y-m-d') }}"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-lg">
                                    @error('endDate') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-3">Special Requests (Optional)</label>
                                    <textarea wire:model="notes" rows="4" 
                                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                              placeholder="Any special requests or notes..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Price Summary -->
                    <div class="mt-8 bg-gradient-to-r from-blue-50 to-cyan-50 rounded-xl p-6 border border-blue-200">
                        <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                            <svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                            Price Summary
                        </h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center py-3 border-b border-blue-200">
                                <span class="text-gray-700 font-medium">Daily Rate:</span>
                                <span class="font-semibold text-gray-900">${{ number_format($vehicle->currentRate?->daily_rate ?? 0, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-blue-200">
                                <span class="text-gray-700 font-medium">Rental Days:</span>
                                <span class="font-semibold text-gray-900">{{ $rentalDays }} days</span>
                            </div>
                            <div class="pt-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-2xl font-bold text-gray-900">Total Amount:</span>
                                    <span class="text-4xl font-bold text-blue-600">${{ number_format($totalPrice, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button wire:click="proceedToTerms" 
                                class="bg-gradient-to-r from-blue-600 to-cyan-600 text-white px-8 py-4 rounded-xl hover:from-blue-700 hover:to-cyan-700 transition-all duration-300 font-bold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <span class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                                Continue to Terms
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Step 2: Terms and Conditions -->
        @if($step === 2)
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-green-600 to-emerald-600 text-white p-8">
                    <h2 class="text-3xl font-bold mb-2">Terms and Conditions</h2>
                    <p class="text-green-100">Please read and accept our rental terms and conditions</p>
                </div>
                
                <div class="p-8">
                    <div class="bg-gray-50 rounded-xl p-6 mb-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                            <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Rental Terms and Conditions
                        </h3>
                        
                        <div class="space-y-6">
                            <div class="bg-white rounded-lg p-4 border border-gray-200">
                                <h4 class="font-bold text-gray-900 mb-3 flex items-center">
                                    <span class="w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-bold mr-3">1</span>
                                    Rental Period
                                </h4>
                                <ul class="text-gray-700 space-y-2 ml-9">
                                    <li>• The rental period begins on <span class="font-semibold">{{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }}</span> and ends on <span class="font-semibold">{{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}</span>.</li>
                                    <li>• Late returns may incur additional charges.</li>
                                </ul>
                            </div>
                            
                            <div class="bg-white rounded-lg p-4 border border-gray-200">
                                <h4 class="font-bold text-gray-900 mb-3 flex items-center">
                                    <span class="w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-bold mr-3">2</span>
                                    Payment Terms
                                </h4>
                                <ul class="text-gray-700 space-y-2 ml-9">
                                    <li>• Full payment is required before vehicle pickup.</li>
                                    <li>• Payment can be made via PayPal or Mobile Money.</li>
                                    <li>• A security deposit may be required.</li>
                                </ul>
                            </div>
                            
                            <div class="bg-white rounded-lg p-4 border border-gray-200">
                                <h4 class="font-bold text-gray-900 mb-3 flex items-center">
                                    <span class="w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-bold mr-3">3</span>
                                    Driver Requirements
                                </h4>
                                <ul class="text-gray-700 space-y-2 ml-9">
                                    <li>• We will provide a professional driver for your rental.</li>
                                    <li>• Valid driver's license required for the provided driver.</li>
                                    <li>• Minimum age: 21 years for the driver.</li>
                                    <li>• Clean driving record preferred for the driver.</li>
                                    <li>• <strong>Customer Responsibility:</strong> You are responsible for ensuring the driver does not consume alcohol or any prohibited substances during the trip.</li>
                                    <li>• Any violation of this policy may result in immediate termination of the rental agreement.</li>
                                </ul>
                            </div>
                            
                            <div class="bg-white rounded-lg p-4 border border-gray-200">
                                <h4 class="font-bold text-gray-900 mb-3 flex items-center">
                                    <span class="w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-bold mr-3">4</span>
                                    Vehicle Condition
                                </h4>
                                <ul class="text-gray-700 space-y-2 ml-9">
                                    <li>• Vehicle must be returned in the same condition as received.</li>
                                    <li>• Any damage will be charged to the renter.</li>
                                    <li>• Fuel tank should be returned at the same level as pickup.</li>
                                </ul>
                            </div>
                            
                            <div class="bg-white rounded-lg p-4 border border-gray-200">
                                <h4 class="font-bold text-gray-900 mb-3 flex items-center">
                                    <span class="w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-bold mr-3">5</span>
                                    Cancellation Policy
                                </h4>
                                <ul class="text-gray-700 space-y-2 ml-9">
                                    <li>• Cancellations made 24 hours before pickup: Full refund.</li>
                                    <li>• Cancellations made less than 24 hours: 50% refund.</li>
                                    <li>• No-shows: No refund.</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-8">
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <input type="checkbox" wire:model="termsAccepted" 
                                       class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            </div>
                            <div>
                                <label class="text-gray-700 font-medium">
                                    I have read and agree to the terms and conditions above. I understand that by proceeding, I am entering into a binding rental agreement.
                                </label>
                                @error('termsAccepted') <span class="text-red-500 text-sm block mt-2">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between">
                        <button wire:click="$set('step', 1)" 
                                class="bg-gray-100 text-gray-700 px-8 py-4 rounded-xl hover:bg-gray-200 transition-all duration-300 font-semibold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <span class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                                Back
                            </span>
                        </button>
                        <button wire:click="proceedToPayment" 
                                class="bg-gradient-to-r from-green-600 to-emerald-600 text-white px-8 py-4 rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-300 font-bold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <span class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                                Continue to Payment
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Step 3: Payment -->
        @if($step === 3)
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-purple-600 to-pink-600 text-white p-8">
                    <h2 class="text-3xl font-bold mb-2">Complete Payment</h2>
                    <p class="text-purple-100">Choose your preferred payment method to confirm your booking</p>
                </div>
                
                <div class="p-8">
                    @if($booking)
                        <!-- Booking Created Successfully -->
                        <div class="bg-green-50 border border-green-200 rounded-xl p-6 mb-8">
                            <div class="flex items-center space-x-3">
                                <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <div>
                                    <h3 class="text-xl font-bold text-green-800">Booking Created Successfully!</h3>
                                    <p class="text-green-700 mt-1">Your booking ID is: <span class="font-mono font-bold text-lg">{{ $booking->booking_code }}</span></p>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Amount -->
                        <div class="bg-gradient-to-r from-blue-50 to-cyan-50 rounded-xl p-8 mb-8 border border-blue-200">
                            <h3 class="text-2xl font-bold text-gray-900 mb-4 text-center">Payment Amount</h3>
                            <div class="text-center">
                                <span class="text-6xl font-bold text-blue-600">${{ number_format($booking->total_amount, 2) }}</span>
                            </div>
                        </div>

                        <!-- Payment Methods -->
                        <div class="mb-8">
                            <h3 class="text-2xl font-bold text-gray-900 mb-6 text-center">Choose Payment Method</h3>
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                <!-- PayPal -->
                                <div class="bg-white border-2 border-gray-200 rounded-2xl p-8 hover:border-blue-300 transition-all duration-300 hover:shadow-xl">
                                    <div class="text-center">
                                        <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                            <svg class="w-10 h-10 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M7.076 21.337H2.47a.641.641 0 0 1-.633-.74L4.944.901C5.026.382 5.474 0 5.998 0h7.46c2.57 0 4.578.543 5.69 1.81 1.01 1.15 1.304 2.42 1.012 4.287-.023.143-.047.288-.077.437-.983 5.05-4.349 6.797-8.647 6.797h-2.19c-.524 0-.968.382-1.05.9l-1.12 7.106zm14.146-14.42a3.35 3.35 0 0 0-.105-.633c-.89-4.812-4.464-6.4-8.94-6.4H5.998c-.524 0-.968.382-1.05.9L2.47 20.597h4.606l1.12-7.106c.082-.518.526-.9 1.05-.9h2.19c4.298 0 7.664-1.747 8.647-6.797.03-.149.054-.294.077-.437.292-1.867-.002-3.137-1.012-4.287z"/>
                                            </svg>
                                        </div>
                                        <h4 class="text-2xl font-bold text-gray-900 mb-3">PayPal</h4>
                                        <p class="text-gray-600 mb-6">Pay securely with PayPal</p>
                                        <a href="{{ \App\Models\Setting::get('paypal_link', '#') }}" 
                                           target="_blank"
                                           class="w-full bg-blue-600 text-white px-8 py-4 rounded-xl hover:bg-blue-700 transition-all duration-300 font-bold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 inline-block">
                                            Pay with PayPal
                                        </a>
                                    </div>
                                </div>

                                <!-- Mobile Money -->
                                <div class="bg-white border-2 border-gray-200 rounded-2xl p-8 hover:border-green-300 transition-all duration-300 hover:shadow-xl">
                                    <div class="text-center">
                                        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                            <svg class="w-10 h-10 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                            </svg>
                                        </div>
                                        <h4 class="text-2xl font-bold text-gray-900 mb-3">Mobile Money</h4>
                                        <p class="text-gray-600 mb-6">Send payment to our mobile number</p>
                                        <div class="bg-gray-50 rounded-xl p-4 mb-6">
                                            <p class="text-gray-700 mb-2">Send <span class="font-bold text-lg">${{ number_format($booking->total_amount, 2) }}</span> to:</p>
                                            <p class="font-mono text-2xl font-bold text-gray-900">{{ \App\Models\Setting::get('mobile_money_number', '+1 (555) 123-4567') }}</p>
                                            <p class="text-sm text-gray-500 mt-2">Reference: {{ $booking->booking_code }}</p>
                                        </div>
                                        <button class="w-full bg-green-600 text-white px-8 py-4 rounded-xl hover:bg-green-700 transition-all duration-300 font-bold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                            I've Sent Payment
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Instructions -->
                        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6 mb-8">
                            <div class="flex items-start space-x-4">
                                <svg class="w-8 h-8 text-yellow-600 mt-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                <div>
                                    <h4 class="text-xl font-bold text-yellow-800 mb-3">Important Payment Instructions</h4>
                                    <ul class="text-yellow-700 space-y-2">
                                        <li>• <strong>Use this booking code as your payment reference:</strong> <span class="font-mono font-bold text-lg">{{ $booking->booking_code }}</span></li>
                                        <li>• <strong>Payment must be completed within 1 hour</strong> to confirm your reservation</li>
                                        <li>• After 1 hour, your booking will be automatically cancelled and the vehicle will be available for others</li>
                                        <li>• You will receive a confirmation email once payment is verified</li>
                                        <li>• Contact us via WhatsApp at {{ \App\Models\Setting::get('whatsapp_contact', '+1 (555) 123-4567') }} if you have any payment issues</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-4 justify-between">
                            <a href="{{ route('dashboard') }}" 
                               class="bg-gray-100 text-gray-700 px-8 py-4 rounded-xl hover:bg-gray-200 transition-all duration-300 font-semibold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-center">
                                View My Bookings
                            </a>
                            <a href="{{ route('home') }}" 
                               class="bg-gradient-to-r from-blue-600 to-cyan-600 text-white px-8 py-4 rounded-xl hover:from-blue-700 hover:to-cyan-700 transition-all duration-300 font-bold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-center">
                                Back to Home
                            </a>
                        </div>
                    @else
                        <!-- Create Booking Button -->
                        <div class="text-center py-12">
                            <div class="w-24 h-24 bg-gradient-to-br from-blue-100 to-cyan-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-4">Ready to Create Your Booking?</h3>
                            <p class="text-lg text-gray-600 mb-8">Click the button below to create your booking and proceed with payment.</p>
                            <button wire:click="createBooking" 
                                    class="bg-gradient-to-r from-blue-600 to-cyan-600 text-white px-12 py-4 rounded-xl hover:from-blue-700 hover:to-cyan-700 transition-all duration-300 font-bold text-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                Create Booking & Proceed to Payment
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
