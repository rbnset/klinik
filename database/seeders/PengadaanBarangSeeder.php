<?php

namespace Database\Seeders;

use App\Models\DetailPembelianObat;
use App\Models\DetailPenerimaanObat;
use App\Models\Obat;
use App\Models\Pembayaran;
use App\Models\PembelianObat;
use App\Models\PenerimaanObat;
use App\Models\RiwayatStok;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PengadaanBarangSeeder extends Seeder
{
    public function run(): void
    {
        // Simulasi 3 Transaksi Pengadaan (Beli Barang)
        for ($i = 1; $i <= 3; $i++) {
            $tgl = Carbon::now()->subDays(30 - ($i * 5)); // Tanggal mundur agar grafik terisi

            // 1. Buat Dokumen PO
            $po = PembelianObat::create([
                'id_supplier' => rand(1, 5),
                'id_pengguna' => 2, // Karyawan Gudang
                'tanggal_pesan' => $tgl,
                'status' => 'selesai',
            ]);

            $totalBayar = 0;

            // 2. Rincian PO (Setiap PO beli 2 macam obat)
            for ($j = 1; $j <= 2; $j++) {
                $idObat = rand(1, 10);
                $obat = Obat::find($idObat);
                $qtyBeli = rand(50, 100);

                $detailPO = DetailPembelianObat::create([
                    'id_pembelian_obat' => $po->id,
                    'id_obat' => $idObat,
                    'jumlah_pesan' => $qtyBeli,
                    'harga_satuan' => $obat->harga_beli,
                ]);

                $totalBayar += ($qtyBeli * $obat->harga_beli);

                // 3. Catat Penerimaan (Faktur)
                $penerimaan = PenerimaanObat::firstOrCreate(
                    ['id_pembelian_obat' => $po->id],
                    ['nomor_faktur' => 'INV-SUP-' . $po->id . rand(100, 999), 'tanggal_terima' => $tgl->copy()->addDays(2)]
                );

                DetailPenerimaanObat::create([
                    'id_penerimaan_obat' => $penerimaan->id,
                    'id_detail_pembelian' => $detailPO->id,
                    'jumlah_diterima' => $qtyBeli,
                ]);

                // 4. Catat Riwayat Stok (BARANG MASUK) & Update Stok Master
                $stokAwal = $obat->stok;
                $stokAkhir = $stokAwal + $qtyBeli;

                RiwayatStok::create([
                    'id_obat' => $idObat,
                    'jenis_transaksi' => 'masuk',
                    'jumlah' => $qtyBeli,
                    'stok_sebelum' => $stokAwal,
                    'stok_sesudah' => $stokAkhir,
                    'referensi_transaksi' => 'PO-' . $po->id . ' / ' . $penerimaan->nomor_faktur,
                ]);

                $obat->update(['stok' => $stokAkhir]); // Pembaruan Stok Asli
            }

            // 5. Catat Pelunasan Tagihan
            Pembayaran::create([
                'id_pembelian_obat' => $po->id,
                'tanggal_bayar' => $tgl->copy()->addDays(3),
                'metode_pembayaran' => 'transfer',
                'total_bayar' => $totalBayar,
            ]);
        }
    }
}
