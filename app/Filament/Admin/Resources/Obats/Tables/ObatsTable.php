<?php

namespace App\Filament\Admin\Resources\Obats\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ObatsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_obat')
                    ->label('Kode SKU')
                    ->searchable()
                    ->copyable() // UX Bagus: Pengguna bisa klik untuk copy kode
                    ->copyMessage('Kode obat disalin!')
                    ->fontFamily('mono') // Font bergaya mesin ketik/barcode
                    ->color('gray'),

                TextColumn::make('nama_obat')
                    ->label('Nama Obat')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('kategori_obat.nama_kategori')
                    ->label('Kategori')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                TextColumn::make('stok')
                    ->label('Sisa Stok')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    // UX Bagus: Warna dinamis berdasarkan jumlah stok
                    ->color(fn(string $state): string => match (true) {
                        $state <= 10 => 'danger',   // Merah (Kritis)
                        $state <= 30 => 'warning',  // Kuning (Menipis)
                        default => 'success',       // Hijau (Aman)
                    }),

                TextColumn::make('satuan')
                    ->label('Satuan')
                    ->searchable(),

                TextColumn::make('harga_beli')
                    ->label('Harga Beli')
                    ->money('IDR', locale: 'id') // Otomatis berformat Rp. 10.000,00
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->filters([])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }
}
