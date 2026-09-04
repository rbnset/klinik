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
        return $schema->components([
            Section::make('Informasi Penerimaan')
                ->description('Catat dokumen penerimaan barang berdasarkan PO dan faktur supplier.')
                ->icon('heroicon-o-inbox-arrow-down')
                ->schema([
                    Select::make('id_pembelian_obat')
                        ->relationship('pembelian_obat', 'id')
                        ->label('Acuan Nomor PO')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->default(request()->query('pembelian'))
                        ->disabled(fn () => request()->query('pembelian') !== null)
                        ->dehydrated(),
                    TextInput::make('nomor_faktur')->label('Nomor Faktur')->unique(ignoreRecord: true)->required(),
                    DatePicker::make('tanggal_terima')->label('Tanggal Terima')->default(now())->required(),
                ])->columns(3),

            Section::make('Pengecekan Fisik')
                ->description('Masukkan jumlah aktual setiap item yang diterima dari supplier.')
                ->icon('heroicon-o-clipboard-document-check')
                ->schema([
                    Repeater::make('detail_penerimaan')
                        ->relationship()
                        ->schema([
                            Select::make('id_detail_pembelian')
                                ->relationship('detail_pembelian', 'id')
                                ->getOptionLabelFromRecordUsing(fn ($record) => $record->obat?->nama_obat . ' · Pesan ' . $record->jumlah_pesan . ' · Sisa ' . $record->sisa_diterima)
                                ->label('Item PO')
                                ->required()
                                ->columnSpan(2),
                            TextInput::make('jumlah_diterima')
                                ->label('Kuantitas Aktual Diterima')
                                ->numeric()
                                ->minValue(1)
                                ->required()
                                ->columnSpan(2),
                        ])->columns(4),
                ]),
        ]);
    }
}
