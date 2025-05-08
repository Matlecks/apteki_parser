<?php

namespace App\Filament\Resources\ParserManagerResource\Pages;

use App\Filament\Resources\ParserManagerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListParserManagers extends ListRecords
{
    protected static string $resource = ParserManagerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
