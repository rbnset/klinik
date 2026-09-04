<?php

namespace App\Filament\Admin\Resources\PembelianObats\Pages;

use App\Filament\Admin\Resources\PembelianObats\PembelianObatResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListPembelianObats extends ListRecords
{
    protected static string $resource = PembelianObatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->visible(fn (): bool => auth()->user()?->role !== 'supplier'),
        ];
    }

    public function getTabs(): array
    {
        $base = PembelianObatResource::getEloquentQuery();

        $perluTindakan = fn (Builder $query) => $query->whereIn('status', ['pending', 'menunggu_konfirmasi_gudang']);
        $belumSelesai = fn (Builder $query) => $query->whereIn('status', ['diproses']);

        return [
            'semua' => Tab::make('Semua')
                ->badge((clone $base)->count()),
            'perlu_tindakan' => Tab::make('Perlu Tindakan')
                ->badge((clone $base)->whereIn('status', ['pending', 'menunggu_konfirmasi_gudang'])->count())
                ->modifyQueryUsing($perluTindakan),
            'belum_selesai' => Tab::make('Belum Selesai')
                ->badge((clone $base)->where('status', 'diproses')->count())
                ->modifyQueryUsing($belumSelesai),
            'selesai' => Tab::make('Selesai')
                ->badge((clone $base)->where('status', 'selesai')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'selesai')),
        ];
    }
}
