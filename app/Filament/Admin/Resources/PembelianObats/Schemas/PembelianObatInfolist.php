<?php

namespace App\Filament\Admin\Resources\PembelianObats\Schemas;

use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PembelianObatInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail Dokumen PO')
                    ->schema([
                        TextEntry::make('id')->label('Nomor PO')->prefix('PO-')->weight('bold'),
                        TextEntry::make('supplier.nama_supplier')->label('Supplier'),
                        TextEntry::make('tanggal_pesan')->label('Tanggal')->dateTime('d M Y'),
                        TextEntry::make('status')->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'pending' => 'warning',
                                'diproses' => 'info',
                                'selesai' => 'success',
                                'dibatalkan' => 'danger',
                            }),
                    ])->columns(4),

                Section::make('Item Pesanan')
                    ->schema([
                        RepeatableEntry::make('detail_pembelian')
                            ->schema([
                                TextEntry::make('obat.nama_obat')->label('Obat'),
                                TextEntry::make('jumlah_pesan')->label('Qty'),
                                TextEntry::make('harga_satuan')->label('Harga Satuan')->money('IDR', locale: 'id'),
                            ])->columns(3)
                    ])
            ]);
    }
}
