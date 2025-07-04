<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Filament\Resources\TransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTransaction extends CreateRecord
{
    protected static string $resource = TransactionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $customerData = $data['customer'];
        $customer = \App\Models\Customer::create($customerData);

        $data['customer_id'] = $customer->id;
        unset($data['customer']);

        return $data;
    }

}
