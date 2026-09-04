<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenerimaanObat extends Model
{
    protected $table = 'penerimaan_obat';
    protected $guarded = ['id'];
    protected $casts = ['tanggal_terima' => 'date', 'stok_diposting_at' => 'datetime'];

    public function pembelian_obat() { return $this->belongsTo(PembelianObat::class, 'id_pembelian_obat'); }
    public function detail_penerimaan() { return $this->hasMany(DetailPenerimaanObat::class, 'id_penerimaan_obat'); }
}
