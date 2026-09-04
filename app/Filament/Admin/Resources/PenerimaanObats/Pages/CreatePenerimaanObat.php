<?php

namespace App\Filament\Admin\Resources\PenerimaanObats\Pages;

use App\Filament\Admin\Resources\PenerimaanObats\PenerimaanObatResource;
use App\Filament\Admin\Resources\PenerimaanObats\Schemas\PenerimaanObatForm;
use App\Models\DetailPembelianObat;
use App\Models\DetailPenerimaanObat;
use App\Models\PembelianObat;
use App\Models\PenerimaanObat;
use App\Services\StokObatService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CreatePenerimaanObat extends CreateRecord
{
    protected static string $resource = PenerimaanObatResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $poId = (int) ($data['id_pembelian_obat'] ?? 0);
        $items = collect($data['detail_penerimaan'] ?? [])
            ->map(fn (array $item): array => [
                'id_detail_pembelian' => (int) ($item['id_detail_pembelian'] ?? 0),
                'jumlah_diterima' => (int) ($item['jumlah_diterima'] ?? 0),
            ])
            ->filter(fn (array $item): bool => $item['id_detail_pembelian'] > 0 && $item['jumlah_diterima'] > 0)
            ->values()
            ->all();

        if ($poId <= 0) {
            throw ValidationException::withMessages([
                'id_pembelian_obat' => 'Silakan pilih nomor PO terlebih dahulu.',
            ]);
        }

        if ($items === []) {
            throw ValidationException::withMessages([
                'detail_penerimaan' => 'Belum ada barang yang diterima. Isi Qty Aktual minimal pada satu item yang benar-benar datang.',
            ]);
        }

        $data['detail_penerimaan'] = $items;

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        return DB::transaction(function () use ($data): Model {
            /** @var PembelianObat $po */
            $po = PembelianObat::query()
                ->lockForUpdate()
                ->findOrFail((int) $data['id_pembelian_obat']);

            if ($po->status !== 'diproses') {
                throw ValidationException::withMessages([
                    'id_pembelian_obat' => 'PO tidak dapat menerima barang. Status PO harus Diproses.',
                ]);
            }

            $items = collect($data['detail_penerimaan'] ?? []);
            $validatedItems = [];

            foreach ($items as $item) {
                /** @var DetailPembelianObat|null $detail */
                $detail = DetailPembelianObat::query()
                    ->where('id', (int) $item['id_detail_pembelian'])
                    ->where('id_pembelian_obat', $po->id)
                    ->lockForUpdate()
                    ->first();

                if (! $detail) {
                    throw ValidationException::withMessages([
                        'detail_penerimaan' => 'Ada item penerimaan yang tidak sesuai dengan PO yang dipilih.',
                    ]);
                }

                $sudahDiterima = (int) DetailPenerimaanObat::query()
                    ->where('id_detail_pembelian', $detail->id)
                    ->sum('jumlah_diterima');
                $sisa = max(0, (int) $detail->jumlah_pesan - $sudahDiterima);
                $jumlah = (int) $item['jumlah_diterima'];

                if ($jumlah <= 0) {
                    continue;
                }

                if ($sisa <= 0) {
                    throw ValidationException::withMessages([
                        'detail_penerimaan' => "{$detail->obat?->nama_obat} sudah lengkap diterima dan tidak boleh diterima lagi.",
                    ]);
                }

                if ($jumlah > $sisa) {
                    throw ValidationException::withMessages([
                        'detail_penerimaan' => "Jumlah {$detail->obat?->nama_obat} melebihi sisa PO. Sisa yang dapat diterima hanya {$sisa} {$detail->obat?->satuan}.",
                    ]);
                }

                $validatedItems[] = [
                    'id_detail_pembelian' => $detail->id,
                    'jumlah_diterima' => $jumlah,
                ];
            }

            if ($validatedItems === []) {
                throw ValidationException::withMessages([
                    'detail_penerimaan' => 'Minimal satu obat harus diterima dengan jumlah lebih dari 0.',
                ]);
            }

            $penerimaan = new PenerimaanObat();
            $penerimaan->id_pembelian_obat = $po->id;
            $penerimaan->nomor_faktur = filled($data['nomor_faktur'] ?? null) ? $data['nomor_faktur'] : null;
            $penerimaan->tanggal_terima = $data['tanggal_terima'];
            $penerimaan->save();

            foreach ($validatedItems as $item) {
                $penerimaan->detail_penerimaan()->create($item);
            }

            // Posting stok dilakukan sebelum transaksi selesai. Jika gagal,
            // seluruh penerimaan dan perubahan stok ikut rollback.
            app(StokObatService::class)->postingPenerimaan($penerimaan);

            return $penerimaan->fresh();
        });
    }

    protected function getRedirectUrl(): string
    {
        return \App\Filament\Admin\Resources\PembelianObats\PembelianObatResource::getUrl('view', [
            'record' => $this->record->id_pembelian_obat,
        ]);
    }
}
