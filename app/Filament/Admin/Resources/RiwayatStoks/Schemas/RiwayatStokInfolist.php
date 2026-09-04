<?php

namespace App\Filament\Admin\Resources\RiwayatStoks\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RiwayatStokInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Riwayat Stok')
                ->description('Jejak perubahan persediaan yang tercatat secara otomatis dari transaksi sumber.')
                ->icon('heroicon-o-document-chart-bar')
                ->schema([
                    TextEntry::make('id')
                        ->label('ID Audit')
                        ->formatStateUsing(fn ($state) => 'STK-' . str_pad((string) $state, 5, '0', STR_PAD_LEFT))
                        ->fontFamily('mono')
                        ->weight('bold'),
                    TextEntry::make('tanggal_mutasi')
                        ->label('Tanggal')
                        ->state(fn ($record) => $record->tanggal_mutasi ?? $record->created_at?->toDateString())
                        ->date('d M Y'),
                    TextEntry::make('obat.nama_obat')->label('Obat')->weight('bold'),
                    TextEntry::make('obat.satuan')->label('Satuan')->placeholder('-'),
                    TextEntry::make('sumber_label')->label('Aktivitas')->badge(),
                    TextEntry::make('referensi_transaksi')->label('Referensi')->fontFamily('mono')->weight('bold')->copyable()->placeholder('-'),
                    TextEntry::make('jenis_transaksi')
                        ->label('Arah Mutasi')
                        ->badge()
                        ->formatStateUsing(fn ($state) => $state === 'masuk' ? 'Stok Masuk' : 'Stok Keluar')
                        ->color(fn (string $state): string => $state === 'masuk' ? 'success' : 'danger'),
                    TextEntry::make('jumlah')->label('Jumlah Mutasi')->numeric()->weight('bold'),
                    TextEntry::make('stok_sebelum')->label('Stok Sebelum')->numeric(),
                    TextEntry::make('stok_sesudah')->label('Stok Sesudah')->numeric()->weight('bold'),
                    TextEntry::make('keterangan')->label('Catatan')->placeholder('-')->columnSpanFull(),
                ])->columns(2),

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
