<?php

namespace App\Filament\Admin\Widgets;

use App\Filament\Admin\Resources\Obats\ObatResource;
use App\Filament\Admin\Resources\Pembayarans\PembayaranResource;
use App\Filament\Admin\Resources\PembelianObats\PembelianObatResource;
use App\Filament\Admin\Resources\PermintaanObats\PermintaanObatResource;
use App\Filament\Admin\Resources\Suppliers\SupplierResource;
use App\Filament\Admin\Resources\Users\UserResource;
use App\Models\Obat;
use App\Models\Pembayaran;
use App\Models\PembelianObat;
use App\Models\PermintaanObat;
use App\Models\Supplier;
use App\Models\User;
use Filament\Widgets\Widget;

class AdminDashboardWidget extends Widget
{
    protected string $view = 'filament.admin.widgets.admin-dashboard-widget';

    protected static ?int $sort = -5;

    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        return auth()->user()?->role === 'admin';
    }

    public function getAdminName(): string
    {
        return auth()->user()?->name ?? 'Administrator';
    }

    public function getPendingSupplierCount(): int
    {
        return Supplier::where('status_pengajuan', 'menunggu')->count();
    }

    public function getCriticalStockCount(): int
    {
        return Obat::where('stok', '<=', 10)->count();
    }

    public function getPendingRequestCount(): int
    {
        return PermintaanObat::where('status', 'pending')->count();
    }

    public function getProcurementActionCount(): int
    {
        return PembelianObat::whereIn('status', ['pending', 'menunggu_konfirmasi_gudang'])->count();
    }

    public function getPendingPaymentCount(): int
    {
        return Pembayaran::where('status', 'menunggu_supplier')->count();
    }

    public function getUserCount(): int
    {
        return User::count();
    }

    public function getActionRows(): array
    {
        $rows = [];

        $suppliers = Supplier::query()
            ->where('status_pengajuan', 'menunggu')
            ->latest('created_at')
            ->limit(4)
            ->get();

        foreach ($suppliers as $supplier) {
            $rows[] = [
                'priority' => 0,
                'label' => 'Verifikasi Supplier',
                'title' => 'Pengajuan supplier menunggu pemeriksaan',
                'detail' => $supplier->nama_supplier,
                'url' => SupplierResource::getUrl('view', ['record' => $supplier]),
                'tone' => 'warning',
                'icon' => 'heroicon-o-building-storefront',
            ];
        }

        $payments = Pembayaran::query()
            ->where('status', 'menunggu_supplier')
            ->with('pembelian_obat.supplier')
            ->latest('tanggal_bayar')
            ->limit(3)
            ->get();

        foreach ($payments as $payment) {
            $po = $payment->pembelian_obat;
            $rows[] = [
                'priority' => 1,
                'label' => 'Pembayaran',
                'title' => 'Pembayaran menunggu persetujuan supplier',
                'detail' => 'PO-' . str_pad((string) $po?->id, 5, '0', STR_PAD_LEFT) . ' · Rp ' . number_format((int) $payment->total_bayar, 0, ',', '.'),
                'url' => PembayaranResource::getUrl('view', ['record' => $payment]),
                'tone' => 'warning',
                'icon' => 'heroicon-o-banknotes',
            ];
        }

        $priceChanges = PembelianObat::query()
            ->where('status', 'menunggu_konfirmasi_gudang')
            ->with('supplier')
            ->latest('tanggal_pesan')
            ->limit(3)
            ->get();

        foreach ($priceChanges as $po) {
            $rows[] = [
                'priority' => 1,
                'label' => 'Pengadaan',
                'title' => 'Perubahan harga supplier perlu keputusan',
                'detail' => 'PO-' . str_pad((string) $po->id, 5, '0', STR_PAD_LEFT) . ' · ' . ($po->supplier?->nama_supplier ?? '-'),
                'url' => PembelianObatResource::getUrl('view', ['record' => $po]),
                'tone' => 'warning',
                'icon' => 'heroicon-o-shopping-cart',
            ];
        }

        $requests = PermintaanObat::query()
            ->where('status', 'pending')
            ->with('pengguna')
            ->latest('tanggal_permintaan')
            ->limit(3)
            ->get();

        foreach ($requests as $request) {
            $rows[] = [
                'priority' => 1,
                'label' => 'Permintaan Internal',
                'title' => 'Permintaan obat menunggu keputusan gudang',
                'detail' => 'REQ-' . str_pad((string) $request->id, 5, '0', STR_PAD_LEFT) . ' · ' . ($request->pengguna?->name ?? '-'),
                'url' => PermintaanObatResource::getUrl('view', ['record' => $request]),
                'tone' => 'info',
                'icon' => 'heroicon-o-clipboard-document-list',
            ];
        }

        if ($this->getCriticalStockCount() > 0) {
            $rows[] = [
                'priority' => 2,
                'label' => 'Persediaan',
                'title' => 'Ada obat dengan stok kritis',
                'detail' => $this->getCriticalStockCount() . ' obat berada pada batas ≤ 10 unit',
                'url' => ObatResource::getUrl(),
                'tone' => 'danger',
                'icon' => 'heroicon-o-exclamation-triangle',
            ];
        }

        return collect($rows)
            ->sortBy('priority')
            ->take(10)
            ->values()
            ->all();
    }

    public function url(string $key): string
    {
        return match ($key) {
            'supplier' => SupplierResource::getUrl() . '?tableFilters[status_pengajuan][value]=menunggu',
            'users' => UserResource::getUrl(),
            'stock' => ObatResource::getUrl(),
            'request' => PermintaanObatResource::getUrl() . '?activeTab=perlu_tindakan',
            'po' => PembelianObatResource::getUrl() . '?activeTab=perlu_tindakan',
            'payment' => PembayaranResource::getUrl() . '?activeTab=perlu_persetujuan',
            default => '#',
        };
    }
}
