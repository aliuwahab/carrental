<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DownloadVehicleImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:download-vehicles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download sample vehicle images to storage';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Downloading vehicle images...');
        
        // Use the default filesystem disk configuration
        $disk = config('filesystems.default');
        $storage = Storage::disk($disk);
        
        // Create storage directory if it doesn't exist
        $storagePath = 'vehicle-images';
        try {
            if (!$storage->exists($storagePath)) {
                $storage->makeDirectory($storagePath);
            }
        } catch (\Exception $e) {
            // Directory might already exist or creation might not be needed for S3
            $this->info("Directory creation skipped: " . $e->getMessage());
        }
        
        // Sample vehicle images from reliable sources
        $images = [
            'sedan-1.jpg' => 'https://images.unsplash.com/photo-1555215695-3004980ad54e?w=800&h=600&fit=crop&q=80',
            'sedan-2.jpg' => 'https://images.unsplash.com/photo-1549317336-206569e8475c?w=800&h=600&fit=crop&q=80',
            'suv-1.jpg' => 'https://images.unsplash.com/photo-1563720223185-11003d516935?w=800&h=600&fit=crop&q=80',
            'economy-1.jpg' => 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?w=800&h=600&fit=crop&q=80',
            'luxury-1.jpg' => 'https://images.unsplash.com/photo-1555215695-3004980ad54e?w=800&h=600&fit=crop&q=80',
        ];
        
        $downloaded = 0;
        $failed = 0;
        
        foreach ($images as $filename => $url) {
            try {
                $this->info("Downloading {$filename}...");
                
                // Download image content
                $imageContent = file_get_contents($url);
                if ($imageContent === false) {
                    $this->error("Failed to download {$filename}");
                    $failed++;
                    continue;
                }
                
                // Save to storage
                $filePath = $storagePath . '/' . $filename;
                $storage->put($filePath, $imageContent);
                
                $this->info("✓ Downloaded {$filename}");
                $downloaded++;
                
            } catch (\Exception $e) {
                $this->error("Failed to download {$filename}: " . $e->getMessage());
                $failed++;
            }
        }
        
        $this->info("Download complete! {$downloaded} images downloaded, {$failed} failed.");
        
        if ($downloaded > 0) {
            $this->info("Images stored in: {$disk}://{$storagePath}/");
            if ($disk === 'local') {
                $this->info("Run 'php artisan storage:link' to create public symlink.");
            } else {
                $this->info("Images are stored in cloud storage ({$disk}).");
            }
        }
    }
}
