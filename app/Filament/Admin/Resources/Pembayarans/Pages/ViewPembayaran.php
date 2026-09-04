<?php

namespace App\Filament\Admin\Resources\Pembayarans\Pages;

use App\Filament\Admin\Resources\Pembayarans\PembayaranResource;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewPembayaran extends ViewRecord
{
    protected static string $resource = PembayaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('cetakPdf')->label('Cetak Bukti')->icon('heroicon-o-printer')->url(fn () => route('admin.cetak.pembayaran', ['pembayaran' => $this->record]))->openUrlInNewTab(),
            EditAction::make(),
        ];
    }
}
