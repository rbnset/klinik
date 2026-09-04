<?php

namespace App\Filament\Admin\Resources\PermintaanObats\Pages;

use App\Filament\Admin\Resources\PermintaanObats\PermintaanObatResource;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPermintaanObat extends EditRecord
{
    protected static string $resource = PermintaanObatResource::class;

    protected function getHeaderActions(): array
    {
        return [ViewAction::make()];
    }

    public function mount(int|string $record): void
    {
        parent::mount($record);

        if (auth()->user()?->role === 'karyawan') {
            abort(403);
        }
    }
}
