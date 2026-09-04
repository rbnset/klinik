<?php

namespace App\Filament\Admin\Widgets;

use App\Filament\Admin\Resources\PembelianObats\PembelianObatResource;
use App\Filament\Admin\Resources\Suppliers\SupplierResource;
use App\Models\PembelianObat;
use Filament\Widgets\Widget;

class SupplierDashboardWidget extends Widget
{
    protected string $view = 'filament.admin.widgets.supplier-dashboard-widget';

    protected static ?int $sort = -5;

    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        return auth()->user()?->role === 'supplier';
    }

    public function getSupplierName(): string
    {
        return auth()->user()?->supplier?->nama_supplier ?? auth()->user()?->name ?? 'Supplier';
    }

    public function getPendingCount(): int
    {
        $supplierId = auth()->user()?->supplier?->id;

        return $supplierId ? PembelianObat::where('id_supplier', $supplierId)->where('status', 'pending')->count() : 0;
    }

    public function getWaitingCount(): int
    {
        $supplierId = auth()->user()?->supplier?->id;

        return $supplierId ? PembelianObat::where('id_supplier', $supplierId)->where('status', 'menunggu_konfirmasi_gudang')->count() : 0;
    }

    public function getProcessingCount(): int
    {
        $supplierId = auth()->user()?->supplier?->id;

        return $supplierId ? PembelianObat::where('id_supplier', $supplierId)->where('status', 'diproses')->count() : 0;
    }

    public function getOrdersUrl(): string
    {
        return PembelianObatResource::getUrl('index');
    }

    public function getProfileUrl(): string
    {
        return SupplierResource::getUrl('edit', ['record' => auth()->user()?->supplier?->getKey()]);
    }
}
