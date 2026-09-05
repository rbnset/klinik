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
        return match (auth()->user()?->role) { 'admin' => 'Dashboard Administrator', 'supplier' => 'Portal Supplier', 'karyawan' => 'Dashboard Gudang', 'pemilik' => 'Dashboard Pemilik', default => 'Dashboard', };
    }

    public function getSubheading(): ?string
    {
        if (auth()->user()?->role === 'admin') {
            return 'Pusat kontrol akses, verifikasi supplier, dan pengawasan proses sistem.';
        }
        if (auth()->user()?->role === 'supplier') {
            return 'Kelola pesanan, konfirmasi harga, dan pantau proses pengadaan Anda.';
        }
        if (auth()->user()?->role === 'karyawan') {
            return 'Pusat kendali operasional gudang dan pengadaan obat.';
        }
        if (auth()->user()?->role === 'pemilik') {
            return 'Pantau kinerja pengadaan, pengeluaran, persediaan, dan distribusi usaha.';
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
            'admin' => [\App\Filament\Admin\Widgets\AdminDashboardWidget::class],
            'karyawan' => [\App\Filament\Admin\Widgets\WarehouseDashboardWidget::class],
            'pemilik' => [
                \App\Filament\Admin\Widgets\PemilikDashboardWidget::class,
                \App\Filament\Admin\Widgets\PemilikPengeluaranChart::class,
                \App\Filament\Admin\Widgets\PemilikDistribusiChart::class,
                \App\Filament\Admin\Widgets\PemilikSupplierChart::class,
                \App\Filament\Admin\Widgets\PemilikTopObatWidget::class,
            ],
            'bidan' => [\App\Filament\Admin\Widgets\BidanDashboardWidget::class],
            'supplier' => [\App\Filament\Admin\Widgets\SupplierDashboardWidget::class],
            default => [],
        };
    }

    public function filtersForm(Schema $schema): Schema
    {
        if (auth()->user()?->role !== 'pemilik') {
            return $schema->components([]);
        }

        return $schema->components([
            Select::make('mode_periode')
                ->label('Jenis Periode')
                ->options([
                    'bulan_tahun' => 'Bulan & Tahun',
                    'rentang_tanggal' => 'Rentang Tanggal',
                ])
                ->default('bulan_tahun')
                ->live()
                ->columnSpanFull(),
            Select::make('bulan')
                ->label('Bulan')
                ->options([
                    '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                    '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                    '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember',
                ])
                ->default(now()->format('m'))
                ->live()
                ->visible(fn (\Filament\Schemas\Components\Utilities\Get $get): bool => $get('mode_periode') !== 'rentang_tanggal'),
            Select::make('tahun')
                ->label('Tahun')
                ->options(array_combine(
                    range(now()->subYears(4)->format('Y'), now()->format('Y')),
                    range(now()->subYears(4)->format('Y'), now()->format('Y')),
                ))
                ->default(now()->format('Y'))
                ->live()
                ->visible(fn (\Filament\Schemas\Components\Utilities\Get $get): bool => $get('mode_periode') !== 'rentang_tanggal'),
            \Filament\Forms\Components\DatePicker::make('tanggal_mulai')
                ->label('Tanggal Mulai')
                ->default(now()->startOfMonth())
                ->live()
                ->visible(fn (\Filament\Schemas\Components\Utilities\Get $get): bool => $get('mode_periode') === 'rentang_tanggal'),
            \Filament\Forms\Components\DatePicker::make('tanggal_akhir')
                ->label('Tanggal Akhir')
                ->default(now())
                ->live()
                ->visible(fn (\Filament\Schemas\Components\Utilities\Get $get): bool => $get('mode_periode') === 'rentang_tanggal'),
        ])->columns(2);
    }
}
