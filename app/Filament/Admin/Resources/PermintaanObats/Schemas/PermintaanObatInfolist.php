<?php

namespace App\Filament\Admin\Resources\PermintaanObats\Schemas;

use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PermintaanObatInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Permintaan')
                ->description('Detail dokumen pengajuan kebutuhan obat internal.')
                ->icon('heroicon-o-inbox-arrow-down')
                ->schema([
                    TextEntry::make('id')
                        ->label('Nomor Permintaan')
                        ->formatStateUsing(fn ($state) => 'REQ-' . str_pad((string) $state, 5, '0', STR_PAD_LEFT))
                        ->fontFamily('mono')
                        ->weight('bold'),
                    TextEntry::make('pengguna.name')->label('Pemohon')->placeholder('-'),
                    TextEntry::make('tanggal_permintaan')->label('Tanggal Pengajuan')->date('d M Y'),
                    TextEntry::make('status')
                        ->label('Status')
                        ->badge()
                        ->formatStateUsing(fn ($state) => match ($state) {
                            'pending' => 'Menunggu Persetujuan',
                            'disetujui' => 'Disetujui',
                            'ditolak' => 'Ditolak',
                            'dibatalkan' => 'Dibatalkan',
                            default => $state,
                        })
                        ->color(fn (string $state): string => match ($state) {
                            'pending' => 'warning',
                            'disetujui' => 'success',
                            'ditolak' => 'danger',
                            'dibatalkan' => 'gray',
                            default => 'gray',
                        }),
                    TextEntry::make('keterangan')->label('Catatan / Urgensi')->placeholder('-')->columnSpanFull(),
                ])->columns(2),

            Section::make('Rincian Obat')
                ->description('Daftar obat yang diajukan beserta jumlah yang disetujui.')
                ->icon('heroicon-o-clipboard-document-list')
                ->schema([
                    RepeatableEntry::make('detail_permintaan')
                        ->schema([
                            TextEntry::make('obat.nama_obat')->label('Obat'),
                            TextEntry::make('obat.satuan')->label('Satuan')->placeholder('-'),
                            TextEntry::make('jumlah_diminta')->label('Diminta')->numeric(),
                            TextEntry::make('jumlah_disetujui')->label('Disetujui')->numeric()->badge()->color('success')->placeholder('-'),
                        ])->columns(4),
                ]),

            Section::make('Informasi Sistem')
                ->icon('heroicon-o-clock')
                ->collapsed()
                ->schema([
                    TextEntry::make('created_at')->label('Dibuat')->dateTime('d M Y, H:i')->placeholder('-'),
                    TextEntry::make('updated_at')->label('Terakhir Diubah')->dateTime('d M Y, H:i')->placeholder('-'),
                ])->columns(2),
        ]);
    }
}
