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
                    ->copyable()
                    ->copyMessage('Kode obat disalin!')
                    ->fontFamily('mono'),

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
                    ->label('Stok')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color(fn ($state): string => match (true) {
                        (int) $state <= 0 => 'danger',
                        (int) $state <= 10 => 'warning',
                        (int) $state <= 30 => 'info',
                        default => 'success',
                    }),

                TextColumn::make('satuan')->label('Satuan')->searchable(),

                TextColumn::make('harga_beli_terakhir')
                    ->label('Harga Beli Terakhir')
                    ->state(fn ($record) => $record->harga_beli_terakhir)
                    ->money('IDR', locale: 'id')
                    ->placeholder('—')
                    ->sortable(false),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }
}
