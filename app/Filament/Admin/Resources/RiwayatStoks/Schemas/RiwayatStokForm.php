<?php

namespace App\Filament\Admin\Resources\RiwayatStoks\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RiwayatStokForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Mutasi')
                    ->schema([
                        TextInput::make('referensi_transaksi')->disabled(),
                        TextInput::make('jenis_transaksi')->disabled(),
                        TextInput::make('jumlah')->disabled(),
                    ])->columns(3),
            ]);
    }
}
