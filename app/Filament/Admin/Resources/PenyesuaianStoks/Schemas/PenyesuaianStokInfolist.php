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
            ->components([
                Section::make('Rincian Penyesuaian Fisik')
                    ->schema([
                        TextEntry::make('tanggal')->label('Tanggal Opname')->dateTime('d M Y'),
                        TextEntry::make('obat.nama_obat')->label('Nama Obat')->weight('bold')->size('lg'),
                        TextEntry::make('pengguna.name')->label('Petugas Eksekutor'),

                        TextEntry::make('jenis')
                            ->label('Tindakan')
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'penambahan' => 'success',
                                'pengurangan' => 'danger',
                            }),

                        TextEntry::make('alasan')->label('Alasan')->formatStateUsing(fn(string $state): string => ucfirst(str_replace('_', ' ', $state))),
                        TextEntry::make('jumlah')->label('Kuantitas (Unit)')->weight('bold'),

                        TextEntry::make('keterangan')->label('Catatan')->columnSpanFull(),
                    ])->columns(3)
            ]);
    }
}
