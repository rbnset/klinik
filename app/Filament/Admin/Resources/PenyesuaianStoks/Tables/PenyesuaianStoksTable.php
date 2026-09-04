<?php

namespace App\Filament\Admin\Resources\PenyesuaianStoks\Tables;

use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PenyesuaianStoksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tanggal')->label('Tanggal')->dateTime('d M Y')->sortable(),
                TextColumn::make('obat.nama_obat')->label('Nama Obat')->searchable()->weight('bold'),
                TextColumn::make('jenis')
                    ->label('Tindakan')
                    ->badge()
                    // UX: Warna hijau untuk penambahan, merah untuk pengurangan
                    ->color(fn(string $state): string => match ($state) {
                        'penambahan' => 'success',
                        'pengurangan' => 'danger',
                    }),
                TextColumn::make('alasan')->label('Alasan')->formatStateUsing(fn(string $state): string => ucfirst(str_replace('_', ' ', $state))),
                TextColumn::make('jumlah')->label('Jumlah (Qty)')->numeric(),
                TextColumn::make('pengguna.name')->label('Petugas')->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->recordActions([
                ViewAction::make(),
                EditAction::make()->visible(fn ($record) => ! $record->stok_diposting_at),
            ])
            ->toolbarActions([]);
    }
}
