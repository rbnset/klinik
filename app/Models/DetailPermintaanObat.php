<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPermintaanObat extends Model
{
    protected $table = 'detail_permintaan_obat';
    protected $guarded = ['id'];
    public function permintaan_obat()
    {
        return $this->belongsTo(PermintaanObat::class, 'id_permintaan_obat');
    }
    public function obat()
    {
        return $this->belongsTo(Obat::class, 'id_obat');
    }
}
