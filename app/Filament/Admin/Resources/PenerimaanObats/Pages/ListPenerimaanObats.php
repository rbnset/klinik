<?php

namespace App\Filament\Admin\Resources\PenerimaanObats\Pages;

use App\Filament\Admin\Resources\PenerimaanObats\PenerimaanObatResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListPenerimaanObats extends ListRecords
{
    protected static string $resource = PenerimaanObatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->visible(fn (): bool => auth()->user()?->role !== 'supplier'),
        ];
    }

    public function getTabs(): array
    {
        $base = PenerimaanObatResource::getEloquentQuery();

        return [
            'semua' => Tab::make('Semua')
                ->badge((clone $base)->count()),
            'perlu_diposting' => Tab::make('Perlu Diposting')
                ->badge((clone $base)->whereNull('stok_diposting_at')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->whereNull('stok_diposting_at')),
            'selesai' => Tab::make('Selesai')
                ->badge((clone $base)->whereNotNull('stok_diposting_at')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->whereNotNull('stok_diposting_at')),
        ];
    }
}
