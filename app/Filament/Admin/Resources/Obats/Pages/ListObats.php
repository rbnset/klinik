<?php

namespace App\Filament\Admin\Resources\Obats\Pages;

use App\Filament\Admin\Resources\Obats\ObatResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListObats extends ListRecords
{
    protected static string $resource = ObatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
