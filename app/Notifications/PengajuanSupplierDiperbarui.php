<?php

namespace App\Notifications;

use App\Models\Supplier;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PengajuanSupplierDiperbarui extends Notification
{
    use Queueable;

    public function __construct(public Supplier $supplier, public string $status)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        if ($this->status === 'disetujui') {
            return FilamentNotification::make()
                ->title('Akun Supplier Disetujui')
                ->body('Pengajuan supplier Anda telah disetujui. Anda sekarang dapat masuk ke sistem.')
                ->icon('heroicon-o-check-circle')
                ->iconColor('success')
                ->getDatabaseMessage();
        }

        return FilamentNotification::make()
            ->title('Pengajuan Supplier Ditolak')
            ->body('Pengajuan Anda ditolak. Silakan cek alasan penolakan pada halaman login.')
            ->icon('heroicon-o-x-circle')
            ->iconColor('danger')
            ->getDatabaseMessage();
    }
}
