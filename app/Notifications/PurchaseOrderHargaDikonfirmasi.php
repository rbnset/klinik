<?php
namespace App\Notifications;
use App\Models\PembelianObat;
use App\Filament\Admin\Resources\PembelianObats\PembelianObatResource;
use Filament\Actions\Action;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
class PurchaseOrderHargaDikonfirmasi extends Notification { use Queueable; public function __construct(public PembelianObat $pembelian, public bool $ditolak=false, public ?string $reason=null) {} public function via(object $notifiable): array { return ['database']; } public function toDatabase(object $notifiable): array { $no='PO-'.str_pad((string)$this->pembelian->id,5,'0',STR_PAD_LEFT); $title=$this->ditolak?'Perubahan Harga Ditolak':'Perubahan Harga Disetujui'; $body=$this->ditolak?"Perubahan harga pada {$no} ditolak gudang. Alasan: {$this->reason}. Silakan revisi harga dan kirim kembali.":"Perubahan harga pada {$no} disetujui gudang. PO dapat diproses."; return FilamentNotification::make()->title($title)->body($body)->icon($this->ditolak?'heroicon-o-x-circle':'heroicon-o-check-circle')->iconColor($this->ditolak?'danger':'success')->actions([Action::make('lihat')->label('Lihat PO')->url(PembelianObatResource::getUrl('view', ['record' => $this->pembelian->getKey()]))])->getDatabaseMessage(); } }
