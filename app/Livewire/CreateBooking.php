<?php

namespace App\Livewire;

use App\Actions\BookingAction;
use App\Data\CreateBookingData;
use App\Models\Booking;
use App\Models\Vehicle;
use App\Models\User;
use App\Events\BookingCreated;
use App\Events\GuestAccountCreated;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateBooking extends Component
{
    public Vehicle $vehicle;
    public $startDate;
    public $endDate;
    public $rentalDays = 0;
    public $totalPrice = 0;
    public $termsAccepted = false;
    public $notes = '';
    
    // Guest information
    public $guestName = '';
    public $guestEmail = '';
    public $guestPhone = '';
    
    public $step = 1; // 1: Review, 2: Terms, 3: Payment
    public $booking = null;
    public $temporaryPassword = null;

    public function mount(Vehicle $vehicle)
    {
        $this->vehicle = $vehicle->load('currentRate');
        $this->startDate = request('start_date', now()->addDay()->format('Y-m-d'));
        $this->endDate = request('end_date', now()->addDay()->format('Y-m-d'));
        
        // Check if we're coming from dashboard to complete payment
        if (request('step') === '3') {
            $this->step = 3;
            
            // If booking_id is provided, load that specific booking
            if (request('booking_id')) {
                $this->booking = auth()->user()->bookings()
                    ->where('id', request('booking_id'))
                    ->where('status', 'pending')
                    ->first();
                
                if ($this->booking) {
                    // Update dates from the existing booking
                    $this->startDate = $this->booking->start_date->format('Y-m-d');
                    $this->endDate = $this->booking->end_date->format('Y-m-d');
                }
            } else {
                // Try to find existing pending booking for this vehicle and user
                $this->booking = auth()->user()->bookings()
                    ->where('vehicle_id', $this->vehicle->id)
                    ->where('status', 'pending')
                    ->where('start_date', $this->startDate)
                    ->where('end_date', $this->endDate)
                    ->first();
            }
        }
        
        $this->calculatePrice();
    }

    public function calculatePrice()
    {
        if ($this->startDate && $this->endDate) {
            $this->rentalDays = Carbon::parse($this->startDate)->diffInDays(Carbon::parse($this->endDate)) + 1;
            $bookingAction = app(BookingAction::class);
            $this->totalPrice = $bookingAction->calculatePrice($this->vehicle, $this->rentalDays);
        }
    }

    public function updatedStartDate()
    {
        $this->calculatePrice();
    }

    public function adjustEndDate()
    {
        // If we have both dates, maintain the rental duration
        if ($this->startDate && $this->endDate) {
            $oldStart = Carbon::parse($this->startDate);
            $oldEnd = Carbon::parse($this->endDate);
            $rentalDays = $oldStart->diffInDays($oldEnd) + 1;
            
            // Set new end date maintaining the same rental duration
            $newStart = Carbon::parse($this->startDate);
            $this->endDate = $newStart->addDays($rentalDays - 1)->format('Y-m-d');
            
            $this->calculatePrice();
        }
    }

    public function updatedEndDate()
    {
        $this->calculatePrice();
    }

    public function proceedToTerms()
    {
        $this->validate([
            'startDate' => 'required|date|after_or_equal:today',
            'endDate' => 'required|date|after:startDate',
        ], [
            'startDate.after_or_equal' => 'Pickup date must be today or in the future.',
            'endDate.after' => 'Return date must be after pickup date.',
        ]);

        // Check availability
        $bookingAction = app(BookingAction::class);
        $isAvailable = $bookingAction->checkAvailability(
            \App\Data\CheckAvailabilityData::from([
                'vehicle_id' => $this->vehicle->id,
                'start_date' => Carbon::parse($this->startDate),
                'end_date' => Carbon::parse($this->endDate),
            ])
        );

        if (!$isAvailable) {
            session()->flash('error', 'This vehicle is not available for the selected dates.');
            return;
        }

        $this->step = 2;
        $this->dispatch('scroll-to-top');
    }

    public function proceedToPayment()
    {
        $validationRules = [
            'termsAccepted' => 'required|accepted',
        ];

        // If user is not authenticated, require guest information
        if (!auth()->check()) {
            $validationRules['guestName'] = 'required|string|max:255';
            $validationRules['guestEmail'] = 'required|email|max:255';
            $validationRules['guestPhone'] = 'required|string|max:20';
        }

        $this->validate($validationRules);

        $this->step = 3;
        $this->dispatch('scroll-to-top');
    }

    public function createBooking()
    {
        $validationRules = [
            'termsAccepted' => 'required|accepted',
        ];

        // If user is not authenticated, require guest information
        if (!auth()->check()) {
            $validationRules['guestName'] = 'required|string|max:255';
            $validationRules['guestEmail'] = 'required|email|max:255';
            $validationRules['guestPhone'] = 'required|string|max:20';
        }

        $this->validate($validationRules);

        try {
            $userId = auth()->id();
            $isNewAccount = false;
            
            // If user is not authenticated, create or find user account
            if (!$userId) {
                $user = User::where('email', $this->guestEmail)->first();
                
                if (!$user) {
                    // Create new user account
                    $this->temporaryPassword = Str::random(12);
                    
                    $user = User::create([
                        'name' => $this->guestName,
                        'email' => $this->guestEmail,
                        'phone' => $this->guestPhone,
                        'password' => Hash::make($this->temporaryPassword),
                        'role' => 'customer',
                    ]);
                    
                    $isNewAccount = true;
                    
                    // Fire event for new account creation
                    event(new GuestAccountCreated($user, $this->temporaryPassword));
                } else {
                    // Update phone if provided and user exists
                    if ($this->guestPhone && !$user->phone) {
                        $user->update(['phone' => $this->guestPhone]);
                    }
                }
                
                $userId = $user->id;
            }
            
            $bookingAction = app(BookingAction::class);
            
            $bookingData = CreateBookingData::from([
                'vehicle_id' => $this->vehicle->id,
                'start_date' => Carbon::parse($this->startDate),
                'end_date' => Carbon::parse($this->endDate),
                'user_id' => $userId,
                'terms_accepted' => $this->termsAccepted,
                'notes' => $this->notes,
            ]);

            $this->booking = $bookingAction->createBooking($bookingData);
            
            // Update booking status and set 1-hour expiration
            $this->booking->update([
                'status' => 'pending',
                'expires_at' => now()->addHour(), // 1 hour to complete payment
            ]);
            
            // Fire event for booking creation
            event(new BookingCreated($this->booking, $isNewAccount));
            
            $message = 'Booking created successfully! Please complete payment to confirm your reservation.';
            if ($isNewAccount) {
                $message .= ' An account has been created for you. Check your email for login details.';
            }
            
            session()->flash('success', $message);
            $this->dispatch('scroll-to-top');
            
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
            $this->dispatch('scroll-to-top');
        }
    }

    public function render()
    {
        return view('livewire.create-booking')
            ->layout('components.layouts.public');
    }
}
