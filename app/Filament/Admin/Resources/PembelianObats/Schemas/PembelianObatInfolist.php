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
        return $schema->components([
            Section::make('Informasi Pemesanan')
                ->description('Identitas Purchase Order (PO), supplier, tanggal, dan status pemesanan.')
                ->icon('heroicon-o-shopping-cart')
                ->schema([
                    TextEntry::make('id')
                        ->label('Nomor PO')
                        ->formatStateUsing(fn ($state) => 'PO-' . str_pad((string) $state, 5, '0', STR_PAD_LEFT))
                        ->fontFamily('mono')
                        ->weight('bold'),
                    TextEntry::make('supplier.nama_supplier')->label('Supplier')->placeholder('-'),
                    TextEntry::make('pengguna.name')->label('Dibuat Oleh')->placeholder('-'),
                    TextEntry::make('tanggal_pesan')->label('Tanggal Pesan')->date('d M Y'),
                    TextEntry::make('status')
                        ->label('Status PO')
                        ->badge()
                        ->formatStateUsing(fn ($state) => match ($state) {
                            'pending' => 'Menunggu',
                            'diproses' => 'Diproses Supplier',
                            'selesai' => 'Selesai',
                            'dibatalkan' => 'Dibatalkan',
                            default => $state,
                        })
                        ->color(fn (string $state): string => match ($state) {
                            'pending' => 'warning',
                            'diproses' => 'info',
                            'selesai' => 'success',
                            'dibatalkan' => 'danger',
                            default => 'gray',
                        }),
                ])->columns(2),

            Section::make('Ringkasan Pengadaan')
                ->description('Ringkasan nilai pesanan, pembayaran, penerimaan, dan jumlah item.')
                ->icon('heroicon-o-chart-bar')
                ->schema([
                    TextEntry::make('total_pesanan')->label('Nilai Pesanan')->money('IDR', locale: 'id')->weight('bold'),
                    TextEntry::make('total_dibayar')->label('Sudah Dibayar')->money('IDR', locale: 'id'),
                    TextEntry::make('sisa_tagihan')->label('Sisa Tagihan')->money('IDR', locale: 'id')->weight('bold'),
                    TextEntry::make('status_pembayaran')
                        ->label('Status Pembayaran')
                        ->badge()
                        ->formatStateUsing(fn ($state) => match ($state) {
                            'belum_dibayar' => 'Belum Dibayar',
                            'sebagian' => 'Dibayar Sebagian',
                            'lunas' => 'Lunas',
                            default => $state,
                        })
                        ->color(fn (string $state): string => match ($state) {
                            'belum_dibayar' => 'danger',
                            'sebagian' => 'warning',
                            'lunas' => 'success',
                            default => 'gray',
                        }),
                    TextEntry::make('status_penerimaan')
                        ->label('Status Penerimaan')
                        ->badge()
                        ->formatStateUsing(fn ($state) => match ($state) {
                            'belum_diterima' => 'Belum Diterima',
                            'sebagian' => 'Diterima Sebagian',
                            'lengkap' => 'Lengkap',
                            default => $state,
                        })
                        ->color(fn (string $state): string => match ($state) {
                            'belum_diterima' => 'danger',
                            'sebagian' => 'warning',
                            'lengkap' => 'success',
                            default => 'gray',
                        }),
                    TextEntry::make('total_item')->label('Jenis Obat')->formatStateUsing(fn ($state) => $state . ' item'),
                    TextEntry::make('total_item_diterima')->label('Item Lengkap Diterima')->formatStateUsing(fn ($state) => $state . ' item'),
                ])->columns(3),

            Section::make('Rincian Obat')
                ->description('Harga pada setiap baris merupakan snapshot harga supplier saat PO dibuat.')
                ->icon('heroicon-o-clipboard-document-list')
                ->schema([
                    RepeatableEntry::make('detail_pembelian')
                        ->schema([
                            TextEntry::make('obat.nama_obat')->label('Obat')->weight('bold'),
                            TextEntry::make('obat.satuan')->label('Satuan')->placeholder('-'),
                            TextEntry::make('jumlah_pesan')->label('Qty Pesan')->numeric(),
                            TextEntry::make('harga_satuan')->label('Harga Supplier')->money('IDR', locale: 'id'),
                            TextEntry::make('jumlah_diterima')->label('Sudah Diterima')->numeric(),
                            TextEntry::make('sisa_diterima')->label('Sisa Diterima')->numeric(),
                        ])->columns(3),
                ]),

            Section::make('Informasi Sistem')
                ->icon('heroicon-o-clock')
                ->collapsed()
                ->schema([
                    TextEntry::make('created_at')->label('Dibuat')->dateTime('d M Y, H:i')->placeholder('-'),
                    TextEntry::make('updated_at')->label('Terakhir Diubah')->dateTime('d M Y, H:i')->placeholder('-'),
                ])->columns(2),
        ]);
    }
}
