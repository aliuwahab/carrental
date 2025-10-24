<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Filament\Resources\BookingResource\RelationManagers;
use App\Models\Booking;
use App\Models\PaymentDetail;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationLabel = 'Bookings';
    protected static ?string $modelLabel = 'Booking';
    protected static ?string $pluralModelLabel = 'Bookings';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Booking Information')
                    ->schema([
                        Forms\Components\TextInput::make('booking_code')
                            ->required()
                            ->disabled()
                            ->dehydrated()
                            ->default(fn () => 'BK' . strtoupper(uniqid())),
                        
                        Forms\Components\Select::make('user_id')
                            ->label('Customer')
                            ->relationship('user', 'name')
                            ->disabled()
                            ->dehydrated(false),
                        
                        Forms\Components\Select::make('vehicle_id')
                            ->label('Vehicle')
                            ->relationship('vehicle', 'name')
                            ->disabled()
                            ->dehydrated(false),
                    ])->columns(2),
                
                Section::make('Rental Details')
                    ->schema([
                        Forms\Components\DatePicker::make('start_date')
                            ->label('Start Date')
                            ->disabled()
                            ->dehydrated(false),
                        
                        Forms\Components\DatePicker::make('end_date')
                            ->label('End Date')
                            ->disabled()
                            ->dehydrated(false),
                        
                        Forms\Components\TextInput::make('rental_days')
                            ->label('Rental Days')
                            ->disabled()
                            ->dehydrated(false),
                        
                        Forms\Components\TextInput::make('daily_rate')
                            ->label('Daily Rate')
                            ->disabled()
                            ->dehydrated(false),
                        
                        Forms\Components\TextInput::make('total_amount')
                            ->label('Total Amount')
                            ->disabled()
                            ->dehydrated(false),
                    ])->columns(2),
                
                Section::make('Payment & Status')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'pending' => 'Pending',
                                'confirmed' => 'Confirmed',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required()
                            ->default('pending'),
                        
                        Forms\Components\Select::make('payment_method')
                            ->options([
                                'paypal' => 'PayPal',
                                'mobile_money' => 'Mobile Money',
                                'bank_transfer' => 'Bank Transfer',
                                'cash' => 'Cash',
                            ])
                            ->searchable(),
                        
                        Forms\Components\TextInput::make('payment_reference')
                            ->maxLength(255)
                            ->label('Payment Reference'),
                        
                        Forms\Components\Toggle::make('terms_accepted')
                            ->label('Terms Accepted')
                            ->default(false),
                        
                        Forms\Components\DateTimePicker::make('terms_accepted_at')
                            ->label('Terms Accepted At'),
                    ])->columns(2),
                
                Section::make('Timestamps')
                    ->schema([
                        Forms\Components\DateTimePicker::make('confirmed_at')
                            ->label('Confirmed At')
                            ->disabled()
                            ->dehydrated(false),
                        
                        Forms\Components\DateTimePicker::make('completed_at')
                            ->label('Completed At')
                            ->disabled()
                            ->dehydrated(false),
                        
                        Forms\Components\DateTimePicker::make('cancelled_at')
                            ->label('Cancelled At')
                            ->disabled()
                            ->dehydrated(false),
                        
                        Forms\Components\DateTimePicker::make('expires_at')
                            ->label('Expires At')
                            ->disabled()
                            ->dehydrated(false),
                    ])->columns(2)
                    ->collapsible(),
                
                Section::make('Additional Information')
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->maxLength(65535)
                            ->rows(3)
                            ->label('Additional Notes'),
                    ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('booking_code')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold')
                    ->label('Booking ID'),
                
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->sortable()
                    ->label('Customer'),
                
                Tables\Columns\TextColumn::make('vehicle.name')
                    ->searchable()
                    ->sortable()
                    ->label('Vehicle'),
                
                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->sortable()
                    ->label('Start Date'),
                
                Tables\Columns\TextColumn::make('end_date')
                    ->date()
                    ->sortable()
                    ->label('End Date'),
                
                Tables\Columns\TextColumn::make('rental_days')
                    ->numeric()
                    ->sortable()
                    ->label('Days'),
                
                Tables\Columns\TextColumn::make('total_amount')
                    ->money('USD')
                    ->sortable()
                    ->label('Total'),
                
                Tables\Columns\TextColumn::make('status')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'pending' => 'warning',
                        'confirmed' => 'success',
                        'completed' => 'info',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),
                
                Tables\Columns\TextColumn::make('payment_method')
                    ->searchable()
                    ->badge()
                    ->color('info')
                    ->label('Payment'),
                
                Tables\Columns\TextColumn::make('paymentDetail.amount')
                    ->money('USD')
                    ->label('Paid Amount')
                    ->sortable(),
                
                Tables\Columns\IconColumn::make('paymentDetail.verified_at')
                    ->boolean()
                    ->label('Payment Verified')
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                
                Tables\Columns\IconColumn::make('paymentDetail.payment_info.receipt_path')
                    ->boolean()
                    ->label('Receipt')
                    ->trueIcon('heroicon-o-document')
                    ->falseIcon('heroicon-o-document-text')
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->getStateUsing(fn ($record) => !empty($record->paymentDetail?->payment_info['receipt_path'] ?? null)),
                
                Tables\Columns\IconColumn::make('terms_accepted')
                    ->boolean()
                    ->label('Terms')
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                
                Tables\Columns\TextColumn::make('confirmed_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Confirmed'),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ]),
                
                Tables\Filters\SelectFilter::make('payment_method')
                    ->options([
                        'paypal' => 'PayPal',
                        'mobile_money' => 'Mobile Money',
                        'bank_transfer' => 'Bank Transfer',
                        'cash' => 'Cash',
                    ]),
                
                Tables\Filters\Filter::make('terms_accepted')
                    ->query(fn (Builder $query): Builder => $query->where('terms_accepted', true))
                    ->label('Terms Accepted'),
                
                Tables\Filters\Filter::make('confirmed')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('confirmed_at'))
                    ->label('Confirmed Bookings'),
                
                Tables\Filters\Filter::make('expired')
                    ->query(fn (Builder $query): Builder => $query->where('expires_at', '<', now()))
                    ->label('Expired Bookings'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                
                Tables\Actions\Action::make('confirm_booking')
                    ->label('Confirm Booking')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Booking $record): bool => $record->status === 'pending')
                    ->form([
                        Forms\Components\Section::make('Payment Details')
                            ->schema([
                                Forms\Components\TextInput::make('payment_reference')
                                    ->label('Payment Reference')
                                    ->required()
                                    ->maxLength(255)
                                    ->helperText('Enter the payment reference provided by the customer'),
                                
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
                                    ->default(fn (Booking $record): float => $record->total_amount),
                                
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
                    ->action(function (Booking $record, array $data): void {
                        // Create payment detail
                        $paymentDetail = $record->paymentDetail()->create([
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
                        $record->update([
                            'status' => 'confirmed',
                            'confirmed_at' => now(),
                        ]);
                        
                        // Send notification to user
                        $record->user->notify(new \App\Notifications\BookingConfirmed($record));
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Confirm Booking Payment')
                    ->modalDescription('Please enter the payment details to confirm this booking.')
                    ->modalSubmitActionLabel('Confirm Booking'),
                
                Tables\Actions\Action::make('view_receipt')
                    ->label('View Receipt')
                    ->icon('heroicon-o-document')
                    ->color('info')
                    ->visible(fn (Booking $record): bool => 
                        $record->paymentDetail && 
                        !empty($record->paymentDetail->payment_info['receipt_path'] ?? null)
                    )
                    ->url(fn (Booking $record): string => 
                        \Storage::url($record->paymentDetail->payment_info['receipt_path'])
                    )
                    ->openUrlInNewTab(),
                
                Tables\Actions\Action::make('cancel_booking')
                    ->label('Cancel Booking')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (Booking $record): bool => in_array($record->status, ['pending', 'draft']))
                    ->requiresConfirmation()
                    ->action(function (Booking $record): void {
                        $record->update([
                            'status' => 'cancelled',
                            'cancelled_at' => now(),
                        ]);
                    }),
                
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'view' => Pages\ViewBooking::route('/{record}'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}
