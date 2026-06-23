<?php

namespace App\Filament\Admin\Resources\RiwayatStoks\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RiwayatStoksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Tanggal Mutasi')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),

                TextColumn::make('obat.nama_obat')
                    ->label('Nama Obat')
                    ->searchable()
                    ->weight('bold'),

                TextColumn::make('jenis_transaksi')
                    ->label('Jenis Mutasi')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'masuk' => 'success', // Hijau untuk barang masuk
                        'keluar' => 'danger', // Merah untuk barang keluar
                    })
                    ->formatStateUsing(fn(string $state): string => strtoupper($state)),

                TextColumn::make('jumlah')->label('Qty')->numeric()->sortable(),

                TextColumn::make('stok_sebelum')->label('Stok Awal')->numeric()->color('gray'),

                TextColumn::make('stok_sesudah')->label('Stok Akhir')->numeric()->weight('bold')->color('primary'),

                TextColumn::make('referensi_transaksi')
                    ->label('Referensi Dokumen')
                    ->searchable()
                    ->fontFamily('mono'), // Font mirip barcode agar tegas
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            // Hanya ada tombol View, tidak ada Edit/Delete untuk menjaga audit log
            ->recordActions([ViewAction::make()]);
    }
}
