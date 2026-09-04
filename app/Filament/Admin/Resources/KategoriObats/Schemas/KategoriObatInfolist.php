<?php

namespace App\Filament\Admin\Resources\KategoriObats\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class KategoriObatInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Kategori')
                ->description('Informasi dasar kategori yang digunakan untuk mengelompokkan obat.')
                ->icon('heroicon-o-tag')
                ->schema([
                    TextEntry::make('nama_kategori')
                        ->label('Nama Kategori')
                        ->weight('bold')
                        ->size('lg'),
                ])->columns(2),

            Section::make('Informasi Sistem')
                ->description('Waktu pencatatan dan perubahan terakhir pada data.')
                ->icon('heroicon-o-clock')
                ->collapsed()
                ->schema([
                    TextEntry::make('created_at')
                        ->label('Didaftarkan')
                        ->dateTime('d M Y, H:i')
                        ->placeholder('-'),
                    TextEntry::make('updated_at')
                        ->label('Terakhir Diubah')
                        ->dateTime('d M Y, H:i')
                        ->placeholder('-'),
                ])->columns(2),
        ]);
    }
}
