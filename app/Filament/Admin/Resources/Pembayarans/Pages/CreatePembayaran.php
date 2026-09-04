<?php
namespace App\Filament\Admin\Resources\Pembayarans\Pages;
use App\Filament\Admin\Resources\Pembayarans\PembayaranResource;
use App\Models\PembelianObat;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Validation\ValidationException;
class CreatePembayaran extends CreateRecord
{
 protected static string $resource = PembayaranResource::class;
 protected function mutateFormDataBeforeCreate(array $data): array { if(request()->query('pembelian')) $data['id_pembelian_obat']=(int)request()->query('pembelian'); $po=PembelianObat::findOrFail($data['id_pembelian_obat']); if((int)$data['total_bayar']>$po->sisa_tagihan) throw ValidationException::withMessages(['total_bayar'=>'Pembayaran melebihi sisa tagihan PO (Rp '.number_format($po->sisa_tagihan,0,',','.').').']); return $data; }
 protected function getRedirectUrl(): string { return \App\Filament\Admin\Resources\PembelianObats\PembelianObatResource::getUrl('view', ['record'=>$this->record->id_pembelian_obat]); }
}
