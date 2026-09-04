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
        $role = auth()->user()?->role;

        $actions = [];

        if (in_array($role, ['admin', 'karyawan', 'bidan'], true)) {
            $actions[] = [
                'label' => 'Ajukan Permintaan',
                'description' => 'Buat permintaan obat internal.',
                'icon' => 'clipboard-document-list',
                'url' => PermintaanObatResource::getUrl('create'),
            ];
        }

        if (in_array($role, ['admin', 'karyawan'], true)) {
            $actions[] = [
                'label' => 'Buat PO',
                'description' => 'Buat pemesanan kepada supplier.',
                'icon' => 'shopping-cart',
                'url' => PembelianObatResource::getUrl('create'),
            ];
            $actions[] = [
                'label' => 'Catat Penerimaan',
                'description' => 'Terima barang dan posting stok.',
                'icon' => 'inbox-arrow-down',
                'url' => PenerimaanObatResource::getUrl('create'),
            ];
        }

        if (in_array($role, ['admin', 'karyawan', 'bidan', 'pemilik'], true)) {
            $actions[] = [
                'label' => 'Katalog Obat',
                'description' => 'Cek stok obat.',
                'icon' => 'beaker',
                'url' => ObatResource::getUrl(),
            ];
        }

        return ['actions' => $actions];
    }
}
