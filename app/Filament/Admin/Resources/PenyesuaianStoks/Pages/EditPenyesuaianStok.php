<?php

namespace App\Filament\Admin\Resources\PenyesuaianStoks\Pages;

use App\Filament\Admin\Resources\PenyesuaianStoks\PenyesuaianStokResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPenyesuaianStok extends EditRecord
{
    protected static string $resource = PenyesuaianStokResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
