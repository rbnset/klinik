<?php

namespace App\Filament\Admin\Resources\PenerimaanObats\Pages;

use App\Filament\Admin\Resources\PenerimaanObats\PenerimaanObatResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPenerimaanObat extends EditRecord
{
    protected static string $resource = PenerimaanObatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
