<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenyesuaianStok extends Model
{
    protected $table = 'penyesuaian_stok';
    protected $guarded = ['id'];
    public function obat()
    {
        return $this->belongsTo(Obat::class, 'id_obat');
    }
    public function pengguna()
    {
        return $this->belongsTo(User::class, 'id_pengguna');
    }
}
