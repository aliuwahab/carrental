<?php

namespace App\Livewire;

use App\Models\Vehicle;
use Carbon\Carbon;
use Livewire\Component;

class VehicleDetail extends Component
{
    public Vehicle $vehicle;
    public $startDate;
    public $endDate;
    public $isAvailable = true;
    public $totalPrice = 0;
    public $rentalDays = 0;
    public $selectedImageIndex = 0;

    public function mount(Vehicle $vehicle)
    {
        $this->vehicle = $vehicle->load('currentRate');
        $this->startDate = request('start_date', now()->addDay()->format('Y-m-d'));
        $this->endDate = request('end_date', now()->addDay()->format('Y-m-d'));
        
        $this->checkAvailability();
    }

    public function selectImage($index)
    {
        $this->selectedImageIndex = $index;
    }

    public function getGalleryImages()
    {
        return $this->vehicle->getMedia('gallery');
    }

    public function getMainImage()
    {
        $mainImage = $this->vehicle->getFirstMedia('main_image');
        if ($mainImage) {
            return $mainImage;
        }
        
        // Fallback to first gallery image if no main image
        $galleryImages = $this->getGalleryImages();
        return $galleryImages->first();
    }

    public function getMainImageUrl()
    {
        return $this->vehicle->getMainImageUrl();
    }

    public function getGalleryUrls()
    {
        return $this->vehicle->getGalleryUrls();
    }

    public function updatedStartDate()
    {
        $this->checkAvailability();
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
            
            $this->checkAvailability();
        }
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
