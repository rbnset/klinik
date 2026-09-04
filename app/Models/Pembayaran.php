<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';
    protected $guarded = ['id'];
    protected $casts = ['tanggal_bayar' => 'date', 'total_bayar' => 'integer'];

    public function pembelian_obat() { return $this->belongsTo(PembelianObat::class, 'id_pembelian_obat'); }
}
