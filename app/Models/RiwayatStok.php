<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatStok extends Model
{
    protected $table = 'riwayat_stok';
    protected $guarded = ['id'];
    public function obat()
    {
        return $this->belongsTo(Obat::class, 'id_obat');
    }
}
