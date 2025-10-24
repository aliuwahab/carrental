<?php

namespace App\Filament\Resources\BookingResource\Pages;

use App\Filament\Resources\BookingResource;
use Filament\Actions;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewBooking extends ViewRecord
{
    protected static string $resource = BookingResource::class;

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
                Section::make('Booking Information')
                    ->schema([
                        TextEntry::make('booking_code')
                            ->label('Booking ID')
                            ->copyable()
                            ->weight('bold'),
                        
                        TextEntry::make('user.name')
                            ->label('Customer'),
                        
                        TextEntry::make('vehicle.name')
                            ->label('Vehicle'),
                        
                        TextEntry::make('start_date')
                            ->label('Start Date')
                            ->date(),
                        
                        TextEntry::make('end_date')
                            ->label('End Date')
                            ->date(),
                        
                        TextEntry::make('rental_days')
                            ->label('Rental Days'),
                        
                        TextEntry::make('daily_rate')
                            ->label('Daily Rate')
                            ->money('USD'),
                        
                        TextEntry::make('total_amount')
                            ->label('Total Amount')
                            ->money('USD')
                            ->weight('bold'),
                    ])->columns(2),
                
                Section::make('Payment & Status')
                    ->schema([
                        TextEntry::make('status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'draft' => 'gray',
                                'pending' => 'warning',
                                'confirmed' => 'success',
                                'completed' => 'info',
                                'cancelled' => 'danger',
                                default => 'gray',
                            }),
                        
                        TextEntry::make('payment_method')
                            ->label('Payment Method')
                            ->badge()
                            ->color('info'),
                        
                        TextEntry::make('payment_reference')
                            ->label('Payment Reference')
                            ->copyable(),
                        
                        IconEntry::make('terms_accepted')
                            ->label('Terms Accepted')
                            ->boolean()
                            ->trueIcon('heroicon-o-check-circle')
                            ->falseIcon('heroicon-o-x-circle')
                            ->trueColor('success')
                            ->falseColor('danger'),
                        
                        TextEntry::make('paymentDetail.amount')
                            ->label('Paid Amount')
                            ->money('USD'),
                        
                        IconEntry::make('paymentDetail.verified_at')
                            ->label('Payment Verified')
                            ->boolean()
                            ->trueIcon('heroicon-o-check-circle')
                            ->falseIcon('heroicon-o-x-circle')
                            ->trueColor('success')
                            ->falseColor('danger'),
                    ])->columns(2),
                
                Section::make('Timestamps')
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Created At')
                            ->dateTime(),
                        
                        TextEntry::make('confirmed_at')
                            ->label('Confirmed At')
                            ->dateTime(),
                        
                        TextEntry::make('completed_at')
                            ->label('Completed At')
                            ->dateTime(),
                        
                        TextEntry::make('cancelled_at')
                            ->label('Cancelled At')
                            ->dateTime(),
                        
                        TextEntry::make('expires_at')
                            ->label('Expires At')
                            ->dateTime(),
                    ])->columns(2)
                    ->collapsible(),
                
                Section::make('Additional Information')
                    ->schema([
                        TextEntry::make('notes')
                            ->label('Notes')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
