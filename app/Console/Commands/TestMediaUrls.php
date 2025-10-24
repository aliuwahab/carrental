<?php

namespace App\Console\Commands;

use App\Models\Vehicle;
use Illuminate\Console\Command;

class TestMediaUrls extends Command
{
    protected $signature = 'media:test-urls';
    protected $description = 'Test media URL generation to verify signed URLs are working';

    public function handle()
    {
        $this->info('Testing media URL generation...');
        
        $vehicle = Vehicle::first();
        if (!$vehicle) {
            $this->error('No vehicles found');
            return;
        }
        
        $this->info("Testing vehicle: {$vehicle->name}");
        
        // Test main image URL
        $mainImageUrl = $vehicle->getMainImageUrl();
        if ($mainImageUrl) {
            $this->info("Main image URL: " . substr($mainImageUrl, 0, 100) . '...');
            
            if (str_contains($mainImageUrl, 'X-Amz-')) {
                $this->info('✅ Signed URLs are working!');
            } else {
                $this->error('❌ Signed URLs are NOT working - URL is not signed');
            }
        } else {
            $this->error('❌ No main image found');
        }
        
        // Test gallery URLs
        $galleryUrls = $vehicle->getGalleryUrls();
        if (count($galleryUrls) > 0) {
            $this->info("Gallery images: " . count($galleryUrls));
            $firstGalleryUrl = $galleryUrls[0];
            $this->info("First gallery URL: " . substr($firstGalleryUrl, 0, 100) . '...');
            
            if (str_contains($firstGalleryUrl, 'X-Amz-')) {
                $this->info('✅ Gallery signed URLs are working!');
            } else {
                $this->error('❌ Gallery signed URLs are NOT working');
            }
        } else {
            $this->info('No gallery images found');
        }
        
        $this->info('Test complete!');
    }
}
