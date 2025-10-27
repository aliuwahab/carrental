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
                        Infolists\Components\ImageEntry::make('main_image')
                            ->label('Main Image')
                            ->getStateUsing(function ($record) {
                                return $record->getMainImageUrl();
                            })
                            ->size(300),
                        
                        Infolists\Components\ImageEntry::make('gallery')
                            ->label('Gallery Images')
                            ->getStateUsing(function ($record) {
                                $galleryUrls = $record->getGalleryUrls();
                                return array_slice($galleryUrls, 0, 8);
                            })
                            ->size(150)
                            ->limit(8)
                            ->limitedRemainingText(),
                    ])->columns(1),
                
                Infolists\Components\Section::make('Status & Pricing')
                    ->schema([
                        Infolists\Components\TextEntry::make('currentRate.daily_rate')
                            ->money('USD')
                            ->label('Current Daily Rate')
                            ->weight('bold')
                            ->color('success'),
                        
                        Infolists\Components\IconEntry::make('is_active')
                            ->boolean()
                            ->label('Available for Rental')
                            ->trueIcon('heroicon-o-check-circle')
                            ->falseIcon('heroicon-o-x-circle')
                            ->trueColor('success')
                            ->falseColor('danger'),
                        
                        Infolists\Components\Actions::make([
                            Infolists\Components\Actions\Action::make('edit_current_rate')
                                ->label('Edit Current Rate')
                                ->icon('heroicon-o-pencil')
                                ->color('info')
                                ->url(fn ($record) => $record->currentRate ? route('filament.admin.resources.vehicle-rates.edit', $record->currentRate) : '#')
                                ->openUrlInNewTab()
                                ->visible(fn ($record) => $record->currentRate !== null),
                        ])
                        ->columnSpanFull()
                        ->alignCenter(),
                    ])->columns(2),
                
                Infolists\Components\Section::make('Rate History')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('vehicleRates')
                            ->label('All Rates for This Vehicle')
                            ->schema([
                                Infolists\Components\TextEntry::make('daily_rate')
                                    ->money('USD')
                                    ->label('Daily Rate')
                                    ->weight(fn ($record) => $record->is_current ? 'bold' : 'normal')
                                    ->color(fn ($record) => $record->is_current ? 'success' : 'gray'),
                                
                                Infolists\Components\TextEntry::make('effective_from')
                                    ->label('Effective From')
                                    ->dateTime()
                                    ->weight(fn ($record) => $record->is_current ? 'bold' : 'normal'),
                                
                                Infolists\Components\TextEntry::make('effective_to')
                                    ->label('Effective To')
                                    ->dateTime()
                                    ->placeholder('Currently Active')
                                    ->weight(fn ($record) => $record->is_current ? 'bold' : 'normal'),
                                
                                Infolists\Components\TextEntry::make('is_current')
                                    ->label('Status')
                                    ->formatStateUsing(fn ($state) => $state ? 'CURRENT RATE' : 'Historical')
                                    ->color(fn ($state) => $state ? 'success' : 'gray')
                                    ->weight(fn ($record) => $record->is_current ? 'bold' : 'normal'),
                                
                                Infolists\Components\TextEntry::make('created_at')
                                    ->label('Created')
                                    ->dateTime()
                                    ->weight(fn ($record) => $record->is_current ? 'bold' : 'normal'),
                                
                                Infolists\Components\Actions::make([
                                    Infolists\Components\Actions\Action::make('edit_rate')
                                        ->label('Edit Rate')
                                        ->icon('heroicon-o-pencil')
                                        ->color('info')
                                        ->url(fn ($record) => route('filament.admin.resources.vehicle-rates.edit', $record))
                                        ->openUrlInNewTab(),
                                ]),
                            ])
                            ->columns(6),
                    ])
                    ->collapsible(),
            ]);
    }
}
