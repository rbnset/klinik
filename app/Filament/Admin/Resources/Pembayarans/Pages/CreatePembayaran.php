<?php
namespace App\Filament\Admin\Resources\Pembayarans\Pages;
use App\Filament\Admin\Resources\Pembayarans\PembayaranResource; use App\Models\PembelianObat; use Filament\Resources\Pages\CreateRecord; use Illuminate\Validation\ValidationException; use App\Notifications\PembayaranDibuatUntukSupplier;
class CreatePembayaran extends CreateRecord { protected static string $resource=PembayaranResource::class;
 protected function mutateFormDataBeforeCreate(array $data):array { if(request()->query('pembelian')) $data['id_pembelian_obat']=(int)request()->query('pembelian'); $po=PembelianObat::findOrFail($data['id_pembelian_obat']); if($po->status==='dibatalkan') throw ValidationException::withMessages(['id_pembelian_obat'=>'PO yang dibatalkan tidak dapat menerima pembayaran.']); $reserved=(int)$po->pembayaran()->whereIn('status',['menunggu_supplier','disetujui_supplier'])->sum('total_bayar'); $available=max(0,$po->total_pesanan-$reserved); if((int)$data['total_bayar']>$available) throw ValidationException::withMessages(['total_bayar'=>'Pembayaran melebihi sisa tagihan yang tersedia. Maksimal Rp '.number_format($available,0,',','.').'.']); $data['status']='menunggu_supplier'; return $data; }
 protected function afterCreate():void { $supplierUser=$this->record->pembelian_obat?->supplier?->pengguna; if($supplierUser) $supplierUser->notify(new PembayaranDibuatUntukSupplier($this->record)); }
 protected function getRedirectUrl():string{return \App\Filament\Admin\Resources\PembelianObats\PembelianObatResource::getUrl('view',['record'=>$this->record->id_pembelian_obat]);}
}
