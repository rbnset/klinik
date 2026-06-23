<?php

namespace App\Filament\Admin\Resources\PenerimaanObats\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PenerimaanObatForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Validasi Faktur Masuk')
                    ->schema([
                        Select::make('id_pembelian_obat')
                            ->relationship('pembelian_obat', 'id')
                            ->label('Acuan Nomor PO')
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('nomor_faktur')
                            ->label('Nomor Faktur')
                            ->unique(ignoreRecord: true)
                            ->required(),
                        DatePicker::make('tanggal_terima')
                            ->label('Tanggal Terima')
                            ->default(now())
                            ->required(),
                    ])->columns(3),

                Section::make('Pengecekan Fisik')
                    ->schema([
                        Repeater::make('detail_penerimaan')
                            ->relationship()
                            ->schema([
                                Select::make('id_detail_pembelian')
                                    ->relationship('detail_pembelian', 'id') // Ambil ID baris PO
                                    ->label('Item di PO (ID)')
                                    ->required()
                                    ->columnSpan(2),
                                TextInput::make('jumlah_diterima')
                                    ->label('Kuantitas Aktual Diterima')
                                    ->numeric()
                                    ->required()
                                    ->columnSpan(2),
                            ])->columns(4)
                    ])
            ]);
    }
}
