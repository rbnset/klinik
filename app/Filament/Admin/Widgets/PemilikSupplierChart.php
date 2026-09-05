<?php

namespace App\Filament\Admin\Widgets;

use App\Models\PembelianObat;
use App\Support\DashboardPeriod;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Facades\DB;

class PemilikSupplierChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected ?string $heading = 'Nilai Pengadaan per Supplier';
    protected ?string $description = 'Supplier dengan nilai PO terbesar pada periode terpilih.';
    protected ?string $maxHeight = '320px';
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool { return auth()->user()?->role === 'pemilik'; }

    protected function getType(): string { return 'bar'; }

    protected function getData(): array
    {
        $p = DashboardPeriod::resolve($this->filters);
        $rows = PembelianObat::query()
            ->join('supplier', 'pembelian_obat.id_supplier', '=', 'supplier.id')
            ->join('detail_pembelian_obat', 'pembelian_obat.id', '=', 'detail_pembelian_obat.id_pembelian_obat')
            ->whereBetween('pembelian_obat.tanggal_pesan', [$p['start']->toDateString(), $p['end']->toDateString()])
            ->whereNotIn('pembelian_obat.status', ['dibatalkan', 'ditolak_supplier'])
            ->select('supplier.nama_supplier')
            ->selectRaw('SUM(detail_pembelian_obat.jumlah_pesan * detail_pembelian_obat.harga_satuan) as total')
            ->groupBy('supplier.id', 'supplier.nama_supplier')
            ->orderByDesc('total')->limit(5)->get();

        return [
            'labels' => $rows->pluck('nama_supplier')->all(),
            'datasets' => [['label' => 'Nilai Pengadaan (Rp)', 'data' => $rows->pluck('total')->map(fn ($v) => (int) $v)->all()]],
        ];
    }
}
