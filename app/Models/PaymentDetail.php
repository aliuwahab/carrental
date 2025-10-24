<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentDetail extends Model
{
    protected $fillable = [
        'booking_id',
        'payment_method',
        'payment_info',
        'amount',
        'transaction_id',
        'paid_at',
        'verified_at',
    ];

    protected $casts = [
        'payment_info' => 'array',
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function isPaid(): bool
    {
        return !is_null($this->paid_at);
    }

    public function isVerified(): bool
    {
        return !is_null($this->verified_at);
    }

    public function markAsPaid($transactionId = null)
    {
        $this->update([
            'paid_at' => now(),
            'transaction_id' => $transactionId,
        ]);
    }

    public function markAsVerified()
    {
        $this->update([
            'verified_at' => now(),
        ]);
    }
}
