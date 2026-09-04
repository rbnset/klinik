<?php

namespace App\Filament\Admin\Resources\Pembayarans\Schemas;

use App\Models\PembelianObat;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PembayaranForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Pembayaran')
                ->description('Catat pembayaran terhadap tagihan PO. Sistem menghitung sisa tagihan secara otomatis.')
                ->icon('heroicon-o-banknotes')
                ->schema([
                    Select::make('id_pembelian_obat')
                        ->relationship('pembelian_obat', 'id')
                        ->label('PO / Tagihan')
                        ->getOptionLabelFromRecordUsing(fn (PembelianObat $record) => 'PO-' . str_pad((string) $record->id, 5, '0', STR_PAD_LEFT) . ' · ' . ($record->supplier?->nama_supplier ?? '-') . ' · Sisa Rp ' . number_format($record->sisa_tagihan, 0, ',', '.'))
                        ->searchable()->preload()->required()
                        ->disabled(fn () => request()->query('pembelian') !== null)
                        ->default(fn () => request()->query('pembelian'))
                        ->dehydrated(),
                    DatePicker::make('tanggal_bayar')->label('Tanggal Pembayaran')->default(now())->required(),
                    Select::make('metode_pembayaran')
                        ->label('Metode Pembayaran')
                        ->options(['tunai' => 'Tunai', 'transfer' => 'Transfer'])->required(),
                    TextInput::make('total_bayar')
                        ->label('Jumlah Pembayaran')->numeric()->prefix('Rp')->minValue(1)->required()
                        ->helperText(fn ($get) => ($po = $get('id_pembelian_obat'))
                            ? 'Sisa tagihan saat ini: Rp ' . number_format(PembelianObat::find($po)?->sisa_tagihan ?? 0, 0, ',', '.')
                            : 'Pilih PO untuk melihat sisa tagihan.'),
                ])->columns(2),
        ]);
    }
}
