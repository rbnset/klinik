<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';
    protected $guarded = ['id'];
    public function pembelian_obat()
    {
        return $this->belongsTo(PembelianObat::class, 'id_pembelian_obat');
    }
}
