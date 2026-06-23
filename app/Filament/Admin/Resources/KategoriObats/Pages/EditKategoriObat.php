<?php

namespace App\Filament\Admin\Resources\KategoriObats\Pages;

use App\Filament\Admin\Resources\KategoriObats\KategoriObatResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditKategoriObat extends EditRecord
{
    protected static string $resource = KategoriObatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
