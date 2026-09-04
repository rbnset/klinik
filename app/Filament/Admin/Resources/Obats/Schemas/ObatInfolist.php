<?php

namespace App\Filament\Admin\Resources\Obats\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ObatInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Identitas Obat')
                ->description('Data identitas tetap obat dan klasifikasinya.')
                ->icon('heroicon-o-beaker')
                ->schema([
                    TextEntry::make('kode_obat')->label('Kode / SKU')->copyable()->fontFamily('mono')->weight('bold'),
                    TextEntry::make('nama_obat')->label('Nama Obat')->weight('bold')->size('lg'),
                    TextEntry::make('kategori_obat.nama_kategori')->label('Kategori')->badge()->color('gray'),
                    TextEntry::make('satuan')->label('Satuan Kemasan'),
                ])->columns(2),

            Section::make('Persediaan & Harga')
                ->description('Ringkasan saldo stok dan harga pembelian terakhir yang berasal dari transaksi pembelian.')
                ->icon('heroicon-o-archive-box')
                ->schema([
                    TextEntry::make('stok')
                        ->label('Stok Saat Ini')
                        ->numeric()
                        ->suffix(fn ($record) => ' ' . ($record->satuan ?? 'unit'))
                        ->weight('bold'),
                    TextEntry::make('stok_tersedia')
                        ->label('Stok Tersedia')
                        ->numeric()
                        ->suffix(fn ($record) => ' ' . ($record->satuan ?? 'unit')),
                    TextEntry::make('status_stok')
                        ->label('Kondisi Stok')
                        ->state(fn ($record) => match (true) {
                            (int) $record->stok <= 0 => 'Habis',
                            (int) $record->stok <= 10 => 'Kritis',
                            (int) $record->stok <= 30 => 'Menipis',
                            default => 'Aman',
                        })
                        ->badge()
                        ->color(fn ($record): string => match (true) {
                            (int) $record->stok <= 0 => 'danger',
                            (int) $record->stok <= 10 => 'warning',
                            (int) $record->stok <= 30 => 'info',
                            default => 'success',
                        }),
                    TextEntry::make('harga_beli_terakhir')
                        ->label('Harga Beli Terakhir')
                        ->money('IDR', locale: 'id')
                        ->placeholder('Belum ada pembelian')
                        ->visible(fn () => auth()->user()?->role !== 'bidan'),
                    TextEntry::make('pembelian_terakhir_supplier')
                        ->label('Supplier Terakhir')
                        ->visible(fn () => auth()->user()?->role !== 'bidan')
                        ->state(function ($record) {
                            $detail = $record->detail_pembelian()
                                ->with('pembelian_obat.supplier')
                                ->whereHas('pembelian_obat', fn ($q) => $q->where('status', '!=', 'dibatalkan'))
                                ->get()
                                ->sortByDesc(fn ($item) => optional($item->pembelian_obat)->tanggal_pesan)
                                ->first();

                            return $detail?->pembelian_obat?->supplier?->nama_supplier ?? 'Belum ada pembelian';
                        }),
                ])->columns(2),

            Section::make('Informasi Sistem')
                ->description('Metadata pencatatan data obat.')
                ->icon('heroicon-o-clock')
                ->collapsed()
                ->schema([
                    TextEntry::make('created_at')->label('Didaftarkan')->dateTime('d M Y, H:i')->placeholder('-'),
                    TextEntry::make('updated_at')->label('Terakhir Diubah')->dateTime('d M Y, H:i')->placeholder('-'),
                ])->columns(2),
        ]);
    }
}
