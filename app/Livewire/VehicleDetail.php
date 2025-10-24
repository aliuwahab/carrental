<?php

namespace App\Livewire;

use App\Models\Vehicle;
use Livewire\Component;

class VehicleDetail extends Component
{
    public Vehicle $vehicle;
    public $startDate;
    public $endDate;
    public $isAvailable = true;
    public $totalPrice = 0;
    public $rentalDays = 0;

    public function mount(Vehicle $vehicle)
    {
        $this->vehicle = $vehicle->load('currentRate');
        $this->startDate = request('start_date', now()->addDay()->format('Y-m-d'));
        $this->endDate = request('end_date', now()->addDays(3)->format('Y-m-d'));
        
        $this->checkAvailability();
    }

    public function updatedStartDate()
    {
        $this->checkAvailability();
    }

    public function updatedEndDate()
    {
        $this->checkAvailability();
    }

    public function checkAvailability()
    {
        if ($this->startDate && $this->endDate) {
            $bookingAction = app(\App\Actions\BookingAction::class);
            $this->isAvailable = $bookingAction->checkAvailability(
                \App\Data\CheckAvailabilityData::from([
                    'vehicle_id' => $this->vehicle->id,
                    'start_date' => \Carbon\Carbon::parse($this->startDate),
                    'end_date' => \Carbon\Carbon::parse($this->endDate),
                ])
            );

            if ($this->isAvailable) {
                $this->rentalDays = \Carbon\Carbon::parse($this->startDate)->diffInDays(\Carbon\Carbon::parse($this->endDate)) + 1;
                $this->totalPrice = $bookingAction->calculatePrice($this->vehicle, $this->rentalDays);
            }
        }
    }

    public function bookNow()
    {
        if (!$this->isAvailable) {
            session()->flash('error', 'This vehicle is not available for the selected dates.');
            return;
        }

        return redirect()->route('booking.create', [
            'vehicle' => $this->vehicle->slug,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
        ]);
    }

    public function render()
    {
        return view('livewire.vehicle-detail')
            ->layout('components.layouts.public');
    }
}
