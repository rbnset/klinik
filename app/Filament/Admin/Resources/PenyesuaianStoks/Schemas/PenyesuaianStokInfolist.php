<?php

namespace App\Filament\Admin\Resources\PenyesuaianStoks\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PenyesuaianStokInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Informasi Penyesuaian')
                    ->description('Catatan penyesuaian stok berdasarkan hasil pemeriksaan fisik.')
                    ->icon('heroicon-o-adjustments-horizontal')
                    ->schema([
                        TextEntry::make('id')
                            ->label('Nomor Penyesuaian')
                            ->formatStateUsing(fn($state) => 'ADJ-' . str_pad((string) $state, 5, '0', STR_PAD_LEFT))
                            ->fontFamily('mono')
                            ->weight('bold'),
                        TextEntry::make('obat.nama_obat')->label('Nama Obat')->weight('bold'),
                        TextEntry::make('obat.satuan')->label('Satuan')->placeholder('-'),
                        TextEntry::make('pengguna.name')->label('Petugas Pemeriksa')->placeholder('-'),
                        TextEntry::make('tanggal')->label('Tanggal Opname')->date('d M Y'),
                        TextEntry::make('jenis')
                            ->label('Tindakan')
                            ->badge()
                            ->formatStateUsing(fn($state) => match ($state) {
                                'penambahan' => 'Penambahan',
                                'pengurangan' => 'Pengurangan',
                                default => $state,
                            })
                            ->color(fn(string $state): string => match ($state) {
                                'penambahan' => 'success',
                                'pengurangan' => 'danger',
                                default => 'gray',
                            }),
                        TextEntry::make('alasan')
                            ->label('Alasan')
                            ->formatStateUsing(fn($state) => ucfirst(str_replace('_', ' ', (string) $state))),
                        TextEntry::make('jumlah')->label('Kuantitas')->numeric()->weight('bold'),
                        TextEntry::make('stok_diposting_at')->label('Stok Diposting')->dateTime('d M Y, H:i')->placeholder('Belum diposting'),
                        TextEntry::make('keterangan')->label('Catatan')->placeholder('-')->columnSpanFull(),
                    ])->columns(2),

                Section::make('Informasi Sistem')
                    ->icon('heroicon-o-clock')
                    ->collapsed()
                    ->schema([
                        TextEntry::make('created_at')->label('Dicatat')->dateTime('d M Y, H:i')->placeholder('-'),
                        TextEntry::make('updated_at')->label('Terakhir Diubah')->dateTime('d M Y, H:i')->placeholder('-'),
                    ])->columns(2),
            ]);
    }
}
