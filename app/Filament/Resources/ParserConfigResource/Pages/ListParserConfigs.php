<?php

namespace App\Filament\Resources\ParserConfigResource\Pages;

use App\Filament\Resources\ParserConfigResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListParserConfigs extends ListRecords
{
    protected static string $resource = ParserConfigResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
