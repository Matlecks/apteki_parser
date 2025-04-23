<?php

namespace App\Filament\Resources\ParserConfigResource\Pages;

use App\Filament\Resources\ParserConfigResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditParserConfig extends EditRecord
{
    protected static string $resource = ParserConfigResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
