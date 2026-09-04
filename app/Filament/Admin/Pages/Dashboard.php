<?php

namespace App\Filament\Admin\Pages;

use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    use HasFiltersForm;

    public static function canAccess(): bool
    {
        return auth()->check();
    }

    public function getHeading(): string
    {
        return match (auth()->user()?->role) { 'supplier' => 'Portal Supplier', 'karyawan' => 'Dashboard Gudang', default => 'Dashboard', };
    }

    public function getSubheading(): ?string
    {
        if (auth()->user()?->role === 'supplier') {
            return 'Kelola pesanan, konfirmasi harga, dan pantau proses pengadaan Anda.';
        }
        if (auth()->user()?->role === 'karyawan') {
            return 'Pusat kendali operasional gudang dan pengadaan obat.';
        }

        return null;
    }

    public function getColumns(): int | array
    {
        return in_array(auth()->user()?->role, ['supplier', 'karyawan'], true) ? 1 : 2;
    }

    public function getWidgets(): array
    {
        $role = auth()->user()?->role;

        return match ($role) {
            'admin' => [
                \App\Filament\Admin\Widgets\StatsOverview::class,
                \App\Filament\Admin\Widgets\QuickActionsWidget::class,
                \App\Filament\Admin\Widgets\PeringatanStokWidget::class,
                \App\Filament\Admin\Widgets\TopPermintaanWidget::class,
            ],
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

    public function filtersForm(Schema $schema): Schema
    {
        if (in_array(auth()->user()?->role, ['supplier', 'karyawan', 'bidan'], true)) {
            return $schema->components([]);
        }

        return $schema->components([
            Select::make('bulan')->label('Filter Bulan')->options([
                '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember',
            ])->default(now()->format('m')),
            Select::make('tahun')->label('Filter Tahun')->options(array_combine(
                range(now()->subYears(2)->format('Y'), now()->format('Y')),
                range(now()->subYears(2)->format('Y'), now()->format('Y')),
            ))->default(now()->format('Y')),
        ])->columns(2);
    }
}
