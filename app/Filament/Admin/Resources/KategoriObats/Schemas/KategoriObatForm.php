<?php

namespace App\Filament\Admin\Resources\KategoriObats\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class KategoriObatForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Kategori')
                ->description('Tentukan nama kategori untuk mengelompokkan obat di katalog.')
                ->icon('heroicon-o-tag')
                ->schema([
                    TextInput::make('nama_kategori')
                        ->label('Nama Kategori')
                        ->placeholder('Contoh: Tablet, Kapsul, Sirup')
                        ->required()
                        ->maxLength(100)
                        ->autofocus(),
                ])
                ->columns(2),
        ]);
    }
}
