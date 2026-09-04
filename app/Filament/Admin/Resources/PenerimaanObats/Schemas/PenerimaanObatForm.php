<?php

namespace App\Filament\Admin\Resources\PenerimaanObats\Schemas;

use App\Models\DetailPembelianObat;
use App\Models\PembelianObat;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PenerimaanObatForm
{
    public static function configure(Schema $schema): Schema
    {
        $poId = request()->query('pembelian');

        return $schema->components([
            Section::make('Informasi Penerimaan')
                ->description('Catat barang yang benar-benar diterima. Stok bertambah otomatis setelah disimpan.')
                ->icon('heroicon-o-inbox-arrow-down')
                ->schema([
                    Select::make('id_pembelian_obat')
                        ->relationship('pembelian_obat', 'id')
                        ->label('Nomor PO')
                        ->getOptionLabelFromRecordUsing(fn (PembelianObat $record) => 'PO-' . str_pad((string) $record->id, 5, '0', STR_PAD_LEFT) . ' · ' . ($record->supplier?->nama_supplier ?? '-'))
                        ->searchable()->preload()->required()
                        ->default($poId)
                        ->disabled(fn () => $poId !== null)
                        ->dehydrated(),
                    TextInput::make('nomor_faktur')->label('Nomor Faktur')->unique(ignoreRecord: true)->required(),
                    DatePicker::make('tanggal_terima')->label('Tanggal Terima')->default(now())->required(),
                ])->columns(3),

            Section::make('Pengecekan Fisik')
                ->description('Hanya item PO yang masih memiliki sisa penerimaan yang dapat dipilih.')
                ->icon('heroicon-o-clipboard-document-check')
                ->schema([
                    Repeater::make('detail_penerimaan')
                        ->relationship()
                        ->schema([
                            Select::make('id_detail_pembelian')
                                ->label('Item PO')
                                ->options(function (callable $get) {
                                    $currentPo = $get('../../id_pembelian_obat') ?: request()->query('pembelian');
                                    if (! $currentPo) return [];
                                    return DetailPembelianObat::query()
                                        ->where('id_pembelian_obat', $currentPo)
                                        ->with('obat')
                                        ->get()
                                        ->filter(fn ($detail) => $detail->sisa_diterima > 0)
                                        ->mapWithKeys(fn ($detail) => [
                                            $detail->id => $detail->obat?->nama_obat . ' · Pesan ' . $detail->jumlah_pesan . ' · Sisa ' . $detail->sisa_diterima,
                                        ])->all();
                                })
                                ->searchable()->preload()->required()->columnSpan(2),
                            TextInput::make('jumlah_diterima')
                                ->label('Qty Aktual Diterima')
                                ->numeric()->minValue(1)->required()
                                ->helperText('Tidak boleh melebihi sisa qty PO.')
                                ->columnSpan(2),
                        ])->columns(4)
                        ->addActionLabel('Tambah Item'),
                ]),
        ]);
    }
}
