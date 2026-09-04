<?php

namespace App\Filament\Admin\Resources\Obats\Pages;

use App\Filament\Admin\Resources\Obats\ObatResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditObat extends EditRecord
{
    protected static string $resource = ObatResource::class;


    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
