<?php

namespace App\Filament\Admin\Resources\PermintaanObats\Pages;

use App\Filament\Admin\Resources\PermintaanObats\PermintaanObatResource;
use App\Models\User;
use App\Notifications\PermintaanObatDibuat;
use Filament\Resources\Pages\CreateRecord;

class CreatePermintaanObat extends CreateRecord
{
    protected static string $resource = PermintaanObatResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['id_pengguna'] = auth()->id();
        $data['status'] = 'pending';

        return $data;
    }

    protected function afterCreate(): void
    {
        $karyawanList = User::where('role', 'karyawan')->get();

        foreach ($karyawanList as $karyawan) {
            $karyawan->notify(
                new PermintaanObatDibuat(
                    $this->record,
                    auth()->user()
                )
            );
        }
    }
}
