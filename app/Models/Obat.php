<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    protected $table = 'obat';
    protected $guarded = ['id'];

    public function getTotalPendingAttribute(): int
    {
        return \App\Models\DetailPermintaanObat::query()
            ->whereHas('permintaan_obat', function ($query) {
                $query->where('status', 'pending');
            })
            ->where('id_obat', $this->id)
            ->sum('jumlah_diminta');
    }

    public function getStokTersediaAttribute(): int
    {
        return max(
            0,
            $this->stok - $this->total_pending
        );
    }

    public function kategori_obat()
    {
        return $this->belongsTo(KategoriObat::class, 'id_kategori_obat');
    }
    public function detail_pembelian()
    {
        return $this->hasMany(DetailPembelianObat::class, 'id_obat');
    }
    public function detail_permintaan()
    {
        return $this->hasMany(DetailPermintaanObat::class, 'id_obat');
    }
    public function riwayat_stok()
    {
        return $this->hasMany(RiwayatStok::class, 'id_obat');
    }
    public function penyesuaian_stok()
    {
        return $this->hasMany(PenyesuaianStok::class, 'id_obat');
    }
}
