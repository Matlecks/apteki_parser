<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CountryResource\Pages;
use App\Filament\Resources\CountryResource\RelationManagers;
use App\Models\Country;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CountryResource extends Resource
{
    protected static ?string $model = Country::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'География';

    protected static ?string $modelLabel = 'Страна';

    protected static ?string $pluralModelLabel = 'Страны';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(100),

                Forms\Components\TextInput::make('iso3')
                    ->maxLength(3),

                Forms\Components\TextInput::make('numeric_code')
                    ->maxLength(3),

                Forms\Components\TextInput::make('iso2')
                    ->maxLength(2),

                Forms\Components\TextInput::make('phonecode'),

                Forms\Components\TextInput::make('capital'),

                Forms\Components\TextInput::make('currency'),

                Forms\Components\TextInput::make('currency_name'),

                Forms\Components\TextInput::make('currency_symbol'),

                Forms\Components\TextInput::make('tld'),

                Forms\Components\TextInput::make('native'),

                Forms\Components\Select::make('region_id')
                    ->relationship('region', 'name'),

                Forms\Components\Select::make('subregion_id')
                    ->relationship('subRegion', 'name'),

                Forms\Components\TextInput::make('nationality'),

                Forms\Components\Textarea::make('timezones'),

                Forms\Components\Textarea::make('translations'),

                Forms\Components\TextInput::make('latitude')
                    ->numeric(),

                Forms\Components\TextInput::make('longitude')
                    ->numeric(),

                Forms\Components\TextInput::make('emoji')
                    ->maxLength(191),

                Forms\Components\TextInput::make('emojiU')
                    ->maxLength(191),

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
                Tables\Columns\TextColumn::make('iso2'),
                Tables\Columns\TextColumn::make('capital'),
                Tables\Columns\TextColumn::make('region.name'),
                Tables\Columns\BooleanColumn::make('flag'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('region')
                    ->relationship('region', 'name'),
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
            'index' => Pages\ListCountries::route('/'),
            'create' => Pages\CreateCountry::route('/create'),
            'edit' => Pages\EditCountry::route('/{record}/edit'),
        ];
    }
}
