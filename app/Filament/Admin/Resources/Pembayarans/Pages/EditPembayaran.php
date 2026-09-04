<?php
namespace App\Filament\Admin\Resources\Pembayarans\Pages;
use App\Filament\Admin\Resources\Pembayarans\PembayaranResource;
use App\Models\Pembayaran;
use App\Models\PembelianObat;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Validation\ValidationException;
class EditPembayaran extends EditRecord
{
 protected static string $resource = PembayaranResource::class;
 protected function mutateFormDataBeforeSave(array $data): array { $po=PembelianObat::findOrFail($data['id_pembelian_obat']); if($po->status === 'dibatalkan') throw ValidationException::withMessages(['id_pembelian_obat'=>'PO yang dibatalkan tidak dapat menerima pembayaran.']); $other=(int)$po->pembayaran()->where('id', '!=', $this->record->id)->sum('total_bayar'); $sisa=max(0,$po->total_pesanan-$other); if((int)$data['total_bayar']>$sisa) throw ValidationException::withMessages(['total_bayar'=>'Pembayaran melebihi sisa tagihan PO. Maksimal Rp '.number_format($sisa,0,',','.').'.']); return $data; }
}
