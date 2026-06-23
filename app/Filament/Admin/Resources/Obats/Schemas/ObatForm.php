<?php

namespace App\Filament\Admin\Resources\Obats\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ObatForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas Produk')
                    ->schema([
                        TextInput::make('kode_obat')
                            ->label('Kode/SKU Obat')
                            ->placeholder('Contoh: PAR-001')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(30),

                        TextInput::make('nama_obat')
                            ->label('Nama Obat')
                            ->placeholder('Contoh: Paracetamol 500mg')
                            ->required()
                            ->maxLength(100),

                        Select::make('id_kategori_obat')
                            ->label('Kategori')
                            ->relationship('kategori_obat', 'nama_kategori')
                            ->searchable()
                            ->preload()
                            ->required(),
                    ])->columns(3), // Dibuat sejajar 3 kolom agar layar lebar dimanfaatkan maksimal

                Section::make('Persediaan & Harga')
                    ->schema([
                        TextInput::make('satuan')
                            ->label('Satuan Kemasan')
                            ->placeholder('Contoh: Botol, Strip, Pcs')
                            ->required()
                            ->maxLength(30),

                        TextInput::make('stok')
                            ->label('Stok Awal')
                            ->numeric()
                            ->default(0)
                            ->required()
                            ->minValue(0),

                        TextInput::make('harga_beli')
                            ->label('Harga Beli (Satuan)')
                            ->numeric()
                            ->prefix('Rp') // Menambahkan teks Rp di dalam input
                            ->required()
                            ->minValue(0),
                    ])->columns(3),
            ]);
    }
}
