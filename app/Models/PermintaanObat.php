<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermintaanObat extends Model
{
    protected $table = 'permintaan_obat';
    protected $guarded = ['id'];

    protected $casts = [
        'tanggal_permintaan' => 'date',
        'disetujui_at' => 'datetime',
        'diserahkan_at' => 'datetime',
        'dikonfirmasi_at' => 'datetime',
        'stok_diposting_at' => 'datetime',
        'ditolak_at' => 'datetime',
    ];

    public function pengguna()
    {
        return $this->belongsTo(User::class, 'id_pengguna');
    }
    public function detail_permintaan()
    {
        return $this->hasMany(DetailPermintaanObat::class, 'id_permintaan_obat');
    }

    public function disetujuiOleh() { return $this->belongsTo(User::class, 'disetujui_oleh'); }
    public function diserahkanOleh() { return $this->belongsTo(User::class, 'diserahkan_oleh'); }
    public function dikonfirmasiOleh() { return $this->belongsTo(User::class, 'dikonfirmasi_oleh'); }
    public function ditolakOleh() { return $this->belongsTo(User::class, 'ditolak_oleh'); }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu Persetujuan',
            'disetujui' => 'Disetujui · Siap Diserahkan',
            'diserahkan' => 'Menunggu Konfirmasi Bidan',
            'selesai' => 'Selesai',
            'ditolak' => 'Ditolak',
            'dibatalkan' => 'Dibatalkan',
            default => ucfirst((string) $this->status),
        };
    }
}
