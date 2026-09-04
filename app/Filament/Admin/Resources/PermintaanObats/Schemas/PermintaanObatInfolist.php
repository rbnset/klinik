<?php

namespace App\Filament\Admin\Resources\PermintaanObats\Schemas;

use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PermintaanObatInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Ringkasan Permintaan')
                ->description('Identitas pengajuan, pemohon, status proses, dan keputusan gudang.')
                ->icon('heroicon-o-inbox-arrow-down')
                ->schema([
                    TextEntry::make('id')->label('Nomor Permintaan')->formatStateUsing(fn ($state) => 'REQ-' . str_pad((string) $state, 5, '0', STR_PAD_LEFT))->fontFamily('mono')->weight('bold'),
                    TextEntry::make('pengguna.name')->label('Pemohon')->placeholder('-'),
                    TextEntry::make('tanggal_permintaan')->label('Tanggal Pengajuan')->date('d M Y'),
                    TextEntry::make('status')->label('Status')->badge()->formatStateUsing(fn ($state) => match ($state) { 'pending' => 'Menunggu Persetujuan', 'disetujui' => 'Disetujui · Siap Diserahkan', 'diserahkan' => 'Menunggu Konfirmasi Bidan', 'selesai' => 'Selesai', 'ditolak' => 'Ditolak', 'dibatalkan' => 'Dibatalkan', default => ucfirst((string) $state), })->color(fn ($state) => match ($state) { 'pending' => 'warning', 'disetujui' => 'info', 'diserahkan' => 'warning', 'selesai' => 'success', 'ditolak' => 'danger', 'dibatalkan' => 'gray', default => 'gray' }),
                    TextEntry::make('keterangan')->label('Keterangan / Urgensi')->placeholder('-')->columnSpanFull(),
                ])->columns(2),

            Section::make('Rincian Obat')
                ->description('Jumlah diminta dibandingkan dengan keputusan gudang. Hanya item yang disiapkan yang mengurangi stok.')
                ->icon('heroicon-o-clipboard-document-list')
                ->schema([
                    RepeatableEntry::make('detail_permintaan')->schema([
                        TextEntry::make('obat.nama_obat')->label('Obat')->weight('bold'),
                        TextEntry::make('obat.satuan')->label('Satuan')->placeholder('-'),
                        TextEntry::make('jumlah_diminta')->label('Diminta')->numeric(),
                        TextEntry::make('jumlah_disetujui')->label('Disiapkan')->numeric()->placeholder('Tidak disiapkan'),
                    ])->columns(4),
                ]),

            Section::make('Jejak Proses Internal')
                ->description('Riwayat tahapan permintaan dari pengajuan sampai obat dikonfirmasi diterima.')
                ->icon('heroicon-o-arrow-path')
                ->schema([
                    TextEntry::make('disetujui_at')->label('Disetujui Gudang')->dateTime('d M Y, H:i')->placeholder('Belum'),
                    TextEntry::make('disetujuiOleh.name')->label('Disetujui Oleh')->placeholder('-'),
                    TextEntry::make('diserahkan_at')->label('Diserahkan')->dateTime('d M Y, H:i')->placeholder('Belum'),
                    TextEntry::make('diserahkanOleh.name')->label('Diserahkan Oleh')->placeholder('-'),
                    TextEntry::make('dikonfirmasi_at')->label('Dikonfirmasi Bidan')->dateTime('d M Y, H:i')->placeholder('Belum'),
                    TextEntry::make('dikonfirmasiOleh.name')->label('Dikonfirmasi Oleh')->placeholder('-'),
                    TextEntry::make('stok_diposting_at')->label('Stok Diposting')->dateTime('d M Y, H:i')->placeholder('Belum')->columnSpanFull(),
                    TextEntry::make('catatan_gudang')->label('Catatan Gudang')->placeholder('Tidak ada catatan')->columnSpanFull(),
                    TextEntry::make('ditolak_at')->label('Ditolak Pada')->dateTime('d M Y, H:i')->placeholder('-'),
                    TextEntry::make('ditolakOleh.name')->label('Ditolak Oleh')->placeholder('-'),
                    TextEntry::make('alasan_penolakan')->label('Alasan Penolakan')->placeholder('Tidak ada')->columnSpanFull(),
                ])->columns(2),
        ]);
    }
}
