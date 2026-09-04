<?php

namespace App\Filament\Admin\Widgets;

use App\Filament\Admin\Resources\Obats\ObatResource;
use App\Filament\Admin\Resources\Pembayarans\PembayaranResource;
use App\Filament\Admin\Resources\PembelianObats\PembelianObatResource;
use App\Filament\Admin\Resources\PenerimaanObats\PenerimaanObatResource;
use App\Filament\Admin\Resources\PermintaanObats\PermintaanObatResource;
use App\Models\DetailPembelianObat;
use App\Models\Obat;
use App\Models\Pembayaran;
use App\Models\PembelianObat;
use App\Models\PermintaanObat;
use Filament\Widgets\Widget;

class WarehouseDashboardWidget extends Widget
{
    protected string $view = 'filament.admin.widgets.warehouse-dashboard-widget';

    protected static ?int $sort = -5;

    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        return auth()->user()?->role === 'karyawan';
    }

    public function getStokKritis(): int
    {
        return Obat::where('stok', '<=', 10)->count();
    }

    public function getPermintaanPending(): int
    {
        return PermintaanObat::where('status', 'pending')->count();
    }

    public function getPoPerluTindakan(): int
    {
        return PembelianObat::whereIn('status', ['pending', 'menunggu_konfirmasi_gudang'])->count();
    }

    public function getPoBelumSelesai(): int
    {
        return PembelianObat::where('status', 'diproses')->count();
    }

    public function getPoSelesaiBelumLunas(): int
    {
        return PembelianObat::query()
            ->where('status', 'selesai')
            ->whereRaw("(SELECT COALESCE(SUM(pb.total_bayar), 0) FROM pembayaran pb WHERE pb.id_pembelian_obat = pembelian_obat.id AND pb.status = 'disetujui_supplier') < (SELECT COALESCE(SUM(dp.jumlah_pesan * dp.harga_satuan), 0) FROM detail_pembelian_obat dp WHERE dp.id_pembelian_obat = pembelian_obat.id)")
            ->count();
    }

    public function getPembayaranMenungguSupplier(): int
    {
        return Pembayaran::where('status', 'menunggu_supplier')->count();
    }

    public function getPoPenerimaanSebagian(): int
    {
        return PembelianObat::query()
            ->where('status', 'diproses')
            ->whereHas('detail_pembelian', function ($query) {
                $query->whereRaw('(SELECT COALESCE(SUM(dpo.jumlah_diterima), 0) FROM detail_penerimaan_obat dpo WHERE dpo.id_detail_pembelian = detail_pembelian_obat.id) > 0')
                    ->whereRaw('(SELECT COALESCE(SUM(dpo.jumlah_diterima), 0) FROM detail_penerimaan_obat dpo WHERE dpo.id_detail_pembelian = detail_pembelian_obat.id) < detail_pembelian_obat.jumlah_pesan');
            })
            ->count();
    }

    public function getTotalKekuranganPenerimaan(): int
    {
        return (int) DetailPembelianObat::query()
            ->whereHas('pembelian_obat', fn ($query) => $query->where('status', 'diproses'))
            ->whereRaw('(jumlah_pesan - (SELECT COALESCE(SUM(dpo.jumlah_diterima), 0) FROM detail_penerimaan_obat dpo WHERE dpo.id_detail_pembelian = detail_pembelian_obat.id)) > 0')
            ->selectRaw('COALESCE(SUM(jumlah_pesan - (SELECT COALESCE(SUM(dpo.jumlah_diterima), 0) FROM detail_penerimaan_obat dpo WHERE dpo.id_detail_pembelian = detail_pembelian_obat.id)), 0) as total')
            ->value('total');
    }

    public function getTindakLanjutRows(): array
    {
        $rows = [];

        $pendingPayment = Pembayaran::query()
            ->where('status', 'menunggu_supplier')
            ->with('pembelian_obat.supplier')
            ->latest('tanggal_bayar')
            ->limit(4)
            ->get();

        foreach ($pendingPayment as $payment) {
            $po = $payment->pembelian_obat;
            $rows[] = [
                'prioritas' => 'Persetujuan',
                'judul' => 'Pembayaran menunggu supplier',
                'detail' => 'PO-' . str_pad((string) $po?->id, 5, '0', STR_PAD_LEFT) . ' · Rp ' . number_format((int) $payment->total_bayar, 0, ',', '.'),
                'supplier' => $po?->supplier?->nama_supplier ?? '-',
                'url' => PembayaranResource::getUrl('view', ['record' => $payment]),
                'tone' => 'warning',
            ];
        }

        $priceChanges = PembelianObat::query()
            ->where('status', 'menunggu_konfirmasi_gudang')
            ->with('supplier')
            ->latest('tanggal_pesan')
            ->limit(4)
            ->get();

        foreach ($priceChanges as $po) {
            $rows[] = [
                'prioritas' => 'Harga',
                'judul' => 'Perubahan harga perlu dikonfirmasi',
                'detail' => 'PO-' . str_pad((string) $po->id, 5, '0', STR_PAD_LEFT),
                'supplier' => $po->supplier?->nama_supplier ?? '-',
                'url' => PembelianObatResource::getUrl('view', ['record' => $po]),
                'tone' => 'warning',
            ];
        }

        $partial = PembelianObat::query()
            ->where('status', 'diproses')
            ->whereHas('detail_pembelian', function ($query) {
                $query->whereRaw('(SELECT COALESCE(SUM(dpo.jumlah_diterima), 0) FROM detail_penerimaan_obat dpo WHERE dpo.id_detail_pembelian = detail_pembelian_obat.id) > 0')
                    ->whereRaw('(SELECT COALESCE(SUM(dpo.jumlah_diterima), 0) FROM detail_penerimaan_obat dpo WHERE dpo.id_detail_pembelian = detail_pembelian_obat.id) < detail_pembelian_obat.jumlah_pesan');
            })
            ->with('supplier')
            ->latest('tanggal_pesan')
            ->limit(4)
            ->get();

        foreach ($partial as $po) {
            $kurang = (int) $po->detail_pembelian->sum(function ($detail) {
                return max(0, (int) $detail->jumlah_pesan - (int) $detail->jumlah_diterima);
            });

            $rows[] = [
                'prioritas' => 'Penerimaan',
                'judul' => 'Penerimaan belum lengkap',
                'detail' => 'PO-' . str_pad((string) $po->id, 5, '0', STR_PAD_LEFT) . ' · kurang ' . number_format($kurang, 0, ',', '.') . ' unit',
                'supplier' => $po->supplier?->nama_supplier ?? '-',
                'url' => PembelianObatResource::getUrl('view', ['record' => $po]),
                'tone' => 'danger',
            ];
        }

        $unpaid = PembelianObat::query()
            ->where('status', 'selesai')
            ->whereRaw("(SELECT COALESCE(SUM(pb.total_bayar), 0) FROM pembayaran pb WHERE pb.id_pembelian_obat = pembelian_obat.id AND pb.status = 'disetujui_supplier') < (SELECT COALESCE(SUM(dp.jumlah_pesan * dp.harga_satuan), 0) FROM detail_pembelian_obat dp WHERE dp.id_pembelian_obat = pembelian_obat.id)")
            ->with('supplier')
            ->latest('tanggal_pesan')
            ->limit(4)
            ->get();

        foreach ($unpaid as $po) {
            $rows[] = [
                'prioritas' => 'Tagihan',
                'judul' => 'Barang sudah diterima, tagihan belum lunas',
                'detail' => 'PO-' . str_pad((string) $po->id, 5, '0', STR_PAD_LEFT) . ' · sisa Rp ' . number_format((int) $po->sisa_tagihan, 0, ',', '.'),
                'supplier' => $po->supplier?->nama_supplier ?? '-',
                'url' => PembelianObatResource::getUrl('view', ['record' => $po]),
                'tone' => 'danger',
            ];
        }

        return collect($rows)
            ->sortBy(fn (array $row) => match ($row['tone']) {
                'danger' => 0,
                'warning' => 1,
                default => 2,
            })
            ->take(10)
            ->values()
            ->all();
    }

    public function url(string $resource): string
    {
        return match ($resource) {
            'stok' => ObatResource::getUrl(),
            'permintaan' => PermintaanObatResource::getUrl(),
            'po' => PembelianObatResource::getUrl(),
            'po_perlu_tindakan' => PembelianObatResource::getUrl() . '?activeTab=perlu_tindakan',
            'po_belum_selesai' => PembelianObatResource::getUrl() . '?activeTab=belum_selesai',
            'po_belum_lunas' => PembelianObatResource::getUrl() . '?activeTab=selesai&tableFilters[status_pembayaran][value]=belum_dibayar',
            'penerimaan' => PenerimaanObatResource::getUrl(),
            'penerimaan_perlu_diposting' => PenerimaanObatResource::getUrl() . '?activeTab=perlu_diposting',
            'pembayaran' => PembayaranResource::getUrl(),
            'pembayaran_pending' => PembayaranResource::getUrl() . '?activeTab=perlu_persetujuan',
            default => '#',
        };
    }
}
