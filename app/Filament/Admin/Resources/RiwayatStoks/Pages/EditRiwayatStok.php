<?php

namespace App\Filament\Admin\Resources\RiwayatStoks\Pages;

use App\Filament\Admin\Resources\RiwayatStoks\RiwayatStokResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditRiwayatStok extends EditRecord
{
    protected static string $resource = RiwayatStokResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
