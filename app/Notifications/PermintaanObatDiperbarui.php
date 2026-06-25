<?php

namespace App\Notifications;

use App\Models\PermintaanObat;
use App\Models\User;
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
        return FilamentNotification::make()
            ->title('Status Permintaan Diperbarui')
            ->body(
                "Permintaan obat Anda sekarang berstatus {$this->permintaan->status}"
            )
            ->icon('heroicon-o-bell-alert')
            ->iconColor('success')
            ->getDatabaseMessage();
    }
}
