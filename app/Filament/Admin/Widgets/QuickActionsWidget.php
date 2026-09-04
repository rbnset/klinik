<?php

namespace App\Filament\Admin\Widgets;

use App\Filament\Admin\Resources\Obats\ObatResource;
use App\Filament\Admin\Resources\PembelianObats\PembelianObatResource;
use App\Filament\Admin\Resources\PenerimaanObats\PenerimaanObatResource;
use App\Filament\Admin\Resources\PermintaanObats\PermintaanObatResource;
use Filament\Widgets\Widget;

class QuickActionsWidget extends Widget
{
    protected string $view = 'filament.admin.widgets.quick-actions-widget';
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    protected function getViewData(): array
    {
        return [
            'actions' => [
                ['label' => 'Ajukan Permintaan', 'description' => 'Buat permintaan obat internal.', 'icon' => 'clipboard-document-list', 'url' => PermintaanObatResource::getUrl('create')],
                ['label' => 'Buat PO', 'description' => 'Buat pemesanan kepada supplier.', 'icon' => 'shopping-cart', 'url' => PembelianObatResource::getUrl('create')],
                ['label' => 'Catat Penerimaan', 'description' => 'Terima barang dan posting stok.', 'icon' => 'inbox-arrow-down', 'url' => PenerimaanObatResource::getUrl('create')],
                ['label' => 'Katalog Obat', 'description' => 'Cek stok dan harga terakhir.', 'icon' => 'beaker', 'url' => ObatResource::getUrl()],
            ],
        ];
    }
}
