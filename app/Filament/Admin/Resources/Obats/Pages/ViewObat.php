<?php

namespace App\Filament\Admin\Resources\Obats\Pages;

use App\Filament\Admin\Resources\Obats\ObatResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewObat extends ViewRecord
{
    protected static string $resource = ObatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    public function getView(): string
    {
        return 'admin.obat.show';
    }
}
