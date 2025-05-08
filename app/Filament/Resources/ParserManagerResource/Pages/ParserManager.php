<?php

namespace App\Filament\Resources\ParserManagerResource\Pages;

use App\Models\City;
use App\Models\Country;
use App\Models\ParserConfig;
use App\Models\Region;
use App\Models\State;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Resources\Pages\Page;
use App\Filament\Resources\ParserManagerResource;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

class ParserManager extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = ParserManagerResource::class;

    protected static string $view = 'filament.resources.parse-manager-resource.pages.parser-manager';

    protected static ?string $title = 'Запуск парсеров';
    protected static ?string $navigationLabel = 'Запуск парсеров';
    protected static ?string $modelLabel = 'Запуск парсеров';

    public ?array $data = [];

    public ?int $country_id = null;
    public ?int $state_id = null;
    public ?int $city_id = null;
    public $configs = null;
    public bool $parse_all = false;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Параметры парсинга')
                    ->schema([
                        Forms\Components\Select::make('country_id')
                            ->label('Страны')
                            ->options(Country::all()->pluck('name', 'id'))
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(function ($state) {
                                $this->state_id = null;
                                $this->city_id = null;
                            }),

                        Forms\Components\Select::make('state_id')
                            ->label('Области')
                            ->options(function (Forms\Get $get) {
                                if (!$this->country_id) {
                                    return [];
                                }
                                return State::where('country_id', $this->country_id)
                                    ->pluck('name', 'id');
                            })
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(fn() => $this->city_id = null),

                        Forms\Components\Select::make('city_id')
                            ->label('Города')
                            ->options(function () {
                                if (!$this->state_id) {
                                    return [];
                                }
                                return City::where('state_id', $this->state_id)
                                    ->pluck('name', 'id');
                            })
                            ->live()
                            ->searchable(),

                        Forms\Components\Checkbox::make('parse_all')
                            ->label('Парсить все доступные данные')
                            ->live(),
                    ]),

                Forms\Components\Section::make('Конфигурации для запуска')
                    ->schema([
                        Forms\Components\Grid::make()
                            ->schema(function (Forms\Get $get) {
                                $query = ParserConfig::query()->where('is_active', true);
                                if ($get('country_id')) {
                                    $query->where('country_id', $get('country_id'));
                                }
                                if ($get('state_id')) {
                                    $query->where('state_id', $get('state_id'));
                                }
                                if ($get('city_id')) {
                                    $query->where('city_id', $get('city_id'));
                                }
                                $this->configs = $query->get();

                                return $this->configs->map(function ($config) {
                                    $parsedAt = $config->last_parsed_at
                                        ? Carbon::parse($config->last_parsed_at)->format('d.m.Y H:i')
                                        : 'Парсинг еще не выполнялся';

                                    return Forms\Components\Placeholder::make('config_' . $config->id)
                                        ->label($config->name)
                                        ->content($parsedAt)
                                        ->columnSpanFull();
                                })->toArray();
                            })
                            ->disabled()
                            ->dehydrated()
                            ->columnSpanFull(),
                    ])
                    ->visible(function () {
                        return $this->country_id || $this->state_id || $this->city_id;
                    }),


                Forms\Components\Section::make('Действия')
                    ->schema([
                        \Filament\Forms\Components\Actions::make([
                            Forms\Components\Actions\Action::make('parse_selected')
                                ->label('Запустить парсер')
                                ->button()
                                ->color('success')
                                ->action(function (Forms\Get $get, $livewire) {
//                                    $data = $livewire->form->getState();

                                    if (!empty($this->configs)) {
                                        $livewire->dispatch('notify', [
                                            'type' => 'danger',
                                            'message' => 'Нет конфигураций для выбранных параметров',
                                        ]);
                                        return;
                                    }

                                    try {
//                                        Http::post(route('pharmacy.parse-all'), [
//                                            'configs' => $this->configs->pluck('id')->toArray()
//                                        ]);

                                        $livewire->dispatch('notify', [
                                            'type' => 'success',
                                            'message' => 'Парсер успешно запущен для выбранных данных',
                                        ]);

                                        return redirect()->route('pharmacy.parse-all', [
                                            'config_ids' => !empty($this->configs) ? $this->configs->pluck('id')->toArray() : null,
                                            'debug' => true,
                                            'parse_all' => $get('parse_all'),
                                        ]);
                                    } catch (\Exception $e) {
                                        $livewire->dispatch('notify', [
                                            'type' => 'danger',
                                            'message' => 'Ошибка при запуске парсера: ' . $e->getMessage(),
                                        ]);
                                    }
                                }),
                        ])->fullWidth(),
                    ]),
            ]);
    }

    public function parseSelected(): void
    {
        $data = $this->form->getState();
        // Логика парсинга
        $this->notify('success', 'Парсер запущен для выбранных данных');
    }

    public function parseAll(): void
    {
        // Логика полного парсинга
        $this->notify('success', 'Все парсеры запущены');
    }

//    public static function getPages(): array
//    {
//        return [
//            'index' => ParserManager::route('/'),
//        ];
//    }
}
