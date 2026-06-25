<?php

namespace App\Filament\Admin\Resources\PermintaanObats\Pages;

use App\Filament\Admin\Resources\PermintaanObats\PermintaanObatResource;
use Filament\Resources\Pages\ViewRecord;

class ViewPermintaanObat extends ViewRecord
{
    protected static string $resource = PermintaanObatResource::class;

    protected string $view =
    'filament.admin.resources.permintaan-obat.view-permintaan-obat';
}
