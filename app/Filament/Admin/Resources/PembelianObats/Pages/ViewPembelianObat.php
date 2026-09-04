<?php

namespace App\Filament\Admin\Resources\PembelianObats\Pages;

use App\Filament\Admin\Resources\PembelianObats\PembelianObatResource;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewPembelianObat extends ViewRecord
{
    protected static string $resource = PembelianObatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('cetakPdf')->label('Cetak PO')->icon('heroicon-o-printer')->url(fn () => route('admin.cetak.pembelian', ['pembelian' => $this->record]))->openUrlInNewTab(),
            EditAction::make()
                ->visible(fn () => ! $this->record->penerimaan_obat()->exists() && ! $this->record->pembayaran()->exists()),
        ];
    }
}
