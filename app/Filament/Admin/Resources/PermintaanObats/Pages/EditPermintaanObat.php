<?php

namespace App\Filament\Admin\Resources\PermintaanObats\Pages;

use App\Filament\Admin\Resources\PermintaanObats\PermintaanObatResource;
use App\Notifications\PermintaanObatDiperbarui;
use App\Services\StokObatService;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditPermintaanObat extends EditRecord
{
    protected static string $resource = PermintaanObatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('setujui')
                ->label('Setujui & Kurangi Stok')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn () => auth()->user()->role === 'karyawan' && $this->record->status === 'pending')
                ->requiresConfirmation()
                ->modalHeading('Setujui Permintaan?')
                ->modalDescription(fn () => 'Permintaan akan disetujui dan stok akan langsung berkurang sesuai jumlah yang disetujui. Total ' . $this->record->detail_permintaan->sum('jumlah_disetujui') . ' unit dari ' . $this->record->detail_permintaan->count() . ' item akan diposting ke Riwayat Stok.')
                ->action(function () {
                    $this->save();
                    app(StokObatService::class)->setujuiPermintaan($this->record);
                    Notification::make()->title('Permintaan disetujui')->body('Stok telah diperbarui dan riwayat stok tercatat.')->success()->send();
                    $this->redirect(PermintaanObatResource::getUrl('view', ['record' => $this->record]));
                }),

            Action::make('tolak')
                ->label('Tolak')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->visible(fn () => auth()->user()->role === 'karyawan' && $this->record->status === 'pending')
                ->requiresConfirmation()
                ->modalSubmitActionLabel('Setujui & Kurangi Stok')
                ->modalCancelActionLabel('Batal')
                ->action(function () {
                    $this->save();
                    $this->record->update(['status' => 'ditolak']);
                    Notification::make()->title('Permintaan ditolak')->success()->send();
                    $this->redirect(PermintaanObatResource::getUrl('view', ['record' => $this->record]));
                }),

            ViewAction::make(),
            Action::make('batalkan')
                ->label('Batalkan Permintaan')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->visible(fn () => auth()->user()->role === 'bidan' && $this->record->status === 'pending' && $this->record->id_pengguna === auth()->id())
                ->requiresConfirmation()
                ->action(function () {
                    $this->record->update(['status' => 'dibatalkan']);
                    Notification::make()->title('Permintaan berhasil dibatalkan')->success()->send();
                    $this->redirect(PermintaanObatResource::getUrl());
                }),
        ];
    }

    protected function getFormActions(): array
    {
        if (auth()->user()->role === 'karyawan') return [];
        return parent::getFormActions();
    }

    public function mount(int|string $record): void
    {
        parent::mount($record);
        if ($this->record->status !== 'pending' && auth()->user()->role === 'karyawan') abort(403);
    }
}
