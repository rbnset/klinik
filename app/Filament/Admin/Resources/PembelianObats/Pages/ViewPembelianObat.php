<?php

namespace App\Filament\Admin\Resources\PembelianObats\Pages;

use App\Filament\Admin\Resources\PembelianObats\PembelianObatResource;
use App\Filament\Admin\Resources\PenerimaanObats\PenerimaanObatResource;
use App\Filament\Admin\Resources\Pembayarans\PembayaranResource;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewPembelianObat extends ViewRecord
{
    protected static string $resource = PembelianObatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('catatPenerimaan')
                ->label('Catat Penerimaan')
                ->icon('heroicon-o-inbox-arrow-down')
                ->color('success')
                ->url(fn () => PenerimaanObatResource::getUrl('create', ['pembelian' => $this->record->getKey()]))
                ->visible(fn () => $this->record->status !== 'dibatalkan' && $this->record->status_penerimaan !== 'lengkap'),
            Action::make('catatPembayaran')
                ->label('Catat Pembayaran')
                ->icon('heroicon-o-banknotes')
                ->url(fn () => PembayaranResource::getUrl('create', ['pembelian' => $this->record->getKey()]))
                ->visible(fn () => $this->record->status !== 'dibatalkan' && $this->record->sisa_tagihan > 0),
            Action::make('cetakPdf')->label('Cetak PO')->icon('heroicon-o-printer')->url(fn () => route('admin.cetak.pembelian', ['pembelian' => $this->record]))->openUrlInNewTab(),
            EditAction::make()
                ->visible(fn () => ! $this->record->penerimaan_obat()->exists() && ! $this->record->pembayaran()->exists()),
        ];
    }
}
