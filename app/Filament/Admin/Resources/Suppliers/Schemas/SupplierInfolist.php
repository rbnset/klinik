<?php

namespace App\Filament\Admin\Resources\Suppliers\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SupplierInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Profil Supplier')
                ->description('Informasi kontak dan identitas pemasok obat.')
                ->icon('heroicon-o-truck')
                ->schema([
                    TextEntry::make('nama_supplier')->label('Nama Perusahaan / Supplier')->weight('bold')->size('lg'),
                    TextEntry::make('no_telp')->label('Nomor Telepon / WhatsApp')->placeholder('-')->copyable(),
                    TextEntry::make('alamat')->label('Alamat Lengkap')->placeholder('-')->columnSpanFull(),
                ])->columns(2),

            Section::make('Status Pengajuan')
                ->description('Status verifikasi dan akses supplier.')
                ->icon('heroicon-o-shield-check')
                ->schema([
                    TextEntry::make('status_pengajuan')
                        ->label('Status')
                        ->badge()
                        ->formatStateUsing(fn (?string $state): string => match ($state) {
                            'menunggu' => 'Menunggu Verifikasi',
                            'disetujui' => 'Disetujui',
                            'ditolak' => 'Ditolak',
                            default => 'Belum Diatur',
                        })
                        ->color(fn (?string $state): string => match ($state) {
                            'menunggu' => 'warning',
                            'disetujui' => 'success',
                            'ditolak' => 'danger',
                            default => 'gray',
                        }),
                    TextEntry::make('alasan_penolakan')
                        ->label('Alasan Penolakan')
                        ->placeholder('Tidak ada')
                        ->visible(fn ($record): bool => $record->status_pengajuan === 'ditolak'),
                    TextEntry::make('pengajuan_dapat_diajukan_lagi_at')
                        ->label('Dapat Mengajukan Kembali')
                        ->dateTime('d M Y, H:i')
                        ->placeholder('-')
                        ->visible(fn ($record): bool => $record->status_pengajuan === 'ditolak'),
                ]),

            Section::make('Akun Portal')
                ->description('Akun pengguna yang ditautkan ke supplier, jika tersedia.')
                ->icon('heroicon-o-user-circle')
                ->schema([
                    TextEntry::make('pengguna.name')->label('Akun Pengguna')->placeholder('Tidak ditautkan'),
                    TextEntry::make('pengguna.email')->label('Email')->placeholder('-'),
                ])->columns(2),

            Section::make('Informasi Sistem')
                ->icon('heroicon-o-clock')
                ->collapsed()
                ->schema([
                    TextEntry::make('created_at')->label('Didaftarkan')->dateTime('d M Y, H:i')->placeholder('-'),
                    TextEntry::make('updated_at')->label('Terakhir Diubah')->dateTime('d M Y, H:i')->placeholder('-'),
                ])->columns(2),
        ]);
    }
}
