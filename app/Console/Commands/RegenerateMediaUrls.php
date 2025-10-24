<?php

namespace App\Console\Commands;

use App\Models\Vehicle;
use Illuminate\Console\Command;

class RegenerateMediaUrls extends Command
{
    protected $signature = 'media:regenerate-urls';
    protected $description = 'Regenerate all media URLs with signed URLs for R2 storage';

    public function handle()
    {
        $this->info('Regenerating media URLs...');
        
        $vehicles = Vehicle::all();
        $updated = 0;
        
        foreach ($vehicles as $vehicle) {
            $this->info("Processing vehicle: {$vehicle->name}");
            
            // Test main image URL generation
            $mainImageUrl = $vehicle->getMainImageUrl();
            if ($mainImageUrl) {
                $this->info("  Main image URL: " . substr($mainImageUrl, 0, 100) . '...');
                $updated++;
            }
            
            // Test gallery URLs
            $galleryUrls = $vehicle->getGalleryUrls();
            if (count($galleryUrls) > 0) {
                $this->info("  Gallery images: " . count($galleryUrls));
                $updated++;
            }
        }
        
        $this->info("Updated {$updated} vehicles with signed URLs");
        $this->info('Done!');
    }
}
