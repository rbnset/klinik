<?php
namespace App\Services;
use App\Models\Pembayaran; use App\Models\User; use App\Notifications\PembayaranDiperbarui; use Illuminate\Support\Facades\DB; use Illuminate\Validation\ValidationException;
class PembayaranWorkflowService {
 public function approveBySupplier(Pembayaran $payment, int $supplierUserId): void {
  DB::transaction(function() use($payment,$supplierUserId){
   $payment=$payment->newQuery()->with('pembelian_obat')->lockForUpdate()->findOrFail($payment->id);
   if($payment->status!=='menunggu_supplier') throw ValidationException::withMessages(['status'=>'Pembayaran ini sudah diproses.']);
   $supplier=User::findOrFail($supplierUserId);
   if(!$supplier->isSupplier() || (int)$payment->pembelian_obat?->id_supplier !== (int)$supplier->supplier?->id) throw ValidationException::withMessages(['status'=>'Anda tidak berhak menyetujui pembayaran ini.']);
   $payment->update(['status'=>'disetujui_supplier','disetujui_supplier_at'=>now(),'disetujui_supplier_oleh'=>$supplierUserId,'ditolak_supplier_at'=>null,'catatan_supplier'=>null]);
   $this->notifyWarehouse($payment,true,null);
  });
 }
 public function rejectBySupplier(Pembayaran $payment,int $supplierUserId,string $reason): void {
  DB::transaction(function() use($payment,$supplierUserId,$reason){
   $payment=$payment->newQuery()->with('pembelian_obat')->lockForUpdate()->findOrFail($payment->id);
   if($payment->status!=='menunggu_supplier') throw ValidationException::withMessages(['status'=>'Pembayaran ini sudah diproses.']);
   $supplier=User::findOrFail($supplierUserId);
   if(!$supplier->isSupplier() || (int)$payment->pembelian_obat?->id_supplier !== (int)$supplier->supplier?->id) throw ValidationException::withMessages(['status'=>'Anda tidak berhak menolak pembayaran ini.']);
   $payment->update(['status'=>'ditolak_supplier','ditolak_supplier_at'=>now(),'catatan_supplier'=>$reason,'disetujui_supplier_at'=>null,'disetujui_supplier_oleh'=>null]);
   $this->notifyWarehouse($payment,false,$reason);
  });
 }
 protected function notifyWarehouse(Pembayaran $payment,bool $approved,?string $reason):void {
  User::query()->whereIn('role',['admin','karyawan'])->get()->each(fn(User $u)=>$u->notify(new PembayaranDiperbarui($payment,$approved,$reason)));
 }
}
