<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPenerimaanObat extends Model
{
    protected $table = 'detail_penerimaan_obat';
    protected $guarded = ['id'];
    public function penerimaan_obat() { return $this->belongsTo(PenerimaanObat::class, 'id_penerimaan_obat'); }
    public function detail_pembelian() { return $this->belongsTo(DetailPembelianObat::class, 'id_detail_pembelian'); }
}
