<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'supplier';
    protected $guarded = ['id'];

    protected $casts = [
        'status_pengajuan' => 'string',
    ];

    public function pengguna()
    {
        return $this->belongsTo(User::class, 'id_pengguna');
    }

    public function pembelian_obat()
    {
        return $this->hasMany(PembelianObat::class, 'id_supplier');
    }
}
