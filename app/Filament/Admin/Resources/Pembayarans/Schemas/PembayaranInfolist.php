<?php

namespace App\Filament\Admin\Resources\Pembayarans\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PembayaranInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Pembayaran')
                ->description('Detail transaksi pembayaran tagihan supplier.')
                ->icon('heroicon-o-banknotes')
                ->schema([
                    TextEntry::make('id')
                        ->label('Nomor Pembayaran')
                        ->formatStateUsing(fn ($state) => 'PAY-' . str_pad((string) $state, 5, '0', STR_PAD_LEFT))
                        ->fontFamily('mono')
                        ->weight('bold'),
                    TextEntry::make('pembelian_obat.id')
                        ->label('Nomor PO')
                        ->formatStateUsing(fn ($state) => $state ? 'PO-' . str_pad((string) $state, 5, '0', STR_PAD_LEFT) : '-'),
                    TextEntry::make('pembelian_obat.supplier.nama_supplier')->label('Supplier')->placeholder('-'),
                    TextEntry::make('tanggal_bayar')->label('Tanggal Pembayaran')->date('d M Y'),
                    TextEntry::make('metode_pembayaran')->label('Metode Pembayaran')->badge(),
                    TextEntry::make('total_bayar')->label('Jumlah Pembayaran')->money('IDR', locale: 'id')->weight('bold'),
                    TextEntry::make('pembelian_obat.sisa_tagihan')->label('Sisa Tagihan PO')->money('IDR', locale: 'id')->weight('bold'),
                ])->columns(2),

            Section::make('Informasi Sistem')
                ->description('Metadata pencatatan transaksi pembayaran.')
                ->icon('heroicon-o-clock')
                ->collapsed()
                ->schema([
                    TextEntry::make('created_at')->label('Dicatat')->dateTime('d M Y, H:i')->placeholder('-'),
                    TextEntry::make('updated_at')->label('Terakhir Diubah')->dateTime('d M Y, H:i')->placeholder('-'),
                ])->columns(2),
        ]);
    }
}
