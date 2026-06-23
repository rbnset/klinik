<?php

namespace Database\Seeders;

use App\Models\DetailPermintaanObat;
use App\Models\Obat;
use App\Models\PenyesuaianStok;
use App\Models\PermintaanObat;
use App\Models\RiwayatStok;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SirkulasiInternalSeeder extends Seeder
{
    public function run(): void
    {
        // === A. SIMULASI PERMINTAAN BIDAN ===
        for ($i = 1; $i <= 3; $i++) {
            $tgl = Carbon::now()->subDays(10 - $i);

            $request = PermintaanObat::create([
                'id_pengguna' => 3, // Bidan
                'tanggal_permintaan' => $tgl,
                'status' => 'disetujui',
                'keterangan' => 'Kebutuhan Poli KIA Minggu ke-' . $i,
            ]);

            // Bidan minta 2 macam obat
            for ($j = 1; $j <= 2; $j++) {
                // PERBAIKAN: Hanya ambil acak obat yang stoknya di atas 20
                $obat = Obat::where('stok', '>=', 20)->inRandomOrder()->first();

                // Jika secara kebetulan tidak ada obat bersisa > 20, lewati iterasi ini
                if (!$obat) {
                    continue;
                }

                $qtyMinta = rand(5, 15);

                DetailPermintaanObat::create([
                    'id_permintaan_obat' => $request->id,
                    'id_obat' => $obat->id,
                    'jumlah_diminta' => $qtyMinta + 2,
                    'jumlah_disetujui' => $qtyMinta,
                ]);

                // Catat Riwayat Stok (BARANG KELUAR)
                $stokAwal = $obat->stok;
                $stokAkhir = $stokAwal - $qtyMinta;

                RiwayatStok::create([
                    'id_obat' => $obat->id,
                    'jenis_transaksi' => 'keluar',
                    'jumlah' => $qtyMinta,
                    'stok_sebelum' => $stokAwal,
                    'stok_sesudah' => $stokAkhir, // Angka ini dijamin tidak akan minus
                    'referensi_transaksi' => 'REQ-' . $request->id . ' (Distribusi Internal)',
                ]);

                $obat->update(['stok' => $stokAkhir]);
            }
        }

        // === B. SIMULASI PENYESUAIAN STOK (STOK OPNAME) ===
        for ($k = 1; $k <= 2; $k++) {
            // PERBAIKAN: Hanya ambil obat yang stoknya di atas 5
            $obatOpname = Obat::where('stok', '>=', 5)->inRandomOrder()->first();

            if (!$obatOpname) {
                continue;
            }

            $qtyRusak = rand(1, 3);

            $penyesuaian = PenyesuaianStok::create([
                'id_obat' => $obatOpname->id,
                'id_pengguna' => 2, // Karyawan
                'tanggal' => Carbon::now()->subDays(2),
                'jenis' => 'pengurangan',
                'alasan' => 'rusak',
                'jumlah' => $qtyRusak,
                'keterangan' => 'Barang ditemukan penyok di rak gudang.',
            ]);

            // Catat Riwayat Stok (BARANG KELUAR OPNAME)
            $stokAwal = $obatOpname->stok;
            $stokAkhir = $stokAwal - $qtyRusak;

            RiwayatStok::create([
                'id_obat' => $obatOpname->id,
                'jenis_transaksi' => 'keluar',
                'jumlah' => $qtyRusak,
                'stok_sebelum' => $stokAwal,
                'stok_sesudah' => $stokAkhir,
                'referensi_transaksi' => 'ADJ-' . $penyesuaian->id . ' (Opname Rusak)',
            ]);

            $obatOpname->update(['stok' => $stokAkhir]);
        }
    }
}
