<?php

namespace App\Notifications;

use App\Filament\Admin\Resources\PermintaanObats\PermintaanObatResource;
use App\Models\PermintaanObat;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PermintaanObatDiperbarui extends Notification
{
    use Queueable;

    public function __construct(
        public PermintaanObat $permintaan,
        public User $diperbaruiOleh,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $status = $this->permintaan->status;
        $number = 'REQ-' . str_pad((string) $this->permintaan->id, 5, '0', STR_PAD_LEFT);

        [$title, $body, $color] = match ($status) {
            'disetujui' => [
                'Permintaan Disetujui',
                "{$number} telah disetujui gudang dan obat siap diserahkan.",
                'success',
            ],
            'diserahkan' => [
                'Obat Siap Diterima',
                "{$number} telah diserahkan gudang. Silakan konfirmasi setelah obat diterima secara fisik.",
                'info',
            ],
            'selesai' => [
                'Permintaan Selesai',
                "{$number} telah dikonfirmasi diterima dan proses internal selesai.",
                'success',
            ],
            'ditolak' => [
                'Permintaan Ditolak',
                "{$number} ditolak gudang. Alasan: " . ($this->permintaan->alasan_penolakan ?: 'Tidak ada alasan yang dicantumkan.') ,
                'danger',
            ],
            'dibatalkan' => [
                'Permintaan Dibatalkan',
                "{$number} telah dibatalkan oleh pemohon.",
                'gray',
            ],
            default => [
                'Status Permintaan Diperbarui',
                "{$number} sekarang {$this->permintaan->status_label}.",
                'warning',
            ],
        };

        return FilamentNotification::make()
            ->title($title)
            ->body($body)
            ->icon('heroicon-o-bell-alert')
            ->iconColor($color)
            ->actions([
                Action::make('lihat')
                    ->label('Lihat Permintaan')
                    ->url(PermintaanObatResource::getUrl('view', ['record' => $this->permintaan->getKey()])),
            ])
            ->getDatabaseMessage();
    }
}
