<?php

namespace App\Filament\Admin\Resources\KategoriObats\Pages;

use App\Filament\Admin\Resources\KategoriObats\KategoriObatResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewKategoriObat extends ViewRecord
{
    protected static string $resource = KategoriObatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
