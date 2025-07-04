<?php

namespace App\Filament\Resources\VendorCategoriesResource\Pages;

use App\Filament\Resources\VendorCategoriesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVendorCategories extends EditRecord
{
    protected static string $resource = VendorCategoriesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
