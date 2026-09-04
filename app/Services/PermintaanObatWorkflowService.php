<?php

namespace App\Services;

use App\Models\PermintaanObat;
use App\Models\User;
use App\Notifications\PermintaanObatDiperbarui;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PermintaanObatWorkflowService
{
    /**
     * Menyetujui permintaan berdasarkan keputusan per item dari modal gudang.
     * Stok hanya berkurang untuk item yang dicentang dan jumlah yang disetujui.
     */
    public function approve(PermintaanObat $permintaan, int $userId, array $items, ?string $catatan = null): void
    {
        DB::transaction(function () use ($permintaan, $userId, $items, $catatan): void {
            $locked = PermintaanObat::query()->lockForUpdate()->findOrFail($permintaan->id);

            if ($locked->status !== 'pending') {
                throw ValidationException::withMessages(['status' => 'Hanya permintaan yang masih menunggu yang dapat disetujui.']);
            }

            $locked->load('detail_permintaan.obat');
            if ($locked->detail_permintaan->isEmpty()) {
                throw ValidationException::withMessages(['detail_permintaan' => 'Permintaan belum memiliki rincian obat.']);
            }

            $submitted = collect($items)->keyBy(fn ($item) => (int) ($item['id'] ?? 0));
            $selectedCount = 0;

            foreach ($locked->detail_permintaan as $detail) {
                $row = $submitted->get((int) $detail->id);
                if (! $row) {
                    throw ValidationException::withMessages(['detail_permintaan' => 'Rincian permintaan tidak lengkap. Silakan periksa semua item sebelum menyetujui.']);
                }

                $siapkan = (bool) ($row['siapkan'] ?? false);
                $qty = $siapkan ? (int) ($row['jumlah_disetujui'] ?? 0) : 0;

                if (! $siapkan) {
                    $detail->update(['jumlah_disetujui' => 0]);
                    continue;
                }

                $selectedCount++;
                if ($qty <= 0 || $qty > (int) $detail->jumlah_diminta) {
                    throw ValidationException::withMessages([
                        'detail_permintaan' => "Jumlah disetujui untuk {$detail->obat->nama_obat} harus lebih dari 0 dan tidak boleh melebihi jumlah diminta.",
                    ]);
                }

                $obat = $detail->obat()->lockForUpdate()->first();
                if (! $obat || $qty > (int) $obat->stok) {
                    throw ValidationException::withMessages([
                        'detail_permintaan' => "Stok {$detail->obat->nama_obat} tidak mencukupi. Stok tersedia: " . ($obat?->stok ?? 0) . " {$detail->obat->satuan}.",
                    ]);
                }

                $detail->update(['jumlah_disetujui' => $qty]);
                $detail->setRelation('obat', $obat);
            }

            if ($selectedCount === 0) {
                throw ValidationException::withMessages(['detail_permintaan' => 'Pilih minimal satu obat yang akan disiapkan.']);
            }

            $locked->update([
                'status' => 'disetujui',
                'disetujui_at' => now(),
                'disetujui_oleh' => $userId,
                'catatan_gudang' => $catatan ?: null,
                'alasan_penolakan' => null,
            ]);

            foreach ($locked->detail_permintaan as $detail) {
                $qty = (int) $detail->jumlah_disetujui;
                if ($qty <= 0) {
                    continue;
                }

                $obat = $detail->obat()->lockForUpdate()->firstOrFail();

                app(StokObatService::class)->keluar(
                    obat: $obat,
                    jumlah: $qty,
                    referensi: 'REQ-' . str_pad((string) $locked->id, 5, '0', STR_PAD_LEFT),
                    sumber: 'permintaan',
                    referensiId: $locked->id,
                    keterangan: 'Pengeluaran obat untuk permintaan internal yang disetujui.',
                    tanggal: $locked->tanggal_permintaan?->toDateString(),
                );
            }

            $locked->update(['stok_diposting_at' => now()]);
        });

        $this->notifyApplicant($permintaan->fresh(), 'disetujui');
    }

    public function reject(PermintaanObat $permintaan, int $userId, string $reason): void
    {
        DB::transaction(function () use ($permintaan, $userId, $reason): void {
            $locked = PermintaanObat::query()->lockForUpdate()->findOrFail($permintaan->id);
            if ($locked->status !== 'pending') {
                throw ValidationException::withMessages(['status' => 'Permintaan ini sudah diproses.']);
            }
            $locked->update([
                'status' => 'ditolak',
                'alasan_penolakan' => $reason,
                'catatan_gudang' => null,
                'ditolak_at' => now(),
                'ditolak_oleh' => $userId,
            ]);
        });
        $this->notifyApplicant($permintaan->fresh(), 'ditolak');
    }

    public function handover(PermintaanObat $permintaan, int $userId, ?string $catatan = null): void
    {
        DB::transaction(function () use ($permintaan, $userId, $catatan): void {
            $locked = PermintaanObat::query()->lockForUpdate()->findOrFail($permintaan->id);
            if ($locked->status !== 'disetujui') {
                throw ValidationException::withMessages(['status' => 'Hanya permintaan yang sudah disetujui yang dapat diserahkan.']);
            }
            $locked->update([
                'status' => 'diserahkan',
                'diserahkan_at' => now(),
                'diserahkan_oleh' => $userId,
                'catatan_gudang' => $catatan ?: $locked->catatan_gudang,
            ]);
        });
        $this->notifyApplicant($permintaan->fresh(), 'diserahkan');
    }

    public function confirmReceived(PermintaanObat $permintaan, int $userId): void
    {
        DB::transaction(function () use ($permintaan, $userId): void {
            $locked = PermintaanObat::query()->lockForUpdate()->findOrFail($permintaan->id);
            if ($locked->status !== 'diserahkan') {
                throw ValidationException::withMessages(['status' => 'Konfirmasi hanya dapat dilakukan setelah obat diserahkan gudang.']);
            }
            if ((int) $locked->id_pengguna !== $userId) {
                throw ValidationException::withMessages(['status' => 'Hanya bidan pemohon yang dapat mengonfirmasi penerimaan.']);
            }
            $locked->update([
                'status' => 'selesai',
                'dikonfirmasi_at' => now(),
                'dikonfirmasi_oleh' => $userId,
            ]);
        });
        $this->notifyWarehouse($permintaan->fresh(), 'selesai');
    }

    public function cancel(PermintaanObat $permintaan, int $userId): void
    {
        DB::transaction(function () use ($permintaan, $userId): void {
            $locked = PermintaanObat::query()->lockForUpdate()->findOrFail($permintaan->id);
            if ($locked->status !== 'pending' || (int) $locked->id_pengguna !== $userId) {
                throw ValidationException::withMessages(['status' => 'Permintaan tidak dapat dibatalkan pada tahap ini.']);
            }
            $locked->update(['status' => 'dibatalkan']);
        });
        $this->notifyWarehouse($permintaan->fresh(), 'dibatalkan');
    }

    private function notifyApplicant(PermintaanObat $permintaan, string $status): void
    {
        $pemohon = $permintaan->pengguna;
        if ($pemohon) {
            $pemohon->notify(new PermintaanObatDiperbarui($permintaan, auth()->user() ?? $pemohon));
        }
    }

    private function notifyWarehouse(PermintaanObat $permintaan, string $status): void
    {
        User::query()->whereIn('role', ['admin', 'karyawan'])->get()->each(function (User $user) use ($permintaan): void {
            $user->notify(new PermintaanObatDiperbarui($permintaan, auth()->user() ?? $user));
        });
    }
}
