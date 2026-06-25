<?php

namespace App\Filament\Admin\Resources\PermintaanObats\Pages;

use App\Filament\Admin\Resources\PermintaanObats\PermintaanObatResource;
use App\Notifications\PermintaanObatDiperbarui;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Validation\ValidationException;

class EditPermintaanObat extends EditRecord
{
    protected static string $resource = PermintaanObatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('setujui')
                ->label('Setujui')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(
                    fn() =>
                    auth()->user()->role === 'karyawan'
                        && $this->record->status === 'pending'
                )
                ->requiresConfirmation()
                ->action(function () {

                    foreach ($this->record->detail_permintaan as $detail) {

                        // wajib diisi
                        if (
                            is_null($detail->jumlah_disetujui)
                            || $detail->jumlah_disetujui <= 0
                        ) {

                            Notification::make()
                                ->title('Jumlah disetujui belum lengkap')
                                ->body('Semua item obat harus diisi jumlah yang disetujui.')
                                ->danger()
                                ->send();

                            return;
                        }

                        // tidak boleh lebih dari yang diminta
                        if ($detail->jumlah_disetujui > $detail->jumlah_diminta) {

                            Notification::make()
                                ->title('Jumlah disetujui tidak valid')
                                ->body(
                                    "{$detail->obat->nama_obat}: jumlah disetujui tidak boleh melebihi jumlah yang diminta."
                                )
                                ->danger()
                                ->send();

                            return;
                        }

                        // tidak boleh melebihi stok
                        if ($detail->jumlah_disetujui > $detail->obat->stok) {

                            Notification::make()
                                ->title('Stok tidak mencukupi')
                                ->body(
                                    "{$detail->obat->nama_obat}: stok tersedia {$detail->obat->stok}, tetapi disetujui {$detail->jumlah_disetujui}."
                                )
                                ->danger()
                                ->send();

                            return;
                        }
                    }

                    Notification::make()
                        ->title('Validasi berhasil')
                        ->success()
                        ->send();
                }),

            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        // Kosongkan atau hapus method ini
        // agar notifikasi tidak terkirim setiap edit biasa
    }
}
