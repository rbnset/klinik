<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembelianObat extends Model
{
    protected $table = 'pembelian_obat';
    protected $guarded = ['id'];

    public function supplier() { return $this->belongsTo(Supplier::class, 'id_supplier'); }
    public function pengguna() { return $this->belongsTo(User::class, 'id_pengguna'); }
    public function detail_pembelian() { return $this->hasMany(DetailPembelianObat::class, 'id_pembelian_obat'); }
    public function penerimaan_obat() { return $this->hasMany(PenerimaanObat::class, 'id_pembelian_obat'); }
    public function pembayaran() { return $this->hasMany(Pembayaran::class, 'id_pembelian_obat'); }

    public function getTotalPesananAttribute(): int
    {
        return (int) $this->detail_pembelian()->selectRaw('COALESCE(SUM(jumlah_pesan * harga_satuan), 0) as total')->value('total');
    }

    public function getTotalDibayarAttribute(): int
    {
        return (int) $this->pembayaran()->sum('total_bayar');
    }

    public function getSisaTagihanAttribute(): int
    {
        return max(0, $this->total_pesanan - $this->total_dibayar);
    }

    public function getStatusPembayaranAttribute(): string
    {
        if ($this->total_dibayar <= 0) return 'belum_dibayar';
        if ($this->total_dibayar < $this->total_pesanan) return 'sebagian';
        return 'lunas';
    }

    public function getTotalItemAttribute(): int
    {
        return (int) $this->detail_pembelian()->count();
    }

    public function getTotalItemDiterimaAttribute(): int
    {
        $total = 0;
        foreach ($this->detail_pembelian()->with('detail_penerimaan')->get() as $detail) {
            if ((int) $detail->detail_penerimaan()->sum('jumlah_diterima') >= (int) $detail->jumlah_pesan) $total++;
        }
        return $total;
    }

    public function getStatusPenerimaanAttribute(): string
    {
        if ($this->total_item === 0) return 'belum_diterima';
        if ($this->total_item_diterima === 0) return 'belum_diterima';
        if ($this->total_item_diterima < $this->total_item) return 'sebagian';
        return 'lengkap';
    }
}
