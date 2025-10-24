<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Carbon\Carbon;

class Booking extends Model
{
    protected $fillable = [
        'booking_code',
        'user_id',
        'vehicle_id',
        'start_date',
        'end_date',
        'rental_days',
        'daily_rate',
        'total_amount',
        'status',
        'payment_method',
        'payment_reference',
        'terms_accepted',
        'terms_accepted_at',
        'confirmed_at',
        'completed_at',
        'cancelled_at',
        'expires_at',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'daily_rate' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'terms_accepted' => 'boolean',
        'terms_accepted_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function statusLogs(): HasMany
    {
        return $this->hasMany(BookingStatusLog::class);
    }

    public function paymentDetail(): HasOne
    {
        return $this->hasOne(PaymentDetail::class);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeExpired($query)
    {
        return $query->where('status', 'pending')
                    ->where('expires_at', '<', now());
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function isExpired(): bool
    {
        return $this->status === 'pending' && $this->expires_at && $this->expires_at->isPast();
    }

    public function isActive(): bool
    {
        return in_array($this->status, ['confirmed', 'completed']);
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'confirmed']) && 
               $this->start_date->isFuture();
    }

    public function transitionTo($newStatus, $reason = null, $changedBy = null)
    {
        $oldStatus = $this->status;
        
        $this->update([
            'status' => $newStatus,
            $newStatus . '_at' => now(),
        ]);

        // Log the status change
        $this->statusLogs()->create([
            'from_status' => $oldStatus,
            'to_status' => $newStatus,
            'changed_by' => $changedBy,
            'reason' => $reason,
        ]);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            if (empty($booking->booking_code)) {
                $booking->booking_code = static::generateBookingCode();
            }
            
            if (empty($booking->rental_days)) {
                $booking->rental_days = Carbon::parse($booking->start_date)
                    ->diffInDays(Carbon::parse($booking->end_date)) + 1;
            }
        });
    }

    public static function generateBookingCode(): string
    {
        $prefix = 'BK-' . now()->format('Ymd') . '-';
        $suffix = strtoupper(substr(md5(uniqid()), 0, 4));
        
        $code = $prefix . $suffix;
        
        // Ensure uniqueness
        while (static::where('booking_code', $code)->exists()) {
            $suffix = strtoupper(substr(md5(uniqid()), 0, 4));
            $code = $prefix . $suffix;
        }
        
        return $code;
    }
}
