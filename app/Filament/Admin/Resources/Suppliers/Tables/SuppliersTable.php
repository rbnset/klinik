<?php

namespace App\Filament\Admin\Resources\Suppliers\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SuppliersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_supplier')
                    ->label('Nama Supplier')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->color('primary'),

                TextColumn::make('no_telp')
                    ->label('Nomor Kontak')
                    ->icon('heroicon-m-phone') // Menambahkan ikon kecil di samping nomor
                    ->searchable(),

                TextColumn::make('pengguna.name')
                    ->label('Akun Portal')
                    ->badge() // Ditampilkan dalam bentuk kotak lencana (badge)
                    ->color('success')
                    ->placeholder('Tanpa Akun (Manual)'),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->filters([])
            ->emptyStateHeading('Belum ada supplier')
            ->emptyStateDescription('Tambahkan supplier untuk membuat pemesanan obat.')
            ->recordActions([ActionGroup::make([ViewAction::make(), EditAction::make()])->icon('heroicon-m-ellipsis-vertical')])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }
}
