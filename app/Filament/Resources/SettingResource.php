<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Filament\Resources\SettingResource\RelationManagers;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Settings';
    protected static ?string $modelLabel = 'Setting';
    protected static ?string $pluralModelLabel = 'Settings';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Setting Information')
                    ->schema([
                        Forms\Components\TextInput::make('key')
                            ->required()
                            ->maxLength(255)
                            ->disabled(fn ($record) => $record !== null)
                            ->helperText('Setting key (cannot be changed after creation)'),
                        
                        Forms\Components\Select::make('group')
                            ->options([
                                'contact' => 'Contact',
                                'payment' => 'Payment',
                                'confirmation' => 'Confirmation',
                                'general' => 'General',
                            ])
                            ->required(),
                        
                        Forms\Components\Select::make('type')
                            ->options([
                                'text' => 'Text',
                                'textarea' => 'Textarea',
                                'url' => 'URL',
                                'phone' => 'Phone',
                                'email' => 'Email',
                            ])
                            ->required(),
                        
                        Forms\Components\Textarea::make('description')
                            ->maxLength(1000)
                            ->rows(3),
                    ])
                    ->columns(2),
                
                Forms\Components\Section::make('Setting Value')
                    ->schema([
                        Forms\Components\Textarea::make('value')
                            ->required()
                            ->rows(4)
                            ->helperText('The actual value for this setting'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('group')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'contact' => 'success',
                        'payment' => 'warning',
                        'confirmation' => 'info',
                        'general' => 'gray',
                        default => 'gray',
                    }),
                
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color('gray'),
                
                Tables\Columns\TextColumn::make('value')
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 50 ? $state : null;
                    }),
                
                Tables\Columns\TextColumn::make('description')
                    ->limit(30)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 30 ? $state : null;
                    }),
                
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('group')
                    ->options([
                        'contact' => 'Contact',
                        'payment' => 'Payment',
                        'confirmation' => 'Confirmation',
                        'general' => 'General',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('group', 'asc');
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
            'index' => Pages\ListSettings::route('/'),
            'create' => Pages\CreateSetting::route('/create'),
            'edit' => Pages\EditSetting::route('/{record}/edit'),
        ];
    }
}
