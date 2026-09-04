<?php

namespace App\Filament\Admin\Resources\PembelianObats\Pages;

use App\Filament\Admin\Resources\PembelianObats\PembelianObatResource;
use App\Notifications\PurchaseOrderDiterimaSupplier;
use Filament\Resources\Pages\CreateRecord;

class CreatePembelianObat extends CreateRecord
{
    protected static string $resource = PembelianObatResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['status'] = 'pending';
        $data['id_pengguna'] = auth()->id();
        return $data;
    }

    protected function afterCreate(): void
    {
        $supplier = $this->record->supplier;
        if ($supplier?->pengguna) {
            $supplier->pengguna->notify(new PurchaseOrderDiterimaSupplier($this->record->fresh('supplier')));
        }
    }

    protected function getRedirectUrl(): string
    {
        return PembelianObatResource::getUrl('view', ['record' => $this->record]);
    }
}
