<?php

namespace App\Filament\Admin\Resources\PenerimaanObats\Pages;

use App\Filament\Admin\Resources\PenerimaanObats\PenerimaanObatResource;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPenerimaanObat extends EditRecord
{
    protected static string $resource = PenerimaanObatResource::class;

    public function mount(int|string $record): void
    {
        parent::mount($record);

        if ($this->record->stok_diposting_at) {
            abort(403, 'Penerimaan yang sudah diposting tidak dapat diubah.');
        }
    }

    protected function getHeaderActions(): array
    {
        return [ViewAction::make()];
    }
}
