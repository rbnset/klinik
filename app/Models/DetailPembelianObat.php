<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPembelianObat extends Model
{
    protected $table = 'detail_pembelian_obat';
    protected $guarded = ['id'];
    public function pembelian_obat()
    {
        return $this->belongsTo(PembelianObat::class, 'id_pembelian_obat');
    }
    public function obat()
    {
        return $this->belongsTo(Obat::class, 'id_obat');
    }
    public function detail_penerimaan()
    {
        return $this->hasOne(DetailPenerimaanObat::class, 'id_detail_pembelian');
    }
}
