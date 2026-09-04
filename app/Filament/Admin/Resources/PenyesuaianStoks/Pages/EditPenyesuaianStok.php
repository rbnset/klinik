<?php

namespace App\Filament\Admin\Resources\PenyesuaianStoks\Pages;

use App\Filament\Admin\Resources\PenyesuaianStoks\PenyesuaianStokResource;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPenyesuaianStok extends EditRecord
{
    protected static string $resource = PenyesuaianStokResource::class;

    public function mount(int|string $record): void
    {
        parent::mount($record);
        if ($this->record->stok_diposting_at) abort(403, 'Penyesuaian yang sudah diposting tidak dapat diubah.');
    }

    protected function getHeaderActions(): array
    {
        return [ViewAction::make()];
    }
}
