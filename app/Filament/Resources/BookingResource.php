<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Filament\Resources\BookingResource\RelationManagers;
use App\Models\Booking;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
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
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('email')
                                    ->email()
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('phone')
                                    ->tel()
                                    ->maxLength(255),
                            ])
                            ->createOptionUsing(function (array $data): int {
                                return \App\Models\User::create($data)->getKey();
                            }),
                        
                        Forms\Components\Select::make('vehicle_id')
                            ->relationship('vehicle', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                    ])->columns(2),
                
                Section::make('Rental Details')
                    ->schema([
                        Forms\Components\DatePicker::make('start_date')
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Forms\Set $set, $state, Forms\Get $get) {
                                $endDate = $get('end_date');
                                if ($state && $endDate) {
                                    $start = \Carbon\Carbon::parse($state);
                                    $end = \Carbon\Carbon::parse($endDate);
                                    $days = $start->diffInDays($end) + 1;
                                    $set('rental_days', $days);
                                }
                            }),
                        
                        Forms\Components\DatePicker::make('end_date')
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Forms\Set $set, $state, Forms\Get $get) {
                                $startDate = $get('start_date');
                                if ($state && $startDate) {
                                    $start = \Carbon\Carbon::parse($startDate);
                                    $end = \Carbon\Carbon::parse($state);
                                    $days = $start->diffInDays($end) + 1;
                                    $set('rental_days', $days);
                                }
                            }),
                        
                        Forms\Components\TextInput::make('rental_days')
                            ->numeric()
                            ->disabled()
                            ->dehydrated()
                            ->label('Rental Days'),
                        
                        Forms\Components\TextInput::make('daily_rate')
                            ->numeric()
                            ->prefix('$')
                            ->required()
                            ->label('Daily Rate'),
                        
                        Forms\Components\TextInput::make('total_amount')
                            ->numeric()
                            ->prefix('$')
                            ->required()
                            ->label('Total Amount'),
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
                            ->label('Confirmed At'),
                        
                        Forms\Components\DateTimePicker::make('completed_at')
                            ->label('Completed At'),
                        
                        Forms\Components\DateTimePicker::make('cancelled_at')
                            ->label('Cancelled At'),
                        
                        Forms\Components\DateTimePicker::make('expires_at')
                            ->label('Expires At'),
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
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}
