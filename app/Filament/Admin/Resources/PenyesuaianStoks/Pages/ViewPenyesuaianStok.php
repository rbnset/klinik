<?php

namespace App\Filament\Admin\Resources\PenyesuaianStoks\Pages;

use App\Filament\Admin\Resources\PenyesuaianStoks\PenyesuaianStokResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPenyesuaianStok extends ViewRecord
{
    protected static string $resource = PenyesuaianStokResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
