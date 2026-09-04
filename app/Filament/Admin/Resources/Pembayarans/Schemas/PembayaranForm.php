<?php

namespace App\Filament\Admin\Resources\Pembayarans\Schemas;

use App\Models\PembelianObat;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class PembayaranForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            // ->columns(1)
            ->components([
                Section::make('Informasi Pembayaran')->description('Catat pembayaran terhadap tagihan PO. Pembayaran baru berstatus Menunggu Persetujuan Supplier dan baru dihitung sebagai pembayaran sah setelah disetujui supplier.')->icon('heroicon-o-banknotes')->schema([
                    Select::make('id_pembelian_obat')->relationship('pembelian_obat', 'id')->label('PO / Tagihan')->getOptionLabelFromRecordUsing(fn(PembelianObat $record) => 'PO-' . str_pad((string)$record->id, 5, '0', STR_PAD_LEFT) . ' · ' . ($record->supplier?->nama_supplier ?? '-') . ' · Sisa Rp ' . number_format($record->sisa_tagihan, 0, ',', '.'))->searchable()->preload()->required()->disabled(fn() => request()->query('pembelian') !== null)->default(fn() => request()->query('pembelian'))->dehydrated(),
                    DatePicker::make('tanggal_bayar')->label('Tanggal Pembayaran')->default(now())->required(),
                    Select::make('metode_pembayaran')->label('Metode Pembayaran')->options(['tunai' => 'Tunai', 'transfer' => 'Transfer'])->required()->live(),
                    TextInput::make('total_bayar')->label('Jumlah Pembayaran')->numeric()->prefix('Rp')->minValue(1)->required()->helperText(fn($get) => ($po = $get('id_pembelian_obat')) ? 'Sisa tagihan yang belum disahkan: Rp ' . number_format(PembelianObat::find($po)?->sisa_tagihan ?? 0, 0, ',', '.') : 'Pilih PO untuk melihat sisa tagihan.'),
                    FileUpload::make('bukti_bayar')->label('Bukti Pembayaran')->disk('public')->directory('bukti-pembayaran')->visibility('public')->acceptedFileTypes(['image/jpeg', 'image/png', 'application/pdf'])->maxSize(5120)->downloadable()->openable()->required(fn(Get $get): bool => $get('metode_pembayaran') === 'transfer')->helperText('Wajib untuk transfer. Format JPG, PNG, atau PDF, maksimal 5 MB.')->visible(fn(Get $get): bool => $get('metode_pembayaran') === 'transfer'),
                ])->columns(2),
            ]);
    }
}
