<?php

namespace App\Filament\Admin\Resources\PembelianObats\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PembelianObatsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('No. PO')
                    ->prefix('PO-')
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('supplier.nama_supplier')->label('Supplier')->searchable(),
                TextColumn::make('tanggal_pesan')->label('Tanggal Pesan')->dateTime('d M Y')->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'diproses' => 'info',
                        'selesai' => 'success',
                        'dibatalkan' => 'danger',
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->recordActions([ViewAction::make(), EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
