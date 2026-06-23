<?php

namespace App\Filament\Admin\Resources\PembelianObats\Pages;

use App\Filament\Admin\Resources\PembelianObats\PembelianObatResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPembelianObat extends EditRecord
{
    protected static string $resource = PembelianObatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
