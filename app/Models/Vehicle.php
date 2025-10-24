<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Vehicle extends Model implements HasMedia
{
    use InteractsWithMedia, HasSlug;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'description',
        'features',
        'seats',
        'transmission',
        'fuel_type',
        'main_image',
        'is_active',
    ];

    protected $casts = [
        'features' => 'array',
        'is_active' => 'boolean',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function rates(): HasMany
    {
        return $this->hasMany(VehicleRate::class);
    }

    public function currentRate(): HasOne
    {
        return $this->hasOne(VehicleRate::class)->where('is_current', true);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeAvailable($query, $startDate, $endDate)
    {
        return $query->whereDoesntHave('bookings', function ($q) use ($startDate, $endDate) {
            $q->where('status', '!=', 'cancelled')
              ->where(function ($query) use ($startDate, $endDate) {
                  $query->whereBetween('start_date', [$startDate, $endDate])
                        ->orWhereBetween('end_date', [$startDate, $endDate])
                        ->orWhere(function ($q) use ($startDate, $endDate) {
                            $q->where('start_date', '<=', $startDate)
                              ->where('end_date', '>=', $endDate);
                        });
              });
        });
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(200)
            ->height(150)
            ->sharpen(10)
            ->optimize()
            ->performOnCollections('gallery', 'main_image')
            ->nonQueued();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('main_image')
            ->singleFile();

        $this->addMediaCollection('gallery')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);
    }
}
