<?php

namespace App\Filament\Admin\Resources\PembelianObats\Schemas;

use App\Models\Obat;
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
                ->description('PO dibuat oleh gudang dengan status otomatis Menunggu Supplier. Status berubah melalui respons supplier dan konfirmasi harga.')
                ->icon('heroicon-o-shopping-cart')
                ->schema([
                    Select::make('id_supplier')
                        ->relationship('supplier', 'nama_supplier')
                        ->label('Supplier')->searchable()->preload()->required(),
                    Select::make('id_pengguna')
                        ->relationship('pengguna', 'name')->label('Dibuat Oleh')
                        ->default(auth()->id())->disabled()->dehydrated()->required(),
                    DatePicker::make('tanggal_pesan')->label('Tanggal Pesan')->default(now())->required(),
                    TextInput::make('status_tampilan')
                        ->label('Status PO')
                        ->default('Menunggu Supplier')
                        ->disabled()->dehydrated(false)
                        ->helperText('Status PO dikelola otomatis oleh workflow.'),
                ])->columns(2),

            Section::make('Rincian Obat')
                ->description('Harga awal diisi gudang berdasarkan riwayat harga terakhir. Supplier kemudian mengonfirmasi atau mengusulkan harga baru.')
                ->icon('heroicon-o-clipboard-document-list')
                ->schema([
                    Repeater::make('detail_pembelian')
                        ->relationship()
                        ->schema([
                            Select::make('id_obat')
                                ->relationship('obat', 'nama_obat')
                                ->label('Obat')->searchable()->preload()->required()->live()
                                ->afterStateUpdated(function ($state, callable $set): void {
                                    if (! $state) {
                                        $set('harga_satuan', null);
                                        return;
                                    }
                                    $hargaTerakhir = Obat::find($state)?->harga_beli_terakhir;
                                    if ($hargaTerakhir !== null) $set('harga_satuan', $hargaTerakhir);
                                })->columnSpan(2),
                            TextInput::make('jumlah_pesan')->label('Qty Pesan')->numeric()->minValue(1)->required(),
                            TextInput::make('harga_satuan')->label('Harga Awal / Satuan')->numeric()->minValue(0)->prefix('Rp')->required()
                                ->helperText(fn ($get) => ($obat = Obat::find($get('id_obat')))?->harga_beli_terakhir !== null
                                    ? 'Harga beli terakhir: Rp ' . number_format((int) $obat->harga_beli_terakhir, 0, ',', '.')
                                    : 'Belum ada riwayat harga beli.'),
                        ])->columns(4)
                        ->addActionLabel('Tambah Obat')->reorderable(false),
                ]),
        ]);
    }
}
