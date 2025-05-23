<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ParserConfigResource\Pages;
use App\Filament\Resources\ParserConfigResource\RelationManagers;
use App\Models\City;
use App\Models\Country;
use App\Models\ParserConfig;
use App\Models\State;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ParserConfigResource extends Resource
{
    protected static ?string $model = ParserConfig::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'Конфигурация парсера';

    protected static ?string $pluralModelLabel = 'Конфигурации парсеров';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Основные настройки')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Название конфигурации')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('domain')
                            ->label('Домен сайта')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('example.com'),

                        Forms\Components\TextInput::make('url')
                            ->label('Базовый URL для парсинга')
                            ->required()
                            ->url()
                            ->maxLength(2000),

                        Forms\Components\Fieldset::make('География')
                            ->schema([
                                Forms\Components\Select::make('country_id')
                                    ->label('Страны')
                                    ->options(Country::all()->pluck('name', 'id'))
                                    ->searchable()
                                    ->live()
                                    ->afterStateUpdated(function ($set) {
                                        $set('state_id', null);
                                        $set('city_id', null);
                                    }),

                                Forms\Components\Select::make('state_id')
                                    ->label('Области')
                                    ->options(function (Forms\Get $get) {
                                        $countryId = $get('country_id');
                                        if (!$countryId) {
                                            return [];
                                        }

                                        return State::where('country_id', $countryId)
                                            ->pluck('name', 'id');
                                    })
                                    ->searchable()
                                    ->live()
                                    ->afterStateUpdated(fn ($set) => $set('city_id', null)),

                                Forms\Components\Select::make('city_id')
                                    ->label('Города')
                                    ->options(function (Forms\Get $get) {
                                        $stateId = $get('state_id');
                                        if (!$stateId) {
                                            return [];
                                        }
                                        return City::where('state_id', $stateId)
                                            ->pluck('name', 'id');
                                    })
                                    ->live()
                                    ->searchable(),
                            ]),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Активна')
                            ->default(true),

                        Forms\Components\Toggle::make('has_js')
                            ->label('Страница с js')
                            ->default(false),

                    ])->columns(2),

                Forms\Components\Section::make('GET Настройки')
                    ->schema([

                        Forms\Components\Toggle::make('has_get')
                            ->label('Использовать GET запрос')
                            ->reactive(),

                        Forms\Components\Fieldset::make('CSS селекторы')
                            ->schema([
                                Forms\Components\TextInput::make('selectors.filter')
                                    ->label('Фильтер')
                                    ->required(false),
                                Forms\Components\TextInput::make('selectors.name')
                                    ->label('Название аптеки'),

                                Forms\Components\TextInput::make('selectors.address')
                                    ->label('Адрес'),

                                Forms\Components\TextInput::make('selectors.phone')
                                    ->label('Телефон'),

                                Forms\Components\TextInput::make('selectors.working_hours')
                                    ->label('Часы работы'),

                                Forms\Components\TextInput::make('selectors.website')
                                    ->label('Вебсайт'),
                            ])
                            ->columns(2)
                            ->visible(fn($get) => $get('has_get')),

                        Forms\Components\Fieldset::make('Маппинг полей')
                            ->schema([
                                Forms\Components\TextInput::make('mapping.name')
                                    ->label('Поле названия')
                                    ->default('name'),

                                Forms\Components\TextInput::make('mapping.address')
                                    ->label('Поле адреса')
                                    ->default('address'),

                                Forms\Components\TextInput::make('mapping.phone')
                                    ->label('Поле телефона')
                                    ->default('phone'),

                                Forms\Components\TextInput::make('mapping.opening_hours')
                                    ->label('Поле часов работы')
                                    ->default('opening_hours'),

                                Forms\Components\TextInput::make('mapping.website')
                                    ->label('Поле вебсайта')
                                    ->default('website'),
                            ])
                            ->columns(2)
                            ->visible(fn($get) => $get('has_get')),
                    ]),

                Forms\Components\Section::make('POST Настройки')
                    ->schema([
                        Forms\Components\Toggle::make('has_post')
                            ->label('Использовать POST запрос')
                            ->reactive(),

                        Forms\Components\TextInput::make('post_url')
                            ->label('URL для POST запроса')
                            ->visible(fn($get) => $get('has_post')),


                        Forms\Components\Fieldset::make('Параметры запроса')
                            ->schema([
                                Forms\Components\Radio::make('params_to')
                                    ->label('Параметры передавать в:')
                                    ->options([
                                        'url' => 'url',
                                        'body' => 'body',
                                    ])->columns(2)
                                    ->columnSpan('full')
                                    ->reactive(),

                                Forms\Components\Radio::make('method')
                                    ->label('Метод запроса:')
                                    ->options([
                                        'GET' => 'GET',
                                        'POST' => 'POST',
                                    ])->columns(2)
                                    ->columnSpan('full'),
                            ])->visible(fn($get) => $get('has_post')),

                        Forms\Components\Fieldset::make('Параметры запроса')
                            ->schema([
                                Forms\Components\Radio::make('params_from')
                                    ->label('Параметры брать с:')
                                    ->options([
                                        'html' => 'HTML',
                                        'custom' => 'Кастомный',
                                        'vocabulary' => 'Словарь',
                                    ])
                                    ->live()
                                    ->columnSpan('full')
                                /*->reactive()*/,

                                Forms\Components\Select::make('vocabulary')
                                    ->label('Тип словаря')
                                    ->options([
                                        'states' => 'Области',
                                        'cities' => 'Города',
                                    ])
                                    ->required()
                                    ->visible(fn (Forms\Get $get) => $get('params_from') === 'vocabulary')  // Показывается только если выбран "vocabulary"
                                    ->columnSpan('full'),

                                Forms\Components\Fieldset::make('Параметры HTML')
                                    ->schema([
                                        Forms\Components\TextInput::make('ajax_selectors.form_selector')
                                            ->label('Контейнер селектора'),

                                        Forms\Components\TextInput::make('ajax_selectors.option_selector')
                                            ->label('Селектор вариантов'),

//                                        Forms\Components\TextInput::make('ajax_selectors.submit_selector')
//                                            ->label('Селектор кнопки отправки')
//                                            ->default('input[type="submit"], button[type="submit"]')
                                    ]),
//                                    ->visible(fn($get) => $get('params_from') === 'form'),

                                Forms\Components\KeyValue::make('post_params')
                                    ->label('Параметры POST запроса')
                                    ->keyLabel('Параметр')
                                    ->valueLabel('Значение')
                                    ->columnSpan('full')
//                                    ->visible(fn($get) => $get('params_from') === 'custom'),
                            ])->visible(fn($get) => $get('has_post')),


                        Forms\Components\Fieldset::make('Параметры ответа')
                            ->schema([
                                Forms\Components\Radio::make('response_form')
                                    ->label('Ответ в:')
                                    ->options([
                                        'html' => 'html',
                                        'json' => 'json',
                                    ])->columns(2)
                                    ->reactive(),

                                Forms\Components\Fieldset::make('CSS селекторы')
                                    ->schema([
                                        Forms\Components\TextInput::make('selectors.filter')
                                            ->label('Фильтер')
                                            ->required(false),
                                        Forms\Components\TextInput::make('selectors.name')
                                            ->label('Название аптеки'),

                                        Forms\Components\TextInput::make('selectors.address')
                                            ->label('Адрес'),

                                        Forms\Components\TextInput::make('selectors.phone')
                                            ->label('Телефон'),

                                        Forms\Components\TextInput::make('selectors.working_hours')
                                            ->label('Часы работы'),

                                        Forms\Components\TextInput::make('selectors.website')
                                            ->label('Вебсайт'),
                                    ])->columns(2)
                                    ->visible(fn($get) => $get('response_form') === 'html'),

                                Forms\Components\Fieldset::make('Маппинг полей')
                                    ->schema([
                                        Forms\Components\TextInput::make('mapping.name')
                                            ->label('Поле названия')
                                            ->default('name'),

                                        Forms\Components\TextInput::make('mapping.address')
                                            ->label('Поле адреса')
                                            ->default('address'),

                                        Forms\Components\TextInput::make('mapping.phone')
                                            ->label('Поле телефона')
                                            ->default('phone'),

                                        Forms\Components\TextInput::make('mapping.opening_hours')
                                            ->label('Поле часов работы')
                                            ->default('opening_hours'),

                                        Forms\Components\TextInput::make('mapping.website')
                                            ->label('Поле вебсайта')
                                            ->default('website'),
                                    ])->columns(2)
                                    ->visible(fn($get) => $get('response_form') === 'html'),

                                Forms\Components\Fieldset::make('JSON селекторы')
                                    ->schema([
                                        Forms\Components\TextInput::make('json_paths.name')
                                            ->label('Путь к названию'),

                                        Forms\Components\TextInput::make('json_paths.address')
                                            ->label('Путь к адресу'),

                                        Forms\Components\TextInput::make('json_paths.phone')
                                            ->label('Путь к телефону'),

                                        Forms\Components\TextInput::make('json_paths.opening_hours')
                                            ->label('Путь к часам работы'),

                                        Forms\Components\TextInput::make('json_paths.latitude')
                                            ->label('Путь к широте'),

                                        Forms\Components\TextInput::make('json_paths.longitude')
                                            ->label('Путь к долготе'),
                                    ])
                                    ->visible(fn($get) => $get('response_form') === 'json'),

                                Forms\Components\TextInput::make('json_path_to_array')
                                    ->label('Путь к массиву элементов')
                                    ->visible(fn($get) => $get('response_form') === 'json'),
                            ])->visible(fn($get) => $get('has_post'))
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Название')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('domain')
                    ->label('Домен')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Активна')
                    ->boolean(),

                Tables\Columns\TextColumn::make('last_parsed_at')
                    ->label('Последний парсинг')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Создана')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Обновлена')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('is_active')
                    ->query(fn(Builder $query): Builder => $query->where('is_active', true))
                    ->label('Только активные'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('parse')
                    ->label('Запустить парсинг')
                    ->icon('heroicon-o-arrow-path')
                    ->action(function (ParserConfig $record) {
                        // Здесь можно добавить вызов команды парсинга
                        dispatch(function () use ($record) {
                            \Artisan::call('parse:pharmacies', ['--config' => $record->id]);
                        });
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('activate')
                        ->label('Активировать')
                        ->icon('heroicon-o-check')
                        ->action(function ($records) {
                            $records->each->update(['is_active' => true]);
                        }),
                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Деактивировать')
                        ->icon('heroicon-o-x-mark')
                        ->action(function ($records) {
                            $records->each->update(['is_active' => false]);
                        }),
                ]),
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
            'index' => Pages\ListParserConfigs::route('/'),
            'create' => Pages\CreateParserConfig::route('/create'),
            'edit' => Pages\EditParserConfig::route('/{record}/edit'),
        ];
    }
}
