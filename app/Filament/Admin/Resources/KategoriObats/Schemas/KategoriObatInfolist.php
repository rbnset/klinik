<?php

namespace App\Filament\Admin\Resources\KategoriObats\Schemas;


use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class KategoriObatInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail Kategori Obat')
                    ->schema([
                        TextEntry::make('nama_kategori')
                            ->label('Nama Kategori')
                            ->weight('bold')
                            ->size('lg'), // Ukuran teks diperbesar sedikit

                        TextEntry::make('created_at')
                            ->label('Didaftarkan Pada')
                            ->dateTime('d F Y - H:i')
                            ->placeholder('-'),

                        TextEntry::make('updated_at')
                            ->label('Terakhir Diubah')
                            ->dateTime('d F Y - H:i')
                            ->placeholder('-'),
                    ])
                    ->columns(3) // Membagi layar menjadi 3 kolom agar layout melebar dan rapi
            ]);
    }
}
