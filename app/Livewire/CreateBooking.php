<?php

namespace App\Livewire;

use App\Actions\BookingAction;
use App\Data\CreateBookingData;
use App\Models\Booking;
use App\Models\Vehicle;
use Carbon\Carbon;
use Livewire\Component;

class CreateBooking extends Component
{
    public Vehicle $vehicle;
    public $startDate;
    public $endDate;
    public $rentalDays = 0;
    public $totalPrice = 0;
    public $termsAccepted = false;
    public $notes = '';
    
    public $step = 1; // 1: Review, 2: Terms, 3: Payment
    public $booking = null;

    public function mount(Vehicle $vehicle)
    {
        $this->vehicle = $vehicle->load('currentRate');
        $this->startDate = request('start_date', now()->addDay()->format('Y-m-d'));
        $this->endDate = request('end_date', now()->addDays(3)->format('Y-m-d'));
        
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

    public function proceedToTerms()
    {
        $this->validate([
            'startDate' => 'required|date|after_or_equal:today',
            'endDate' => 'required|date|after:startDate',
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
    }

    public function proceedToPayment()
    {
        $this->validate([
            'termsAccepted' => 'required|accepted',
        ]);

        // Check if user is authenticated
        if (!auth()->check()) {
            session()->flash('error', 'You must be logged in to complete the booking.');
            return redirect()->route('login');
        }

        $this->step = 3;
    }

    public function createBooking()
    {
        $this->validate([
            'termsAccepted' => 'required|accepted',
        ]);

        try {
            $bookingAction = app(BookingAction::class);
            
            $bookingData = CreateBookingData::from([
                'vehicle_id' => $this->vehicle->id,
                'start_date' => Carbon::parse($this->startDate),
                'end_date' => Carbon::parse($this->endDate),
                'user_id' => auth()->id(),
                'terms_accepted' => $this->termsAccepted,
                'notes' => $this->notes,
            ]);

            $this->booking = $bookingAction->createBooking($bookingData);
            
            // Confirm the booking to move it to pending status
            $this->booking = $bookingAction->confirmBooking($this->booking);
            
            session()->flash('success', 'Booking created successfully! Please complete payment to confirm your reservation.');
            
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.create-booking')
            ->layout('components.layouts.public');
    }
}
