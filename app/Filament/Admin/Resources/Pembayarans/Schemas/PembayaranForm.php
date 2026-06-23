<?php

namespace App\Filament\Admin\Resources\Pembayarans\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PembayaranForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Bukti Pelunasan')
                    ->schema([
                        Select::make('id_pembelian_obat')
                            ->relationship('pembelian_obat', 'id')
                            ->label('Tagihan dari PO Nomor')
                            ->searchable()
                            ->preload()
                            ->required(),
                        DatePicker::make('tanggal_bayar')
                            ->label('Tanggal Pembayaran')
                            ->default(now())
                            ->required(),
                        Select::make('metode_pembayaran')
                            ->options([
                                'tunai' => 'Uang Tunai (Cash)',
                                'transfer' => 'Transfer Bank',
                            ])
                            ->required(),
                        TextInput::make('total_bayar')
                            ->label('Total Uang Keluar')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(),
                    ])->columns(2),
            ]);
    }
}
