<?php

namespace App\Filament\Admin\Resources\Pembayarans\Pages;

use App\Filament\Admin\Resources\Pembayarans\PembayaranResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListPembayarans extends ListRecords
{
    protected static string $resource = PembayaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->visible(fn (): bool => auth()->user()?->role !== 'supplier'),
        ];
    }

    public function getTabs(): array
    {
        $base = PembayaranResource::getEloquentQuery();

        return [
            'semua' => Tab::make('Semua')
                ->badge((clone $base)->count()),
            'perlu_persetujuan' => Tab::make('Perlu Persetujuan')
                ->badge((clone $base)->where('status', 'menunggu_supplier')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'menunggu_supplier')),
            'selesai' => Tab::make('Selesai')
                ->badge((clone $base)->where('status', 'disetujui_supplier')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'disetujui_supplier')),
        ];
    }
}
