<?php

namespace App\Filament\Admin\Resources\PenerimaanObats\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PenerimaanObatInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id_pembelian_obat')
                    ->numeric(),
                TextEntry::make('nomor_faktur'),
                TextEntry::make('tanggal_terima')
                    ->date(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
