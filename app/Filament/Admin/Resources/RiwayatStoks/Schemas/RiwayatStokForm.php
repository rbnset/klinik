<?php

namespace App\Filament\Admin\Resources\RiwayatStoks\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RiwayatStokForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Audit Riwayat Stok')
                ->description('Riwayat stok bersifat read-only dan tidak dapat diubah melalui formulir.')
                ->icon('heroicon-o-document-chart-bar')
                ->schema([
                    TextInput::make('referensi_transaksi')->label('Referensi')->disabled(),
                    TextInput::make('jenis_transaksi')->label('Arah Mutasi')->disabled(),
                    TextInput::make('jumlah')->label('Jumlah Mutasi')->disabled(),
                ])->columns(3),
        ]);
    }
}
