<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Obat;
use Filament\Widgets\ChartWidget;

class ProporsiStokChart extends ChartWidget
{
    // Tanpa kata "static" untuk heading dan maxHeight
    protected ?string $heading = 'Proporsi 5 Obat Terbanyak di Gudang';
    protected ?string $maxHeight = '300px';

    // Properti sort tetap menggunakan static sesuai bawaan Filament
    protected static ?int $sort = 3;

    protected function getType(): string
    {
        return 'pie';
    }

    protected function getData(): array
    {
        $topObat = Obat::orderBy('stok', 'desc')->take(5)->get();

        $labels = [];
        $dataStok = [];

        foreach ($topObat as $obat) {
            $labels[] = $obat->nama_obat;
            $dataStok[] = $obat->stok;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Unit',
                    'data' => $dataStok,
                    'backgroundColor' => [
                        '#10b981',
                        '#f59e0b',
                        '#3b82f6',
                        '#ef4444',
                        '#8b5cf6',
                    ],
                ],
            ],
            'labels' => $labels,
        ];
    }
}
