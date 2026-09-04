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
        return $schema->components([
            Section::make('Identitas Obat')
                ->description('Data identitas tetap obat. Harga pembelian dicatat pada transaksi pembelian, bukan di master obat.')
                ->icon('heroicon-o-beaker')
                ->schema([
                    TextInput::make('kode_obat')
                        ->label('Kode / SKU Obat')
                        ->placeholder('Contoh: PAR-001')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(50)
                        ->dehydrateStateUsing(fn ($state) => strtoupper(trim((string) $state))),
                    TextInput::make('nama_obat')
                        ->label('Nama Obat')
                        ->placeholder('Contoh: Paracetamol 500mg')
                        ->required()
                        ->maxLength(150),
                    Select::make('id_kategori_obat')
                        ->label('Kategori Obat')
                        ->relationship('kategori_obat', 'nama_kategori')
                        ->searchable()
                        ->preload()
                        ->required(),
                    TextInput::make('satuan')
                        ->label('Satuan Kemasan')
                        ->placeholder('Contoh: Strip, Botol, Tablet')
                        ->required()
                        ->maxLength(50),
                ])->columns(2),

            Section::make('Persediaan')
                ->description('Stok dikelola otomatis melalui penerimaan, permintaan internal, dan penyesuaian stok.')
                ->icon('heroicon-o-archive-box')
                ->schema([
                    TextInput::make('stok')
                        ->label('Stok Saat Ini')
                        ->numeric()
                        ->default(0)
                        ->disabled()
                        ->dehydrated(false)
                        ->helperText('Stok dikelola otomatis dari penerimaan, permintaan internal, dan penyesuaian stok. Riwayat transaksi dapat ditelusuri dari dokumen sumber terkait.'),
                    TextInput::make('harga_beli_terakhir_display')
                        ->label('Harga Beli Terakhir')
                        ->prefix('Rp')
                        ->disabled()
                        ->dehydrated(false)
                        ->formatStateUsing(fn ($state, $record) => $record?->harga_beli_terakhir ? number_format($record->harga_beli_terakhir, 0, ',', '.') : '-')
                        ->helperText('Diambil otomatis dari transaksi pembelian terakhir yang valid.'),
                ])->columns(2),
        ]);
    }
}
