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
        }
    }
}
