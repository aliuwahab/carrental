<?php

namespace App\Livewire;

use App\Models\Booking;
use Livewire\Component;
use Livewire\WithPagination;

class Dashboard extends Component
{
    use WithPagination;

    public function render()
    {
        $user = auth()->user();
        
        // Get booking statistics
        $totalBookings = $user->bookings()->count();
        $pendingBookings = $user->bookings()->where('status', 'pending')->count();
        $confirmedBookings = $user->bookings()->where('status', 'confirmed')->count();
        $draftBookings = $user->bookings()->where('status', 'draft')->count();
        
        // Get recent bookings (not completed)
        $recentBookings = $user->bookings()
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->with(['vehicle'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.dashboard', [
            'totalBookings' => $totalBookings,
            'pendingBookings' => $pendingBookings,
            'confirmedBookings' => $confirmedBookings,
            'draftBookings' => $draftBookings,
            'recentBookings' => $recentBookings,
        ])
        ->layout('components.layouts.app', ['title' => 'Dashboard']);
    }
}
