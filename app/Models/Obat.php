<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    protected $table = 'obat';
    protected $guarded = ['id'];

    public function getTotalPendingAttribute(): int
    {
        return DetailPermintaanObat::query()
            ->whereHas('permintaan_obat', fn ($query) => $query->where('status', 'pending'))
            ->where('id_obat', $this->id)
            ->sum('jumlah_diminta');
    }

    public function getStokTersediaAttribute(): int
    {
        return max(0, $this->stok - $this->total_pending);
    }

    /** Harga dari pembelian terakhir yang masih valid (bukan PO dibatalkan). */
    public function getHargaBeliTerakhirAttribute(): ?int
    {
        return $this->detail_pembelian()
            ->whereHas('pembelian_obat', fn ($query) => $query->where('status', '!=', 'dibatalkan'))
            ->join('pembelian_obat', 'detail_pembelian_obat.id_pembelian_obat', '=', 'pembelian_obat.id')
            ->orderByDesc('pembelian_obat.tanggal_pesan')
            ->orderByDesc('detail_pembelian_obat.id')
            ->value('detail_pembelian_obat.harga_satuan');
    }

    public function hargaBeliTerakhirDetail()
    {
        return $this->hasOne(DetailPembelianObat::class, 'id_obat')
            ->whereHas('pembelian_obat', fn ($query) => $query->where('status', '!=', 'dibatalkan'))
            ->latestOfMany('id');
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
