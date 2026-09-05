<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Obat;
use App\Models\Pembayaran;
use App\Models\PembelianObat;
use App\Models\PermintaanObat;
use App\Support\DashboardPeriod;
use Filament\Widgets\Widget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Facades\DB;

class PemilikDashboardWidget extends Widget
{
    use InteractsWithPageFilters;

    protected string $view = 'filament.admin.widgets.pemilik-dashboard-widget';
    protected static ?int $sort = -5;
    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        return auth()->user()?->role === 'pemilik';
    }

    protected function period(): array
    {
        return DashboardPeriod::resolve($this->filters);
    }

    public function getPeriodLabel(): string
    {
        $period = $this->period();
        return $period['start']->format('d M Y') . ' – ' . $period['end']->format('d M Y');
    }

    public function getTotalPembelian(): int
    {
        $p = $this->period();
        return (int) PembelianObat::query()
            ->join('detail_pembelian_obat', 'pembelian_obat.id', '=', 'detail_pembelian_obat.id_pembelian_obat')
            ->whereBetween('pembelian_obat.tanggal_pesan', [$p['start']->toDateString(), $p['end']->toDateString()])
            ->whereNotIn('pembelian_obat.status', ['dibatalkan', 'ditolak_supplier'])
            ->sum(DB::raw('detail_pembelian_obat.jumlah_pesan * detail_pembelian_obat.harga_satuan'));
    }

    public function getTotalDibayar(): int
    {
        $p = $this->period();
        return (int) Pembayaran::query()
            ->where('status', 'disetujui_supplier')
            ->whereBetween('tanggal_bayar', [$p['start']->toDateString(), $p['end']->toDateString()])
            ->sum('total_bayar');
    }

    public function getTotalDistribusi(): int
    {
        $p = $this->period();
        return (int) \App\Models\DetailPermintaanObat::query()
            ->join('permintaan_obat', 'detail_permintaan_obat.id_permintaan_obat', '=', 'permintaan_obat.id')
            ->whereNotNull('permintaan_obat.disetujui_at')
            ->whereBetween('permintaan_obat.disetujui_at', [$p['start'], $p['end']])
            ->sum('detail_permintaan_obat.jumlah_disetujui');
    }

    public function getPermintaan(): int
    {
        $p = $this->period();
        return PermintaanObat::query()
            ->whereBetween('tanggal_permintaan', [$p['start']->toDateString(), $p['end']->toDateString()])
            ->count();
    }

    public function getStokKritis(): int
    {
        return Obat::where('stok', '<=', 10)->count();
    }

    public function getTagihanAktif(): int
    {
        return (int) PembelianObat::query()
            ->whereNotIn('status', ['dibatalkan', 'ditolak_supplier'])
            ->get()
            ->sum(fn ($po) => $po->sisa_tagihan);
    }

    public function getPoAktif(): int
    {
        return PembelianObat::query()->whereIn('status', ['pending', 'diproses', 'menunggu_konfirmasi_gudang'])->count();
    }
}
