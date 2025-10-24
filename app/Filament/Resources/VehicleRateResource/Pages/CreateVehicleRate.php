<?php

namespace App\Filament\Resources\VehicleRateResource\Pages;

use App\Filament\Resources\VehicleRateResource;
use App\Models\VehicleRate;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateVehicleRate extends CreateRecord
{
    protected static string $resource = VehicleRateResource::class;

    protected function afterCreate(): void
    {
        // If this rate is set as current, deactivate other current rates for the same vehicle
        if ($this->record->is_current) {
            VehicleRate::where('vehicle_id', $this->record->vehicle_id)
                ->where('id', '!=', $this->record->id)
                ->where('is_current', true)
                ->update(['is_current' => false]);
        }
    }
}
