<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VehicleResource\Pages;
use App\Filament\Resources\VehicleResource\RelationManagers;
use App\Models\Vehicle;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VehicleResource extends Resource
{
    protected static ?string $model = Vehicle::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';
    
    protected static ?string $navigationLabel = 'Vehicles';
    
    protected static ?string $modelLabel = 'Vehicle';
    
    protected static ?string $pluralModelLabel = 'Vehicles';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Vehicle Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', \Illuminate\Support\Str::slug($state)) : null),
                        
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(Vehicle::class, 'slug', ignoreRecord: true),
                        
                        Forms\Components\Select::make('type')
                            ->required()
                            ->options([
                                'economy' => 'Economy',
                                'sedan' => 'Sedan',
                                'suv' => 'SUV',
                                'van' => 'Van',
                                'luxury' => 'Luxury',
                            ])
                            ->searchable(),
                        
                        Forms\Components\Textarea::make('description')
                            ->columnSpanFull()
                            ->rows(3),
                    ])->columns(2),
                
                Forms\Components\Section::make('Vehicle Specifications')
                    ->schema([
                        Forms\Components\TextInput::make('seats')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(20),
                        
                        Forms\Components\Select::make('transmission')
                            ->required()
                            ->options([
                                'manual' => 'Manual',
                                'automatic' => 'Automatic',
                                'semi-automatic' => 'Semi-Automatic',
                            ]),
                        
                        Forms\Components\Select::make('fuel_type')
                            ->required()
                            ->options([
                                'gasoline' => 'Gasoline',
                                'diesel' => 'Diesel',
                                'hybrid' => 'Hybrid',
                                'electric' => 'Electric',
                            ]),
                        
                        Forms\Components\TagsInput::make('features')
                            ->placeholder('Add vehicle features (e.g., GPS, Bluetooth, Air Conditioning)')
                            ->columnSpanFull(),
                    ])->columns(2),
                
                Forms\Components\Section::make('Media & Status')
                    ->schema([
                        Forms\Components\SpatieMediaLibraryFileUpload::make('main_image')
                            ->collection('main_image')
                            ->image()
                            ->visibility('public')
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '16:9',
                                '4:3',
                                '1:1',
                            ])
                            ->helperText('Upload the main vehicle image. This will be displayed as the primary image on the website.'),
                        
                        Forms\Components\SpatieMediaLibraryFileUpload::make('gallery')
                            ->collection('gallery')
                            ->image()
                            ->multiple()
                            ->visibility('public')
                            ->imageEditor()
                            ->reorderable()
                            ->appendFiles()
                            ->helperText('Upload multiple images for the vehicle gallery. Users can browse through these images on the vehicle detail page.'),
                        
                        Forms\Components\Toggle::make('is_active')
                            ->required()
                            ->default(true)
                            ->label('Available for Rental')
                            ->helperText('Toggle to make this vehicle available for rental on the website.'),
                    ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('main_image')
                    ->collection('main_image')
                    ->square()
                    ->size(60),
                
                Tables\Columns\TextColumn::make('gallery_count')
                    ->label('Gallery')
                    ->getStateUsing(function ($record) {
                        return $record->getMedia('gallery')->count() . ' images';
                    })
                    ->badge()
                    ->color('info'),
                
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                
                Tables\Columns\TextColumn::make('type')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'economy' => 'gray',
                        'sedan' => 'blue',
                        'suv' => 'green',
                        'van' => 'orange',
                        'luxury' => 'purple',
                        default => 'gray',
                    }),
                
                Tables\Columns\TextColumn::make('seats')
                    ->numeric()
                    ->sortable()
                    ->label('Seats'),
                
                Tables\Columns\TextColumn::make('transmission')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),
                
                Tables\Columns\TextColumn::make('fuel_type')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('success'),
                
                Tables\Columns\TextColumn::make('currentRate.daily_rate')
                    ->money('USD')
                    ->sortable()
                    ->label('Daily Rate'),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active')
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                
                Tables\Columns\TextColumn::make('bookings_count')
                    ->counts('bookings')
                    ->label('Bookings')
                    ->sortable(),
                
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
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'economy' => 'Economy',
                        'sedan' => 'Sedan',
                        'suv' => 'SUV',
                        'van' => 'Van',
                        'luxury' => 'Luxury',
                    ]),
                
                Tables\Filters\SelectFilter::make('transmission')
                    ->options([
                        'manual' => 'Manual',
                        'automatic' => 'Automatic',
                        'semi-automatic' => 'Semi-Automatic',
                    ]),
                
                Tables\Filters\SelectFilter::make('fuel_type')
                    ->options([
                        'gasoline' => 'Gasoline',
                        'diesel' => 'Diesel',
                        'hybrid' => 'Hybrid',
                        'electric' => 'Electric',
                    ]),
                
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status')
                    ->placeholder('All vehicles')
                    ->trueLabel('Active only')
                    ->falseLabel('Inactive only'),
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
            'index' => Pages\ListVehicles::route('/'),
            'create' => Pages\CreateVehicle::route('/create'),
            'view' => Pages\ViewVehicle::route('/{record}'),
            'edit' => Pages\EditVehicle::route('/{record}/edit'),
        ];
    }
}
