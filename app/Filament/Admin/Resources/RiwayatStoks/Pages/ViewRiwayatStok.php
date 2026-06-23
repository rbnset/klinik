<?php

namespace App\Filament\Admin\Resources\RiwayatStoks\Pages;

use App\Filament\Admin\Resources\RiwayatStoks\RiwayatStokResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewRiwayatStok extends ViewRecord
{
    protected static string $resource = RiwayatStokResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
