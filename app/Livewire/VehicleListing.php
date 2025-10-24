<?php

namespace App\Livewire;

use App\Actions\BookingAction;
use App\Data\VehicleFilterData;
use App\Models\Vehicle;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class VehicleListing extends Component
{
    use WithPagination;

    public $startDate;
    public $endDate;
    public $vehicleType = '';
    public $minPrice = '';
    public $maxPrice = '';
    public $sortBy = 'name';
    public $sortDirection = 'asc';

    public function mount()
    {
        $this->startDate = request('start_date', now()->addDay()->format('Y-m-d'));
        $this->endDate = request('end_date', now()->addDays(3)->format('Y-m-d'));
        $this->vehicleType = request('type', '');
        $this->minPrice = request('min_price', '');
        $this->maxPrice = request('max_price', '');
    }

    public function searchVehicles()
    {
        $this->validate([
            'startDate' => 'required|date|after_or_equal:today',
            'endDate' => 'required|date|after:startDate',
        ]);

        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function adjustEndDate()
    {
        // If we have both dates, maintain the rental duration
        if ($this->startDate && $this->endDate) {
            $oldStart = \Carbon\Carbon::parse($this->startDate);
            $oldEnd = \Carbon\Carbon::parse($this->endDate);
            $rentalDays = $oldStart->diffInDays($oldEnd) + 1;
            
            // Set new end date maintaining the same rental duration
            $newStart = \Carbon\Carbon::parse($this->startDate);
            $this->endDate = $newStart->addDays($rentalDays - 1)->format('Y-m-d');
            
            $this->searchVehicles();
        }
    }

    public function render()
    {
        $filterData = VehicleFilterData::from([
            'start_date' => Carbon::parse($this->startDate),
            'end_date' => Carbon::parse($this->endDate),
            'type' => $this->vehicleType ?: null,
            'min_price' => $this->minPrice ? (float) $this->minPrice : null,
            'max_price' => $this->maxPrice ? (float) $this->maxPrice : null,
        ]);

        $bookingAction = app(BookingAction::class);
        $vehicles = $bookingAction->getAvailableVehicles($filterData);

        // Apply sorting
        $vehicles = $vehicles->sortBy(function ($vehicle) {
            switch ($this->sortBy) {
                case 'price':
                    return $vehicle->currentRate?->daily_rate ?? 0;
                case 'type':
                    return $vehicle->type;
                case 'seats':
                    return $vehicle->seats;
                default:
                    return $vehicle->name;
            }
        });

        if ($this->sortDirection === 'desc') {
            $vehicles = $vehicles->reverse();
        }

        // Convert to paginated collection
        $perPage = 12;
        $currentPage = $this->getPage();
        $total = $vehicles->count();
        $offset = ($currentPage - 1) * $perPage;
        $items = $vehicles->slice($offset, $perPage)->values();
        
        // Create a paginator instance
        $paginatedVehicles = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $currentPage,
            [
                'path' => request()->url(),
                'pageName' => 'page',
            ]
        );

        return view('livewire.vehicle-listing', [
            'vehicles' => $paginatedVehicles,
            'totalVehicles' => $total,
        ])
        ->layout('components.layouts.public');
    }
}
