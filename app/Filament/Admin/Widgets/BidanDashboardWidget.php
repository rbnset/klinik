<?php

namespace App\Filament\Admin\Widgets;

use App\Filament\Admin\Resources\PermintaanObats\PermintaanObatResource;
use App\Models\PermintaanObat;
use Filament\Widgets\Widget;

class BidanDashboardWidget extends Widget
{
    protected string $view = 'filament.admin.widgets.bidan-dashboard-widget';
    protected static ?int $sort = -5;
    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        return auth()->user()?->role === 'bidan';
    }

    private function baseQuery()
    {
        return PermintaanObat::query()->where('id_pengguna', auth()->id());
    }

    public function getBidanName(): string
    {
        return auth()->user()?->name ?? 'Bidan';
    }

    public function getTotalCount(): int
    {
        return $this->baseQuery()->count();
    }

    public function getPendingCount(): int
    {
        return $this->baseQuery()->where('status', 'pending')->count();
    }

    public function getProcessingCount(): int
    {
        return $this->baseQuery()->whereIn('status', ['disetujui', 'diserahkan'])->count();
    }

    public function getCompletedCount(): int
    {
        return $this->baseQuery()->where('status', 'selesai')->count();
    }

    public function getRejectedCount(): int
    {
        return $this->baseQuery()->where('status', 'ditolak')->count();
    }

    public function getLatestRequests(): array
    {
        return $this->baseQuery()
            ->latest('created_at')
            ->limit(5)
            ->get()
            ->map(fn (PermintaanObat $request) => [
                'id' => $request->id,
                'number' => 'REQ-' . str_pad((string) $request->id, 5, '0', STR_PAD_LEFT),
                'date' => $request->tanggal_permintaan?->format('d M Y') ?? '-',
                'status' => $request->status_label,
                'status_color' => match ($request->status) {
                    'pending' => 'warning',
                    'disetujui' => 'info',
                    'diserahkan' => 'warning',
                    'selesai' => 'success',
                    'ditolak' => 'danger',
                    default => 'gray',
                },
                'url' => PermintaanObatResource::getUrl('view', ['record' => $request->id]),
            ])->all();
    }

    public function getRequestsUrl(): string
    {
        return PermintaanObatResource::getUrl('index');
    }

    public function getCreateUrl(): string
    {
        return PermintaanObatResource::getUrl('create');
    }
}
