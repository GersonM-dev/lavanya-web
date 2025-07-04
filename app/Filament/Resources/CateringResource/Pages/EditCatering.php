<?php

namespace App\Filament\Resources\CateringResource\Pages;

use App\Filament\Resources\CateringResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCatering extends EditRecord
{
    protected static string $resource = CateringResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
