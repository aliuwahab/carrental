<?php

namespace App\Filament\Resources\BookingResource\Pages;

use App\Filament\Resources\BookingResource;
use Filament\Actions;
use Filament\Forms;
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
            Actions\Action::make('confirm_booking')
                ->label('Confirm Booking')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn (): bool => $this->record->status === 'pending')
                ->form([
                    Forms\Components\Section::make('Payment Details')
                        ->schema([
                            Forms\Components\TextInput::make('payment_reference')
                                ->label('Payment Reference')
                                ->required()
                                ->maxLength(255)
                                ->helperText('Transaction ID or reference number'),
                            
                            Forms\Components\Select::make('payment_method')
                                ->label('Payment Method')
                                ->options([
                                    'paypal' => 'PayPal',
                                    'mobile_money' => 'Mobile Money',
                                    'bank_transfer' => 'Bank Transfer',
                                    'cash' => 'Cash',
                                ])
                                ->required(),
                            
                            Forms\Components\TextInput::make('payment_amount')
                                ->label('Payment Amount')
                                ->numeric()
                                ->prefix('$')
                                ->required()
                                ->default(fn ($record): float => $record->total_amount),
                            
                            Forms\Components\Textarea::make('payment_notes')
                                ->label('Payment Notes')
                                ->rows(3)
                                ->helperText('Any additional notes about the payment'),
                            
                            Forms\Components\FileUpload::make('payment_receipt')
                                ->label('Payment Receipt')
                                ->acceptedFileTypes(['application/pdf', 'image/*'])
                                ->maxSize(10240) // 10MB
                                ->directory('payment-receipts')
                                ->visibility('private')
                                ->helperText('Upload a photo or PDF of the payment receipt')
                                ->columnSpanFull(),
                        ])->columns(2),
                ])
                ->action(function (array $data): void {
                    // Create payment detail
                    $this->record->paymentDetail()->create([
                        'payment_method' => $data['payment_method'],
                        'amount' => $data['payment_amount'],
                        'transaction_id' => $data['payment_reference'],
                        'payment_info' => [
                            'notes' => $data['payment_notes'] ?? null,
                            'reference' => $data['payment_reference'],
                            'receipt_path' => $data['payment_receipt'] ?? null,
                        ],
                        'paid_at' => now(),
                        'verified_at' => now(),
                    ]);
                    
                    // Update booking status
                    $this->record->update([
                        'status' => 'confirmed',
                        'confirmed_at' => now(),
                    ]);
                    
                    // Send notification to user
                    $this->record->user->notify(new \App\Notifications\BookingConfirmed($this->record));
                })
                ->requiresConfirmation()
                ->modalHeading('Confirm Booking Payment')
                ->modalDescription('Please enter the payment details to confirm this booking.')
                ->modalSubmitActionLabel('Confirm Booking'),
            
            Actions\Action::make('cancel_booking')
                ->label('Cancel Booking')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->visible(fn (): bool => in_array($this->record->status, ['pending', 'draft']))
                ->requiresConfirmation()
                ->modalHeading('Cancel Booking')
                ->modalDescription('Are you sure you want to cancel this booking? This action cannot be undone.')
                ->action(function (): void {
                    $this->record->update([
                        'status' => 'cancelled',
                        'cancelled_at' => now(),
                    ]);
                }),
            
            Actions\Action::make('extend_booking')
                ->label('Extend Booking')
                ->icon('heroicon-o-calendar-days')
                ->color('info')
                ->visible(fn (): bool => $this->record->status === 'confirmed')
                ->form([
                    Forms\Components\DatePicker::make('new_end_date')
                        ->label('New End Date')
                        ->required()
                        ->minDate($this->record->end_date)
                        ->helperText('Select a new end date for the booking'),
                    
                    Forms\Components\Textarea::make('extension_reason')
                        ->label('Reason for Extension')
                        ->rows(3)
                        ->required()
                        ->helperText('Please provide a reason for extending this booking'),
                ])
                ->action(function (array $data): void {
                    $newEndDate = \Carbon\Carbon::parse($data['new_end_date']);
                    $additionalDays = $this->record->end_date->diffInDays($newEndDate);
                    $additionalAmount = $additionalDays * $this->record->daily_rate;
                    
                    $this->record->update([
                        'end_date' => $newEndDate,
                        'rental_days' => $this->record->rental_days + $additionalDays,
                        'total_amount' => $this->record->total_amount + $additionalAmount,
                        'notes' => $this->record->notes . "\n\nExtended on " . now()->format('M d, Y') . ": " . $data['extension_reason'],
                    ]);
                })
                ->requiresConfirmation()
                ->modalHeading('Extend Booking')
                ->modalDescription('Extend the booking end date. Additional charges will be calculated automatically.')
                ->modalSubmitActionLabel('Extend Booking'),
            
            Actions\DeleteAction::make()
                ->visible(fn (): bool => in_array($this->record->status, ['draft', 'cancelled'])),
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
