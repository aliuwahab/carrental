<?php

namespace App\Livewire;

use App\Actions\BookingAction;
use App\Data\VehicleFilterData;
use App\Models\Vehicle;
use Carbon\Carbon;
use Livewire\Component;

class HomePage extends Component
{
    public $startDate;
    public $endDate;
    public $vehicleType = '';
    public $minPrice = '';
    public $maxPrice = '';
    
    public $featuredVehicles;
    public $availableVehicles = [];

    public function mount()
    {
        $this->startDate = now()->addDay()->format('Y-m-d');
        $this->endDate = now()->addDays(3)->format('Y-m-d');
        
        // Load featured vehicles (active vehicles with current rates)
        $this->featuredVehicles = Vehicle::active()
            ->with('currentRate')
            ->take(6)
            ->get();
    }

    public function searchVehicles()
    {
        $this->validate([
            'startDate' => 'required|date|after_or_equal:today',
            'endDate' => 'required|date|after:startDate',
        ], [
            'startDate.after_or_equal' => 'Pickup date must be today or in the future.',
            'endDate.after' => 'Return date must be after pickup date.',
        ]);

        $this->performSearch();
    }

    public function performSearch()
    {
        if ($this->startDate && $this->endDate) {
            $filterData = VehicleFilterData::from([
                'start_date' => Carbon::parse($this->startDate),
                'end_date' => Carbon::parse($this->endDate),
                'type' => $this->vehicleType ?: null,
                'min_price' => $this->minPrice ? (float) $this->minPrice : null,
                'max_price' => $this->maxPrice ? (float) $this->maxPrice : null,
            ]);

            $bookingAction = app(BookingAction::class);
            $this->availableVehicles = $bookingAction->getAvailableVehicles($filterData);
        }
    }

    public function updatedStartDate()
    {
        $this->performSearch();
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
            
            $this->performSearch();
        }
    }

    public function updatedEndDate()
    {
        $this->performSearch();
    }

    public function updatedVehicleType()
    {
        $this->performSearch();
    }

    public function updatedMinPrice()
    {
        $this->performSearch();
    }

    public function updatedMaxPrice()
    {
        $this->performSearch();
    }

    public function render()
    {
        return view('livewire.home-page')
            ->layout('components.layouts.public');
    }
}
