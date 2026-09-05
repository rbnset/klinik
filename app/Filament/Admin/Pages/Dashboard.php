<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Schemas\Schema;

class Dashboard extends BaseDashboard
{
    use HasFiltersForm;

    public static function canAccess(): bool
    {
        return auth()->check();
    }

    public function getHeading(): string
    {
        return match (auth()->user()?->role) {
            'admin' => 'Dashboard Admin',
            'supplier' => 'Portal Supplier',
            'karyawan' => 'Dashboard Gudang',
            'bidan' => 'Portal Bidan',
            'pemilik' => 'Dashboard Pemilik',
            default => 'Dashboard',
        };
    }

    public function getSubheading(): ?string
    {
        return match (auth()->user()?->role) {
            'admin' => 'Pusat kendali administrasi, verifikasi supplier, akses pengguna, dan pengawasan operasional klinik.',
            'supplier' => 'Kelola pesanan, konfirmasi harga, dan pantau proses pengadaan Anda.',
            'karyawan' => 'Pusat kendali operasional gudang dan pengadaan obat.',
            'bidan' => 'Kelola permintaan obat internal dan pantau proses pemenuhannya.',
            'pemilik' => 'Ringkasan kondisi persediaan, pengadaan, dan aktivitas operasional klinik.',
            default => null,
        };
    }

    public function getColumns(): int | array
    {
        return in_array(auth()->user()?->role, ['admin', 'supplier', 'karyawan', 'bidan'], true) ? 1 : 2;
    }

    public function getWidgets(): array
    {
        return match (auth()->user()?->role) {
            'admin' => [\App\Filament\Admin\Widgets\AdminDashboardWidget::class],
            'karyawan' => [\App\Filament\Admin\Widgets\WarehouseDashboardWidget::class],
            'pemilik' => [
                \App\Filament\Admin\Widgets\StatsOverview::class,
                \App\Filament\Admin\Widgets\PeringatanStokWidget::class,
                \App\Filament\Admin\Widgets\TopPermintaanWidget::class,
            ],
            'bidan' => [\App\Filament\Admin\Widgets\BidanDashboardWidget::class],
            'supplier' => [\App\Filament\Admin\Widgets\SupplierDashboardWidget::class],
            default => [],
        };
    }

    /**
     * Dashboard tidak lagi menggunakan filter bulan/tahun.
     * Setiap dashboard menampilkan kondisi operasional yang relevan
     * secara langsung agar admin/petugas dapat fokus pada pekerjaan.
     */
    public function filtersForm(Schema $schema): Schema
    {
        return $schema->components([]);
    }
}
