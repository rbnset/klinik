<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Pembayaran extends Model
{
 protected $table = 'pembayaran'; protected $guarded = ['id'];
 protected $casts = ['tanggal_bayar'=>'date','total_bayar'=>'integer','disetujui_supplier_at'=>'datetime','ditolak_supplier_at'=>'datetime'];
 public function pembelian_obat(){ return $this->belongsTo(PembelianObat::class,'id_pembelian_obat'); }
 public function approver(){ return $this->belongsTo(User::class,'disetujui_supplier_oleh'); }
 public function getStatusLabelAttribute(): string { return match($this->status){ 'menunggu_supplier'=>'Menunggu Persetujuan Supplier','disetujui_supplier'=>'Disetujui Supplier','ditolak_supplier'=>'Ditolak Supplier', default=>ucfirst((string)$this->status)}; }
}
