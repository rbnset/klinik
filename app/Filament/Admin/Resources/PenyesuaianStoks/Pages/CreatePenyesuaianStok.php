<?php

namespace App\Filament\Admin\Resources\PenyesuaianStoks\Pages;

use App\Filament\Admin\Resources\PenyesuaianStoks\PenyesuaianStokResource;
use App\Services\StokObatService;
use Filament\Resources\Pages\CreateRecord;

class CreatePenyesuaianStok extends CreateRecord
{
    protected static string $resource = PenyesuaianStokResource::class;

    protected function afterCreate(): void
    {
        app(StokObatService::class)->postingPenyesuaian($this->record);
    }

    protected function getRedirectUrl(): string
    {
        return PenyesuaianStokResource::getUrl('view', ['record' => $this->record]);
    }
}
