<?php

namespace App\Filament\Admin\Resources\PermintaanObats\Pages;

use App\Filament\Admin\Resources\PermintaanObats\PermintaanObatResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListPermintaanObats extends ListRecords
{
    protected static string $resource = PermintaanObatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getFilteredTableQuery(): Builder
    {
        $query = parent::getFilteredTableQuery();

        $user = auth()->user();

        if (
            $user->role === 'admin'
            || $user->role === 'karyawan'
            || $user->role === 'pemilik'
        ) {
            return $query;
        }

        return $query->where(
            'id_pengguna',
            auth()->id()
        );
    }
}
