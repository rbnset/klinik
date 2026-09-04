<?php

namespace App\Filament\Admin\Resources\Pembayarans\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section as InfoSection;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PembayaranInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Informasi Pembayaran')->description('Pembayaran menjadi sah setelah dikonfirmasi supplier.')->icon('heroicon-o-banknotes')->schema([
                    TextEntry::make('id')->label('Nomor Pembayaran')->formatStateUsing(fn($state) => 'PAY-' . str_pad((string)$state, 5, '0', STR_PAD_LEFT))->fontFamily('mono')->weight('bold'),
                    TextEntry::make('pembelian_obat.id')->label('Nomor PO')->formatStateUsing(fn($state) => $state ? 'PO-' . str_pad((string)$state, 5, '0', STR_PAD_LEFT) : '-'),
                    TextEntry::make('pembelian_obat.supplier.nama_supplier')->label('Supplier')->placeholder('-'),
                    TextEntry::make('tanggal_bayar')->label('Tanggal Pembayaran')->date('d M Y'),
                    TextEntry::make('metode_pembayaran')->label('Metode Pembayaran')->badge(),
                    TextEntry::make('total_bayar')->label('Jumlah Pembayaran')->money('IDR', locale: 'id')->weight('bold'),
                    TextEntry::make('status')->label('Status')->formatStateUsing(fn($state) => match ($state) {
                        'menunggu_supplier' => 'Menunggu Persetujuan Supplier',
                        'disetujui_supplier' => 'Disetujui Supplier',
                        'ditolak_supplier' => 'Ditolak Supplier',
                        default => ucfirst((string)$state)
                    })->badge()->color(fn($state) => match ($state) {
                        'disetujui_supplier' => 'success',
                        'ditolak_supplier' => 'danger',
                        default => 'warning'
                    }),
                    TextEntry::make('bukti_bayar')->label('Bukti Pembayaran')->formatStateUsing(fn($state) => $state ? 'Tersedia' : 'Tidak ada')->url(fn($state) => $state ? \Illuminate\Support\Facades\Storage::disk('public')->url($state) : null, shouldOpenInNewTab: true),
                    TextEntry::make('pembelian_obat.sisa_tagihan')->label('Sisa Tagihan PO')->money('IDR', locale: 'id')->weight('bold'),
                    TextEntry::make('disetujui_supplier_at')->label('Disetujui Supplier')->dateTime('d M Y, H:i')->placeholder('-'),
                    TextEntry::make('catatan_supplier')->label('Catatan Supplier')->placeholder('-')->columnSpanFull(),
                ])->columns(2),
                Section::make('Informasi Sistem')->icon('heroicon-o-clock')->collapsed()->schema([TextEntry::make('created_at')->label('Dicatat')->dateTime('d M Y, H:i')->placeholder('-'), TextEntry::make('updated_at')->label('Terakhir Diubah')->dateTime('d M Y, H:i')->placeholder('-')])->columns(2),
            ]);
    }
}
