<?php

namespace App\Services;

use App\Models\DetailPembelianObat;
use App\Models\PembelianObat;
use App\Notifications\PurchaseOrderHargaDikonfirmasi;
use App\Notifications\PurchaseOrderPerluKonfirmasiGudang;
use App\Notifications\PurchaseOrderSupplierDitolak;
use App\Notifications\PurchaseOrderSupplierDikonfirmasi;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PurchaseOrderWorkflowService
{
    public function supplierConfirm(PembelianObat $po, array $items, ?string $catatan = null): void
    {
        DB::transaction(function () use ($po, $items, $catatan): void {
            $po = PembelianObat::query()->lockForUpdate()->with('supplier.pengguna')->findOrFail($po->id);

            if ($po->status !== 'pending') {
                throw ValidationException::withMessages(['status' => 'PO ini sudah tidak berada pada tahap menunggu konfirmasi supplier.']);
            }

            $detailIds = $po->detail_pembelian()->pluck('id')->map(fn ($id) => (int) $id)->all();
            $submittedIds = collect($items)->pluck('id')->map(fn ($id) => (int) $id)->all();

            if (count($detailIds) !== count($submittedIds) || array_diff($detailIds, $submittedIds) || array_diff($submittedIds, $detailIds)) {
                throw ValidationException::withMessages(['items' => 'Seluruh rincian obat pada PO harus dikonfirmasi.']);
            }

            $hasChangedPrice = false;

            foreach ($items as $item) {
                $detail = DetailPembelianObat::query()->lockForUpdate()->findOrFail($item['id']);
                if ((int) $detail->id_pembelian_obat !== (int) $po->id) {
                    throw ValidationException::withMessages(['items' => 'Rincian obat tidak sesuai dengan PO.']);
                }

                $original = (int) $detail->harga_satuan;
                $sesuai = (bool) ($item['harga_sesuai'] ?? false);
                $hargaSupplier = $sesuai ? $original : (int) ($item['harga_supplier'] ?? 0);

                if ($hargaSupplier < 0) {
                    throw ValidationException::withMessages(['items' => 'Harga supplier tidak boleh negatif.']);
                }

                $detail->update([
                    'harga_supplier' => $hargaSupplier,
                    'status_harga' => $hargaSupplier === $original ? 'sesuai' : 'berubah',
                    'catatan_harga_supplier' => $item['catatan_harga_supplier'] ?? null,
                ]);

                if ($hargaSupplier !== $original) {
                    $hasChangedPrice = true;
                }
            }

            $po->update([
                'supplier_dikonfirmasi_at' => now(),
                'supplier_catatan' => $catatan,
                'status' => $hasChangedPrice ? 'menunggu_konfirmasi_gudang' : 'diproses',
                'ditolak_supplier_at' => null,
                'alasan_penolakan_supplier' => null,
            ]);

            $pengguna = $po->pengguna;
            if ($pengguna) {
                $pengguna->notify(new PurchaseOrderSupplierDikonfirmasi($po->fresh('supplier')));
            }

            if ($hasChangedPrice) {
                User::query()->whereIn('role', ['admin', 'karyawan'])
                    ->when($pengguna, fn ($query) => $query->where('id', '!=', $pengguna->getKey()))
                    ->get()
                    ->each(fn (User $user) => $user->notify(new PurchaseOrderPerluKonfirmasiGudang($po->fresh('supplier'))));
                if ($pengguna) {
                    $pengguna->notify(new PurchaseOrderPerluKonfirmasiGudang($po->fresh('supplier')));
                }
            }
        });
    }

    public function supplierReject(PembelianObat $po, string $reason): void
    {
        DB::transaction(function () use ($po, $reason): void {
            $po = PembelianObat::query()->lockForUpdate()->with('supplier')->findOrFail($po->id);

            if ($po->status !== 'pending') {
                throw ValidationException::withMessages(['status' => 'PO ini sudah tidak dapat ditolak pada tahap sekarang.']);
            }

            $reason = trim($reason);
            if ($reason === '') {
                throw ValidationException::withMessages(['alasan_penolakan_supplier' => 'Alasan penolakan wajib diisi.']);
            }

            $po->update([
                'status' => 'ditolak_supplier',
                'ditolak_supplier_at' => now(),
                'alasan_penolakan_supplier' => $reason,
                'supplier_dikonfirmasi_at' => now(),
            ]);

            $po->pengguna?->notify(new PurchaseOrderSupplierDitolak($po->fresh('supplier')));
        });
    }

    public function approvePriceChanges(PembelianObat $po, int $userId): void
    {
        DB::transaction(function () use ($po, $userId): void {
            $po = PembelianObat::query()->lockForUpdate()->with('supplier')->findOrFail($po->id);
            if ($po->status !== 'menunggu_konfirmasi_gudang') {
                throw ValidationException::withMessages(['status' => 'PO ini tidak sedang menunggu konfirmasi harga dari gudang.']);
            }

            foreach ($po->detail_pembelian()->lockForUpdate()->get() as $detail) {
                if ($detail->harga_supplier === null) {
                    throw ValidationException::withMessages(['items' => 'Ada harga supplier yang belum dikonfirmasi.']);
                }
                $detail->update([
                    'harga_satuan' => $detail->harga_supplier,
                    'status_harga' => 'disetujui',
                ]);
            }

            $po->update([
                'status' => 'diproses',
                'harga_dikonfirmasi_at' => now(),
                'harga_dikonfirmasi_oleh' => $userId,
            ]);

            $po->supplier?->pengguna?->notify(new PurchaseOrderHargaDikonfirmasi($po->fresh('supplier')));
        });
    }

    public function rejectPriceChanges(PembelianObat $po, int $userId, string $reason): void
    {
        DB::transaction(function () use ($po, $userId, $reason): void {
            $po = PembelianObat::query()->lockForUpdate()->with('supplier')->findOrFail($po->id);
            if ($po->status !== 'menunggu_konfirmasi_gudang') {
                throw ValidationException::withMessages(['status' => 'PO ini tidak sedang menunggu konfirmasi harga dari gudang.']);
            }

            $reason = trim($reason);
            if ($reason === '') {
                throw ValidationException::withMessages(['reason' => 'Alasan penolakan perubahan harga wajib diisi.']);
            }

            $po->detail_pembelian()->update([
                'harga_supplier' => null,
                'status_harga' => 'ditolak',
                'catatan_harga_supplier' => $reason,
            ]);

            $po->update([
                'status' => 'pending',
                'harga_dikonfirmasi_at' => now(),
                'harga_dikonfirmasi_oleh' => $userId,
                'supplier_dikonfirmasi_at' => null,
                'supplier_catatan' => 'Perubahan harga ditolak gudang: ' . $reason,
            ]);

            $po->supplier?->pengguna?->notify(new PurchaseOrderHargaDikonfirmasi($po->fresh('supplier'), true, $reason));
        });
    }
}
