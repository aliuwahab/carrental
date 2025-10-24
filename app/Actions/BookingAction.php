<?php

namespace App\Actions;

use App\Data\CheckAvailabilityData;
use App\Data\CreateBookingData;
use App\Data\VehicleFilterData;
use App\Models\Booking;
use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;

class BookingAction
{
    public function checkAvailability(CheckAvailabilityData $data): bool
    {
        $conflictingBookings = Booking::where('vehicle_id', $data->vehicle_id)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($data) {
                $query->whereBetween('start_date', [$data->start_date, $data->end_date])
                      ->orWhereBetween('end_date', [$data->start_date, $data->end_date])
                      ->orWhere(function ($q) use ($data) {
                          $q->where('start_date', '<=', $data->start_date)
                            ->where('end_date', '>=', $data->end_date);
                      });
            })
            ->exists();

        return !$conflictingBookings;
    }

    public function calculatePrice(Vehicle $vehicle, int $days): float
    {
        $dailyRate = $vehicle->currentRate?->daily_rate ?? 0;
        return $dailyRate * $days;
    }

    public function createBooking(CreateBookingData $data): Booking
    {
        return DB::transaction(function () use ($data) {
            $vehicle = Vehicle::findOrFail($data->vehicle_id);
            $days = $data->start_date->diffInDays($data->end_date) + 1;

            // Check availability
            $availabilityData = CheckAvailabilityData::from([
                'vehicle_id' => $data->vehicle_id,
                'start_date' => $data->start_date,
                'end_date' => $data->end_date,
            ]);

            if (!$this->checkAvailability($availabilityData)) {
                throw new \Exception('Vehicle is not available for the selected dates.');
            }

            // Calculate pricing
            $dailyRate = $vehicle->currentRate?->daily_rate ?? 0;
            $totalAmount = $this->calculatePrice($vehicle, $days);

            // Create booking
            $booking = Booking::create([
                'user_id' => $data->user_id,
                'vehicle_id' => $vehicle->id,
                'start_date' => $data->start_date,
                'end_date' => $data->end_date,
                'rental_days' => $days,
                'daily_rate' => $dailyRate,
                'total_amount' => $totalAmount,
                'status' => 'draft',
                'terms_accepted' => $data->terms_accepted,
                'terms_accepted_at' => $data->terms_accepted ? now() : null,
                'expires_at' => now()->addHours(24), // 24 hours to confirm
                'notes' => $data->notes,
            ]);

            // Log initial status
            $booking->statusLogs()->create([
                'from_status' => null,
                'to_status' => 'draft',
                'reason' => 'Booking created',
            ]);

            return $booking;
        });
    }

    public function confirmBooking(Booking $booking): Booking
    {
        return DB::transaction(function () use ($booking) {
            if ($booking->status !== 'draft') {
                throw new \Exception('Only draft bookings can be confirmed.');
            }

            if (!$booking->terms_accepted) {
                throw new \Exception('Terms must be accepted before confirming booking.');
            }

            // Check availability again
            $availabilityData = CheckAvailabilityData::from([
                'vehicle_id' => $booking->vehicle_id,
                'start_date' => $booking->start_date,
                'end_date' => $booking->end_date,
            ]);

            if (!$this->checkAvailability($availabilityData)) {
                throw new \Exception('Vehicle is no longer available for the selected dates.');
            }

            $booking->transitionTo('pending', 'Booking confirmed by customer');

            return $booking;
        });
    }

    public function cancelBooking(Booking $booking, string $reason = null): Booking
    {
        return DB::transaction(function () use ($booking, $reason) {
            if (!$booking->canBeCancelled()) {
                throw new \Exception('This booking cannot be cancelled.');
            }

            $booking->transitionTo('cancelled', $reason);

            return $booking;
        });
    }

    public function completeBooking(Booking $booking): Booking
    {
        return DB::transaction(function () use ($booking) {
            if ($booking->status !== 'confirmed') {
                throw new \Exception('Only confirmed bookings can be completed.');
            }

            if ($booking->end_date->isFuture()) {
                throw new \Exception('Cannot complete booking before end date.');
            }

            $booking->transitionTo('completed', 'Booking completed');

            return $booking;
        });
    }

    public function expirePendingBookings(): int
    {
        $expiredBookings = Booking::expired()->get();
        
        foreach ($expiredBookings as $booking) {
            $booking->transitionTo('cancelled', 'Booking expired due to non-payment');
        }

        return $expiredBookings->count();
    }

    public function completeFinishedBookings(): int
    {
        $finishedBookings = Booking::confirmed()
            ->where('end_date', '<', now())
            ->get();
        
        foreach ($finishedBookings as $booking) {
            $booking->transitionTo('completed', 'Booking automatically completed');
        }

        return $finishedBookings->count();
    }

    public function getAvailableVehicles(VehicleFilterData $data): \Illuminate\Database\Eloquent\Collection
    {
        $query = Vehicle::active()
            ->available($data->start_date->format('Y-m-d'), $data->end_date->format('Y-m-d'))
            ->whereHas('currentRate') // Only include vehicles with a current rate
            ->with('currentRate');

        if ($data->type) {
            $query->byType($data->type);
        }

        if ($data->min_price || $data->max_price) {
            $query->whereHas('currentRate', function ($q) use ($data) {
                if ($data->min_price) {
                    $q->where('daily_rate', '>=', $data->min_price);
                }
                if ($data->max_price) {
                    $q->where('daily_rate', '<=', $data->max_price);
                }
            });
        }

        return $query->get();
    }
}
