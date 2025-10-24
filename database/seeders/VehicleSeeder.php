<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use App\Models\VehicleRate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vehicles = [
            [
                'name' => 'Toyota Camry 2023',
                'type' => 'sedan',
                'description' => 'Comfortable and reliable sedan perfect for city driving and business trips.',
                'features' => ['Air Conditioning', 'GPS Navigation', 'Bluetooth', 'Backup Camera', 'Automatic Transmission'],
                'seats' => 5,
                'transmission' => 'automatic',
                'fuel_type' => 'Gasoline',
                'daily_rate' => 45.00,
            ],
            [
                'name' => 'Honda Civic 2023',
                'type' => 'economy',
                'description' => 'Fuel-efficient economy car ideal for budget-conscious travelers.',
                'features' => ['Air Conditioning', 'USB Ports', 'Automatic Transmission'],
                'seats' => 5,
                'transmission' => 'automatic',
                'fuel_type' => 'Gasoline',
                'daily_rate' => 35.00,
            ],
            [
                'name' => 'BMW X5 2023',
                'type' => 'suv',
                'description' => 'Luxury SUV with premium features and spacious interior.',
                'features' => ['Air Conditioning', 'GPS Navigation', 'Bluetooth', 'Backup Camera', 'Leather Seats', 'Sunroof', 'Automatic Transmission'],
                'seats' => 7,
                'transmission' => 'automatic',
                'fuel_type' => 'Gasoline',
                'daily_rate' => 85.00,
            ],
            [
                'name' => 'Mercedes-Benz S-Class 2023',
                'type' => 'luxury',
                'description' => 'Ultimate luxury sedan with cutting-edge technology and comfort.',
                'features' => ['Air Conditioning', 'GPS Navigation', 'Bluetooth', 'Backup Camera', 'Leather Seats', 'Sunroof', 'Massage Seats', 'Automatic Transmission'],
                'seats' => 5,
                'transmission' => 'automatic',
                'fuel_type' => 'Gasoline',
                'daily_rate' => 120.00,
            ],
            [
                'name' => 'Ford Transit 2023',
                'type' => 'van',
                'description' => 'Spacious van perfect for group travel and cargo transport.',
                'features' => ['Air Conditioning', 'GPS Navigation', 'Bluetooth', 'Backup Camera', 'Automatic Transmission'],
                'seats' => 12,
                'transmission' => 'automatic',
                'fuel_type' => 'Diesel',
                'daily_rate' => 75.00,
            ],
            [
                'name' => 'Nissan Altima 2023',
                'type' => 'sedan',
                'description' => 'Mid-size sedan with excellent fuel economy and modern features.',
                'features' => ['Air Conditioning', 'GPS Navigation', 'Bluetooth', 'Backup Camera', 'Automatic Transmission'],
                'seats' => 5,
                'transmission' => 'automatic',
                'fuel_type' => 'Gasoline',
                'daily_rate' => 42.00,
            ],
            [
                'name' => 'Toyota RAV4 2023',
                'type' => 'suv',
                'description' => 'Compact SUV with excellent reliability and fuel efficiency.',
                'features' => ['Air Conditioning', 'GPS Navigation', 'Bluetooth', 'Backup Camera', 'All-Wheel Drive', 'Automatic Transmission'],
                'seats' => 5,
                'transmission' => 'automatic',
                'fuel_type' => 'Gasoline',
                'daily_rate' => 55.00,
            ],
            [
                'name' => 'Hyundai Elantra 2023',
                'type' => 'economy',
                'description' => 'Affordable compact car with modern styling and features.',
                'features' => ['Air Conditioning', 'USB Ports', 'Bluetooth', 'Automatic Transmission'],
                'seats' => 5,
                'transmission' => 'automatic',
                'fuel_type' => 'Gasoline',
                'daily_rate' => 32.00,
            ],
        ];

        foreach ($vehicles as $vehicleData) {
            $dailyRate = $vehicleData['daily_rate'];
            unset($vehicleData['daily_rate']);

            $vehicle = Vehicle::create($vehicleData);

            // Create current rate for the vehicle
            VehicleRate::create([
                'vehicle_id' => $vehicle->id,
                'daily_rate' => $dailyRate,
                'is_current' => true,
                'effective_from' => now(),
            ]);

            // Add real images from Unsplash
            $this->addVehicleImages($vehicle, $vehicleData['type']);
        }
    }

    private function addVehicleImages(Vehicle $vehicle, string $type): void
    {
        // Use the default filesystem disk configuration
        $disk = config('filesystems.default');
        
        // Use local images from storage
        $localImages = $this->getLocalImagesForType($type);
        
        foreach ($localImages as $index => $imagePath) {
            try {
                // Add to media library from configured storage (S3 in production)
                if ($index === 0) {
                    // First image is main image
                    $vehicle->addMediaFromDisk($imagePath, $disk)
                        ->toMediaCollection('main_image');
                } else {
                    // Additional images go to gallery
                    $vehicle->addMediaFromDisk($imagePath, $disk)
                        ->toMediaCollection('gallery');
                }
            } catch (\Exception $e) {
                // If image fails, create a placeholder
                $this->createPlaceholderImage($vehicle, $index === 0 ? 'main_image' : 'gallery', $type);
            }
        }
    }

    private function createPlaceholderImage(Vehicle $vehicle, string $collection, string $type): void
    {
        try {
            // Create a simple placeholder image using GD
            $width = 800;
            $height = 600;
            $image = imagecreate($width, $height);
            
            // Set colors
            $bgColor = imagecolorallocate($image, 200, 200, 200);
            $textColor = imagecolorallocate($image, 100, 100, 100);
            
            // Fill background
            imagefill($image, 0, 0, $bgColor);
            
            // Add text
            $text = strtoupper($type) . ' VEHICLE';
            $fontSize = 5;
            $textWidth = imagefontwidth($fontSize) * strlen($text);
            $textHeight = imagefontheight($fontSize);
            $x = ($width - $textWidth) / 2;
            $y = ($height - $textHeight) / 2;
            
            imagestring($image, $fontSize, $x, $y, $text, $textColor);
            
            // Save to temporary file
            $tempFile = tempnam(sys_get_temp_dir(), 'placeholder_') . '.jpg';
            imagejpeg($image, $tempFile, 80);
            imagedestroy($image);
            
            // Add to media library (will use configured disk automatically)
            $vehicle->addMedia($tempFile)
                ->toMediaCollection($collection);
                
            // Clean up
            unlink($tempFile);
        } catch (\Exception $e) {
            // If even placeholder creation fails, just continue
        }
    }

    private function getLocalImagesForType(string $type): array
    {
        $localImages = [
            'sedan' => [
                'vehicle-images/sedan-1.jpg',
                'vehicle-images/sedan-2.jpg',
            ],
            'economy' => [
                'vehicle-images/economy-1.jpg',
                'vehicle-images/sedan-1.jpg',
            ],
            'suv' => [
                'vehicle-images/suv-1.jpg',
                'vehicle-images/sedan-2.jpg',
            ],
            'luxury' => [
                'vehicle-images/luxury-1.jpg',
                'vehicle-images/sedan-1.jpg',
            ],
            'van' => [
                'vehicle-images/suv-1.jpg',
                'vehicle-images/economy-1.jpg',
            ],
        ];

        return $localImages[$type] ?? $localImages['sedan'];
    }

    private function getImageUrlsForType(string $type): array
    {
        $imageUrls = [
            'sedan' => [
                'https://images.unsplash.com/photo-1555215695-3004980ad54e?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1549317336-206569e8475c?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1563720223185-11003d516935?w=800&h=600&fit=crop',
            ],
            'economy' => [
                'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1549317336-206569e8475c?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1563720223185-11003d516935?w=800&h=600&fit=crop',
            ],
            'suv' => [
                'https://images.unsplash.com/photo-1549317336-206569e8475c?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1563720223185-11003d516935?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1555215695-3004980ad54e?w=800&h=600&fit=crop',
            ],
            'luxury' => [
                'https://images.unsplash.com/photo-1563720223185-11003d516935?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1555215695-3004980ad54e?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1549317336-206569e8475c?w=800&h=600&fit=crop',
            ],
            'van' => [
                'https://images.unsplash.com/photo-1555215695-3004980ad54e?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1563720223185-11003d516935?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1549317336-206569e8475c?w=800&h=600&fit=crop',
            ],
        ];

        return $imageUrls[$type] ?? $imageUrls['sedan'];
    }
}
