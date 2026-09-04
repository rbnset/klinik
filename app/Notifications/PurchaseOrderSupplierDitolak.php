<?php
namespace App\Notifications;
use App\Models\PembelianObat;
use App\Filament\Admin\Resources\PembelianObats\PembelianObatResource;
use Filament\Actions\Action;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
class PurchaseOrderSupplierDitolak extends Notification { use Queueable; public function __construct(public PembelianObat $pembelian) {} public function via(object $notifiable): array { return ['database']; } public function toDatabase(object $notifiable): array { $no='PO-'.str_pad((string)$this->pembelian->id,5,'0',STR_PAD_LEFT); return FilamentNotification::make()->title('PO Ditolak Supplier')->body("{$no} ditolak supplier. Alasan: {$this->pembelian->alasan_penolakan_supplier}")->icon('heroicon-o-x-circle')->iconColor('danger')->actions([Action::make('lihat')->label('Lihat PO')->url(PembelianObatResource::getUrl('view', ['record' => $this->pembelian->getKey()]))])->getDatabaseMessage(); } }
