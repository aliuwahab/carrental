<?php

namespace App\Filament\Resources\VehicleResource\Pages;

use App\Filament\Resources\VehicleResource;
use App\Models\VehicleRate;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;

class CreateVehicle extends CreateRecord
{
    protected static string $resource = VehicleResource::class;
    
    protected $rateData = [];

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Store rate data for afterCreate
        $this->rateData = [
            'daily_rate' => $data['daily_rate'],
            'effective_from' => $data['effective_from'],
            'is_current' => $data['is_current'] ?? true,
        ];
        
        // Remove rate data from vehicle data
        unset($data['daily_rate'], $data['effective_from'], $data['is_current']);
        
        return $data;
    }

    protected function afterCreate(): void
    {
        $vehicle = $this->record;
        
        // Create the initial rate for this vehicle using stored rate data
        VehicleRate::create([
            'vehicle_id' => $vehicle->id,
            'daily_rate' => $this->rateData['daily_rate'],
            'effective_from' => $this->rateData['effective_from'],
            'is_current' => $this->rateData['is_current'],
        ]);
    }
}
