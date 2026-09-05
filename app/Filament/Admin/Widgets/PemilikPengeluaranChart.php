<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Pembayaran;
use App\Support\DashboardPeriod;
use Carbon\CarbonPeriod;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Facades\DB;

class PemilikPengeluaranChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected ?string $heading = 'Tren Pengeluaran';
    protected ?string $description = 'Pembayaran yang sudah disetujui supplier pada periode terpilih.';
    protected ?string $maxHeight = '320px';
    protected static ?int $sort = 0;
    protected int | string | array $columnSpan = 1;

    public static function canView(): bool { return auth()->user()?->role === 'pemilik'; }

    protected function getType(): string { return 'line'; }

    protected function getData(): array
    {
        $p = DashboardPeriod::resolve($this->filters);
        $query = Pembayaran::query()
            ->where('status', 'disetujui_supplier')
            ->whereBetween('tanggal_bayar', [$p['start']->toDateString(), $p['end']->toDateString()]);

        if ($p['granularity'] === 'daily') {
            $rows = $query->selectRaw('DATE(tanggal_bayar) as periode, SUM(total_bayar) as total')
                ->groupBy(DB::raw('DATE(tanggal_bayar)'))->pluck('total', 'periode');
            $labels = [];
            $data = [];
            foreach (CarbonPeriod::create($p['start']->copy()->startOfDay(), $p['end']->copy()->startOfDay()) as $date) {
                $key = $date->format('Y-m-d');
                $labels[] = $date->format('d M');
                $data[] = (int) ($rows[$key] ?? 0);
            }
        } else {
            $rows = $query->selectRaw("DATE_FORMAT(tanggal_bayar, '%Y-%m') as periode, SUM(total_bayar) as total")
                ->groupBy(DB::raw("DATE_FORMAT(tanggal_bayar, '%Y-%m')"))->pluck('total', 'periode');
            $labels = [];
            $data = [];
            $cursor = $p['start']->copy()->startOfMonth();
            while ($cursor->lte($p['end'])) {
                $key = $cursor->format('Y-m');
                $labels[] = $cursor->format('M Y');
                $data[] = (int) ($rows[$key] ?? 0);
                $cursor->addMonth();
            }
        }

        return ['datasets' => [['label' => 'Pengeluaran', 'data' => $data, 'fill' => true]], 'labels' => $labels];
    }
}
