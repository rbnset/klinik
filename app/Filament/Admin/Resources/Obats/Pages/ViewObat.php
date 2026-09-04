<?php

namespace App\Filament\Admin\Resources\Obats\Pages;

use App\Filament\Admin\Resources\Obats\ObatResource;
use App\Filament\Admin\Resources\PenyesuaianStoks\PenyesuaianStokResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewObat extends ViewRecord
{
    protected static string $resource = ObatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('penyesuaian')
                ->label('Penyesuaian Stok')
                ->icon('heroicon-o-adjustments-horizontal')
                ->url(fn () => PenyesuaianStokResource::getUrl('create', ['obat' => $this->record->getKey()])),
            EditAction::make(),
        ];
    }
}
