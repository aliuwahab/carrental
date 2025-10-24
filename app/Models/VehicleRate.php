<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleRate extends Model
{
    protected $fillable = [
        'vehicle_id',
        'daily_rate',
        'is_current',
        'effective_from',
    ];

    protected $casts = [
        'daily_rate' => 'decimal:2',
        'is_current' => 'boolean',
        'effective_from' => 'datetime',
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function scopeCurrent($query)
    {
        return $query->where('is_current', true);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($rate) {
            if ($rate->is_current) {
                // Set all other rates for this vehicle to not current
                static::where('vehicle_id', $rate->vehicle_id)
                    ->where('id', '!=', $rate->id)
                    ->update(['is_current' => false]);
            }
        });

        static::updating(function ($rate) {
            if ($rate->is_current && $rate->isDirty('is_current')) {
                // Set all other rates for this vehicle to not current
                static::where('vehicle_id', $rate->vehicle_id)
                    ->where('id', '!=', $rate->id)
                    ->update(['is_current' => false]);
            }
        });
    }
}
