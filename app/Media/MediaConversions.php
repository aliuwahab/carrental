<?php

namespace App\Media;

use Spatie\MediaLibrary\Conversions\Conversion;
use Spatie\MediaLibrary\Conversions\ImageGenerators\Image;

class MediaConversions
{
    public static function registerConversions()
    {
        // Thumbnail conversion for gallery images
        Conversion::create('thumb')
            ->width(200)
            ->height(150)
            ->sharpen(10)
            ->optimize()
            ->performOnCollections('gallery', 'main_image');
    }
}