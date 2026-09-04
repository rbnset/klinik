<?php

namespace App\Filament\Admin\Resources\PenyesuaianStoks\Pages;

use App\Filament\Admin\Resources\PenyesuaianStoks\PenyesuaianStokResource;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewPenyesuaianStok extends ViewRecord
{
    protected static string $resource = PenyesuaianStokResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('cetakPdf')->label('Cetak Bukti')->icon('heroicon-o-printer')->url(fn () => route('admin.cetak.penyesuaian', ['penyesuaian' => $this->record]))->openUrlInNewTab(),
            EditAction::make()->visible(fn () => ! $this->record->stok_diposting_at),
        ];
    }
}
