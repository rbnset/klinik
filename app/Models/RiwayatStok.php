<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatStok extends Model
{
    protected $table = 'riwayat_stok';
    protected $guarded = ['id'];

    protected $casts = [
        'tanggal_mutasi' => 'date',
    ];

    public function obat()
    {
        return $this->belongsTo(Obat::class, 'id_obat');
    }

    public function getSumberLabelAttribute(): string
    {
        return match ($this->referensi_tipe) {
            'penerimaan' => 'Penerimaan Obat',
            'permintaan' => 'Permintaan Internal',
            'penyesuaian' => 'Penyesuaian Stok',
            default => 'Penyesuaian Stok',
        };
    }
}
