<?php

namespace App\Livewire;

use App\Models\Booking;
use Livewire\Component;
use Livewire\WithPagination;

class BookingHistory extends Component
{
    use WithPagination;

    public $statusFilter = '';

    public function mount()
    {
        $this->statusFilter = request('status', '');
    }

    public function filterByStatus($status)
    {
        $this->statusFilter = $status;
        $this->resetPage();
    }

    public function render()
    {
        $query = auth()->user()->bookings()->with(['vehicle', 'paymentDetail']);

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.booking-history', [
            'bookings' => $bookings,
        ])
        ->layout('components.layouts.public');
    }
}
