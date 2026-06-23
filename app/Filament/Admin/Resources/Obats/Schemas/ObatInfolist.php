<?php

namespace App\Filament\Admin\Resources\Obats\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ObatInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id_kategori_obat')
                    ->numeric(),
                TextEntry::make('kode_obat'),
                TextEntry::make('nama_obat'),
                TextEntry::make('satuan'),
                TextEntry::make('stok')
                    ->numeric(),
                TextEntry::make('harga_beli')
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
