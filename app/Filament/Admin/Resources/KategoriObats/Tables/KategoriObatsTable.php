<?php

namespace App\Filament\Admin\Resources\KategoriObats\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class KategoriObatsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_kategori')
                    ->label('Nama Kategori')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')      // Tulisan ditebalkan agar mudah dibaca
                    ->color('primary'),   // Diberi warna tema agar terlihat modern

                TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->dateTime('d M Y, H:i') // Format tanggal enak dibaca (Contoh: 15 Aug 2026, 14:30)
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false), // Ditampilkan sebagai default

                TextColumn::make('updated_at')
                    ->label('Terakhir Diperbarui')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc') // Default urutan: Data terbaru di paling atas
            ->striped() // Memberikan warna selang-seling pada baris tabel agar tidak monoton
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
