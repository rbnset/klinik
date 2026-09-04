<?php

namespace App\Filament\Admin\Resources\PembelianObats\Pages;

use App\Filament\Admin\Resources\PembelianObats\PembelianObatResource;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPembelianObat extends EditRecord
{
    protected static string $resource = PembelianObatResource::class;

    public function mount(int|string $record): void
    {
        parent::mount($record);
        if ($this->record->penerimaan_obat()->exists() || $this->record->pembayaran()->exists()) {
            abort(403, 'PO yang sudah memiliki penerimaan atau pembayaran tidak dapat diubah.');
        }
    }

    protected function getHeaderActions(): array
    {
        return [ViewAction::make()];
    }
}
