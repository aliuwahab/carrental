<?php

namespace App\Console\Commands;

use App\Models\Vehicle;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DebugProductionMedia extends Command
{
    protected $signature = 'media:debug-production';
    protected $description = 'Debug media configuration in production';

    public function handle()
    {
        $this->info('🔍 Debugging Production Media Configuration...');
        
        // Check filesystem configuration
        $this->info('📁 Filesystem Configuration:');
        $this->info('  Default disk: ' . config('filesystems.default'));
        $this->info('  S3 key: ' . config('filesystems.disks.s3.key'));
        $this->info('  S3 bucket: ' . config('filesystems.disks.s3.bucket'));
        $this->info('  S3 endpoint: ' . config('filesystems.disks.s3.endpoint'));
        
        // Test R2 connection
        $this->info('🔗 Testing R2 Connection:');
        try {
            $files = Storage::disk('r2')->allFiles();
            $this->info('  ✅ R2 connection successful');
            $this->info('  📄 Files in R2: ' . count($files));
            if (count($files) > 0) {
                $this->info('  📄 Sample file: ' . $files[0]);
            }
        } catch (\Exception $e) {
            $this->error('  ❌ R2 connection failed: ' . $e->getMessage());
        }
        
        // Test signed URL generation
        $this->info('🔐 Testing Signed URL Generation:');
        try {
            $testUrl = Storage::disk('r2')->temporaryUrl('vehicle-images/sedan-1.jpg', now()->addHours(24));
            $this->info('  ✅ Signed URL generation works');
            $this->info('  🔗 Sample signed URL: ' . substr($testUrl, 0, 100) . '...');
            
            if (str_contains($testUrl, 'X-Amz-')) {
                $this->info('  ✅ Signed URL contains proper parameters');
            } else {
                $this->error('  ❌ Signed URL missing parameters');
            }
        } catch (\Exception $e) {
            $this->error('  ❌ Signed URL generation failed: ' . $e->getMessage());
        }
        
        // Check vehicle media
        $vehicle = Vehicle::first();
        if ($vehicle) {
            $this->info('🚗 Testing Vehicle Media:');
            $this->info('  Vehicle: ' . $vehicle->name);
            
            $media = $vehicle->getFirstMedia('main_image');
            if ($media) {
                $this->info('  📷 Media disk: ' . $media->disk);
                $this->info('  📷 Media path: ' . $media->getPath());
                $this->info('  📷 Direct URL: ' . $media->getUrl());
                
                try {
                    $signedUrl = $media->getTemporaryUrl(now()->addHours(24));
                    $this->info('  🔐 Signed URL: ' . substr($signedUrl, 0, 100) . '...');
                    
                    if (str_contains($signedUrl, 'X-Amz-')) {
                        $this->info('  ✅ Vehicle signed URL works');
                    } else {
                        $this->error('  ❌ Vehicle signed URL missing parameters');
                    }
                } catch (\Exception $e) {
                    $this->error('  ❌ Vehicle signed URL failed: ' . $e->getMessage());
                }
            } else {
                $this->error('  ❌ No media found for vehicle');
            }
        } else {
            $this->error('❌ No vehicles found');
        }
        
        $this->info('🏁 Debug complete!');
    }
}
