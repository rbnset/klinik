<?php

namespace App\Filament\Admin\Resources\PermintaanObats\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PermintaanObatsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->prefix('REQ-')
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('pengguna.name')->label('Pemohon')->searchable(),
                TextColumn::make('tanggal_permintaan')->label('Tanggal')->dateTime('d M Y')->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'disetujui' => 'success',
                        'ditolak' => 'danger',
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->recordActions([ViewAction::make(), EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
