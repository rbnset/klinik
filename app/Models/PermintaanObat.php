<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermintaanObat extends Model
{
    protected $table = 'permintaan_obat';
    protected $guarded = ['id'];

    protected $casts = [
        'tanggal_permintaan' => 'date', // ← wajib agar ->format() di notification bisa jalan
    ];

    public function pengguna()
    {
        return $this->belongsTo(User::class, 'id_pengguna');
    }
    public function detail_permintaan()
    {
        return $this->hasMany(DetailPermintaanObat::class, 'id_permintaan_obat');
    }
}
