<?php

namespace App\Filament\Admin\Resources\KategoriObats\Schemas;


use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class KategoriObatForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Kategori')
                    ->description('Kelola pengelompokan data obat untuk memudahkan pencarian di gudang.')
                    ->schema([
                        TextInput::make('nama_kategori')
                            ->label('Nama Kategori')
                            ->placeholder('Contoh: Sirup, Tablet, Salep, dll.')
                            ->required()
                            ->maxLength(100)
                            ->autofocus() // Kursor langsung fokus ke sini saat buka halaman
                            ->columnSpanFull(),
                    ])
                    ->compact(), // Membuat padding form lebih minimalis
            ]);
    }
}
