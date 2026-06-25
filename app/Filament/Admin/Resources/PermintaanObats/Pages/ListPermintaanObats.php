<?php

namespace App\Filament\Admin\Resources\PermintaanObats\Pages;

use App\Filament\Admin\Resources\PermintaanObats\PermintaanObatResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
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

    public function getTabs(): array
    {
        $user = auth()->user();

        $baseQuery = \App\Models\PermintaanObat::query();

        if ($user->role === 'bidan') {
            $baseQuery->where('id_pengguna', $user->id);
        }

        return [

            'semua' => Tab::make('Semua')
                ->badge((clone $baseQuery)->count()),

            'pending' => Tab::make('Pending')
                ->badge(
                    (clone $baseQuery)
                        ->where('status', 'pending')
                        ->count()
                )
                ->modifyQueryUsing(
                    fn($query) => $query->where('status', 'pending')
                ),

            'disetujui' => Tab::make('Disetujui')
                ->badge(
                    (clone $baseQuery)
                        ->where('status', 'disetujui')
                        ->count()
                )
                ->modifyQueryUsing(
                    fn($query) => $query->where('status', 'disetujui')
                ),

            'ditolak' => Tab::make('Ditolak')
                ->badge(
                    (clone $baseQuery)
                        ->where('status', 'ditolak')
                        ->count()
                )
                ->modifyQueryUsing(
                    fn($query) => $query->where('status', 'ditolak')
                ),

            'dibatalkan' => Tab::make('Dibatalkan')
                ->badge(
                    (clone $baseQuery)
                        ->where('status', 'dibatalkan')
                        ->count()
                )
                ->modifyQueryUsing(
                    fn($query) => $query->where('status', 'dibatalkan')
                ),
        ];
    }
}
