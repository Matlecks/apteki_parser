<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StateResource\Pages;
use App\Filament\Resources\StateResource\RelationManagers;
use App\Models\State;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StateResource extends Resource
{
    protected static ?string $model = State::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'География';

    protected static ?string $modelLabel = 'Область';

    protected static ?string $pluralModelLabel = 'Области';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('country_id')
                    ->relationship('country', 'name')
                    ->required(),

                Forms\Components\TextInput::make('country_code')
                    ->required()
                    ->maxLength(2),

                Forms\Components\TextInput::make('fips_code'),

                Forms\Components\TextInput::make('iso2'),

                Forms\Components\TextInput::make('type')
                    ->maxLength(191),

                Forms\Components\TextInput::make('level')
                    ->numeric(),

                Forms\Components\TextInput::make('parent_id')
                    ->numeric(),

                Forms\Components\TextInput::make('latitude')
                    ->numeric(),

                Forms\Components\TextInput::make('longitude')
                    ->numeric(),

                Forms\Components\Toggle::make('flag')
                    ->default(true),

                Forms\Components\TextInput::make('wikiDataId'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('country.name'),
                Tables\Columns\TextColumn::make('country_code'),
                Tables\Columns\BooleanColumn::make('flag'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('country')
                    ->relationship('country', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
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
            'index' => Pages\ListStates::route('/'),
            'create' => Pages\CreateState::route('/create'),
            'edit' => Pages\EditState::route('/{record}/edit'),
        ];
    }
}
