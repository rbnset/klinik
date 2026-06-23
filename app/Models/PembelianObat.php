<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembelianObat extends Model
{
    protected $table = 'pembelian_obat';
    protected $guarded = ['id'];
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'id_supplier');
    }
    public function pengguna()
    {
        return $this->belongsTo(User::class, 'id_pengguna');
    }
    public function detail_pembelian()
    {
        return $this->hasMany(DetailPembelianObat::class, 'id_pembelian_obat');
    }
    public function penerimaan_obat()
    {
        return $this->hasOne(PenerimaanObat::class, 'id_pembelian_obat');
    }
    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'id_pembelian_obat');
    }
}
