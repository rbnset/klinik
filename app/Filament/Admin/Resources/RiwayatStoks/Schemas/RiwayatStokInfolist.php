<?php

namespace App\Filament\Admin\Resources\RiwayatStoks\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RiwayatStokInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail Audit Jejak Rekam')
                    ->schema([
                        TextEntry::make('created_at')->label('Waktu Kejadian')->dateTime('d F Y - H:i:s'),
                        TextEntry::make('obat.nama_obat')->label('Identitas Barang')->weight('bold')->size('lg'),
                        TextEntry::make('referensi_transaksi')->label('Nomor Dokumen Acuan')->color('primary'),

                        TextEntry::make('jenis_transaksi')->label('Arah Pergerakan')->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'masuk' => 'success',
                                'keluar' => 'danger',
                            }),

                        TextEntry::make('stok_sebelum')->label('Saldo Awal'),
                        TextEntry::make('jumlah')->label('Jumlah Mutasi')->weight('bold'),
                        TextEntry::make('stok_sesudah')->label('Saldo Akhir')->color('primary')->weight('bold'),
                    ])->columns(3)
            ]);
    }
}
