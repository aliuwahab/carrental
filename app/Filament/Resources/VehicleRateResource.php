<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VehicleRateResource\Pages;
use App\Filament\Resources\VehicleRateResource\RelationManagers;
use App\Models\VehicleRate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VehicleRateResource extends Resource
{
    protected static ?string $model = VehicleRate::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationLabel = 'Vehicle Rates';
    protected static ?string $modelLabel = 'Vehicle Rate';
    protected static ?string $pluralModelLabel = 'Vehicle Rates';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Rate Information')
                    ->schema([
                        Forms\Components\Select::make('vehicle_id')
                            ->relationship('vehicle', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Forms\Set $set, $state) {
                                if ($state) {
                                    // Set effective_from to now when vehicle is selected
                                    $set('effective_from', now());
                                }
                            }),
                        
                        Forms\Components\TextInput::make('daily_rate')
                            ->required()
                            ->numeric()
                            ->prefix('$')
                            ->minValue(0)
                            ->step(0.01)
                            ->label('Daily Rate'),
                        
                        Forms\Components\DateTimePicker::make('effective_from')
                            ->required()
                            ->default(now())
                            ->label('Effective From'),
                        
                        Forms\Components\Toggle::make('is_current')
                            ->required()
                            ->default(true)
                            ->label('Current Rate')
                            ->helperText('Only one rate can be current per vehicle'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('vehicle.name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->label('Vehicle'),
                
                Tables\Columns\TextColumn::make('daily_rate')
                    ->money('USD')
                    ->sortable()
                    ->weight('bold')
                    ->label('Daily Rate'),
                
                Tables\Columns\IconColumn::make('is_current')
                    ->boolean()
                    ->label('Current')
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('gray'),
                
                Tables\Columns\TextColumn::make('effective_from')
                    ->dateTime()
                    ->sortable()
                    ->label('Effective From'),
                
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
                Tables\Filters\SelectFilter::make('vehicle_id')
                    ->relationship('vehicle', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Vehicle'),
                
                Tables\Filters\TernaryFilter::make('is_current')
                    ->label('Current Rate')
                    ->placeholder('All rates')
                    ->trueLabel('Current only')
                    ->falseLabel('Historical only'),
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
            ->defaultSort('effective_from', 'desc');
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
            'index' => Pages\ListVehicleRates::route('/'),
            'create' => Pages\CreateVehicleRate::route('/create'),
            'edit' => Pages\EditVehicleRate::route('/{record}/edit'),
        ];
    }
}
