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

    public function getSupplierMenunggu(): int
    {
        return Supplier::where('status_pengajuan', 'menunggu')->count();
    }

    public function getStokKritis(): int
    {
        return Obat::where('stok', '<=', 10)->count();
    }

    public function getPoPerluTindakan(): int
    {
        return PembelianObat::whereIn('status', ['pending', 'menunggu_konfirmasi_gudang'])->count();
    }

    public function getPermintaanMenunggu(): int
    {
        return PermintaanObat::where('status', 'pending')->count();
    }

    public function getPembayaranMenunggu(): int
    {
        return Pembayaran::where('status', 'menunggu_supplier')->count();
    }

    public function getPengguna(): int
    {
        return User::whereIn('role', ['admin', 'karyawan', 'bidan', 'pemilik', 'supplier'])->count();
    }

    public function getPerhatian(): array
    {
        $items = [];

        $supplier = $this->getSupplierMenunggu();
        if ($supplier > 0) {
            $items[] = [
                'title' => 'Pengajuan supplier menunggu verifikasi',
                'description' => $supplier . ' pengajuan perlu diperiksa dan diberi keputusan.',
                'icon' => 'heroicon-o-building-storefront',
                'tone' => 'warning',
                'iconClass' => 'text-warning-600 dark:text-warning-400',
                'bgClass' => 'bg-warning-50 text-warning-600 dark:bg-warning-500/10 dark:text-warning-300',
                'url' => SupplierResource::getUrl(),
            ];
        }

        $po = $this->getPoPerluTindakan();
        if ($po > 0) {
            $items[] = [
                'title' => 'Pengadaan membutuhkan tindakan',
                'description' => $po . ' PO menunggu respons supplier atau konfirmasi harga.',
                'icon' => 'heroicon-o-shopping-cart',
                'tone' => 'info',
                'iconClass' => 'text-info-600 dark:text-info-400',
                'bgClass' => 'bg-info-50 text-info-600 dark:bg-info-500/10 dark:text-info-300',
                'url' => PembelianObatResource::getUrl(),
            ];
        }

        $permintaan = $this->getPermintaanMenunggu();
        if ($permintaan > 0) {
            $items[] = [
                'title' => 'Permintaan internal menunggu proses',
                'description' => $permintaan . ' permintaan masih menunggu persetujuan gudang.',
                'icon' => 'heroicon-o-clipboard-document-list',
                'tone' => 'warning',
                'url' => PermintaanObatResource::getUrl(),
            ];
        }

        $pembayaran = $this->getPembayaranMenunggu();
        if ($pembayaran > 0) {
            $items[] = [
                'title' => 'Pembayaran menunggu persetujuan supplier',
                'description' => $pembayaran . ' pembayaran belum mendapat konfirmasi supplier.',
                'icon' => 'heroicon-o-banknotes',
                'tone' => 'danger',
                'iconClass' => 'text-danger-600 dark:text-danger-400',
                'bgClass' => 'bg-danger-50 text-danger-600 dark:bg-danger-500/10 dark:text-danger-300',
                'iconClass' => 'text-danger-600 dark:text-danger-400',
                'bgClass' => 'bg-danger-50 text-danger-600 dark:bg-danger-500/10 dark:text-danger-300',
                'url' => PembayaranResource::getUrl(),
            ];
        }

        $stok = $this->getStokKritis();
        if ($stok > 0) {
            $items[] = [
                'title' => 'Persediaan berada pada level kritis',
                'description' => $stok . ' obat memiliki stok ≤ 10 unit.',
                'icon' => 'heroicon-o-exclamation-triangle',
                'tone' => 'danger',
                'url' => ObatResource::getUrl(),
            ];
        }

        return array_slice($items, 0, 5);
    }

    public function getQuickLinks(): array
    {
        return [
            ['label' => 'Pengguna', 'description' => 'Akun & hak akses', 'icon' => 'heroicon-o-users', 'url' => UserResource::getUrl()],
            ['label' => 'Supplier', 'description' => 'Verifikasi & data supplier', 'icon' => 'heroicon-o-building-storefront', 'url' => SupplierResource::getUrl()],
            ['label' => 'Pengadaan', 'description' => 'Pantau seluruh PO', 'icon' => 'heroicon-o-shopping-cart', 'url' => PembelianObatResource::getUrl()],
            ['label' => 'Persediaan', 'description' => 'Katalog & stok obat', 'icon' => 'heroicon-o-cube', 'url' => ObatResource::getUrl()],
        ];
    }
}
