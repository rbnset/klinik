<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPembelianObat extends Model
{
    protected $table = 'detail_pembelian_obat';
    protected $guarded = ['id'];
    public function pembelian_obat() { return $this->belongsTo(PembelianObat::class, 'id_pembelian_obat'); }
    public function obat() { return $this->belongsTo(Obat::class, 'id_obat'); }
    public function detail_penerimaan() { return $this->hasMany(DetailPenerimaanObat::class, 'id_detail_pembelian'); }
    public function getJumlahDiterimaAttribute(): int { return (int) $this->detail_penerimaan()->sum('jumlah_diterima'); }
    public function getSisaDiterimaAttribute(): int { return max(0, (int) $this->jumlah_pesan - $this->jumlah_diterima); }
}
