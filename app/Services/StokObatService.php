<?php

namespace App\Services;

use App\Models\Obat;
use App\Models\PenyesuaianStok;
use App\Models\PenerimaanObat;
use App\Models\PermintaanObat;
use App\Models\RiwayatStok;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StokObatService
{
    public function masuk(
        Obat $obat,
        int $jumlah,
        ?string $referensi = null,
        ?string $sumber = null,
        ?int $referensiId = null,
        ?string $keterangan = null,
        ?string $tanggal = null,
    ): RiwayatStok {
        return $this->mutasi(
            $obat,
            'masuk',
            $jumlah,
            $referensi,
            $sumber,
            $referensiId,
            $keterangan,
            $tanggal,
        );
    }

    public function keluar(
        Obat $obat,
        int $jumlah,
        ?string $referensi = null,
        ?string $sumber = null,
        ?int $referensiId = null,
        ?string $keterangan = null,
        ?string $tanggal = null,
    ): RiwayatStok {
        return $this->mutasi(
            $obat,
            'keluar',
            $jumlah,
            $referensi,
            $sumber,
            $referensiId,
            $keterangan,
            $tanggal,
        );
    }

    /**
     * Memposting penerimaan ke stok. Satu penerimaan hanya boleh diposting sekali.
     */
    public function postingPenerimaan(PenerimaanObat $penerimaan): void
    {
        DB::transaction(function () use ($penerimaan) {
            $penerimaan->refresh();

            if ($penerimaan->stok_diposting_at) {
                return;
            }

            $penerimaan->load('detail_penerimaan.detail_pembelian.obat', 'pembelian_obat');

            if ($penerimaan->pembelian_obat?->status === 'dibatalkan') {
                throw ValidationException::withMessages(['id_pembelian_obat' => 'PO yang dibatalkan tidak dapat menerima barang.']);
            }

            if ($penerimaan->detail_penerimaan->isEmpty()) {
                throw ValidationException::withMessages([
                    'detail_penerimaan' => 'Penerimaan belum memiliki item obat.',
                ]);
            }

            foreach ($penerimaan->detail_penerimaan as $detail) {
                $detailPembelian = $detail->detail_pembelian;

                if (! $detailPembelian || ! $detailPembelian->obat) {
                    throw ValidationException::withMessages([
                        'detail_penerimaan' => 'Ada item penerimaan yang tidak memiliki obat atau detail pembelian yang valid.',
                    ]);
                }

                $jumlah = (int) $detail->jumlah_diterima;
                $jumlahPesan = (int) $detailPembelian->jumlah_pesan;

                if ($jumlah <= 0) {
                    throw ValidationException::withMessages([
                        'detail_penerimaan' => "Jumlah diterima untuk {$detailPembelian->obat->nama_obat} harus lebih besar dari 0.",
                    ]);
                }

                $sudahDiterima = (int) $detailPembelian->detail_penerimaan()
                    ->where('id', '!=', $detail->id)
                    ->sum('jumlah_diterima');

                if (($sudahDiterima + $jumlah) > $jumlahPesan) {
                    throw ValidationException::withMessages([
                        'detail_penerimaan' => "Jumlah diterima {$detailPembelian->obat->nama_obat} melebihi jumlah yang dipesan.",
                    ]);
                }

                $this->masuk(
                    obat: $detailPembelian->obat,
                    jumlah: $jumlah,
                    referensi: 'GR-' . str_pad((string) $penerimaan->id, 5, '0', STR_PAD_LEFT),
                    sumber: 'penerimaan',
                    referensiId: $penerimaan->id,
                    keterangan: 'Penerimaan obat dari pembelian PO-' . str_pad((string) $penerimaan->id_pembelian_obat, 5, '0', STR_PAD_LEFT),
                    tanggal: $penerimaan->tanggal_terima?->toDateString(),
                );
            }

            $penerimaan->update(['stok_diposting_at' => now()]);

            $po = $penerimaan->pembelian_obat;
            $po->load('detail_pembelian.detail_penerimaan');
            $semuaLengkap = $po->detail_pembelian->isNotEmpty()
                && $po->detail_pembelian->every(fn ($detail) => (int) $detail->detail_penerimaan()->sum('jumlah_diterima') >= (int) $detail->jumlah_pesan);

            if ($semuaLengkap && $po->status === 'diproses') {
                $po->update(['status' => 'selesai']);
            }
        });
    }

    /**
     * Memposting permintaan yang telah disetujui ke stok.
     */
    public function postingPermintaan(PermintaanObat $permintaan): void
    {
        DB::transaction(function () use ($permintaan) {
            $lockedPermintaan = PermintaanObat::query()->lockForUpdate()->findOrFail($permintaan->id);

            if ($lockedPermintaan->stok_diposting_at) {
                return;
            }

            if ($lockedPermintaan->status !== 'disetujui') {
                throw ValidationException::withMessages([
                    'status' => 'Hanya permintaan yang disetujui yang dapat mengurangi stok.',
                ]);
            }

            $lockedPermintaan->load('detail_permintaan.obat');

            if ($lockedPermintaan->detail_permintaan->isEmpty()) {
                throw ValidationException::withMessages([
                    'detail_permintaan' => 'Permintaan belum memiliki item obat.',
                ]);
            }

            foreach ($lockedPermintaan->detail_permintaan as $detail) {
                $jumlah = (int) $detail->jumlah_disetujui;

                if ($jumlah <= 0) {
                    throw ValidationException::withMessages([
                        'detail_permintaan' => "Jumlah disetujui untuk {$detail->obat->nama_obat} belum valid.",
                    ]);
                }

                if ($jumlah > (int) $detail->jumlah_diminta) {
                    throw ValidationException::withMessages([
                        'detail_permintaan' => "Jumlah disetujui untuk {$detail->obat->nama_obat} melebihi jumlah yang diminta.",
                    ]);
                }

                $this->keluar(
                    obat: $detail->obat,
                    jumlah: $jumlah,
                    referensi: 'REQ-' . str_pad((string) $lockedPermintaan->id, 5, '0', STR_PAD_LEFT),
                    sumber: 'permintaan',
                    referensiId: $lockedPermintaan->id,
                    keterangan: 'Pengeluaran obat berdasarkan permintaan internal.',
                    tanggal: $lockedPermintaan->tanggal_permintaan?->toDateString(),
                );
            }

            $lockedPermintaan->update(['stok_diposting_at' => now()]);
        });
    }


    /** Menyetujui permintaan dan langsung memposting pengurangan stok secara atomik. */
    public function setujuiPermintaan(PermintaanObat $permintaan): void
    {
        DB::transaction(function () use ($permintaan) {
            $lockedPermintaan = PermintaanObat::query()->lockForUpdate()->findOrFail($permintaan->id);

            if ($lockedPermintaan->status !== 'pending') {
                throw ValidationException::withMessages(['status' => 'Hanya permintaan berstatus menunggu yang dapat disetujui.']);
            }

            $lockedPermintaan->load('detail_permintaan.obat');
            if ($lockedPermintaan->detail_permintaan->isEmpty()) {
                throw ValidationException::withMessages(['detail_permintaan' => 'Permintaan belum memiliki item obat.']);
            }

            foreach ($lockedPermintaan->detail_permintaan as $detail) {
                $jumlah = (int) $detail->jumlah_disetujui;
                if ($jumlah <= 0 || $jumlah > (int) $detail->jumlah_diminta) {
                    throw ValidationException::withMessages(['detail_permintaan' => "Jumlah disetujui untuk {$detail->obat->nama_obat} harus lebih dari 0 dan tidak melebihi jumlah diminta."]);
                }
                if ($jumlah > (int) $detail->obat->stok) {
                    throw ValidationException::withMessages(['detail_permintaan' => "Stok {$detail->obat->nama_obat} tidak mencukupi. Stok tersedia: {$detail->obat->stok} {$detail->obat->satuan}."]);
                }
            }

            $lockedPermintaan->update(['status' => 'disetujui']);

            foreach ($lockedPermintaan->detail_permintaan as $detail) {
                $this->keluar(
                    obat: $detail->obat,
                    jumlah: (int) $detail->jumlah_disetujui,
                    referensi: 'REQ-' . str_pad((string) $lockedPermintaan->id, 5, '0', STR_PAD_LEFT),
                    sumber: 'permintaan',
                    referensiId: $lockedPermintaan->id,
                    keterangan: 'Pengeluaran obat berdasarkan permintaan internal.',
                    tanggal: $lockedPermintaan->tanggal_permintaan?->toDateString(),
                );
            }

            $lockedPermintaan->update(['stok_diposting_at' => now()]);
        });
    }

    /**
     * Memposting penyesuaian stok ke saldo obat.
     */
    public function postingPenyesuaian(PenyesuaianStok $penyesuaian): void
    {
        DB::transaction(function () use ($penyesuaian) {
            $penyesuaian->refresh();

            if ($penyesuaian->stok_diposting_at) {
                return;
            }

            $penyesuaian->load('obat');

            $reference = 'ADJ-' . str_pad((string) $penyesuaian->id, 5, '0', STR_PAD_LEFT);
            $keterangan = $penyesuaian->keterangan
                ?: 'Penyesuaian stok: ' . str_replace('_', ' ', $penyesuaian->alasan);

            if ($penyesuaian->jenis === 'penambahan') {
                $this->masuk(
                    obat: $penyesuaian->obat,
                    jumlah: (int) $penyesuaian->jumlah,
                    referensi: $reference,
                    sumber: 'penyesuaian',
                    referensiId: $penyesuaian->id,
                    keterangan: $keterangan,
                    tanggal: $penyesuaian->tanggal?->toDateString(),
                );
            } else {
                $this->keluar(
                    obat: $penyesuaian->obat,
                    jumlah: (int) $penyesuaian->jumlah,
                    referensi: $reference,
                    sumber: 'penyesuaian',
                    referensiId: $penyesuaian->id,
                    keterangan: $keterangan,
                    tanggal: $penyesuaian->tanggal?->toDateString(),
                );
            }

            $penyesuaian->update(['stok_diposting_at' => now()]);
        });
    }

    private function mutasi(
        Obat $obat,
        string $jenis,
        int $jumlah,
        ?string $referensi,
        ?string $sumber,
        ?int $referensiId,
        ?string $keterangan,
        ?string $tanggal,
    ): RiwayatStok {
        if ($jumlah <= 0) {
            throw ValidationException::withMessages([
                'jumlah' => 'Jumlah perubahan stok harus lebih besar dari 0.',
            ]);
        }

        $lockedObat = Obat::query()->lockForUpdate()->findOrFail($obat->id);
        $stokSebelum = (int) $lockedObat->stok;

        if ($jenis === 'keluar') {
            if ($jumlah > $stokSebelum) {
                throw ValidationException::withMessages([
                    'jumlah' => "Stok {$lockedObat->nama_obat} tidak mencukupi. Stok saat ini: {$stokSebelum} {$lockedObat->satuan}.",
                ]);
            }

            $stokSesudah = $stokSebelum - $jumlah;
        } else {
            $stokSesudah = $stokSebelum + $jumlah;

            if ($stokSesudah > 65535) {
                throw ValidationException::withMessages([
                    'jumlah' => "Stok {$lockedObat->nama_obat} melebihi batas penyimpanan.",
                ]);
            }
        }

        $lockedObat->update(['stok' => $stokSesudah]);

        return RiwayatStok::query()->create([
            'id_obat' => $lockedObat->id,
            'jenis_transaksi' => $jenis,
            'jumlah' => $jumlah,
            'stok_sebelum' => $stokSebelum,
            'stok_sesudah' => $stokSesudah,
            'referensi_transaksi' => $referensi,
            'referensi_tipe' => $sumber,
            'referensi_id' => $referensiId,
            'keterangan' => $keterangan,
            'tanggal_mutasi' => $tanggal ?: now()->toDateString(),
        ]);
    }
}
