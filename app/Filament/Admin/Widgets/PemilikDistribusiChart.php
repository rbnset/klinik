<?php

namespace App\Filament\Admin\Widgets;

use App\Models\DetailPermintaanObat;
use App\Support\DashboardPeriod;
use Carbon\CarbonPeriod;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Facades\DB;

class PemilikDistribusiChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected ?string $heading = 'Tren Distribusi Obat';
    protected ?string $description = 'Jumlah obat yang disetujui untuk didistribusikan ke internal.';
    protected ?string $maxHeight = '320px';
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 1;

    public static function canView(): bool { return auth()->user()?->role === 'pemilik'; }

    protected function getType(): string { return 'line'; }

    protected function getData(): array
    {
        $p = DashboardPeriod::resolve($this->filters);
        $query = DetailPermintaanObat::query()
            ->join('permintaan_obat', 'detail_permintaan_obat.id_permintaan_obat', '=', 'permintaan_obat.id')
            ->whereNotNull('permintaan_obat.disetujui_at')
            ->whereBetween('permintaan_obat.disetujui_at', [$p['start'], $p['end']]);

        if ($p['granularity'] === 'daily') {
            $rows = $query->selectRaw('DATE(permintaan_obat.disetujui_at) as periode, SUM(detail_permintaan_obat.jumlah_disetujui) as total')
                ->groupBy(DB::raw('DATE(permintaan_obat.disetujui_at)'))->pluck('total', 'periode');
            $labels = [];
            $data = [];
            foreach (CarbonPeriod::create($p['start']->copy()->startOfDay(), $p['end']->copy()->startOfDay()) as $date) {
                $key = $date->format('Y-m-d');
                $labels[] = $date->format('d M');
                $data[] = (int) ($rows[$key] ?? 0);
            }
        } else {
            $rows = $query->selectRaw("DATE_FORMAT(permintaan_obat.disetujui_at, '%Y-%m') as periode, SUM(detail_permintaan_obat.jumlah_disetujui) as total")
                ->groupBy(DB::raw("DATE_FORMAT(permintaan_obat.disetujui_at, '%Y-%m')"))->pluck('total', 'periode');
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

        return ['datasets' => [['label' => 'Distribusi', 'data' => $data, 'fill' => true]], 'labels' => $labels];
    }
}
