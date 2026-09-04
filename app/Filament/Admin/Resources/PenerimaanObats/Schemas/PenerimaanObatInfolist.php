<?php

namespace App\Filament\Admin\Resources\PenerimaanObats\Schemas;

use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PenerimaanObatInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Penerimaan')
                ->description('Dokumen penerimaan barang dan faktur dari supplier.')
                ->icon('heroicon-o-inbox-arrow-down')
                ->schema([
                    TextEntry::make('id')
                        ->label('Nomor Penerimaan')
                        ->formatStateUsing(fn ($state) => 'GR-' . str_pad((string) $state, 5, '0', STR_PAD_LEFT))
                        ->fontFamily('mono')
                        ->weight('bold'),
                    TextEntry::make('id_pembelian_obat')
                        ->label('Nomor PO')
                        ->formatStateUsing(fn ($state) => $state ? 'PO-' . str_pad((string) $state, 5, '0', STR_PAD_LEFT) : '-'),
                    TextEntry::make('pembelian_obat.supplier.nama_supplier')->label('Supplier')->placeholder('-'),
                    TextEntry::make('nomor_faktur')->label('Nomor Faktur')->placeholder('-')->copyable(),
                    TextEntry::make('tanggal_terima')->label('Tanggal Terima')->date('d M Y'),
                    TextEntry::make('stok_diposting_at')->label('Stok Diposting')->dateTime('d M Y, H:i')->placeholder('Belum diposting'),
                ])->columns(2),

            Section::make('Rincian Barang Diterima')
                ->description('Daftar item yang diterima pada dokumen ini.')
                ->icon('heroicon-o-clipboard-document-list')
                ->schema([
                    RepeatableEntry::make('detail_penerimaan')
                        ->schema([
                            TextEntry::make('detail_pembelian.obat.nama_obat')->label('Obat'),
                            TextEntry::make('jumlah_diterima')->label('Qty Diterima')->numeric()->weight('bold'),
                            TextEntry::make('detail_pembelian.obat.satuan')->label('Satuan'),
                        ])->columns(3),
                ]),

            Section::make('Informasi Sistem')
                ->icon('heroicon-o-clock')
                ->collapsed()
                ->schema([
                    TextEntry::make('created_at')->label('Dicatat')->dateTime('d M Y, H:i')->placeholder('-'),
                    TextEntry::make('updated_at')->label('Terakhir Diubah')->dateTime('d M Y, H:i')->placeholder('-'),
                ])->columns(2),
        ]);
    }
}
