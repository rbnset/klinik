<?php

namespace App\Notifications;

use App\Models\PermintaanObat;
use App\Models\User;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PermintaanObatDibuat extends Notification
{
    use Queueable;

    public function __construct(
        public PermintaanObat $permintaan,
        public User $pemohon,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return FilamentNotification::make()
            ->title('Permintaan Obat Baru')
            ->body(
                "{$this->pemohon->name} mengajukan permintaan obat."
            )
            ->icon('heroicon-o-inbox-arrow-down')
            ->iconColor('warning')
            ->getDatabaseMessage();
    }
}
