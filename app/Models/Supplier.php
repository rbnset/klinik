<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Supplier extends Model
{
    protected static function booted(): void
    {
        static::saving(function (Supplier $supplier): void {
            if ($supplier->isDirty('status_pengajuan')) {
                if ($supplier->status_pengajuan === 'ditolak') {
                    $supplier->ditolak_at ??= now();
                    $supplier->pengajuan_dapat_diajukan_lagi_at ??= now()->addDays(3);
                } elseif ($supplier->status_pengajuan === 'disetujui') {
                    $supplier->alasan_penolakan = null;
                    $supplier->ditolak_at = null;
                    $supplier->pengajuan_dapat_diajukan_lagi_at = null;
                } elseif ($supplier->status_pengajuan === 'menunggu') {
                    $supplier->alasan_penolakan = null;
                    $supplier->ditolak_at = null;
                    $supplier->pengajuan_dapat_diajukan_lagi_at = null;
                }
            }
        });
    }
    protected $table = 'supplier';
    protected $guarded = ['id'];

    protected $casts = [
        'status_pengajuan' => 'string',
        'ditolak_at' => 'datetime',
        'pengajuan_dapat_diajukan_lagi_at' => 'datetime',
    ];

    public function pengguna()
    {
        return $this->belongsTo(User::class, 'id_pengguna');
    }

    public function pembelian_obat()
    {
        return $this->hasMany(PembelianObat::class, 'id_supplier');
    }

    public function getBolehMengajukanLagiAttribute(): bool
    {
        return $this->status_pengajuan === 'ditolak'
            && $this->pengajuan_dapat_diajukan_lagi_at instanceof Carbon
            && now()->greaterThanOrEqualTo($this->pengajuan_dapat_diajukan_lagi_at);
    }
}
