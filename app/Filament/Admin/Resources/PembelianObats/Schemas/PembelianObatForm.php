<?php

namespace App\Filament\Admin\Resources\PembelianObats\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PembelianObatForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Pesanan (PO)')
                    ->schema([
                        Select::make('id_supplier')
                            ->relationship('supplier', 'nama_supplier')
                            ->label('Pemasok / Supplier')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('id_pengguna')
                            ->relationship('pengguna', 'name')
                            ->label('Dibuat Oleh (Karyawan)')
                            ->default(auth()->id())
                            ->required(),

                        DatePicker::make('tanggal_pesan')
                            ->label('Tanggal Pesan')
                            ->default(now())
                            ->required(),

                        Select::make('status')
                            ->options([
                                'pending' => 'Pending (Menunggu)',
                                'diproses' => 'Diproses Supplier',
                                'selesai' => 'Selesai (Diterima)',
                                'dibatalkan' => 'Dibatalkan',
                            ])
                            ->default('pending')
                            ->required(),
                    ])->columns(2),

                Section::make('Rincian Barang')
                    ->schema([
                        Repeater::make('detail_pembelian')
                            ->relationship()
                            ->schema([
                                Select::make('id_obat')
                                    ->relationship('obat', 'nama_obat')
                                    ->label('Pilih Obat')
                                    ->searchable()
                                    ->required()
                                    ->columnSpan(2),

                                TextInput::make('jumlah_pesan')
                                    ->label('Kuantitas')
                                    ->numeric()
                                    ->required()
                                    ->columnSpan(1),

                                TextInput::make('harga_satuan')
                                    ->label('Harga Satuan')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->required()
                                    ->columnSpan(1),
                            ])
                            ->columns(4)
                            ->addActionLabel('Tambah Obat Lain')
                    ]),
            ]);
    }
}
