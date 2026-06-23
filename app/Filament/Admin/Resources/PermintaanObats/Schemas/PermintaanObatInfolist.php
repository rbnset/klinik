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
        return $schema
            ->components([
                Section::make('Detail Dokumen Permintaan')
                    ->schema([
                        TextEntry::make('id')->label('Nomor Request')->prefix('REQ-')->weight('bold'),
                        TextEntry::make('pengguna.name')->label('Pemohon'),
                        TextEntry::make('tanggal_permintaan')->label('Tanggal')->dateTime('d M Y'),
                        TextEntry::make('status')->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'pending' => 'warning',
                                'disetujui' => 'success',
                                'ditolak' => 'danger',
                            }),
                        TextEntry::make('keterangan')->label('Catatan')->columnSpanFull(),
                    ])->columns(4),

                Section::make('Item Barang')
                    ->schema([
                        RepeatableEntry::make('detail_permintaan')
                            ->schema([
                                TextEntry::make('obat.nama_obat')->label('Obat'),
                                TextEntry::make('jumlah_diminta')->label('Diminta'),
                                TextEntry::make('jumlah_disetujui')->label('Disetujui')->badge()->color('success'),
                            ])->columns(3)
                    ])
            ]);
    }
}
