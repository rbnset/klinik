<?php

namespace App\Filament\Admin\Resources\Pembayarans\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PembayaranInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id_pembelian_obat')
                    ->numeric(),
                TextEntry::make('tanggal_bayar')
                    ->date(),
                TextEntry::make('metode_pembayaran')
                    ->badge(),
                TextEntry::make('total_bayar')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
