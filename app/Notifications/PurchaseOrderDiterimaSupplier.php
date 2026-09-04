<?php
namespace App\Notifications;
use App\Models\PembelianObat;
use App\Filament\Admin\Resources\PembelianObats\PembelianObatResource;
use Filament\Actions\Action;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
class PurchaseOrderDiterimaSupplier extends Notification { use Queueable; public function __construct(public PembelianObat $pembelian) {} public function via(object $notifiable): array { return ['database']; } public function toDatabase(object $notifiable): array { $no='PO-'.str_pad((string)$this->pembelian->id,5,'0',STR_PAD_LEFT); return FilamentNotification::make()->title('PO Baru Diterima')->body("Klinik membuat {$no} untuk supplier Anda. Silakan periksa detail pesanan.")->icon('heroicon-o-shopping-cart')->iconColor('info')->actions([Action::make('lihat')->label('Lihat PO')->url(PembelianObatResource::getUrl('view', ['record' => $this->pembelian->getKey()]))])->getDatabaseMessage(); } }
