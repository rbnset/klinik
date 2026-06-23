<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Pembayaran;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class TrenPembelianChart extends ChartWidget
{
    use InteractsWithPageFilters;

    // Sesuai dengan standar Filament v3, tanpa static
    protected ?string $heading = 'Grafik Tren Pengeluaran Harian';
    protected ?string $maxHeight = '300px';

    protected static ?int $sort = 2; // static hanya untuk sort

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $bulan = $this->filters['bulan'] ?? now()->format('m');
        $tahun = $this->filters['tahun'] ?? now()->format('Y');

        $hariDalamBulan = Carbon::parse($tahun . '-' . $bulan . '-01')->daysInMonth;

        $labels = [];
        $dataPengeluaran = [];

        for ($i = 1; $i <= $hariDalamBulan; $i++) {
            $tanggalString = Carbon::createFromDate($tahun, $bulan, $i)->format('Y-m-d');

            $totalHariIni = Pembayaran::whereDate('tanggal_bayar', $tanggalString)->sum('total_bayar');

            $labels[] = 'Tgl ' . $i;
            $dataPengeluaran[] = $totalHariIni;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Pengeluaran (Rp)',
                    'data' => $dataPengeluaran,
                    'backgroundColor' => '#3b82f6',
                    'borderRadius' => 4,
                ],
            ],
            'labels' => $labels,
        ];
    }
}
