<?php

namespace App\Filament\Resources\VehicleResource\Pages;

use App\Filament\Resources\VehicleResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class ViewVehicle extends ViewRecord
{
    protected static string $resource = VehicleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Vehicle Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('name')
                            ->label('Vehicle Name'),
                        
                        Infolists\Components\TextEntry::make('type')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'economy' => 'gray',
                                'sedan' => 'blue',
                                'suv' => 'green',
                                'van' => 'orange',
                                'luxury' => 'purple',
                                default => 'gray',
                            }),
                        
                        Infolists\Components\TextEntry::make('description')
                            ->columnSpanFull(),
                    ])->columns(2),
                
                Infolists\Components\Section::make('Specifications')
                    ->schema([
                        Infolists\Components\TextEntry::make('seats')
                            ->label('Seats'),
                        
                        Infolists\Components\TextEntry::make('transmission')
                            ->badge()
                            ->color('info'),
                        
                        Infolists\Components\TextEntry::make('fuel_type')
                            ->badge()
                            ->color('success'),
                        
                        Infolists\Components\TextEntry::make('features')
                            ->label('Features')
                            ->listWithLineBreaks()
                            ->columnSpanFull(),
                    ])->columns(3),
                
                Infolists\Components\Section::make('Media')
                    ->schema([
                        Infolists\Components\SpatieMediaLibraryImageEntry::make('main_image')
                            ->collection('main_image')
                            ->label('Main Image')
                            ->size(300),
                        
                        Infolists\Components\SpatieMediaLibraryImageEntry::make('gallery')
                            ->collection('gallery')
                            ->label('Gallery Images')
                            ->size(150)
                            ->limit(8)
                            ->limitedRemainingText(),
                    ])->columns(1),
                
                Infolists\Components\Section::make('Status & Pricing')
                    ->schema([
                        Infolists\Components\TextEntry::make('currentRate.daily_rate')
                            ->money('USD')
                            ->label('Daily Rate'),
                        
                        Infolists\Components\IconEntry::make('is_active')
                            ->boolean()
                            ->label('Available for Rental')
                            ->trueIcon('heroicon-o-check-circle')
                            ->falseIcon('heroicon-o-x-circle')
                            ->trueColor('success')
                            ->falseColor('danger'),
                    ])->columns(2),
            ]);
    }
}
