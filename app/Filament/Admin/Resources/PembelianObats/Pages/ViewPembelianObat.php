<?php

namespace App\Filament\Admin\Resources\PembelianObats\Pages;

use App\Filament\Admin\Resources\PembelianObats\PembelianObatResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPembelianObat extends ViewRecord
{
    protected static string $resource = PembelianObatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
