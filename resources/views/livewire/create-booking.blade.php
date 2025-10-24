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
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Review Your Booking</h2>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Vehicle Details -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Vehicle Details</h3>
                        <div class="flex items-center space-x-4 mb-4">
                            <img src="{{ $vehicle->main_image ?: 'https://via.placeholder.com/120x80?text=Vehicle' }}" 
                                 alt="{{ $vehicle->name }}" 
                                 class="w-24 h-16 object-cover rounded">
                            <div>
                                <h4 class="font-semibold text-gray-900">{{ $vehicle->name }}</h4>
                                <p class="text-sm text-gray-600">{{ ucfirst($vehicle->type) }} • {{ $vehicle->seats }} seats</p>
                            </div>
                        </div>
                        
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Transmission:</span>
                                <span class="text-gray-900">{{ ucfirst($vehicle->transmission) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Fuel Type:</span>
                                <span class="text-gray-900">{{ $vehicle->fuel_type }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Daily Rate:</span>
                                <span class="text-gray-900 font-semibold">${{ number_format($vehicle->currentRate?->daily_rate ?? 0, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Booking Details -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Booking Details</h3>
                        
                        <div class="space-y-4">
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
                                <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                                <textarea wire:model="notes" rows="3" 
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                          placeholder="Any special requests or notes..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Price Summary -->
                <div class="mt-8 border-t pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Price Summary</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
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
                </div>

                <div class="mt-8 flex justify-end">
                    <button wire:click="proceedToTerms" 
                            class="bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700 transition-colors font-semibold">
                        Continue to Terms
                    </button>
                </div>
            </div>
        @endif

        <!-- Step 2: Terms and Conditions -->
        @if($step === 2)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Terms and Conditions</h2>
                
                <div class="prose max-w-none mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Rental Terms and Conditions</h3>
                    
                    <div class="space-y-4 text-sm text-gray-700">
                        <div>
                            <h4 class="font-semibold text-gray-900">1. Rental Period</h4>
                            <p>• The rental period begins on {{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} and ends on {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}.</p>
                            <p>• Late returns may incur additional charges.</p>
                        </div>
                        
                        <div>
                            <h4 class="font-semibold text-gray-900">2. Payment Terms</h4>
                            <p>• Full payment is required before vehicle pickup.</p>
                            <p>• Payment can be made via PayPal or Mobile Money.</p>
                            <p>• A security deposit may be required.</p>
                        </div>
                        
                        <div>
                            <h4 class="font-semibold text-gray-900">3. Driver Requirements</h4>
                            <p>• Valid driver's license required.</p>
                            <p>• Minimum age: 21 years.</p>
                            <p>• Clean driving record preferred.</p>
                        </div>
                        
                        <div>
                            <h4 class="font-semibold text-gray-900">4. Vehicle Condition</h4>
                            <p>• Vehicle must be returned in the same condition as received.</p>
                            <p>• Any damage will be charged to the renter.</p>
                            <p>• Fuel tank should be returned at the same level as pickup.</p>
                        </div>
                        
                        <div>
                            <h4 class="font-semibold text-gray-900">5. Cancellation Policy</h4>
                            <p>• Cancellations made 24 hours before pickup: Full refund.</p>
                            <p>• Cancellations made less than 24 hours: 50% refund.</p>
                            <p>• No-shows: No refund.</p>
                        </div>
                    </div>
                </div>

                <div class="border-t pt-6">
                    <div class="flex items-start space-x-3">
                        <input type="checkbox" wire:model="termsAccepted" 
                               class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label class="text-sm text-gray-700">
                            I have read and agree to the terms and conditions above. I understand that by proceeding, I am entering into a binding rental agreement.
                        </label>
                    </div>
                    @error('termsAccepted') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mt-8 flex justify-between">
                    <button wire:click="$set('step', 1)" 
                            class="bg-gray-300 text-gray-700 px-6 py-3 rounded-md hover:bg-gray-400 transition-colors font-semibold">
                        Back
                    </button>
                    <button wire:click="proceedToPayment" 
                            class="bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700 transition-colors font-semibold">
                        Continue to Payment
                    </button>
                </div>
            </div>
        @endif

        <!-- Step 3: Payment -->
        @if($step === 3)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Complete Payment</h2>
                
                @if($booking)
                    <!-- Booking Created Successfully -->
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="font-semibold text-green-800">Booking Created Successfully!</span>
                        </div>
                        <p class="text-green-700 mt-2">Your booking ID is: <span class="font-mono font-semibold">{{ $booking->booking_code }}</span></p>
                    </div>

                    <!-- Payment Instructions -->
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Amount</h3>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="text-center">
                                    <span class="text-3xl font-bold text-blue-600">${{ number_format($booking->total_amount, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Methods</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- PayPal -->
                                <div class="border border-gray-200 rounded-lg p-6">
                                    <div class="text-center">
                                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M7.076 21.337H2.47a.641.641 0 0 1-.633-.74L4.944.901C5.026.382 5.474 0 5.998 0h7.46c2.57 0 4.578.543 5.69 1.81 1.01 1.15 1.304 2.42 1.012 4.287-.023.143-.047.288-.077.437-.983 5.05-4.349 6.797-8.647 6.797h-2.19c-.524 0-.968.382-1.05.9l-1.12 7.106zm14.146-14.42a3.35 3.35 0 0 0-.105-.633c-.89-4.812-4.464-6.4-8.94-6.4H5.998c-.524 0-.968.382-1.05.9L2.47 20.597h4.606l1.12-7.106c.082-.518.526-.9 1.05-.9h2.19c4.298 0 7.664-1.747 8.647-6.797.03-.149.054-.294.077-.437.292-1.867-.002-3.137-1.012-4.287z"/>
                                            </svg>
                                        </div>
                                        <h4 class="font-semibold text-gray-900 mb-2">PayPal</h4>
                                        <p class="text-sm text-gray-600 mb-4">Pay securely with PayPal</p>
                                        <a href="#" 
                                           class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors inline-block">
                                            Pay with PayPal
                                        </a>
                                    </div>
                                </div>

                                <!-- Mobile Money -->
                                <div class="border border-gray-200 rounded-lg p-6">
                                    <div class="text-center">
                                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                            </svg>
                                        </div>
                                        <h4 class="font-semibold text-gray-900 mb-2">Mobile Money</h4>
                                        <p class="text-sm text-gray-600 mb-4">Send payment to our mobile number</p>
                                        <div class="bg-gray-50 rounded-lg p-3 mb-4">
                                            <p class="text-sm text-gray-700">Send <span class="font-semibold">${{ number_format($booking->total_amount, 2) }}</span> to:</p>
                                            <p class="font-mono text-lg font-semibold text-gray-900">+1 (555) 123-4567</p>
                                            <p class="text-xs text-gray-500">Reference: {{ $booking->booking_code }}</p>
                                        </div>
                                        <button class="w-full bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition-colors">
                                            I've Sent Payment
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <div class="flex items-start space-x-2">
                                <svg class="w-5 h-5 text-yellow-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                <div>
                                    <h4 class="font-semibold text-yellow-800">Important Payment Instructions</h4>
                                    <ul class="text-sm text-yellow-700 mt-2 space-y-1">
                                        <li>• Include your booking reference ({{ $booking->booking_code }}) in the payment description</li>
                                        <li>• Payment must be completed within 24 hours to confirm your reservation</li>
                                        <li>• You will receive a confirmation email once payment is verified</li>
                                        <li>• Contact us at +1 (555) 123-4567 if you have any payment issues</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-between">
                        <a href="{{ route('bookings.index') }}" 
                           class="bg-gray-300 text-gray-700 px-6 py-3 rounded-md hover:bg-gray-400 transition-colors font-semibold">
                            View My Bookings
                        </a>
                        <a href="{{ route('home') }}" 
                           class="bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700 transition-colors font-semibold">
                            Back to Home
                        </a>
                    </div>
                @else
                    <!-- Create Booking Button -->
                    <div class="text-center">
                        <p class="text-gray-600 mb-6">Click the button below to create your booking and proceed with payment.</p>
                        <button wire:click="createBooking" 
                                class="bg-blue-600 text-white px-8 py-3 rounded-md hover:bg-blue-700 transition-colors font-semibold text-lg">
                            Create Booking & Proceed to Payment
                        </button>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
