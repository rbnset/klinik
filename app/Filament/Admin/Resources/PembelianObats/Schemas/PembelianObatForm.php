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
        return $schema->components([
            Section::make('Informasi Pemesanan')
                ->description('Buat Purchase Order (PO) kepada supplier dan tentukan status pemesanan.')
                ->icon('heroicon-o-shopping-cart')
                ->schema([
                    Select::make('id_supplier')->relationship('supplier', 'nama_supplier')->label('Supplier')->searchable()->preload()->required(),
                    Select::make('id_pengguna')->relationship('pengguna', 'name')->label('Dibuat Oleh')->default(auth()->id())->disabled()->dehydrated()->required(),
                    DatePicker::make('tanggal_pesan')->label('Tanggal Pesan')->default(now())->required(),
                    Select::make('status')
                        ->label('Status PO')
                        ->options(['pending' => 'Menunggu', 'diproses' => 'Diproses Supplier', 'selesai' => 'Selesai', 'dibatalkan' => 'Dibatalkan'])
                        ->default('pending')
                        ->required(),
                ])->columns(2),

            Section::make('Rincian Obat')
                ->description('Harga satuan mengikuti penawaran supplier saat PO dibuat dan menjadi histori harga beli obat.')
                ->icon('heroicon-o-clipboard-document-list')
                ->schema([
                    Repeater::make('detail_pembelian')
                        ->relationship()
                        ->schema([
                            Select::make('id_obat')->relationship('obat', 'nama_obat')->label('Obat')->searchable()->preload()->required()->columnSpan(2),
                            TextInput::make('jumlah_pesan')->label('Qty Pesan')->numeric()->minValue(1)->required(),
                            TextInput::make('harga_satuan')->label('Harga dari Supplier')->numeric()->minValue(0)->prefix('Rp')->required(),
                        ])->columns(4)
                        ->addActionLabel('Tambah Obat')
                        ->reorderable(false),
                ]),
        ]);
    }
}
