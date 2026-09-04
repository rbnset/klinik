<?php

namespace App\Filament\Admin\Resources\PembelianObats\Pages;

use App\Filament\Admin\Resources\PembelianObats\PembelianObatResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePembelianObat extends CreateRecord
{
    protected static string $resource = PembelianObatResource::class;

    protected function getRedirectUrl(): string
    {
        return PembelianObatResource::getUrl('view', ['record' => $this->record]);
    }
}
