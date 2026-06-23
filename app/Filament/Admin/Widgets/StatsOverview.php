<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Obat;
use App\Models\Pembayaran;
use App\Models\PembelianObat;
use App\Models\PermintaanObat;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 1; // Posisi Widget di paling atas

    // Mengatur agar form filter memicu update data (bukan kolomnya yang melebar)
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        $bulan = $this->filters['bulan'] ?? now()->format('m');
        $tahun = $this->filters['tahun'] ?? now()->format('Y');

        $totalStok = Obat::sum('stok');

        $totalPO = PembelianObat::whereMonth('tanggal_pesan', $bulan)
            ->whereYear('tanggal_pesan', $tahun)
            ->count();

        $totalPermintaan = PermintaanObat::whereMonth('tanggal_permintaan', $bulan)
            ->whereYear('tanggal_permintaan', $tahun)
            ->count();

        $totalPengeluaran = Pembayaran::whereMonth('tanggal_bayar', $bulan)
            ->whereYear('tanggal_bayar', $tahun)
            ->sum('total_bayar');

        return [
            Stat::make('Total Persediaan Fisik', number_format($totalStok, 0, ',', '.') . ' Unit')
                ->description('Kapasitas seluruh obat')
                ->descriptionIcon('heroicon-m-cube')
                ->color('success'),

            Stat::make('Permintaan Internal', $totalPermintaan . ' Dokumen')
                ->description('Distribusi ke Bidan/Poli')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('warning'),

            Stat::make('Pesanan Eksternal (PO)', $totalPO . ' Transaksi')
                ->description('Pengadaan ke Supplier')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('info'),

            Stat::make('Pengeluaran Belanja', 'Rp ' . number_format($totalPengeluaran, 0, ',', '.'))
                ->description('Total uang keluar bulan ini')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('danger'),
        ];
    }
}
