<?php

namespace App\Filament\Admin\Resources\KategoriObats\Pages;

use App\Filament\Admin\Resources\KategoriObats\KategoriObatResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKategoriObats extends ListRecords
{
    protected static string $resource = KategoriObatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
