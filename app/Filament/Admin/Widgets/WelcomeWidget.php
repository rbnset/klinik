<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class WelcomeWidget extends Widget
{
    protected string $view = 'filament.admin.widgets.welcome-widget';

    // Muncul paling atas dashboard
    protected static ?int $sort = -3;

    // Lebar penuh
    protected int|string|array $columnSpan = 'full';

    /**
     * Waktu saat ini dalam zona WIB (Asia/Jakarta), tidak peduli
     * timezone default server/aplikasi diset ke apa.
     */
    protected function now(): \Illuminate\Support\Carbon
    {
        return now('Asia/Jakarta');
    }

    public function getGreeting(): string
    {
        $hour = $this->now()->hour;

        return match (true) {
            $hour >= 4 && $hour < 11  => 'Selamat Pagi',
            $hour >= 11 && $hour < 15 => 'Selamat Siang',
            $hour >= 15 && $hour < 18 => 'Selamat Sore',
            default => 'Selamat Malam',
        };
    }

    public function getGreetingEmoji(): string
    {
        $hour = $this->now()->hour;

        return match (true) {
            $hour >= 4 && $hour < 11  => '☀️',
            $hour >= 11 && $hour < 15 => '🌤️',
            $hour >= 15 && $hour < 18 => '🌇',
            default => '🌙',
        };
    }

    public function getUserName(): string
    {
        return Auth::user()?->name ?? 'Pengguna';
    }

    public function getInitials(): string
    {
        $name  = trim($this->getUserName());
        $words = preg_split('/\s+/', $name);

        if (count($words) >= 2) {
            return strtoupper(mb_substr($words[0], 0, 1) . mb_substr($words[1], 0, 1));
        }

        return strtoupper(mb_substr($name, 0, 2));
    }
}
