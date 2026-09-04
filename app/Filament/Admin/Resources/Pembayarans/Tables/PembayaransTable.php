<?php

namespace App\Filament\Admin\Resources\Pembayarans\Tables;

use App\Models\Pembayaran;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PembayaransTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('No. Pembayaran')->formatStateUsing(fn ($state) => 'PAY-' . str_pad((string) $state, 5, '0', STR_PAD_LEFT))->fontFamily('mono')->weight('bold'),
                TextColumn::make('pembelian_obat.id')->label('No. PO')->formatStateUsing(fn ($state) => 'PO-' . str_pad((string) $state, 5, '0', STR_PAD_LEFT))->sortable(),
                TextColumn::make('pembelian_obat.supplier.nama_supplier')->label('Supplier')->searchable(),
                TextColumn::make('tanggal_bayar')->label('Tanggal Bayar')->date('d M Y')->sortable(),
                TextColumn::make('metode_pembayaran')->label('Metode')->badge()->formatStateUsing(fn ($state) => ucfirst($state)),
                TextColumn::make('total_bayar')->label('Jumlah Bayar')->money('IDR', locale: 'id')->sortable(),
            ])
            ->defaultSort('tanggal_bayar', 'desc')
            ->striped()
            ->recordActions([ViewAction::make(), EditAction::make()])
            ->toolbarActions([]);
    }
}
