<?php

namespace App\Filament\Resources\VendorCategoriesResource\Pages;

use App\Filament\Resources\VendorCategoriesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVendorCategories extends ListRecords
{
    protected static string $resource = VendorCategoriesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
